<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        DB::table('employees')->insert([
            [
                'username' => 'admin',
                'password_hash' => bcrypt('password'),
                'first_name' => 'Admin',
                'last_name' => 'User',
                'department' => 'Management',
                'role' => 'Administrator',
                'hierarchy_level' => 'Level 1',
                'employee_status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'sales1',
                'password_hash' => bcrypt('password'),
                'first_name' => 'Sales',
                'last_name' => 'Rep',
                'department' => 'Sales',
                'role' => 'Sales Representative',
                'hierarchy_level' => 'Level 2',
                'employee_status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('customers')->insert([
            [
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'email' => 'juan@example.com',
                'contact_no' => '09171234567',
                'address' => '123 Sample St., Makati City',
                'preferences' => 'Email',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Maria',
                'last_name' => 'Santos',
                'email' => 'maria@example.com',
                'contact_no' => '09181234567',
                'address' => '456 Example Ave., Pasig City',
                'preferences' => 'SMS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('products')->insert([
            [
                'product_name' => 'Widget A',
                'category' => 'Electronics',
                'description' => 'High-quality widget for business use.',
                'unit_price' => 1250.00,
                'stock_quantity' => 150,
                'product_status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'Gadget B',
                'category' => 'Accessories',
                'description' => 'Useful gadget for daily operations.',
                'unit_price' => 650.00,
                'stock_quantity' => 210,
                'product_status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('pricing_rules')->insert([
            [
                'rule_name' => 'Standard Discount',
                'discount_type' => 'percentage',
                'discount_value' => 10.00,
                'tax_rate' => 12.00,
                'start_date' => now()->subDays(30)->toDateString(),
                'end_date' => now()->addDays(30)->toDateString(),
                'status' => 'active',
            ],
        ]);
    }
}