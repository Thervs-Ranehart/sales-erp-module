<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ServiceRequestsFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_requests_can_be_filtered_by_technician_and_date(): void
    {
        DB::table('customers')->insert([
            ['customer_id' => 1, 'first_name' => 'Jane', 'last_name' => 'Smith', 'email' => 'jane@example.com', 'created_at' => now(), 'updated_at' => now()],
            ['customer_id' => 2, 'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('employees')->insert([
            ['employee_id' => 1, 'username' => 'emp1', 'password_hash' => 'hash', 'first_name' => 'Alice', 'last_name' => 'Ng', 'created_at' => now(), 'updated_at' => now()],
            ['employee_id' => 2, 'username' => 'emp2', 'password_hash' => 'hash', 'first_name' => 'Bob', 'last_name' => 'Lee', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('products')->insert([
            ['product_id' => 1, 'product_name' => 'Widget A', 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 2, 'product_name' => 'Widget B', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('sales_orders')->insert([
            ['order_id' => 1, 'order_number' => 'SO-1001', 'customer_id' => 1, 'employee_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['order_id' => 2, 'order_number' => 'SO-1002', 'customer_id' => 2, 'employee_id' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('support_tickets')->insert([
            ['ticket_id' => 1, 'order_id' => 1, 'customer_id' => 1, 'product_id' => 1, 'ticket_type' => 'service', 'subject' => 'First issue', 'priority' => 'High', 'status' => 'Open', 'created_at' => now()],
            ['ticket_id' => 2, 'order_id' => 2, 'customer_id' => 2, 'product_id' => 2, 'ticket_type' => 'service', 'subject' => 'Second issue', 'priority' => 'Medium', 'status' => 'Open', 'created_at' => now()],
        ]);

        DB::table('ticket_assignments')->insert([
            ['assignment_id' => 1, 'ticket_id' => 1, 'employee_id' => 1, 'assigned_at' => now(), 'assignment_status' => 'active'],
            ['assignment_id' => 2, 'ticket_id' => 2, 'employee_id' => 2, 'assigned_at' => now(), 'assignment_status' => 'active'],
        ]);

        DB::table('service_requests')->insert([
            ['request_id' => 1, 'ticket_id' => 1, 'request_type' => 'Installation', 'service_status' => 'Scheduled', 'scheduled_date' => '2026-07-25 09:00:00', 'completion_date' => null],
            ['request_id' => 2, 'ticket_id' => 2, 'request_type' => 'Repair', 'service_status' => 'Scheduled', 'scheduled_date' => '2026-07-25 10:00:00', 'completion_date' => null],
        ]);

        $response = $this->get('/support/service-requests?technician=1&date=2026-07-25');

        $response->assertOk();
        $response->assertSee('SR-1');
        $response->assertDontSee('SR-2');
    }
}
