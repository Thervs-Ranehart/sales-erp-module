<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Models\SalesOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

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
    });

    return redirect()
        ->route('invoices.index')
        ->with('success', 'Invoice created successfully.');
}
   public function show(Invoice $invoice): View
{
    $invoice->load([
        'salesOrder.customer',
        'employee',
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

        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with(
                'success',
                'Invoice deleted successfully.'
            );
    }


}