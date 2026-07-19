<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesTarget;
use Illuminate\Database\Seeder;

class SalesForecastingSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::query()->orderBy('employee_id')->get();
        if ($employees->isEmpty()) {
            return;
        }

        $customers = collect([
            ['first_name' => 'Maria', 'last_name' => 'Santos', 'email' => 'maria.santos@example.test'],
            ['first_name' => 'Jose', 'last_name' => 'Reyes', 'email' => 'jose.reyes@example.test'],
            ['first_name' => 'Ana', 'last_name' => 'Cruz', 'email' => 'ana.cruz@example.test'],
        ])->map(fn (array $data): Customer => Customer::query()->updateOrCreate(['email' => $data['email']], $data));

        $products = collect([
            ['product_name' => 'Business Laptop', 'category' => 'Computers', 'unit_price' => 52000, 'stock_quantity' => 50, 'product_status' => 'Active'],
            ['product_name' => 'Office Printer', 'category' => 'Office Equipment', 'unit_price' => 18500, 'stock_quantity' => 80, 'product_status' => 'Active'],
            ['product_name' => 'POS Terminal', 'category' => 'Retail Equipment', 'unit_price' => 32000, 'stock_quantity' => 40, 'product_status' => 'Active'],
        ])->map(fn (array $data): Product => Product::query()->updateOrCreate(['product_name' => $data['product_name']], $data));

        foreach (range(0, 5) as $monthOffset) {
            foreach (range(0, 1) as $sequence) {
                $date = now()->startOfMonth()->subMonths(5 - $monthOffset)->addDays(4 + ($sequence * 10));
                $employee = $employees[($monthOffset + $sequence) % $employees->count()];
                $customer = $customers[($monthOffset + $sequence) % $customers->count()];
                $product = $products[($monthOffset + $sequence) % $products->count()];
                $quantity = 2 + (($monthOffset + $sequence) % 4);
                $subtotal = (float) $product->unit_price * $quantity;
                $tax = $subtotal * .12;
                $orderNumber = 'FORECAST-DEMO-'.$date->format('Ym').'-'.($sequence + 1);

                $order = SalesOrder::query()->updateOrCreate(['order_number' => $orderNumber], [
                    'customer_id' => $customer->customer_id,
                    'employee_id' => $employee->employee_id,
                    'order_date' => $date->toDateString(),
                    'payment_method' => 'Bank Transfer',
                    'payment_status' => 'Paid',
                    'order_status' => 'Delivered',
                    'warehouse' => ['NCR', 'CALABARZON', 'Central Visayas'][($monthOffset + $sequence) % 3],
                    'subtotal' => $subtotal,
                    'discount' => 0,
                    'tax' => $tax,
                    'shipping_fee' => 1000,
                    'total_amount' => $subtotal + $tax + 1000,
                ]);

                $order->items()->updateOrCreate(['product_id' => $product->product_id], [
                    'quantity' => $quantity,
                    'unit_price' => $product->unit_price,
                    'discount' => 0,
                    'subtotal' => $subtotal,
                ]);
            }
        }

        foreach ($employees as $employee) {
            foreach (range(0, 5) as $monthOffset) {
                $month = now()->startOfMonth()->subMonths($monthOffset);
                SalesTarget::query()->updateOrCreate([
                    'employee_id' => $employee->employee_id,
                    'target_month' => $month->month,
                    'target_year' => $month->year,
                ], [
                    'sales_target' => 2,
                    'revenue_target' => 150000,
                    'created_by' => $employees->first()->employee_id,
                    'created_at' => now(),
                ]);
            }
        }
    }
}
