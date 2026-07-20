<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SupportTicketAssignmentModalTest extends TestCase
{
    use RefreshDatabase;

    public function test_support_tickets_index_filters_by_customer_and_date_range(): void
    {
        DB::table('customers')->insert([
            'customer_id' => 1,
            'first_name' => 'Acme',
            'last_name' => 'Ltd',
            'email' => 'acme@example.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('customers')->insert([
            'customer_id' => 2,
            'first_name' => 'Beta',
            'last_name' => 'Corp',
            'email' => 'beta@example.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('products')->insert([
            'product_id' => 1,
            'product_name' => 'Widget',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('employees')->insert([
            'employee_id' => 1,
            'username' => 'emp1',
            'password_hash' => 'hash',
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sales_orders')->insert([
            'order_id' => 1,
            'order_number' => 'SO-001',
            'customer_id' => 1,
            'employee_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('support_tickets')->insert([
            [
                'order_id' => 1,
                'customer_id' => 1,
                'product_id' => 1,
                'ticket_type' => 'service',
                'subject' => 'First ticket',
                'description' => 'Needs review',
                'priority' => 'High',
                'status' => 'Pending',
                'created_at' => '2026-01-15 10:00:00',
            ],
            [
                'order_id' => 1,
                'customer_id' => 2,
                'product_id' => 1,
                'ticket_type' => 'service',
                'subject' => 'Second ticket',
                'description' => 'Needs review',
                'priority' => 'Medium',
                'status' => 'Pending',
                'created_at' => '2026-01-20 10:00:00',
            ],
        ]);

        $response = $this->get('/support/tickets?customer=Acme&from_date=2026-01-01&to_date=2026-01-16');

        $response->assertOk();
        $response->assertViewHas('tickets', function ($tickets) {
            return $tickets->count() === 1 && $tickets->first()->subject === 'First ticket';
        });
    }

    public function test_assign_ticket_modal_save_button_is_enabled_and_saves_assignment(): void
    {
        DB::table('customers')->insert([
            'customer_id' => 1,
            'first_name' => 'Acme',
            'last_name' => 'Ltd',
            'email' => 'acme@example.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('products')->insert([
            'product_id' => 1,
            'product_name' => 'Widget',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('employees')->insert([
            'employee_id' => 1,
            'username' => 'emp1',
            'password_hash' => 'hash',
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sales_orders')->insert([
            'order_id' => 1,
            'order_number' => 'SO-001',
            'customer_id' => 1,
            'employee_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $ticketId = DB::table('support_tickets')->insertGetId([
            'order_id' => 1,
            'customer_id' => 1,
            'product_id' => 1,
            'ticket_type' => 'service',
            'subject' => 'Broken device',
            'description' => 'Needs review',
            'priority' => 'High',
            'status' => 'Open',
            'created_at' => now(),
        ]);

        $html = view('support.tickets-assign-modal', [
            'ticket' => null,
            'employees' => collect(),
            'currentEmployeeId' => null,
        ])->render();

        $this->assertStringContainsString('id="ticketsAssignSaveBtn"', $html);
        $this->assertMatchesRegularExpression('/<button[^>]*id="ticketsAssignSaveBtn"[^>]*>\s*Save\s*<\/button>/s', $html);
        $this->assertStringNotContainsString('disabled', $html);

        $response = $this->postJson("/support/tickets/{$ticketId}/assign", [
            'employee_id' => 1,
        ]);

        $response->assertOk();
        $response->assertJsonPath('message', 'Ticket assigned successfully.');
        $this->assertDatabaseHas('ticket_assignments', [
            'ticket_id' => $ticketId,
            'employee_id' => 1,
            'assignment_status' => 'Active',
        ]);
    }
}
