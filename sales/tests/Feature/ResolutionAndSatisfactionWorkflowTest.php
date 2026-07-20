<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ResolutionAndSatisfactionWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_resolution_listing_ticket_status_filter_details_metrics_and_ticket_link(): void
    {
        $this->seedContext();

        $response = $this->get('/support/resolution-tracking?ticket_status=Resolved');
        $response->assertOk()
            ->assertSee('RS-1')
            ->assertDontSee('RS-2')
            ->assertSee('/support/tickets?ticket_id=1', false)
            ->assertViewHas('totalResolutionCount', 2)
            ->assertViewHas('resolvedTicketCount', 1)
            ->assertViewHas('averageResolutionTime', 2.0)
            ->assertViewHas('qcPassedCount', 1)
            ->assertViewHas('qcFailedCount', 1)
            ->assertViewHas('pendingQcCount', 0);

        $this->getJson('/support/resolution-tracking/1/show')
            ->assertOk()
            ->assertJsonPath('resolution.outcome', 'Resolved')
            ->assertJsonPath('resolution.qc_status', 'Passed')
            ->assertJsonPath('assignedEmployee.name', 'Alex Support');
    }

    public function test_satisfaction_listing_search_rating_filter_metrics_and_ticket_link(): void
    {
        $this->seedContext();

        $this->get('/support/customer-satisfaction?search=Jane%20Smith')
            ->assertOk()
            ->assertSee('FB-1')
            ->assertSee('Jane Smith')
            ->assertSee('/support/tickets?ticket_id=1', false);

        $this->get('/support/customer-satisfaction?rating=1')
            ->assertOk()
            ->assertSee('FB-2')
            ->assertDontSee('FB-1')
            ->assertViewHas('responsesCount', 1)
            ->assertViewHas('averageRating', 1.0)
            ->assertViewHas('satisfactionPct', 0)
            ->assertViewHas('dissatisfiedCount', 1);

    }

    private function seedContext(): void
    {
        DB::table('customers')->insert(['customer_id' => 1, 'first_name' => 'Jane', 'last_name' => 'Smith', 'email' => 'jane@example.com', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('employees')->insert(['employee_id' => 1, 'username' => 'alex', 'password_hash' => 'hash', 'first_name' => 'Alex', 'last_name' => 'Support', 'department' => 'Support', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('products')->insert(['product_id' => 1, 'product_name' => 'Widget A', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('sales_orders')->insert(['order_id' => 1, 'order_number' => 'SO-1001', 'customer_id' => 1, 'employee_id' => 1, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('support_tickets')->insert([
            ['ticket_id' => 1, 'order_id' => 1, 'customer_id' => 1, 'product_id' => 1, 'ticket_type' => 'Service', 'subject' => 'Resolved issue', 'priority' => 'High', 'status' => 'Resolved', 'created_at' => now()],
            ['ticket_id' => 2, 'order_id' => 1, 'customer_id' => 1, 'product_id' => 1, 'ticket_type' => 'Service', 'subject' => 'Open issue', 'priority' => 'Low', 'status' => 'Open', 'created_at' => now()],
        ]);
        DB::table('resolution_tracking')->insert([
            ['resolution_id' => 1, 'ticket_id' => 1, 'resolved_by' => 1, 'resolution_summary' => 'Resolved', 'root_cause' => 'Incorrect setup', 'corrective_action' => 'Configuration passed review', 'qc_status' => 'Passed', 'resolution_time_hours' => 2.5, 'resolved_at' => now()],
            ['resolution_id' => 2, 'ticket_id' => 2, 'resolved_by' => 1, 'resolution_summary' => 'Investigating', 'root_cause' => 'Part failure', 'corrective_action' => 'QC failed inspection', 'qc_status' => 'Failed', 'resolution_time_hours' => 1.5, 'resolved_at' => null],
        ]);
        DB::table('satisfaction_monitoring')->insert([
            ['feedback_id' => 1, 'ticket_id' => 1, 'rating' => 5, 'satisfaction_level' => 'Satisfied', 'comments' => 'Excellent support.', 'submitted_at' => now()],
            ['feedback_id' => 2, 'ticket_id' => 2, 'rating' => 1, 'satisfaction_level' => 'Dissatisfied', 'comments' => 'Still waiting.', 'submitted_at' => now()->subDay()],
        ]);
    }
}
