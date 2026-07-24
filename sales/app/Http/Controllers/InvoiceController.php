<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Employee;
use App\Models\FinanceTransaction;
use App\Models\InventoryTransaction;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\SalesApproval;
use App\Models\SalesAuditLog;
use App\Models\SalesOrder;
use App\Services\LoyaltyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class InvoiceController extends Controller
{
    public function index(): View
    {
        $invoices = Invoice::with([
            'salesOrder.customer',
            'employee',
        ])
            ->latest('invoice_id')
            ->get();

        $statusCounts = [
            'all' => $invoices->count(),
            'pending' => $invoices->where('payment_status', 'Pending')->count(),
            'paid' => $invoices->where('payment_status', 'Paid')->count(),
            'cancelled' => $invoices->where('payment_status', 'Cancelled')->count(),
        ];

        return view(
            'sales.invoices',
            compact('invoices', 'statusCounts')
        );
    }

    public function create(Request $request): View
    {
        $invoice = new Invoice;
        $salesOrders = SalesOrder::query()
            ->with(['customer', 'items', 'invoices.items'])
            ->whereNotIn('order_status', ['pending', 'cancelled'])
            ->get()
            ->filter(fn (SalesOrder $order) => $order->items->sum('quantity') > $order->invoices
                ->where('payment_status', '!=', 'Cancelled')
                ->flatMap->items
                ->sum('quantity'));
        $selectedOrderId = $request->integer('order_id');
        $selectedOrder = $selectedOrderId
            ? $salesOrders->firstWhere('order_id', $selectedOrderId)
            : null;

        return view(
            'sales.create-invoice',
            [
                'invoice' => $invoice,
                'salesOrders' => $salesOrders,
                'selectedOrder' => $selectedOrder,
                'isEdit' => false,
            ]
        );
    }

    public function store(StoreInvoiceRequest $request)
    {
        $validated = $request->validated();

        $order = SalesOrder::with(['items', 'invoices.items'])->findOrFail($validated['order_id']);

        if (in_array(strtolower((string) $order->order_status), ['pending', 'cancelled'], true)) {
            return back()->withInput()->withErrors([
                'order_id' => 'Only processed, shipped, or delivered orders can be invoiced.',
            ]);
        }

        try {
            DB::transaction(function () use ($validated, $order) {
                $selectedItems = $this->invoiceableItems($order, $validated['quantities'] ?? []);
                $subtotal = (float) $selectedItems->sum('subtotal');
                $orderSubtotal = max(0.01, (float) $order->subtotal);
                $ratio = min(1, $subtotal / $orderSubtotal);
                $discount = round((float) $order->discount * $ratio, 2);
                $tax = round((float) $order->tax * $ratio, 2);
                $shipping = round((float) $order->shipping_fee * $ratio, 2);
                $validated = array_merge($validated, [
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'tax' => $tax,
                    'shipping_fee' => $shipping,
                    'total_amount' => round($subtotal - $discount + $tax + $shipping, 2),
                ]);
                $validated['invoice_number'] = null;
                $invoice = Invoice::create($validated);
                $invoice->update([
                    'invoice_number' => 'INV-'.str_pad((string) $invoice->invoice_id, 5, '0', STR_PAD_LEFT),
                ]);

                foreach ($selectedItems as $orderItem) {
                    $invoice->items()->create([
                        'order_item_id' => $orderItem->order_item_id,
                        'product_id' => $orderItem->product_id,
                        'quantity' => $orderItem->quantity,
                        'unit_price' => $orderItem->unit_price,
                        'subtotal' => $orderItem->subtotal,
                    ]);
                }

                if ($invoice->payment_status !== 'Cancelled') {
                    $this->applyLedgerEntries($invoice);
                }
                app(LoyaltyService::class)->awardForInvoice($invoice->fresh(['salesOrder.customer']));
                SalesAuditLog::record($invoice, 'invoice_created', null, $invoice->fresh()->toArray());
            });
        } catch (RuntimeException $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'order_id' => $e->getMessage(),
                ]);
        }

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load([
            'salesOrder.customer',
            'employee',
            'inventoryTransactions.product',
            'financeTransactions',
            'items.product',
            'creditNotes.items.invoiceItem.product',
        ]);

        return view(
            'sales.generate-invoice',
            compact('invoice')
        );
    }

    public function edit(
        Invoice $invoice
    ): View {

        return view(
            'sales.create-invoice',
            [
                'invoice' => $invoice,
                'salesOrders' => SalesOrder::with('customer')->get(),
                'selectedOrder' => null,
                'isEdit' => true,
            ]
        );
    }

    public function update(
        UpdateInvoiceRequest $request,
        Invoice $invoice
    ): RedirectResponse {

        $validated = $request->validated();
        $order = $invoice->salesOrder()->with('items')->firstOrFail();
        $oldValues = $invoice->toArray();
        $wasCancelled = $invoice->payment_status === 'Cancelled';
        $willBeCancelled = $validated['payment_status'] === 'Cancelled';
        $isManager = in_array(strtolower((string) $request->session()->get('employee_role')), ['manager', 'administrator', 'admin', 'owner', 'supervisor'], true);

        if (! $wasCancelled && $willBeCancelled && ! $isManager && ! SalesApproval::query()->where([
            'approvable_type' => Invoice::class,
            'approvable_id' => $invoice->invoice_id,
            'action' => 'invoice_cancellation',
            'status' => 'Approved',
        ])->exists()) {
            SalesApproval::query()->firstOrCreate([
                'requested_by' => $validated['employee_id'],
                'approvable_type' => Invoice::class,
                'approvable_id' => $invoice->invoice_id,
                'action' => 'invoice_cancellation',
                'status' => 'Pending',
            ], ['reason' => 'Invoice cancellation requested from the edit form.']);

            return back()->withInput()->with('success', 'Invoice cancellation was submitted for manager approval.');
        }

        try {
            DB::transaction(function () use ($invoice, $validated, $oldValues, $wasCancelled, $willBeCancelled): void {
                if (! $wasCancelled && $willBeCancelled) {
                    app(LoyaltyService::class)->reverseInvoice($invoice->load('salesOrder.customer.loyaltyProgram'));
                    $this->reverseLedgerEntries($invoice);
                } elseif ($wasCancelled && ! $willBeCancelled) {
                    $this->applyLedgerEntries($invoice);
                }

                $invoice->update([
                    'employee_id' => $validated['employee_id'],
                    'invoice_date' => $validated['invoice_date'],
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => $validated['payment_status'],
                    'subtotal' => $invoice->subtotal,
                    'discount' => $invoice->discount,
                    'tax' => $invoice->tax,
                    'shipping_fee' => $invoice->shipping_fee,
                    'total_amount' => $invoice->total_amount,
                ]);

                if (! $willBeCancelled) {
                    $invoice->inventoryTransactions()->update([
                        'transaction_date' => $validated['invoice_date'],
                    ]);
                    $invoice->financeTransactions()->where('payment_method', '!=', 'Credit Note')->update([
                        'amount' => $invoice->total_amount,
                        'payment_method' => $validated['payment_method'],
                        'transaction_date' => $validated['invoice_date'],
                    ]);
                }
                SalesAuditLog::record($invoice, 'invoice_updated', $oldValues, $invoice->fresh()->toArray());
                app(LoyaltyService::class)->awardForInvoice($invoice->fresh(['salesOrder.customer']));
            });
        } catch (RuntimeException $exception) {
            return back()->withInput()->withErrors(['order_id' => $exception->getMessage()]);
        }

        return redirect()
            ->route('invoices.index')
            ->with(
                'success',
                'Invoice updated successfully.'
            );
    }

    public function destroy(
        Request $request,
        Invoice $invoice
    ): RedirectResponse {
        if ($invoice->creditNotes()->exists()) {
            return back()->withErrors(['invoice' => 'Invoices with issued credit notes must be retained for audit history.']);
        }

        $employeeId = (int) ($request->session()->get('employee_id') ?? Employee::query()->value('employee_id'));
        $isManager = in_array(strtolower((string) $request->session()->get('employee_role')), ['manager', 'administrator', 'admin', 'owner', 'supervisor'], true);
        $isApproved = SalesApproval::query()->where([
            'approvable_type' => Invoice::class,
            'approvable_id' => $invoice->invoice_id,
            'action' => 'invoice_cancellation',
            'status' => 'Approved',
        ])->exists();

        if (! $isManager && ! $isApproved) {
            SalesApproval::query()->firstOrCreate([
                'requested_by' => $employeeId,
                'approvable_type' => Invoice::class,
                'approvable_id' => $invoice->invoice_id,
                'action' => 'invoice_cancellation',
                'status' => 'Pending',
            ], ['reason' => 'Invoice cancellation requested from the invoice list.']);

            return back()->with('success', 'Invoice cancellation was submitted for manager approval.');
        }

        DB::transaction(function () use ($invoice) {
            if ($invoice->payment_status !== 'Cancelled') {
                app(LoyaltyService::class)->reverseInvoice($invoice->load('salesOrder.customer.loyaltyProgram'));
                $this->reverseLedgerEntries($invoice);
            }
            SalesAuditLog::record($invoice, 'invoice_cancelled', $invoice->toArray(), null, 'Approved invoice cancellation');
            $invoice->update(['payment_status' => 'Cancelled']);
        });

        return redirect()
            ->route('invoices.index')
            ->with(
                'success',
                'Invoice cancelled successfully. Its audit record was retained.'
            );
    }

    private function applyLedgerEntries(Invoice $invoice): void
    {
        $invoice->loadMissing('items');
        foreach ($invoice->items as $invoiceItem) {
            $product = Product::query()
                ->where('product_id', $invoiceItem->product_id)
                ->lockForUpdate()
                ->first();

            if (! $product) {
                throw new RuntimeException("Product #{$invoiceItem->product_id} no longer exists.");
            }

            if ((int) $product->stock_quantity < (int) $invoiceItem->quantity) {
                throw new RuntimeException(
                    "Insufficient stock for \"{$product->product_name}\": have {$product->stock_quantity}, need {$invoiceItem->quantity}."
                );
            }

            InventoryTransaction::query()->create([
                'invoice_id' => $invoice->invoice_id,
                'product_id' => $invoiceItem->product_id,
                'quantity_out' => $invoiceItem->quantity,
                'transaction_date' => $invoice->invoice_date,
            ]);
            $product->decrement('stock_quantity', $invoiceItem->quantity);
        }

        FinanceTransaction::query()->create([
            'invoice_id' => $invoice->invoice_id,
            'amount' => $invoice->total_amount,
            'payment_method' => $invoice->payment_method,
            'transaction_date' => $invoice->invoice_date,
        ]);
    }

    private function reverseLedgerEntries(Invoice $invoice): void
    {
        foreach ($invoice->inventoryTransactions()->lockForUpdate()->get() as $transaction) {
            Product::query()
                ->where('product_id', $transaction->product_id)
                ->increment('stock_quantity', $transaction->quantity_out);
        }

        $invoice->inventoryTransactions()->delete();
        $invoice->financeTransactions()->delete();
    }

    private function invoiceableItems(SalesOrder $order, array $requestedQuantities)
    {
        $previousQuantities = $order->invoices
            ->where('payment_status', '!=', 'Cancelled')
            ->flatMap->items
            ->groupBy(fn ($item) => $item->order_item_id ?: 'product-'.$item->product_id)
            ->map(fn ($items) => (int) $items->sum('quantity'));

        return $order->items->map(function ($item) use ($previousQuantities, $requestedQuantities) {
            $remaining = max(0, (int) $item->quantity - (int) ($previousQuantities[$item->order_item_id] ?? $previousQuantities['product-'.$item->product_id] ?? 0));
            $quantity = array_key_exists($item->order_item_id, $requestedQuantities)
                ? (int) $requestedQuantities[$item->order_item_id]
                : $remaining;

            if ($quantity < 0 || $quantity > $remaining) {
                throw new RuntimeException("Invoice quantity for product #{$item->product_id} exceeds the remaining quantity.");
            }

            if ($quantity === 0) {
                return null;
            }

            $copy = clone $item;
            $copy->quantity = $quantity;
            $copy->subtotal = round((float) $item->unit_price * $quantity, 2);

            return $copy;
        })->filter()->values()->tap(function ($items): void {
            if ($items->isEmpty()) {
                throw new RuntimeException('Enter a quantity for at least one remaining order item.');
            }
        });
    }
}
