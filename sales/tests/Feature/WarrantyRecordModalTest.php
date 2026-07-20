<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class WarrantyRecordModalTest extends TestCase
{
    use RefreshDatabase;

    public function test_warranty_record_show_endpoint_returns_fields_for_modal(): void
    {
        DB::table('products')->insert([
            'product_id' => 1,
            'product_name' => 'Widget A',
            'stock_quantity' => 3,
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

        DB::table('customers')->insert([
            'customer_id' => 1,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
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

        $warrantyId = DB::table('warranty_records')->insertGetId([
            'order_id' => 1,
            'product_id' => 1,
            'warranty_number' => 'WR-1001',
            'warranty_start' => '2025-01-01',
            'warranty_end' => '2026-01-01',
            'warranty_status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson("/support/warranty-records/{$warrantyId}/show");

        $response->assertOk();
        $response->assertJsonPath('warranty.warranty_number', 'WR-1001');
        $response->assertJsonPath('warranty.product.product_name', 'Widget A');
        $response->assertJsonPath('warranty.order.order_number', 'SO-1001');
        $response->assertJsonMissingPath('warranty.quantity');
        $response->assertJsonMissingPath('warranty.coverage_type');
    }

    public function test_warranty_record_show_endpoint_returns_json_not_found_response(): void
    {
        $this->getJson('/support/warranty-records/999/show')
            ->assertNotFound()
            ->assertJsonPath('message', 'Warranty record not found.');
    }
}
