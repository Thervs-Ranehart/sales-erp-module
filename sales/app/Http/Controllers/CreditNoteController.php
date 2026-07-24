<?php

namespace App\Http\Controllers;

use App\Models\CreditNote;
use App\Models\Employee;
use App\Models\FinanceTransaction;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\SalesApproval;
use App\Models\SalesAuditLog;
use App\Services\LoyaltyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreditNoteController extends Controller
{
    public function store(Request $request, Invoice $invoice): RedirectResponse
    {
        $data = $request->validate([
            'reason' => ['required', 'string', 'max:2000'],
            'quantities' => ['required', 'array'],
            'quantities.*' => ['nullable', 'integer', 'min:0'],
        ]);
        $employeeId = (int) ($request->session()->get('employee_id') ?? Employee::query()->value('employee_id'));

        if (! $this->isManager($request) && ! SalesApproval::query()
            ->where(['approvable_type' => Invoice::class, 'approvable_id' => $invoice->invoice_id, 'action' => 'credit_note', 'status' => 'Approved'])
            ->exists()) {
            SalesApproval::query()->firstOrCreate([
                'requested_by' => $employeeId,
                'approvable_type' => Invoice::class,
                'approvable_id' => $invoice->invoice_id,
                'action' => 'credit_note',
                'status' => 'Pending',
            ], ['reason' => $data['reason']]);

            return back()->with('success', 'Credit-note approval was requested from a manager.');
        }

        $creditNote = DB::transaction(function () use ($data, $invoice, $employeeId): CreditNote {
            $invoice->load('items');
            $selected = collect($data['quantities'])->filter(fn ($quantity) => (int) $quantity > 0);
            if ($selected->isEmpty()) {
                throw ValidationException::withMessages(['quantities' => 'Enter a return quantity for at least one product.']);
            }

            $creditNote = CreditNote::query()->create([
                'invoice_id' => $invoice->invoice_id,
                'created_by' => $employeeId,
                'approved_by' => $employeeId,
                'credit_note_number' => null,
                'status' => 'Issued',
                'reason' => $data['reason'],
                'amount' => 0,
                'issued_at' => now(),
            ]);
            $creditNote->update(['credit_note_number' => 'CN-'.str_pad((string) $creditNote->credit_note_id, 5, '0', STR_PAD_LEFT)]);
            $amount = 0.0;

            foreach ($selected as $invoiceItemId => $quantity) {
                $item = $invoice->items->firstWhere('invoice_item_id', (int) $invoiceItemId);
                $alreadyCredited = (int) DB::table('credit_note_items')
                    ->join('credit_notes', 'credit_notes.credit_note_id', '=', 'credit_note_items.credit_note_id')
                    ->where('credit_notes.invoice_id', $invoice->invoice_id)
                    ->where('credit_note_items.invoice_item_id', $invoiceItemId)
                    ->where('credit_notes.status', 'Issued')
                    ->sum('credit_note_items.quantity');
                $remaining = $item ? (int) $item->quantity - $alreadyCredited : -1;
                if (! $item || (int) $quantity > $remaining) {
                    throw ValidationException::withMessages(["quantities.$invoiceItemId" => 'Return quantity exceeds the remaining refundable amount.']);
                }

                $lineAmount = round((float) $item->unit_price * (int) $quantity, 2);
                $creditNote->items()->create([
                    'invoice_item_id' => $item->invoice_item_id,
                    'quantity' => $quantity,
                    'amount' => $lineAmount,
                ]);
                Product::query()->where('product_id', $item->product_id)->increment('stock_quantity', $quantity);
                $amount += $lineAmount;
            }

            $creditNote->update(['amount' => $amount]);
            FinanceTransaction::query()->create([
                'invoice_id' => $invoice->invoice_id,
                'amount' => -$amount,
                'payment_method' => 'Credit Note',
                'transaction_date' => now()->toDateString(),
            ]);
            SalesAuditLog::record($creditNote, 'credit_note_issued', null, $creditNote->fresh()->toArray(), $data['reason']);
            $customer = $invoice->salesOrder?->customer;
            if ($customer) {
                app(LoyaltyService::class)->post(
                    $customer,
                    'Reversal',
                    -((int) floor($amount / 100)),
                    $creditNote,
                    "Points reversed for {$creditNote->credit_note_number}"
                );
            }

            return $creditNote;
        });

        return back()->with('success', "{$creditNote->credit_note_number} issued and inventory/finance were reconciled.");
    }

    private function isManager(Request $request): bool
    {
        return in_array(strtolower((string) $request->session()->get('employee_role')), ['manager', 'administrator', 'admin', 'owner', 'supervisor'], true);
    }
}
