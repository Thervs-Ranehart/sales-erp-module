<?php

use App\Models\Customer;
use App\Models\LoyaltyProgram;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('VIP members appear in membership-level totals, including automatically updated members', function (): void {
    $existingVip = Customer::query()->create([
        'first_name' => 'Existing',
        'last_name' => 'VIP',
        'email' => 'existing-vip@example.test',
        'customer_status' => 'Active',
    ]);

    LoyaltyProgram::query()->create([
        'customer_id' => $existingVip->customer_id,
        'membership_level' => 'VIP',
        'available_points' => 3000,
        'points_earned' => 3000,
        'points_redeemed' => 0,
        'enrollment_date' => today(),
    ]);

    $this->get(route('crm.loyalty'))
        ->assertOk()
        ->assertViewHas('vipCount', 1)
        ->assertSee('VIP Tier');

    $newVip = Customer::query()->create([
        'first_name' => 'New',
        'last_name' => 'VIP',
        'email' => 'new-vip@example.test',
        'customer_status' => 'Active',
    ]);

    LoyaltyProgram::query()->create([
        'customer_id' => $newVip->customer_id,
        'membership_level' => 'VIP',
        'available_points' => 3000,
        'points_earned' => 3000,
        'points_redeemed' => 0,
        'enrollment_date' => today(),
    ]);

    $this->get(route('crm.loyalty'))
        ->assertOk()
        ->assertViewHas('vipCount', 2);
});
