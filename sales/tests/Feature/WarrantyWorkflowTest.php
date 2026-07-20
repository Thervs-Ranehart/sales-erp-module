<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class WarrantyWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_warranty_records_and_claims_can_be_listed_and_searched_by_customer_name(): void
    {
        [$warrantyId, $claimId] = $this->seedWarrantyContext();

        $this->get('/support/warranty-records?search=Jane%20Smith')->assertOk()->assertSee('WR-1001');
        $this->get('/support/warranty-claims?search=Jane%20Smith')->assertOk()->assertSee('WC-'.$claimId);
        $this->get('/support/warranty-records?search=SO-1001')->assertOk()->assertSee('WR-1001');
        $this->get("/support/warranty-records?search={$warrantyId}")->assertOk()->assertSee('WR-1001');
        $this->get('/support/warranty-records?search=NoSuchWarranty')->assertOk()->assertSee('No warranty records found for the selected criteria.');
        $this->get('/support/warranty-records?status=Active&customer=1&product=1')
            ->assertOk()
            ->assertSee('WR-1001');
        $this->assertNotNull($warrantyId);
    }

    public function test_warranty_claim_page_renders_one_table_and_each_modal_once(): void
    {
        $this->seedWarrantyContext();

        $content = $this->get('/support/warranty-claims')->assertOk()->getContent();

        $this->assertSame(1, substr_count($content, 'id="warrantyClaimsTable"'));
        $this->assertSame(1, substr_count($content, 'id="warrantyClaimModal"'));
        $this->assertSame(1, substr_count($content, 'id="warrantyClaimStatusModal"'));
    }

    public function test_warranty_claim_filters_use_ids_dates_and_canonical_statuses(): void
    {
        [$warrantyId, $claimId] = $this->seedWarrantyContext();

        $matchingUrl = "/support/warranty-claims?status=Pending&customer=1&warranty_id={$warrantyId}&ticket_id=1&from_date=".now()->subDay()->toDateString().'&to_date='.now()->addDay()->toDateString();

        $this->get($matchingUrl)
            ->assertOk()
            ->assertSee('WC-'.$claimId)
            ->assertSee('value="Pending" selected', false)
            ->assertSee('value="'.$warrantyId.'" selected', false)
            ->assertSee('value="1" selected', false);

        $this->get('/support/warranty-claims?status=Approved')
            ->assertOk()
            ->assertSee('No warranty claims found.');

        $this->get('/support/warranty-claims?status=pending')
            ->assertRedirect()
            ->assertSessionHasErrors('status');

        for ($i = 0; $i < 10; $i++) {
            DB::table('warranty_claims')->insert([
                'warranty_id' => $warrantyId,
                'ticket_id' => 1,
                'claim_reason' => 'Additional claim '.$i,
                'claim_status' => 'Pending',
                'claim_date' => now()->subMinutes($i + 1),
            ]);
        }

        $this->get('/support/warranty-claims?status=Pending&page=2')
            ->assertOk()
            ->assertSee('status=Pending')
            ->assertSee('pagination', false)
            ->assertDontSee('<svg', false);
    }

    public function test_warranty_record_and_claim_details_return_real_related_data(): void
    {
        [$warrantyId, $claimId] = $this->seedWarrantyContext();

        $this->getJson("/support/warranty-records/{$warrantyId}/show")
            ->assertOk()
            ->assertJsonPath('warranty.customer.name', 'Jane Smith')
            ->assertJsonPath('warranty.order.order_number', 'SO-1001')
            ->assertJsonPath('warranty.warranty_status', 'Active');

        $this->getJson("/support/warranty-claims/{$claimId}/show")
            ->assertOk()
            ->assertJsonPath('claim.ticket_number', 'TK-1')
            ->assertJsonPath('warranty.customer.name', 'Jane Smith')
            ->assertJsonPath('assignedEmployee.name', 'Alice Ng');
    }

    public function test_claim_status_validation_and_approval_timestamp_behavior(): void
    {
        [, $claimId] = $this->seedWarrantyContext();

        $this->postJson("/support/warranty-claims/{$claimId}/status", ['claim_status' => 'Unknown'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('claim_status');

        $approved = $this->postJson("/support/warranty-claims/{$claimId}/status", ['claim_status' => 'Approved'])
            ->assertOk()
            ->assertJsonPath('status', 'Approved');
        $approvedDate = $approved->json('approved_date');

        $this->postJson("/support/warranty-claims/{$claimId}/status", ['claim_status' => 'Completed'])
            ->assertOk()
            ->assertJsonPath('status', 'Completed')
            ->assertJsonPath('approved_date', $approvedDate);
        $this->assertDatabaseHas('warranty_claims', ['claim_id' => $claimId, 'claim_status' => 'Completed']);
    }

    /** @return array{int, int} */
    private function seedWarrantyContext(): array
    {
        DB::table('customers')->insert(['customer_id' => 1, 'first_name' => 'Jane', 'last_name' => 'Smith', 'email' => 'jane@example.com', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('products')->insert(['product_id' => 1, 'product_name' => 'Widget A', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('employees')->insert(['employee_id' => 1, 'username' => 'alice', 'password_hash' => 'hash', 'first_name' => 'Alice', 'last_name' => 'Ng', 'department' => 'Support', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('sales_orders')->insert(['order_id' => 1, 'order_number' => 'SO-1001', 'customer_id' => 1, 'employee_id' => 1, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('support_tickets')->insert(['ticket_id' => 1, 'order_id' => 1, 'customer_id' => 1, 'product_id' => 1, 'ticket_type' => 'Service', 'subject' => 'Battery issue', 'priority' => 'High', 'status' => 'Open', 'created_at' => now()]);
        DB::table('ticket_assignments')->insert(['ticket_id' => 1, 'employee_id' => 1, 'assigned_at' => now(), 'assignment_status' => 'Active']);
        $warrantyId = DB::table('warranty_records')->insertGetId(['order_id' => 1, 'product_id' => 1, 'warranty_number' => 'WR-1001', 'warranty_start' => now()->subMonth(), 'warranty_end' => now()->addYear(), 'warranty_status' => 'Active', 'created_at' => now(), 'updated_at' => now()]);
        $claimId = DB::table('warranty_claims')->insertGetId(['warranty_id' => $warrantyId, 'ticket_id' => 1, 'claim_reason' => 'Battery issue', 'claim_status' => 'Pending', 'claim_date' => now()]);

        return [$warrantyId, $claimId];
    }
}
