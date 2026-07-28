<?php

use App\Models\Customer;
use App\Models\LoyaltyProgram;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('customer type filters use loyalty membership levels and combine with search and status', function (): void {
    $vip = Customer::query()->create([
        'first_name' => 'Violet',
        'last_name' => 'Member',
        'email' => 'violet@example.test',
        'customer_status' => 'Active',
    ]);
    $corporate = Customer::query()->create([
        'first_name' => 'Corbin',
        'last_name' => 'Account',
        'email' => 'corbin@example.test',
        'customer_status' => 'Active',
    ]);
    $regular = Customer::query()->create([
        'first_name' => 'Riley',
        'last_name' => 'Customer',
        'email' => 'riley@example.test',
        'customer_status' => 'Active',
    ]);

    LoyaltyProgram::query()->create([
        'customer_id' => $vip->customer_id,
        'membership_level' => 'VIP',
        'available_points' => 3000,
        'points_earned' => 3000,
        'points_redeemed' => 0,
        'enrollment_date' => today(),
    ]);
    LoyaltyProgram::query()->create([
        'customer_id' => $corporate->customer_id,
        'membership_level' => 'Corporate',
        'available_points' => 0,
        'points_earned' => 0,
        'points_redeemed' => 0,
        'enrollment_date' => today(),
    ]);

    $this->get(route('crm.directory', ['type' => 'VIP', 'status' => 'Active', 'search' => 'Violet']))
        ->assertOk()
        ->assertSee('violet@example.test')
        ->assertDontSee('corbin@example.test')
        ->assertDontSee('riley@example.test');

    $this->get(route('crm.directory', ['type' => 'Corporate', 'status' => 'Active', 'search' => 'Corbin']))
        ->assertOk()
        ->assertSee('corbin@example.test')
        ->assertDontSee('violet@example.test')
        ->assertDontSee('riley@example.test');
});
