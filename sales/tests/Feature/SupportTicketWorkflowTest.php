<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SupportTicketWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_tickets_can_be_searched_by_full_customer_name_and_order_number(): void
    {
        $ticketId = $this->seedTicket();

        $this->get('/support/tickets?search=Jane%20Smith')->assertOk()->assertSee('TK-'.$ticketId);
        $this->get('/support/tickets?search=SO-1001')->assertOk()->assertSee('TK-'.$ticketId);
        $this->get('/support/tickets?search=Widget%20A')->assertOk()->assertSee('TK-'.$ticketId);
    }

    public function test_ticket_page_renders_one_table_and_each_ticket_modal_once(): void
    {
        $this->seedTicket();

        $content = $this->get('/support/tickets')->assertOk()->getContent();

        $this->assertSame(1, substr_count($content, 'id="supportTicketsTable"'));
        $this->assertSame(1, substr_count($content, 'id="ticketDetailsModal"'));
        $this->assertSame(1, substr_count($content, 'id="ticketsAssignModal"'));
        $this->assertSame(1, substr_count($content, 'id="ticketStatusModal"'));
    }

    public function test_ticket_details_assignment_reassignment_and_status_timestamps_are_persisted(): void
    {
        $ticketId = $this->seedTicket();

        $this->postJson("/support/tickets/{$ticketId}/assign", ['employee_id' => 1])
            ->assertOk()
            ->assertJsonPath('changed', true);
        $this->postJson("/support/tickets/{$ticketId}/assign", ['employee_id' => 2])
            ->assertOk()
            ->assertJsonPath('changed', true);
        $this->postJson("/support/tickets/{$ticketId}/assign", ['employee_id' => 2])
            ->assertOk()
            ->assertJsonPath('changed', false);

        $this->assertDatabaseHas('ticket_assignments', ['ticket_id' => $ticketId, 'employee_id' => 1, 'assignment_status' => 'Reassigned']);
        $this->assertDatabaseHas('ticket_assignments', ['ticket_id' => $ticketId, 'employee_id' => 2, 'assignment_status' => 'Active']);

        $this->postJson("/support/tickets/{$ticketId}/status", ['status' => 'In Progress'])->assertOk();
        $this->postJson("/support/tickets/{$ticketId}/status", ['status' => 'Resolved'])->assertOk()->assertJsonPath('status', 'Resolved');
        $this->assertDatabaseMissing('support_tickets', ['ticket_id' => $ticketId, 'resolved_at' => null]);
        $this->postJson("/support/tickets/{$ticketId}/status", ['status' => 'Closed'])->assertOk()->assertJsonPath('status', 'Closed');
        $this->assertDatabaseMissing('support_tickets', ['ticket_id' => $ticketId, 'closed_at' => null]);

        $this->getJson("/support/tickets/{$ticketId}/show")
            ->assertOk()
            ->assertJsonPath('assignedEmployee.name', 'Bob Lee')
            ->assertJsonCount(2, 'assignmentHistory');
    }

    public function test_ticket_writes_reject_invalid_statuses_and_employee_ids_without_server_errors(): void
    {
        $ticketId = $this->seedTicket();

        $this->postJson("/support/tickets/{$ticketId}/assign", ['employee_id' => 999])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('employee_id');
        $this->postJson("/support/tickets/{$ticketId}/status", ['status' => 'Invalid'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('status');
        $this->postJson('/support/tickets/999/assign', ['employee_id' => 1])->assertNotFound();
    }

    private function seedTicket(): int
    {
        DB::table('customers')->insert(['customer_id' => 1, 'first_name' => 'Jane', 'last_name' => 'Smith', 'email' => 'jane@example.com', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('products')->insert(['product_id' => 1, 'product_name' => 'Widget A', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('employees')->insert([
            ['employee_id' => 1, 'username' => 'alice', 'password_hash' => 'hash', 'first_name' => 'Alice', 'last_name' => 'Ng', 'department' => 'Support', 'created_at' => now(), 'updated_at' => now()],
            ['employee_id' => 2, 'username' => 'bob', 'password_hash' => 'hash', 'first_name' => 'Bob', 'last_name' => 'Lee', 'department' => 'Support', 'created_at' => now(), 'updated_at' => now()],
        ]);
        DB::table('sales_orders')->insert(['order_id' => 1, 'order_number' => 'SO-1001', 'customer_id' => 1, 'employee_id' => 1, 'created_at' => now(), 'updated_at' => now()]);

        return DB::table('support_tickets')->insertGetId([
            'order_id' => 1, 'customer_id' => 1, 'product_id' => 1, 'ticket_type' => 'Service', 'subject' => 'Installation issue', 'description' => 'Needs technician review', 'priority' => 'High', 'status' => 'Open', 'created_at' => now(),
        ]);
    }
}
