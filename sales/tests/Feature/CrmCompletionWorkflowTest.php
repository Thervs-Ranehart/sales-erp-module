<?php

use App\Models\Customer;
use App\Models\CustomerBehaviorAnalysis;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Reward;
use App\Models\SalesOrder;
use App\Services\RetentionAutomationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->employee = Employee::query()->create([
        'username' => 'crm-completion-user',
        'password_hash' => password_hash('password', PASSWORD_BCRYPT),
        'first_name' => 'CRM',
        'last_name' => 'Specialist',
        'department' => 'CRM',
        'role' => 'Manager',
        'employee_status' => 'Active',
    ]);
    $this->customer = Customer::query()->create([
        'first_name' => 'Complete',
        'last_name' => 'Customer',
        'email' => 'complete@example.test',
        'customer_status' => 'Active',
    ]);
    $this->customer->profile()->create([
        'preferences' => 'Email promotions',
        'preferred_contact' => 'Email',
        'preferred_product_category' => 'Hardware',
        'marketing_consent' => true,
    ]);
    $this->product = Product::query()->create([
        'product_name' => 'CRM Product',
        'category' => 'Hardware',
        'unit_price' => 1000,
        'stock_quantity' => 20,
        'product_status' => 'Active',
    ]);
});

test('paid invoices award points idempotently and recalculate loyalty tier', function (): void {
    $order = SalesOrder::query()->create([
        'order_number' => 'SO-CRM-001',
        'customer_id' => $this->customer->customer_id,
        'employee_id' => $this->employee->employee_id,
        'order_date' => now(),
        'order_status' => 'processed',
        'subtotal' => 60000,
        'discount' => 0,
        'tax' => 0,
        'shipping_fee' => 0,
        'total_amount' => 60000,
    ]);
    $item = $order->items()->create([
        'product_id' => $this->product->product_id,
        'quantity' => 1,
        'unit_price' => 60000,
        'discount' => 0,
        'subtotal' => 60000,
    ]);
    $this->product->update(['stock_quantity' => 2]);

    $payload = [
        'order_id' => $order->order_id,
        'invoice_date' => now()->toDateString(),
        'payment_method' => 'Cash',
        'payment_status' => 'Paid',
        'quantities' => [$item->order_item_id => 1],
    ];
    $this->withSession(['employee_id' => $this->employee->employee_id, 'employee_role' => 'Manager'])
        ->post(route('invoices.store'), $payload)->assertRedirect(route('invoices.index'));

    $loyalty = $this->customer->fresh()->loyaltyProgram;
    expect($loyalty->available_points)->toBe(600)
        ->and($loyalty->membership_level)->toBe('Silver')
        ->and($loyalty->pointTransactions()->count())->toBe(1);
});

test('reward redemption deducts points and cancellation restores them', function (): void {
    $loyalty = $this->customer->loyaltyProgram()->create([
        'membership_level' => 'Bronze',
        'points_earned' => 500,
        'points_redeemed' => 0,
        'available_points' => 500,
        'enrollment_date' => now(),
    ]);
    $reward = Reward::query()->create([
        'name' => 'Voucher',
        'points_required' => 200,
        'status' => 'available',
        'icon' => 'bi-gift',
    ]);

    $this->withSession(['employee_id' => $this->employee->employee_id])
        ->post(route('crm.redemptions.store'), [
            'loyalty_id' => $loyalty->loyalty_id,
            'reward_id' => $reward->reward_id,
            'quantity' => 1,
        ])->assertRedirect();

    $redemption = $loyalty->redemptions()->firstOrFail();
    expect($loyalty->fresh()->available_points)->toBe(300);

    $this->patch(route('crm.redemptions.cancel', $redemption))->assertRedirect();
    expect($loyalty->fresh()->available_points)->toBe(500)
        ->and($redemption->fresh()->status)->toBe('Cancelled');
});

test('campaign targeting enforces segment and marketing consent', function (): void {
    $this->customer->segments()->create(['segment_name' => 'High-Value', 'last_updated' => now()]);
    $excluded = Customer::query()->create([
        'first_name' => 'No',
        'last_name' => 'Consent',
        'email' => 'no-consent@example.test',
        'customer_status' => 'Active',
    ]);
    $excluded->profile()->create(['marketing_consent' => false]);
    $excluded->segments()->create(['segment_name' => 'High-Value', 'last_updated' => now()]);

    $this->withSession(['employee_id' => $this->employee->employee_id])
        ->post(route('crm.campaigns.store'), [
            'campaign_name' => 'VIP Retention',
            'channel' => 'Email',
            'target_segment' => 'High-Value',
            'message' => 'Thank you for being a valued customer.',
        ])->assertRedirect();

    $this->assertDatabaseHas('campaign_recipients', ['customer_id' => $this->customer->customer_id]);
    $this->assertDatabaseMissing('campaign_recipients', ['customer_id' => $excluded->customer_id]);
});

test('customers can be archived and restored without losing history', function (): void {
    $this->withSession(['employee_id' => $this->employee->employee_id])
        ->patch(route('crm.directory.archive', $this->customer), ['archive_reason' => 'Duplicate inactive account'])
        ->assertRedirect();

    expect($this->customer->fresh()->customer_status)->toBe('Archived')
        ->and(Customer::query()->available()->whereKey($this->customer->customer_id)->exists())->toBeFalse();

    $this->patch(route('crm.directory.restore', $this->customer))->assertRedirect();
    expect($this->customer->fresh()->customer_status)->toBe('Active');
});

test('behavior predictions and retention automation are explainable and deduplicated', function (): void {
    $order = SalesOrder::query()->create([
        'order_number' => 'SO-CRM-OLD',
        'customer_id' => $this->customer->customer_id,
        'employee_id' => $this->employee->employee_id,
        'order_date' => now()->subDays(120),
        'order_status' => 'delivered',
        'subtotal' => 1000,
        'discount' => 0,
        'tax' => 0,
        'shipping_fee' => 0,
        'total_amount' => 1000,
    ]);
    $order->items()->create([
        'product_id' => $this->product->product_id,
        'quantity' => 1,
        'unit_price' => 1000,
        'discount' => 0,
        'subtotal' => 1000,
    ]);

    $analysis = CustomerBehaviorAnalysis::generateFor($this->customer->fresh(['salesOrders.items.product']));
    expect($analysis['churn_risk_score'])->toBeGreaterThan(0)
        ->and($analysis['retention_recommendation'])->not->toBeEmpty()
        ->and($analysis)->toHaveKeys(['spending_trend', 'predicted_90_day_value', 'recommended_product_category']);

    $service = app(RetentionAutomationService::class);
    expect($service->run())->toBe(1)
        ->and($service->run())->toBe(0);
    $this->assertDatabaseCount('communication_logs', 1);
    $this->assertDatabaseCount('notifications', 1);
});
