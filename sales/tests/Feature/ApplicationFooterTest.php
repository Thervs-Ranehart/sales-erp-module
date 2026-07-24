<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the shared application footer appears across system modules', function (string $routeName): void {
    $this->get(route($routeName))
        ->assertOk()
        ->assertSee('Application footer', false)
        ->assertSee('cl-logo.svg', false)
        ->assertSee('Company Name')
        ->assertSee('Sales and Customer Management System')
        ->assertSee(route('about.index'), false)
        ->assertSee('Version 1.0');
})->with([
    'dashboard' => 'dashboard',
    'sales orders' => 'sales.index',
    'customer management' => 'crm.directory',
    'after-sales support' => 'support.tickets',
    'reporting and forecasting' => 'forecasting.index',
]);

test('the login page keeps its separate design without the application footer', function (): void {
    $this->get('/')
        ->assertOk()
        ->assertDontSee('Application footer', false);
});
