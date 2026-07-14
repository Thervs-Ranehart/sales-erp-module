<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\FinanceTransaction;
use App\Models\Invoice;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\SalesOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class InvoiceController extends Controller
{
    public function index(): View
    {
        $invoices = Invoice::with([
            'salesOrder.customer',
            'employee'
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

    public function create(): View
    {
        $invoice = new Invoice();

       return view(
    'sales.create-invoice',
    [
        'invoice' => $invoice,
        'salesOrders' => SalesOrder::with('customer')->get(),
        'isEdit' => false,
    ]
);
    }


   public function store(StoreInvoiceRequest $request)
{
    $validated = $request->validated();

    if (Invoice::where('order_id', $validated['order_id'])->exists()) {

        return back()
            ->withInput()
            ->withErrors([
                'order_id' => 'This Sales Order already has an invoice.'
            ]);
    }

    $order = SalesOrder::with('items')->findOrFail($validated['order_id']);

    try {
        DB::transaction(function () use ($validated, $order) {

            $latest = Invoice::latest('invoice_id')->first();

            $nextNumber = $latest
                ? $latest->invoice_id + 1
                : 1;

            $validated['invoice_number'] =
                'INV-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            $invoice = Invoice::create($validated);

            foreach ($order->items as $orderItem) {
                $invoice->items()->create([
                    'product_id' => $orderItem->product_id,
                    'quantity'   => $orderItem->quantity,
                    'unit_price' => $orderItem->unit_price,
                    'subtotal'   => $orderItem->subtotal,
                ]);
            }

            // --- ERP sync: inventory + finance -------------------------
            // Everything below runs inside the same transaction as the
            // invoice/invoice-item creation above. Any failure (including
            // an insufficient-stock exception) rolls back the invoice too.

            foreach ($order->items as $orderItem) {
                // Lock the product row so concurrent invoices can't both
                // read a stale stock_quantity and oversell it.
                $product = Product::where('product_id', $orderItem->product_id)
                    ->lockForUpdate()
                    ->first();

                if (! $product) {
                    throw new RuntimeException(
                        "Product #{$orderItem->product_id} no longer exists."
                    );
                }

                if ($product->stock_quantity < $orderItem->quantity) {
                    throw new RuntimeException(
                        "Insufficient stock for \"{$product->product_name}\": "
                            . "have {$product->stock_quantity}, need {$orderItem->quantity}."
                    );
                }

                InventoryTransaction::create([
                    'invoice_id'        => $invoice->invoice_id,
                    'product_id'        => $orderItem->product_id,
                    'quantity_out'      => $orderItem->quantity,
                    'transaction_date'  => $invoice->invoice_date,
                ]);

                $product->decrement('stock_quantity', $orderItem->quantity);
            }

            FinanceTransaction::create([
                'invoice_id'       => $invoice->invoice_id,
                'amount'           => $invoice->total_amount,
                'payment_method'   => $invoice->payment_method,
                'transaction_date' => $invoice->invoice_date,
            ]);
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
                'isEdit' => true,
            ]
        );
    }

    public function update(
        UpdateInvoiceRequest $request,
        Invoice $invoice
    ): RedirectResponse {

        $invoice->update(
            $request->validated()
        );

        return redirect()
            ->route('invoices.index')
            ->with(
                'success',
                'Invoice updated successfully.'
            );
    }

    public function destroy(
        Invoice $invoice
    ): RedirectResponse {

        // The invoice has child rows in invoice_items, inventory_transactions
        // and finance_transactions, all with foreign keys pointing back at
        // invoices with no cascade rule. Deleting the invoice directly trips
        // a foreign key constraint violation, so the related ERP records
        // must be removed first, inside the same transaction.
        DB::transaction(function () use ($invoice) {
            $invoice->inventoryTransactions()->delete();
            $invoice->financeTransactions()->delete();
            $invoice->items()->delete();
            $invoice->delete();
        });

        return redirect()
            ->route('invoices.index')
            ->with(
                'success',
                'Invoice deleted successfully.'
            );
    }


}