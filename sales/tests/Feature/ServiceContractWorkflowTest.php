<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ServiceContractWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_contracts_can_be_listed_searched_and_filtered(): void
    {
        $contracts = $this->seedContracts();

        $this->get('/support/service-contracts?search=Jane%20Smith')
            ->assertOk()
            ->assertSee('SC-ACTIVE');

        $this->get('/support/service-contracts?search=Widget%20A')
            ->assertOk()
            ->assertSee('SC-ACTIVE');

        $this->get('/support/service-contracts?status=Expired')
            ->assertOk()
            ->assertSee('SC-EXPIRED')
            ->assertDontSee('SC-ACTIVE');

        $this->assertCount(3, $contracts);
    }

    public function test_contract_details_and_service_request_link_use_real_relationships(): void
    {
        $contracts = $this->seedContracts();

        $this->getJson("/support/service-contracts/{$contracts['active']}/show")
            ->assertOk()
            ->assertJsonPath('contract.contract_number', 'SC-ACTIVE')
            ->assertJsonPath('contract.customer.name', 'Jane Smith')
            ->assertJsonPath('contract.product.product_name', 'Widget A')
            ->assertJsonPath('contract.contract_status', 'Active');

        $this->getJson('/support/service-requests/1/show')
            ->assertOk()
            ->assertJsonPath('ticket.service_contract.contract_number', 'SC-ACTIVE')
            ->assertJsonPath('ticket.service_contract.coverage', 'Covered');
    }

    public function test_contract_metrics_use_dates_and_invalid_contract_ids_return_not_found(): void
    {
        $this->seedContracts();

        $this->get('/support/service-contracts')
            ->assertOk()
            ->assertViewHas('activeContractCount', 2)
            ->assertViewHas('expiringSoonCount', 1)
            ->assertViewHas('expiredCount', 1);

        $this->getJson('/support/service-contracts/99999/show')->assertNotFound();
    }

    /** @return array{active: int, expiring: int, expired: int} */
    private function seedContracts(): array
    {
        DB::table('customers')->insert([
            'customer_id' => 1,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('products')->insert([
            'product_id' => 1,
            'product_name' => 'Widget A',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('employees')->insert([
            'employee_id' => 1,
            'username' => 'support-agent',
            'password_hash' => 'hash',
            'first_name' => 'Alex',
            'last_name' => 'Support',
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

        $active = DB::table('service_contracts')->insertGetId([
            'customer_id' => 1,
            'product_id' => 1,
            'contract_number' => 'SC-ACTIVE',
            'service_type' => 'On-site support',
            'service_start' => today()->subMonth(),
            'service_end' => today()->addDays(90),
            'contract_status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $expiring = DB::table('service_contracts')->insertGetId([
            'customer_id' => 1,
            'product_id' => 1,
            'contract_number' => 'SC-EXPIRING',
            'service_type' => 'Remote support',
            'service_start' => today()->subYear(),
            'service_end' => today()->addDays(15),
            'contract_status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $expired = DB::table('service_contracts')->insertGetId([
            'customer_id' => 1,
            'product_id' => 1,
            'contract_number' => 'SC-EXPIRED',
            'service_type' => 'On-site support',
            'service_start' => today()->subYear(),
            'service_end' => today()->subDay(),
            'contract_status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('support_tickets')->insert([
            'ticket_id' => 1,
            'order_id' => 1,
            'customer_id' => 1,
            'product_id' => 1,
            'service_contract_id' => $active,
            'ticket_type' => 'Service',
            'subject' => 'Installation request',
            'priority' => 'Medium',
            'status' => 'Open',
            'created_at' => now(),
        ]);
        DB::table('service_requests')->insert([
            'request_id' => 1,
            'ticket_id' => 1,
            'request_type' => 'Installation',
            'service_status' => 'Pending',
        ]);

        return compact('active', 'expiring', 'expired');
    }
}
