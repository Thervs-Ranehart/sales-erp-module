<?php

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the profile edit action opens the dedicated customer edit page', function (): void {
    $customer = Customer::query()->create([
        'first_name' => 'Profile',
        'last_name' => 'Customer',
        'email' => 'profile@example.test',
        'customer_status' => 'Active',
    ]);

    $this->get(route('crm.profiles.edit', $customer))
        ->assertRedirect(route('crm.directory.edit', $customer));

    $this->get(route('crm.directory.edit', $customer))
        ->assertOk()
        ->assertSee('Edit Customer')
        ->assertSee('Profile Customer');
});
