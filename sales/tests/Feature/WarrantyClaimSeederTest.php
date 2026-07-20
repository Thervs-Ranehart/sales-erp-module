<?php

namespace Tests\Feature;

use Database\Seeders\WarrantyClaimSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class WarrantyClaimSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_ten_idempotent_claims_with_valid_relationships(): void
    {
        DB::table('customers')->insert(['customer_id' => 1, 'first_name' => 'Mara', 'last_name' => 'Santos', 'email' => 'mara@example.com', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('products')->insert(['product_id' => 1, 'product_name' => 'Control Module', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('employees')->insert(['employee_id' => 1, 'username' => 'tech1', 'password_hash' => 'hash', 'first_name' => 'Lia', 'last_name' => 'Tan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('sales_orders')->insert(['order_id' => 1, 'order_number' => 'SO-WC-001', 'customer_id' => 1, 'employee_id' => 1, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('support_tickets')->insert(['ticket_id' => 1, 'order_id' => 1, 'customer_id' => 1, 'product_id' => 1, 'ticket_type' => 'Warranty', 'subject' => 'Module problem', 'priority' => 'Medium', 'status' => 'Open', 'created_at' => now()]);
        DB::table('warranty_records')->insert(['warranty_id' => 1, 'order_id' => 1, 'product_id' => 1, 'warranty_number' => 'WR-WC-001', 'warranty_start' => now()->subYear(), 'warranty_end' => now()->addYear(), 'warranty_status' => 'Active', 'created_at' => now(), 'updated_at' => now()]);

        $this->seed(WarrantyClaimSeeder::class);
        $this->assertDatabaseCount('warranty_claims', 10);
        $this->assertDatabaseHas('warranty_claims', ['warranty_id' => 1, 'ticket_id' => 1, 'claim_status' => 'Approved']);
        $this->assertDatabaseHas('warranty_claims', ['warranty_id' => 1, 'ticket_id' => 1, 'claim_status' => 'Completed']);

        $this->seed(WarrantyClaimSeeder::class);
        $this->assertDatabaseCount('warranty_claims', 10);
    }

    public function test_it_skips_when_no_valid_warranty_ticket_pair_exists(): void
    {
        $this->seed(WarrantyClaimSeeder::class);

        $this->assertDatabaseCount('warranty_claims', 0);
    }
}
