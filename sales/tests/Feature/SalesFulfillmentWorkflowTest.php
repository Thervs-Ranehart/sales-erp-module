<?php

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\SalesApproval;
use App\Models\SalesOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->employee = Employee::query()->create([
        'username' => 'fulfillment-user',
        'password_hash' => password_hash('password', PASSWORD_BCRYPT),
        'first_name' => 'Fulfillment',
        'last_name' => 'User',
        'department' => 'Sales',
        'role' => 'Sales Representative',
        'employee_status' => 'Active',
    ]);
    $this->customer = Customer::query()->create([
        'first_name' => 'Fulfillment',
        'last_name' => 'Customer',
        'email' => 'fulfillment@example.test',
    ]);
    $this->product = Product::query()->create([
        'product_name' => 'Fulfillment Product',
        'unit_price' => 100,
        'stock_quantity' => 20,
        'product_status' => 'Active',
    ]);
    $this->order = SalesOrder::query()->create([
        'order_number' => 'SO-FULFILLMENT-001',
        'customer_id' => $this->customer->customer_id,
        'employee_id' => $this->employee->employee_id,
        'order_date' => now(),
        'order_status' => 'processed',
        'subtotal' => 1000,
        'discount' => 0,
        'tax' => 120,
        'shipping_fee' => 0,
        'total_amount' => 1120,
    ]);
    $this->orderItem = $this->order->items()->create([
        'product_id' => $this->product->product_id,
        'quantity' => 10,
        'unit_price' => 100,
        'discount' => 0,
        'subtotal' => 1000,
    ]);
});

test('partial shipments expose backorders and synchronize order status', function (): void {
    $session = ['employee_id' => $this->employee->employee_id, 'employee_role' => 'Warehouse Staff'];

    $this->withSession($session)->post(route('sales.shipments.store', $this->order), [
        'carrier' => 'ERP Courier',
        'tracking_number' => 'TRACK-001',
        'shipment_status' => 'Shipped',
        'quantities' => [$this->orderItem->order_item_id => 4],
    ])->assertRedirect();

    expect($this->order->fresh()->order_status)->toBe('shipped')
        ->and($this->order->fresh()->shippedQuantityFor($this->orderItem->order_item_id))->toBe(4);

    $this->withSession($session)->post(route('sales.shipments.store', $this->order), [
        'shipment_status' => 'Delivered',
        'proof_of_delivery' => 'Received by customer',
        'quantities' => [$this->orderItem->order_item_id => 6],
    ])->assertRedirect();

    $firstShipment = $this->order->shipments()->oldest('shipment_id')->firstOrFail();
    $this->withSession($session)->patch(route('sales.shipments.update', $firstShipment), [
        'shipment_status' => 'Delivered',
        'carrier' => $firstShipment->carrier,
        'tracking_number' => $firstShipment->tracking_number,
        'proof_of_delivery' => 'Received by customer',
    ])->assertRedirect();

    expect($this->order->fresh()->order_status)->toBe('delivered')
        ->and($this->order->shipments()->count())->toBe(2);
    $this->assertDatabaseCount('sales_audit_logs', 3);
});

test('an order can be invoiced in multiple stock-safe portions', function (): void {
    $session = ['employee_id' => $this->employee->employee_id, 'employee_role' => 'Sales Representative'];
    $payload = [
        'order_id' => $this->order->order_id,
        'invoice_date' => now()->toDateString(),
        'payment_method' => 'Cash',
        'payment_status' => 'Paid',
    ];

    $this->withSession($session)->post(route('invoices.store'), $payload + [
        'quantities' => [$this->orderItem->order_item_id => 4],
    ])->assertRedirect(route('invoices.index'));
    $this->withSession($session)->post(route('invoices.store'), $payload + [
        'quantities' => [$this->orderItem->order_item_id => 6],
    ])->assertRedirect(route('invoices.index'));

    expect($this->order->invoices()->count())->toBe(2)
        ->and($this->order->invoices()->sum('subtotal'))->toEqual(1000)
        ->and($this->product->fresh()->stock_quantity)->toBe(10);

    $this->withSession($session)->post(route('invoices.store'), $payload + [
        'quantities' => [$this->orderItem->order_item_id => 1],
    ])->assertSessionHasErrors('order_id');
});

test('credit notes restore stock and create a finance reversal', function (): void {
    $this->withSession([
        'employee_id' => $this->employee->employee_id,
        'employee_role' => 'Manager',
    ])->post(route('invoices.store'), [
        'order_id' => $this->order->order_id,
        'invoice_date' => now()->toDateString(),
        'payment_method' => 'Cash',
        'payment_status' => 'Paid',
        'quantities' => [$this->orderItem->order_item_id => 5],
    ])->assertRedirect(route('invoices.index'));

    $invoice = Invoice::query()->firstOrFail();
    $invoiceItem = $invoice->items()->firstOrFail();
    expect($this->product->fresh()->stock_quantity)->toBe(15);

    $this->withSession([
        'employee_id' => $this->employee->employee_id,
        'employee_role' => 'Manager',
    ])->post(route('invoices.credit-notes.store', $invoice), [
        'reason' => 'Two items returned',
        'quantities' => [$invoiceItem->invoice_item_id => 2],
    ])->assertRedirect();

    expect($this->product->fresh()->stock_quantity)->toBe(17);
    $this->assertDatabaseHas('credit_notes', ['invoice_id' => $invoice->invoice_id, 'amount' => 200]);
    $this->assertDatabaseHas('finance_transactions', ['invoice_id' => $invoice->invoice_id, 'amount' => -200]);
});

test('staff invoice cancellation requires manager approval', function (): void {
    $invoice = Invoice::query()->create([
        'invoice_number' => 'INV-APPROVAL-001',
        'order_id' => $this->order->order_id,
        'employee_id' => $this->employee->employee_id,
        'invoice_date' => now(),
        'payment_method' => 'Cash',
        'payment_status' => 'Cancelled',
        'subtotal' => 100,
        'discount' => 0,
        'tax' => 12,
        'shipping_fee' => 0,
        'total_amount' => 112,
    ]);

    $this->withSession([
        'employee_id' => $this->employee->employee_id,
        'employee_role' => 'Sales Representative',
    ])->delete(route('invoices.destroy', $invoice))->assertRedirect();

    $this->assertDatabaseHas('invoices', ['invoice_id' => $invoice->invoice_id]);
    $approval = SalesApproval::query()->firstOrFail();
    expect($approval->status)->toBe('Pending');

    $this->withSession([
        'employee_id' => $this->employee->employee_id,
        'employee_role' => 'Manager',
    ])->patch(route('sales.approvals.update', $approval), [
        'status' => 'Approved',
        'review_notes' => 'Verified cancellation',
    ])->assertRedirect();

    expect($approval->fresh()->status)->toBe('Approved');
});
