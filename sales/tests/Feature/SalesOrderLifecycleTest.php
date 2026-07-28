<?php

use App\Models\Customer;
use App\Models\Employee;
use App\Models\LoyaltyProgram;
use App\Models\PricingRule;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\Reward;
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
        'category' => 'Hardware',
        'unit_price' => 1000,
        'stock_quantity' => 20,
        'product_status' => 'Active',
    ]);
});

test('product categories are available when creating orders and quotations', function (): void {
    $this->get(route('sales.create'))
        ->assertOk()
        ->assertSee('All categories')
        ->assertSee('Hardware');

    $this->get(route('quotations.create'))
        ->assertOk()
        ->assertSee('All categories')
        ->assertSee('Hardware');
});

test('inactive pricing rules are not shown when creating a sales order', function (): void {
    PricingRule::query()->create([
        'rule_name' => 'Active order discount',
        'discount_type' => 'Percentage',
        'discount_value' => 10,
        'tax_rate' => 12,
        'start_date' => now()->subDay(),
        'end_date' => now()->addDay(),
        'status' => 'Active',
    ]);

    PricingRule::query()->create([
        'rule_name' => 'Inactive order discount',
        'discount_type' => 'Percentage',
        'discount_value' => 15,
        'tax_rate' => 12,
        'start_date' => now()->subDay(),
        'end_date' => now()->addDay(),
        'status' => 'Inactive',
    ]);

    PricingRule::query()->create([
        'rule_name' => 'Expired order discount',
        'discount_type' => 'Percentage',
        'discount_value' => 15,
        'tax_rate' => 12,
        'start_date' => now()->subDays(2),
        'end_date' => now()->subDay(),
        'status' => 'Active',
    ]);

    $this->get(route('sales.create'))
        ->assertOk()
        ->assertSee('Active order discount')
        ->assertDontSee('Inactive order discount')
        ->assertDontSee('Expired order discount');
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

test('an eligible loyalty reward applies its sales discount and deducts points once', function (): void {
    $loyalty = LoyaltyProgram::query()->create([
        'customer_id' => $this->customer->customer_id,
        'membership_level' => 'Bronze',
        'available_points' => 1000,
        'points_earned' => 1000,
        'points_redeemed' => 0,
        'enrollment_date' => today(),
    ]);
    $reward = Reward::query()->create([
        'name' => '₱200 Loyalty Voucher',
        'points_required' => 200,
        'discount_type' => 'Fixed',
        'discount_value' => 200,
        'status' => 'available',
    ]);

    $this->withSession(['employee_id' => $this->employee->employee_id])
        ->post(route('sales.store'), [
            'customer_id' => $this->customer->customer_id,
            'order_date' => today()->toDateString(),
            'product_id' => [$this->product->product_id],
            'qty' => [1],
            'price' => [1000],
            'discount' => 0,
            'tax' => 12,
            'status' => 'pending',
            'reward_id' => $reward->reward_id,
        ])->assertRedirect(route('sales.index'));

    $order = SalesOrder::query()->firstOrFail();

    expect((float) $order->discount)->toBe(200.0)
        ->and((float) $order->total_amount)->toBe(896.0)
        ->and($loyalty->fresh()->available_points)->toBe(800)
        ->and($loyalty->fresh()->points_redeemed)->toBe(200);

    $this->assertDatabaseHas('reward_redemptions', [
        'order_id' => $order->order_id,
        'reward_id' => $reward->reward_id,
        'points_used' => 200,
    ]);
});

test('a paid processed sales order automatically creates its invoice', function (): void {
    $this->withSession(['employee_id' => $this->employee->employee_id])
        ->post(route('sales.store'), [
            'customer_id' => $this->customer->customer_id,
            'order_date' => today()->toDateString(),
            'product_id' => [$this->product->product_id],
            'qty' => [1],
            'price' => [1000],
            'discount' => 0,
            'tax' => 12,
            'status' => 'processed',
            'payment_method' => 'Cash',
            'payment_status' => 'Paid',
        ])
        ->assertRedirect(route('sales.index'));

    $order = SalesOrder::query()->firstOrFail();

    $this->assertDatabaseHas('invoices', [
        'order_id' => $order->order_id,
        'payment_method' => 'Cash',
        'payment_status' => 'Paid',
    ]);
    expect($this->product->fresh()->stock_quantity)->toBe(19);
});

test('a sales order can move directly to a later fulfillment status', function (): void {
    $order = SalesOrder::query()->create([
        'order_number' => 'SO-STATUS-001',
        'customer_id' => $this->customer->customer_id,
        'employee_id' => $this->employee->employee_id,
        'order_date' => today(),
        'order_status' => 'pending',
        'subtotal' => 1000,
        'discount' => 0,
        'tax' => 120,
        'shipping_fee' => 0,
        'total_amount' => 1120,
    ]);

    $this->patch(route('sales.update-status', $order), ['status' => 'shipped'])
        ->assertRedirect(route('sales.profile', $order));

    expect($order->fresh()->order_status)->toBe('shipped');
});

test('a processed order edit form displays its archived customer', function (): void {
    $this->customer->update(['customer_status' => 'Archived']);

    $order = SalesOrder::query()->create([
        'order_number' => 'SO-EDIT-ARCHIVED-001',
        'customer_id' => $this->customer->customer_id,
        'employee_id' => $this->employee->employee_id,
        'order_date' => today(),
        'order_status' => 'processed',
        'subtotal' => 1000,
        'discount' => 0,
        'tax' => 120,
        'shipping_fee' => 0,
        'total_amount' => 1120,
    ]);

    $this->get(route('sales.edit', $order))
        ->assertOk()
        ->assertSee('Lifecycle Customer')
        ->assertSeeHtml('value="'.$this->customer->customer_id.'"');
});

test('a processed order edit form preselects its saved products', function (): void {
    $order = SalesOrder::query()->create([
        'order_number' => 'SO-EDIT-PRODUCT-001',
        'customer_id' => $this->customer->customer_id,
        'employee_id' => $this->employee->employee_id,
        'order_date' => today(),
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

    $this->get(route('sales.edit', $order))
        ->assertOk()
        ->assertSee('Lifecycle Product')
        ->assertSeeHtml('value="'.$this->product->product_id.'"')
        ->assertSeeHtml('value="2"');
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

test('a quotation can be saved with its calculated item totals', function (): void {
    $this->withSession(['employee_id' => $this->employee->employee_id])
        ->post(route('quotations.store'), [
            'customer_id' => $this->customer->customer_id,
            'quotation_date' => now()->toDateString(),
            'valid_until' => now()->addDays(30)->toDateString(),
            'status' => 'draft',
            'discount' => 0,
            'tax' => 12,
            'product_id' => [$this->product->product_id],
            'qty' => [2],
            'price' => [1000],
        ])
        ->assertRedirect(route('quotations.index'));

    $quotation = Quotation::query()->firstOrFail();

    expect($quotation->quotation_number)->toBe('QT-00001')
        ->and((float) $quotation->total_amount)->toBe(2240.0)
        ->and($quotation->items)->toHaveCount(1)
        ->and((float) $quotation->items->first()->unit_price)->toBe(1000.0);
});

test('accepting a quotation without conversion leaves it ready to convert later', function (): void {
    $quotation = Quotation::query()->create([
        'quotation_number' => 'QT-LIFECYCLE-ACCEPT',
        'customer_id' => $this->customer->customer_id,
        'employee_id' => $this->employee->employee_id,
        'quotation_date' => now(),
        'valid_until' => now()->addWeek(),
        'subtotal' => 1000,
        'discount' => 0,
        'tax' => 120,
        'shipping_fee' => 0,
        'total_amount' => 1120,
        'quotation_status' => 'sent',
    ]);
    $quotation->items()->create([
        'product_id' => $this->product->product_id,
        'quantity' => 1,
        'unit_price' => 1000,
        'discount' => 0,
        'subtotal' => 1000,
    ]);

    $this->patch(route('quotations.update-status', $quotation), ['status' => 'accepted'])
        ->assertRedirect(route('quotations.index'));

    $this->assertDatabaseHas('quotations', [
        'quotation_id' => $quotation->quotation_id,
        'quotation_status' => 'accepted',
    ]);
    $this->assertDatabaseMissing('sales_orders', ['quotation_id' => $quotation->quotation_id]);

    $this->post(route('quotations.convert', $quotation))
        ->assertRedirect();

    $this->assertDatabaseHas('sales_orders', [
        'quotation_id' => $quotation->quotation_id,
        'order_status' => 'pending',
    ]);
});

test('an expired quotation can be reopened after its validity is extended', function (): void {
    $quotation = Quotation::query()->create([
        'quotation_number' => 'QT-LIFECYCLE-EXPIRED',
        'customer_id' => $this->customer->customer_id,
        'employee_id' => $this->employee->employee_id,
        'quotation_date' => now()->subWeek(),
        'valid_until' => now()->subDay(),
        'subtotal' => 1000,
        'discount' => 0,
        'tax' => 120,
        'shipping_fee' => 0,
        'total_amount' => 1120,
        'quotation_status' => 'expired',
    ]);
    $quotation->items()->create([
        'product_id' => $this->product->product_id,
        'quantity' => 1,
        'unit_price' => 1000,
        'discount' => 0,
        'subtotal' => 1000,
    ]);

    $this->withSession(['employee_id' => $this->employee->employee_id])
        ->put(route('quotations.update', $quotation), [
            'customer_id' => $this->customer->customer_id,
            'quotation_date' => now()->toDateString(),
            'valid_until' => now()->addWeek()->toDateString(),
            'status' => 'sent',
            'discount' => 0,
            'tax' => 12,
            'product_id' => [$this->product->product_id],
            'qty' => [1],
            'price' => [1000],
        ])
        ->assertRedirect(route('quotations.index'));

    $this->assertDatabaseHas('quotations', [
        'quotation_id' => $quotation->quotation_id,
        'quotation_status' => 'sent',
    ]);
});

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
