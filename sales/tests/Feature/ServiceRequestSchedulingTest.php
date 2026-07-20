<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ServiceRequestSchedulingTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_requests_can_be_listed_searched_and_filtered(): void
    {
        $this->seedContext();

        $this->get('/support/service-requests?search=Jane%20Smith')->assertOk()->assertSee('SR-1');
        $this->get('/support/service-requests?search=Widget%20A')->assertOk()->assertSee('SR-1');
        $this->get('/support/service-requests?status=Pending')->assertOk()->assertSee('SR-1');
    }

    public function test_details_return_real_coverage_and_related_data(): void
    {
        $this->seedContext();

        $this->getJson('/support/service-requests/1/show')
            ->assertOk()
            ->assertJsonPath('ticket.name', 'Jane Smith')
            ->assertJsonPath('ticket.product', 'Widget A')
            ->assertJsonPath('ticket.service_contract.contract_number', 'SC-1001')
            ->assertJsonPath('ticket.service_contract.coverage', 'Covered')
            ->assertJsonPath('contract.contract_id', 1)
            ->assertJsonPath('contract.view_url', route('support.service-contracts', ['open_contract' => 1]));
    }

    public function test_schedule_can_be_created_updated_and_persists_after_refresh(): void
    {
        $this->seedContext();

        $this->patchJson('/support/service-requests/1/schedule', [
            'technician_id' => 1,
            'scheduled_date' => '2026-08-01',
            'scheduled_time' => '09:00',
            'scheduled_end' => '11:00',
            'priority' => 'High',
            'schedule_notes' => 'Bring installation tools.',
        ])->assertOk()->assertJsonPath('status', 'Scheduled')->assertJsonPath('technician', 'Alex Support');

        $this->patchJson('/support/service-requests/1/schedule', [
            'technician_id' => 2,
            'scheduled_date' => '2026-08-02',
            'scheduled_time' => '13:00',
            'scheduled_end' => '15:00',
            'priority' => 'Low',
            'schedule_notes' => 'Customer confirmed the new time.',
        ])->assertOk()->assertJsonPath('priority', 'Low')->assertJsonPath('technician', 'Blair Field');

        $this->assertDatabaseHas('service_requests', [
            'request_id' => 1,
            'technician_id' => 2,
            'service_status' => 'Scheduled',
            'schedule_notes' => 'Customer confirmed the new time.',
        ]);
        $this->getJson('/support/service-requests/1/show')
            ->assertOk()
            ->assertJsonPath('request.scheduled_at', '2026-08-02 13:00')
            ->assertJsonPath('request.scheduled_end', '2026-08-02 15:00')
            ->assertJsonPath('request.technician.name', 'Blair Field')
            ->assertJsonPath('request.schedule_notes', 'Customer confirmed the new time.');
    }

    public function test_schedule_rejects_unknown_technicians_and_invalid_time_ranges(): void
    {
        $this->seedContext();

        $this->patchJson('/support/service-requests/1/schedule', [
            'technician_id' => 999,
            'scheduled_date' => '2026-08-01',
            'scheduled_time' => '09:00',
            'priority' => 'Medium',
        ])->assertUnprocessable()->assertJsonValidationErrors('technician_id');

        $this->patchJson('/support/service-requests/1/schedule', [
            'technician_id' => 1,
            'scheduled_date' => '2026-08-01',
            'scheduled_time' => '11:00',
            'scheduled_end' => '10:00',
            'priority' => 'Medium',
        ])->assertUnprocessable()->assertJsonValidationErrors('scheduled_end');
    }

    private function seedContext(): void
    {
        DB::table('customers')->insert(['customer_id' => 1, 'first_name' => 'Jane', 'last_name' => 'Smith', 'email' => 'jane@example.com', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('employees')->insert([
            ['employee_id' => 1, 'username' => 'alex', 'password_hash' => 'hash', 'first_name' => 'Alex', 'last_name' => 'Support', 'department' => 'Support', 'created_at' => now(), 'updated_at' => now()],
            ['employee_id' => 2, 'username' => 'blair', 'password_hash' => 'hash', 'first_name' => 'Blair', 'last_name' => 'Field', 'department' => 'Field Service', 'created_at' => now(), 'updated_at' => now()],
        ]);
        DB::table('products')->insert(['product_id' => 1, 'product_name' => 'Widget A', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('sales_orders')->insert(['order_id' => 1, 'order_number' => 'SO-1001', 'customer_id' => 1, 'employee_id' => 1, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('service_contracts')->insert(['contract_id' => 1, 'customer_id' => 1, 'product_id' => 1, 'contract_number' => 'SC-1001', 'service_type' => 'Installation', 'service_start' => today()->subMonth(), 'service_end' => today()->addMonth(), 'contract_status' => 'Active', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('support_tickets')->insert(['ticket_id' => 1, 'order_id' => 1, 'customer_id' => 1, 'product_id' => 1, 'service_contract_id' => 1, 'ticket_type' => 'Service', 'subject' => 'Installation issue', 'description' => 'Needs an on-site installation visit.', 'priority' => 'Medium', 'status' => 'Open', 'created_at' => now()]);
        DB::table('service_requests')->insert(['request_id' => 1, 'ticket_id' => 1, 'request_type' => 'Installation', 'service_status' => 'Pending']);
    }
}
