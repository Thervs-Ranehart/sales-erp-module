<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ServiceRequestModalTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_request_show_endpoint_returns_modal_fields(): void
    {
        DB::table('customers')->insert([
            'customer_id' => 1,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('employees')->insert([
            'employee_id' => 1,
            'username' => 'emp1',
            'password_hash' => 'hash',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('products')->insert([
            'product_id' => 1,
            'product_name' => 'Widget A',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sales_orders')->insert([
            'order_id' => 1,
            'order_number' => 'SO-1001',
            'customer_id' => 1,
            'employee_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('support_tickets')->insert([
            'ticket_id' => 1,
            'order_id' => 1,
            'customer_id' => 1,
            'product_id' => 1,
            'ticket_type' => 'service',
            'subject' => 'Broken device',
            'priority' => 'High',
            'status' => 'Open',
            'created_at' => now(),
        ]);

        $requestId = DB::table('service_requests')->insertGetId([
            'request_id' => 1,
            'ticket_id' => 1,
            'request_type' => 'Installation',
            'service_status' => 'Scheduled',
            'scheduled_date' => '2026-07-25',
            'completion_date' => '2026-07-25 14:00:00',
        ]);

        $response = $this->getJson("/support/service-requests/{$requestId}/show");

        $response->assertOk();
        $response->assertJsonPath('request.request_id', $requestId);
        $response->assertJsonPath('request.request_type', 'Installation');
        $response->assertJsonPath('request.scheduled_date', '2026-07-25');
        $response->assertJsonPath('ticket.ticket_id', 1);
        $response->assertJsonPath('ticket.name', 'Jane Smith');
    }
}
