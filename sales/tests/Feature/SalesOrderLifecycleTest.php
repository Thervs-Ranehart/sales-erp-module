<?php

use App\Models\Customer;
use App\Models\Employee;
use App\Models\PricingRule;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\SalesOrder;
use App\Services\PricingCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->employee = Employee::query()->create([
        'username' => 'sales-lifecycle-user',
        'password_hash' => password_hash('password', PASSWORD_BCRYPT),
        'first_name' => 'Sales',
        'last_name' => 'Agent',
        'department' => 'Sales',
        'role' => 'Sales Representative',
        'employee_status' => 'Active',
    ]);

    $this->customer = Customer::query()->create([
        'first_name' => 'Lifecycle',
        'last_name' => 'Customer',
        'email' => 'lifecycle@example.test',
    ]);

    $this->product = Product::query()->create([
        'product_name' => 'Lifecycle Product',
        'unit_price' => 1000,
        'stock_quantity' => 20,
        'product_status' => 'Active',
    ]);
});

test('pricing uses authoritative product prices and applies fixed discounts', function (): void {
    $rule = PricingRule::query()->create([
        'rule_name' => 'Fixed 250',
        'discount_type' => 'Fixed',
        'discount_value' => 250,
        'tax_rate' => 12,
        'start_date' => now()->subDay(),
        'end_date' => now()->addDay(),
        'status' => 'Active',
    ]);

    $totals = app(PricingCalculator::class)->calculate(
        [$this->product->product_id],
        [2],
        now(),
        $rule->pricing_rule_id,
    );

    expect($totals)
        ->subtotal->toBe(2000.0)
        ->discount->toBe(250.0)
        ->tax->toBe(210.0)
        ->total->toBe(1960.0)
        ->and($totals['items'][0]['unit_price'])->toBe(1000.0);
});

test('inactive and out of date pricing rules are rejected', function (array $overrides): void {
    $rule = PricingRule::query()->create(array_merge([
        'rule_name' => 'Unavailable rule',
        'discount_type' => 'Percentage',
        'discount_value' => 10,
        'tax_rate' => 12,
        'start_date' => now()->subDay(),
        'end_date' => now()->addDay(),
        'status' => 'Active',
    ], $overrides));

    expect(fn () => app(PricingCalculator::class)->calculate(
        [$this->product->product_id],
        [1],
        now(),
        $rule->pricing_rule_id,
    ))->toThrow(ValidationException::class);
})->with([
    'inactive' => [['status' => 'Inactive']],
    'future' => [['start_date' => now()->addDay(), 'end_date' => now()->addDays(2)]],
    'expired' => [['start_date' => now()->subDays(2), 'end_date' => now()->subDay()]],
]);

test('an accepted quotation converts once and copies its backend records', function (): void {
    $quotation = Quotation::query()->create([
        'quotation_number' => 'QT-LIFECYCLE-001',
        'customer_id' => $this->customer->customer_id,
        'employee_id' => $this->employee->employee_id,
        'quotation_date' => now(),
        'valid_until' => now()->addWeek(),
        'subtotal' => 2000,
        'discount' => 0,
        'tax' => 240,
        'shipping_fee' => 0,
        'total_amount' => 2240,
        'quotation_status' => 'accepted',
    ]);
    $quotation->items()->create([
        'product_id' => $this->product->product_id,
        'quantity' => 2,
        'unit_price' => 1000,
        'discount' => 0,
        'subtotal' => 2000,
    ]);

    $this->withSession(['employee_id' => $this->employee->employee_id])
        ->post(route('quotations.convert', $quotation))
        ->assertRedirect();

    $order = SalesOrder::query()->where('quotation_id', $quotation->quotation_id)->firstOrFail();

    expect($order->customer_id)->toBe($this->customer->customer_id)
        ->and($order->items)->toHaveCount(1)
        ->and((float) $order->total_amount)->toBe(2240.0);

    $this->get(route('quotations.show', $quotation))->assertOk();
    $this->post(route('quotations.convert', $quotation))->assertRedirect();

    expect(SalesOrder::query()->where('quotation_id', $quotation->quotation_id)->count())->toBe(1);
});

test('pending orders cannot be invoiced', function (): void {
    $order = SalesOrder::query()->create([
        'order_number' => 'SO-LIFECYCLE-001',
        'customer_id' => $this->customer->customer_id,
        'employee_id' => $this->employee->employee_id,
        'order_date' => now(),
        'order_status' => 'pending',
        'subtotal' => 1000,
        'discount' => 0,
        'tax' => 120,
        'shipping_fee' => 0,
        'total_amount' => 1120,
    ]);

    $this->withSession(['employee_id' => $this->employee->employee_id])
        ->post(route('invoices.store'), [
            'order_id' => $order->order_id,
            'invoice_date' => now()->toDateString(),
            'payment_method' => 'Cash',
            'payment_status' => 'Pending',
        ])
        ->assertSessionHasErrors('order_id');

    $this->assertDatabaseCount('invoices', 0);
});

test('invoice deletion reverses inventory and finance entries', function (): void {
    $order = SalesOrder::query()->create([
        'order_number' => 'SO-LIFECYCLE-002',
        'customer_id' => $this->customer->customer_id,
        'employee_id' => $this->employee->employee_id,
        'order_date' => now(),
        'order_status' => 'processed',
        'subtotal' => 2000,
        'discount' => 0,
        'tax' => 240,
        'shipping_fee' => 0,
        'total_amount' => 2240,
    ]);
    $order->items()->create([
        'product_id' => $this->product->product_id,
        'quantity' => 2,
        'unit_price' => 1000,
        'discount' => 0,
        'subtotal' => 2000,
    ]);

    $this->withSession(['employee_id' => $this->employee->employee_id])
        ->post(route('invoices.store'), [
            'order_id' => $order->order_id,
            'invoice_date' => now()->toDateString(),
            'payment_method' => 'Cash',
            'payment_status' => 'Paid',
        ])
        ->assertRedirect(route('invoices.index'));

    $invoice = $order->invoices()->firstOrFail();

    expect($this->product->fresh()->stock_quantity)->toBe(18);
    $this->assertDatabaseHas('finance_transactions', ['invoice_id' => $invoice->invoice_id, 'amount' => 2240]);

    $this->withSession([
        'employee_id' => $this->employee->employee_id,
        'employee_role' => 'Manager',
    ])->delete(route('invoices.destroy', $invoice))->assertRedirect(route('invoices.index'));

    expect($this->product->fresh()->stock_quantity)->toBe(20);
    $this->assertDatabaseMissing('finance_transactions', ['invoice_id' => $invoice->invoice_id]);
    $this->assertDatabaseMissing('inventory_transactions', ['invoice_id' => $invoice->invoice_id]);
});
