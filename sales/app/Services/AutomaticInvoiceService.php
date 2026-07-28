<?php

namespace App\Services;

use App\Models\FinanceTransaction;
use App\Models\InventoryTransaction;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\SalesAuditLog;
use App\Models\SalesOrder;
use RuntimeException;

class AutomaticInvoiceService
{
    public function createForProcessedOrder(SalesOrder $order, int $employeeId): Invoice
    {
        $existingInvoice = $order->invoices()->first();

        if ($existingInvoice) {
            return $existingInvoice;
        }

        $order->loadMissing('items', 'customer');

        if ($order->items->isEmpty()) {
            throw new RuntimeException('A sales order needs at least one item before an invoice can be created.');
        }

        $invoice = Invoice::query()->create([
            'invoice_number' => null,
            'order_id' => $order->order_id,
            'employee_id' => $employeeId,
            'invoice_date' => $order->order_date ?? today(),
            'payment_method' => $order->payment_method ?? 'Cash',
            'payment_status' => $order->payment_status ?? 'Pending',
            'subtotal' => $order->subtotal,
            'discount' => $order->discount,
            'tax' => $order->tax,
            'shipping_fee' => $order->shipping_fee ?? 0,
            'total_amount' => $order->total_amount,
        ]);
        $invoice->update([
            'invoice_number' => 'INV-'.str_pad((string) $invoice->invoice_id, 5, '0', STR_PAD_LEFT),
        ]);

        foreach ($order->items as $orderItem) {
            $invoice->items()->create([
                'order_item_id' => $orderItem->order_item_id,
                'product_id' => $orderItem->product_id,
                'quantity' => $orderItem->quantity,
                'unit_price' => $orderItem->unit_price,
                'subtotal' => $orderItem->subtotal,
            ]);
        }

        $this->applyLedgerEntries($invoice);
        app(LoyaltyService::class)->awardForInvoice($invoice->fresh(['salesOrder.customer']));
        SalesAuditLog::record($invoice, 'invoice_created', null, $invoice->fresh()->toArray());

        return $invoice;
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
}
