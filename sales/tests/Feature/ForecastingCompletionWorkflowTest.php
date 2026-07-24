<?php

use App\Models\Customer;
use App\Models\Employee;
use App\Models\ForecastRecommendation;
use App\Models\Product;
use App\Models\SalesForecast;
use App\Models\SalesOrder;
use App\Models\SalesRegion;
use App\Services\SalesAnalyticsService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->employee = Employee::query()->create([
        'username' => 'forecast-manager',
        'password_hash' => password_hash('password', PASSWORD_BCRYPT),
        'first_name' => 'Forecast',
        'last_name' => 'Manager',
        'department' => 'Sales',
        'role' => 'Manager',
        'employee_status' => 'Active',
    ]);
    $this->region = SalesRegion::query()->create([
        'region_code' => 'NCR',
        'region_name' => 'National Capital Region',
        'country' => 'Philippines',
        'status' => 'Active',
    ]);
    $this->customer = Customer::query()->create([
        'first_name' => 'Regional',
        'last_name' => 'Customer',
        'email' => 'regional@example.test',
        'region_id' => $this->region->region_id,
    ]);
    $this->product = Product::query()->create([
        'product_name' => 'Forecast Product',
        'category' => 'Hardware',
        'unit_price' => 1000,
        'stock_quantity' => 100,
        'product_status' => 'Active',
    ]);

    foreach (range(1, 8) as $month) {
        $order = SalesOrder::query()->create([
            'order_number' => "SO-FC-{$month}",
            'customer_id' => $this->customer->customer_id,
            'employee_id' => $this->employee->employee_id,
            'order_date' => now()->startOfYear()->addMonths($month - 1),
            'order_status' => 'delivered',
            'warehouse' => 'Central Warehouse',
            'subtotal' => 1000 * $month,
            'discount' => 0,
            'tax' => 0,
            'shipping_fee' => 0,
            'total_amount' => 1000 * $month,
        ]);
        $order->items()->create([
            'product_id' => $this->product->product_id,
            'quantity' => $month,
            'unit_price' => 1000,
            'discount' => 0,
            'subtotal' => 1000 * $month,
        ]);
    }
    $this->withSession(['employee_id' => $this->employee->employee_id, 'employee_role' => 'Manager']);
});

test('forecasting uses an explainable ensemble with statistical error measures', function (): void {
    $forecast = app(SalesAnalyticsService::class)->forecast([100, 140, 180, 220, 260, 300, 350, 410]);

    expect($forecast['method'])->toBe('ensemble-trend-seasonal-moving-growth')
        ->and($forecast['sampleSize'])->toBe(8)
        ->and($forecast['predictionUpper'])->toBeGreaterThanOrEqual($forecast['nextMonth'])
        ->and($forecast['predictionLower'])->toBeLessThanOrEqual($forecast['nextMonth'])
        ->and($forecast)->toHaveKeys(['mae', 'mape', 'rmse']);
});

test('sales reports use customer regions separately from warehouses and exports are real', function (): void {
    $snapshot = app(SalesAnalyticsService::class)->snapshot(now()->year);
    expect($snapshot['regionalSales']->keys()->first())->toBe('National Capital Region')
        ->and($snapshot['warehouseSales']->keys()->first())->toBe('Central Warehouse');

    $this->get(route('forecasting.export', ['type' => 'reports', 'format' => 'csv', 'year' => now()->year]))
        ->assertOk()
        ->assertHeader('content-type', 'text/csv; charset=UTF-8')
        ->assertDownload();
    $this->assertDatabaseHas('forecast_export_logs', ['export_type' => 'reports', 'format' => 'csv']);
});

test('recommendations can be reviewed assigned approved and converted into planning actions', function (): void {
    $this->get(route('forecasting.recommendations', ['year' => now()->year]))->assertOk();
    $recommendation = ForecastRecommendation::query()->where('recommendation_type', 'Inventory Planning')->firstOrFail();

    $this->patch(route('forecasting.recommendations.update', $recommendation), [
        'implementation_status' => 'Approved',
        'assigned_to' => $this->employee->employee_id,
        'assigned_department' => 'Inventory Team',
        'due_date' => today()->addWeek()->toDateString(),
        'decision_notes' => 'Approved after reviewing forecast evidence.',
    ])->assertRedirect();

    expect($recommendation->fresh()->implementation_status)->toBe('Approved');
    $this->assertDatabaseHas('planning_actions', [
        'recommendation_id' => $recommendation->recommendation_id,
        'action_type' => 'Inventory Planning',
    ]);
    $this->assertDatabaseHas('forecast_workflow_events', ['subject_id' => $recommendation->recommendation_id]);
});

test('saved forecasts accept actual outcomes and calculate accuracy', function (): void {
    $this->get(route('forecasting.forecast', ['year' => now()->year]))->assertOk();
    $forecast = SalesForecast::query()->firstOrFail();

    $this->patch(route('forecasting.forecasts.evaluate', $forecast), ['actual_revenue' => 25000])->assertRedirect();
    expect($forecast->fresh()->forecast_status)->toBe('Evaluated')
        ->and($forecast->fresh()->mae)->not->toBeNull()
        ->and($forecast->fresh()->mape)->not->toBeNull();
});
