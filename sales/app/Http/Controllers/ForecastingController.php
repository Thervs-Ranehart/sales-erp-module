<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\SalesForecast;
use App\Models\SalesRegion;
use App\Models\SalesTarget;
use App\Services\ForecastingPersistenceService;
use App\Services\SalesAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ForecastingController extends Controller
{
    public function __construct(
        private readonly SalesAnalyticsService $analytics,
        private readonly ForecastingPersistenceService $persistence,
    ) {}

    public function index(Request $request)
    {
        $snapshot = $this->snapshot($request);
        $forecast = $this->analytics->forecast($snapshot['monthlyRevenue']);
        $target = (float) array_sum($snapshot['monthlyTargets']);
        $achievement = $target > 0 ? ($snapshot['totalRevenue'] / $target) * 100 : 0;
        $recommendations = $this->recommendationsFrom($snapshot, $forecast);

        return view('forecasting.index', [
            'dashboardKpis' => [
                'totalRevenue' => $snapshot['totalRevenue'],
                'salesTarget' => $target,
                'achievementRate' => $achievement,
                'revenueGrowth' => $forecast['growthRate'],
                'nextMonthForecast' => $forecast['nextMonth'],
                'highPriorityRecommendations' => collect($recommendations)->where('priority', 'High')->count(),
            ],
            'revenueTrend' => $this->chart($snapshot['labels'], $snapshot['monthlyRevenue']),
            'targetVsActualSummary' => [
                'labels' => $snapshot['labels'],
                'targetSeries' => $snapshot['monthlyTargets'],
                'actualSeries' => $snapshot['monthlyRevenue'],
                'target' => $target,
                'actual' => $snapshot['totalRevenue'],
                'achievement' => $achievement,
                'gap' => $snapshot['totalRevenue'] - $target,
                'status' => $target <= 0 ? 'No Target Set' : ($achievement >= 100 ? 'Exceeded Target' : 'Below Target'),
            ],
            'forecastSummary' => [
                'latestActual' => (float) (collect($snapshot['monthlyRevenue'])->filter()->last() ?? 0),
                'nextMonthForecast' => $forecast['nextMonth'],
                'growthRate' => $forecast['growthRate'],
                'direction' => $this->direction($forecast['growthRate']),
                'confidence' => $forecast['confidence'],
            ],
            'topProducts' => $this->ranked($snapshot['productSales'], 3),
            'topRegions' => $this->ranked($snapshot['regionalSales'], 3),
            'topRepresentatives' => $this->ranked($snapshot['representativeSales'], 3),
            'priorityRecommendations' => collect($recommendations)->take(3)->all(),
            'recentInsights' => $this->salesInsights($snapshot, $forecast),
        ]);
    }

    public function reports(Request $request)
    {
        $snapshot = $this->snapshot($request);
        $values = $snapshot['monthlyRevenue'];
        $orders = $snapshot['monthlyOrders'];
        $totalRevenue = (float) array_sum($values);
        $totalOrders = (int) array_sum($orders);
        $nonEmpty = collect($values)->filter(fn ($value): bool => (float) $value > 0);
        $first = (float) ($nonEmpty->first() ?? 0);
        $last = (float) ($nonEmpty->last() ?? 0);
        $bestIndex = $values === [] ? null : array_search(max($values), $values, true);
        $kpis = [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'averageOrderValue' => $totalOrders > 0 ? $totalRevenue / $totalOrders : 0,
            'activeCustomers' => $snapshot['activeCustomers'],
            'salesGrowth' => $first > 0 ? (($last - $first) / $first) * 100 : 0,
            'bestMonth' => $bestIndex === null ? 'No sales data' : $snapshot['labels'][$bestIndex],
        ];
        $rows = collect($snapshot['labels'])->map(function (string $label, int $index) use ($values, $orders): array {
            $revenue = (float) $values[$index];
            $orderCount = (int) $orders[$index];
            $previous = $index > 0 ? (float) $values[$index - 1] : 0;

            return [
                'period' => $label,
                'revenue' => $revenue,
                'orders' => $orderCount,
                'averageOrderValue' => $orderCount > 0 ? $revenue / $orderCount : 0,
                'growth' => $previous > 0 ? (($revenue - $previous) / $previous) * 100 : null,
            ];
        })->all();

        if ($employeeId = $this->employeeId($request)) {
            $this->persistence->saveReport($snapshot, $kpis, $employeeId);
        }

        return view('forecasting.reports', [
            'monthlyRevenue' => $this->chart($snapshot['labels'], $values),
            'topProducts' => $this->collectionChart($snapshot['productSales']),
            'salesByRegion' => $this->collectionChart($snapshot['regionalSales']),
            'salesByWarehouse' => $this->collectionChart($snapshot['warehouseSales']),
            'salesByRepresentative' => $this->collectionChart($snapshot['representativeSales']),
            'selectedYear' => (int) $request->query('year', now()->year),
            'reportKpis' => $kpis,
            'monthlyReportRows' => $rows,
            'reportInsights' => $this->salesInsights($snapshot, $this->analytics->forecast($values)),
            'filterOptions' => $this->analytics->filterOptions(),
            'salesRegions' => Schema::hasTable('sales_regions') ? SalesRegion::query()->withCount('customers')->orderBy('region_name')->get() : collect(),
            'regionCustomers' => Schema::hasTable('sales_regions') ? Customer::query()->with('region')->orderBy('first_name')->get() : collect(),
        ]);
    }

    public function performance(Request $request)
    {
        $snapshot = $this->snapshot($request);
        if (Schema::hasTable('sales_performance')) {
            $this->persistence->savePerformance($snapshot);
        }
        $rows = $this->performanceRows($snapshot);
        $status = $request->query('status', 'all');
        if ($status !== 'all') {
            $expected = ['exceeded' => 'Exceeded', 'on-target' => 'On Target', 'below-target' => 'Below'][$status] ?? null;
            $rows = $expected ? array_values(array_filter($rows, fn (array $row): bool => $row['status'] === $expected)) : $rows;
        }

        $target = (float) array_sum($snapshot['monthlyTargets']);
        $actual = (float) array_sum($snapshot['monthlyRevenue']);
        $achievement = $target > 0 ? ($actual / $target) * 100 : 0;
        $best = collect($rows)->sortByDesc('achievement')->first();
        $lowest = collect($rows)->sortBy('achievement')->first();

        return view('forecasting.performance', [
            'monthlyTargetVsActual' => ['labels' => $snapshot['labels'], 'target' => $snapshot['monthlyTargets'], 'actual' => $snapshot['monthlyRevenue']],
            'achievementTrend' => $this->chart($snapshot['labels'], collect($snapshot['monthlyRevenue'])->map(fn ($value, $index): float => ($snapshot['monthlyTargets'][$index] ?? 0) > 0 ? ((float) $value / (float) $snapshot['monthlyTargets'][$index]) * 100 : 0)->all()),
            'achievementByProduct' => $this->relativePerformance($snapshot['productSales']),
            'achievementByRegion' => $this->relativePerformance($snapshot['regionalSales']),
            'achievementByRepresentative' => ['labels' => collect($rows)->pluck('employee_name')->all(), 'values' => collect($rows)->pluck('achievement')->all()],
            'salesTargetTotal' => $target,
            'actualRevenueTotal' => $actual,
            'achievementRate' => $achievement,
            'salesGap' => $actual - $target,
            'bestPerformer' => ['name' => $best['employee_name'] ?? 'No data', 'achievement' => $best['achievement'] ?? 0],
            'lowestPerformer' => ['name' => $lowest['employee_name'] ?? 'No data', 'achievement' => $lowest['achievement'] ?? 0],
            'targetVsActualRows' => $rows,
            'performanceInsights' => $this->performanceInsights($snapshot, $rows),
            'filterOptions' => $this->analytics->filterOptions(),
            'employees' => Schema::hasTable('employees') ? Employee::query()->orderBy('first_name')->get() : collect(),
            'salesTargets' => Schema::hasTable('sales_targets') ? SalesTarget::query()->with('employee')->orderByDesc('target_year')->orderByDesc('target_month')->get() : collect(),
        ]);
    }

    public function forecast(Request $request)
    {
        $snapshot = $this->snapshot($request);
        $calculation = $this->analytics->forecast($snapshot['monthlyRevenue']);
        $multiplier = match ($request->query('scenario')) {
            'optimistic' => 1.1, 'conservative' => .9, default => 1.0
        };
        $horizon = match ($request->query('forecast-horizon')) {
            'next-month' => 1, 'next-6-months' => 6, 'next-12-months' => 12, default => 3
        };
        $growthFactor = 1 + ($calculation['growthRate'] / 100);
        $next = $calculation['nextMonth'] * $multiplier;
        $futureValues = collect(range(0, $horizon - 1))->map(fn (int $offset): float => round($next * ($growthFactor ** $offset), 2))->all();
        $futureLabels = collect(range(1, $horizon))->map(fn (int $offset): string => $snapshot['end']->addMonths($offset)->format('M Y'))->all();
        $historicalValues = $snapshot['monthlyRevenue'];
        $connection = (float) (collect($historicalValues)->last() ?? 0);
        $padding = array_fill(0, max(0, count($historicalValues) - 1), null);
        $chartLabels = array_merge($snapshot['labels'], $futureLabels);
        $forecastSeries = array_merge($padding, [$connection], $futureValues);
        $quarter = (float) array_sum($futureValues);
        $savedForecast = ($employeeId = $this->employeeId($request))
            && Schema::hasColumn('sales_forecasts', 'version')
            ? $this->persistence->saveForecast($snapshot, $calculation, $employeeId, $multiplier)
            : null;

        return view('forecasting.forecast', [
            'forecastKpis' => ['latestActualRevenue' => $connection, 'nextMonthForecast' => $next, 'growthRate' => $calculation['growthRate'], 'quarterForecast' => $quarter, 'confidence' => $calculation['confidence'], 'status' => $this->direction($calculation['growthRate'])],
            'historicalAndForecast' => [
                'labels' => $chartLabels,
                'actual' => array_merge($historicalValues, array_fill(0, $horizon, null)),
                'forecast' => $forecastSeries,
                'forecastLow' => array_map(fn ($value) => $value === null ? null : max(0, $value - ($calculation['rmse'] * 1.96)), $forecastSeries),
                'forecastHigh' => array_map(fn ($value) => $value === null ? null : $value + ($calculation['rmse'] * 1.96), $forecastSeries),
            ],
            'forecastSummary' => ['period' => "Next $horizon month(s)", 'expectedRevenue' => $quarter, 'expectedGrowth' => $calculation['growthRate'], 'projectedSalesGap' => $quarter - ($connection * $horizon), 'direction' => $this->direction($calculation['growthRate']), 'confidence' => $calculation['confidence']],
            'forecastByProduct' => $this->forecastBreakdown($snapshot['productSales'], $growthFactor * $multiplier, $calculation['growthRate']),
            'forecastByRegion' => $this->forecastBreakdown($snapshot['regionalSales'], $growthFactor * $multiplier, $calculation['growthRate']),
            'forecastByRepresentative' => $this->forecastBreakdown($snapshot['representativeSales'], $growthFactor * $multiplier, $calculation['growthRate']),
            'historicalRows' => $this->historicalRows($snapshot),
            'forecastInsights' => $this->salesInsights($snapshot, $calculation),
            'planningRecommendations' => collect($this->recommendationsFrom($snapshot, $calculation))->pluck('action')->all(),
            'filterOptions' => $this->analytics->filterOptions(),
            'savedForecast' => $savedForecast,
            'forecastAccuracy' => [
                'method' => $calculation['method'],
                'sampleSize' => $calculation['sampleSize'],
                'lower' => $calculation['predictionLower'],
                'upper' => $calculation['predictionUpper'],
                'mae' => $calculation['mae'],
                'mape' => $calculation['mape'],
                'rmse' => $calculation['rmse'],
            ],
            'forecastRuns' => Schema::hasColumn('sales_forecasts', 'version') ? SalesForecast::query()->orderByDesc('generated_at')->take(10)->get() : collect(),
        ]);
    }

    public function recommendations(Request $request)
    {
        $snapshot = $this->snapshot($request);
        $forecast = $this->analytics->forecast($snapshot['monthlyRevenue']);
        $generated = $this->recommendationsFrom($snapshot, $forecast);
        $savedForecast = null;
        if (($employeeId = $this->employeeId($request))
            && Schema::hasColumn('sales_forecasts', 'version')
            && Schema::hasColumn('forecast_recommendations', 'assigned_to')) {
            $savedForecast = $this->persistence->saveForecast($snapshot, $forecast, $employeeId);
            $this->persistence->saveRecommendations($savedForecast, $generated, $employeeId);
        }
        $savedRecommendations = $savedForecast?->recommendations()->with('assignee')->orderByDesc('created_at')->get() ?? collect();
        $rows = collect($generated)->filter(function (array $row) use ($request): bool {
            return $this->matches($row['category'], $request->query('category'), 'all-categories')
                && $this->matches($row['priority'], $request->query('priority'), 'all-priorities')
                && $this->matches($row['status'], $request->query('status'), 'all-statuses');
        })->values();
        $categories = collect($generated)->groupBy('category')->map(fn (Collection $items, string $category): array => [
            'name' => $category, 'count' => $items->count(), 'priority' => $items->contains('priority', 'High') ? 'High' : 'Medium',
            'issue' => $items->first()['insight'], 'icon' => 'bar-chart', 'tone' => 'primary',
        ])->values()->all();

        return view('forecasting.recommendations', [
            'recommendationKpis' => ['total' => $rows->count(), 'highPriority' => $rows->where('priority', 'High')->count(), 'opportunities' => $rows->where('type', 'opportunity')->count(), 'risks' => $rows->where('type', 'risk')->count(), 'inventoryActions' => $rows->where('category', 'Inventory Planning')->count(), 'marketingActions' => $rows->where('category', 'Marketing')->count()],
            'priorityRecommendations' => $rows->sortBy(fn (array $row): int => $row['priority'] === 'High' ? 0 : 1)->take(4)->all(),
            'recommendationCategories' => $categories,
            'recommendationRows' => $savedRecommendations->isNotEmpty()
                ? $savedRecommendations->map(fn ($row): array => [
                    'id' => $row->recommendation_id,
                    'title' => $row->title,
                    'category' => $row->recommendation_type,
                    'basis' => $row->evidence,
                    'priority' => $row->priority,
                    'impact' => $row->description,
                    'responsible_team' => $row->assigned_department,
                    'assigned_to' => $row->assigned_to,
                    'due_date' => $row->due_date?->toDateString(),
                    'status' => $row->implementation_status,
                    'decision_notes' => $row->decision_notes,
                    'outcome' => $row->outcome,
                ])->all()
                : $rows->map(fn (array $row): array => ['id' => null, 'title' => $row['title'], 'category' => $row['category'], 'basis' => $row['metric'], 'priority' => $row['priority'], 'impact' => $row['impact'], 'responsible_team' => $row['team'], 'assigned_to' => null, 'due_date' => null, 'status' => $row['status'], 'decision_notes' => null, 'outcome' => null])->all(),
            'supportingInsights' => $this->salesInsights($snapshot, $forecast),
            'actionPlan' => ['Database-Generated Actions' => $rows->map(fn (array $row): array => ['action' => $row['action'], 'team' => $row['team'], 'timeline' => $row['priority'] === 'High' ? 'Within 7 days' : 'Within 30 days', 'result' => $row['impact']])->all()],
            'filterOptions' => $this->analytics->filterOptions(),
            'employees' => Schema::hasTable('employees')
                ? Employee::query()->where('employee_status', 'Active')->orderBy('first_name')->get()
                : collect(),
        ]);
    }

    public function salesAnalysis(Request $request)
    {
        $tab = in_array($request->query('tab'), ['product', 'region', 'representative'], true) ? $request->query('tab') : 'product';
        $snapshot = $this->snapshot($request);

        return view('forecasting.sales-analysis', [
            'activeTab' => $tab,
            'topProducts' => $this->collectionChart($snapshot['productSales']),
            'salesByRegion' => $this->collectionChart($snapshot['regionalSales']),
            'salesByRepresentative' => $this->collectionChart($snapshot['representativeSales']),
            'filterOptions' => $this->analytics->filterOptions(),
        ]);
    }

    /** @return array<string, mixed> */
    private function snapshot(Request $request): array
    {
        $filters = $request->query();
        if (isset($filters['historical-period'])) {
            $filters['period'] = $filters['historical-period'];
        }

        return $this->analytics->snapshot((int) $request->query('year', now()->year), $filters);
    }

    /** @return array<int, array<string, mixed>> */
    private function performanceRows(array $snapshot): array
    {
        return $snapshot['targets']->groupBy('employee_id')->map(function (Collection $targets) use ($snapshot): array {
            $name = trim(($targets->first()->first_name ?? '').' '.($targets->first()->last_name ?? '')) ?: 'Unassigned Representative';
            $target = (float) $targets->sum('revenue_target');
            $actual = (float) ($snapshot['representativeSales'][$name] ?? 0);
            $achievement = $target > 0 ? ($actual / $target) * 100 : 0;

            return ['employee_name' => $name, 'target' => $target, 'actual' => $actual, 'achievement' => $achievement, 'gap' => $actual - $target, 'status' => $achievement >= 100 ? 'Exceeded' : ($achievement >= 95 ? 'On Target' : 'Below')];
        })->values()->all();
    }

    /** @return array<int, array<string, string>> */
    private function recommendationsFrom(array $snapshot, array $forecast): array
    {
        if ($snapshot['orders']->isEmpty()) {
            return [];
        }

        $recommendations = [];
        if ($snapshot['productSales']->isNotEmpty()) {
            $name = (string) $snapshot['productSales']->keys()->first();
            $value = (float) $snapshot['productSales']->first();
            $recommendations[] = ['title' => "Review inventory for $name", 'category' => 'Inventory Planning', 'priority' => 'High', 'insight' => "$name is the leading filtered product by revenue.", 'action' => 'Align stock and procurement plans with recorded demand.', 'impact' => 'Reduce stockout risk for the strongest product.', 'metric' => '₱'.number_format($value, 2).' product revenue', 'status' => 'New', 'team' => 'Inventory Team', 'type' => 'opportunity'];
        }
        if ($snapshot['regionalSales']->isNotEmpty()) {
            $name = (string) $snapshot['regionalSales']->keys()->last();
            $value = (float) $snapshot['regionalSales']->last();
            $recommendations[] = ['title' => "Review sales activity in $name", 'category' => 'Marketing', 'priority' => 'Medium', 'insight' => "$name has the lowest revenue in the filtered period.", 'action' => 'Review local pipeline and prepare a targeted campaign.', 'impact' => 'Improve demand in the lowest-performing customer region.', 'metric' => '₱'.number_format($value, 2).' regional revenue', 'status' => 'New', 'team' => 'Marketing Team', 'type' => 'risk'];
        }
        if ($snapshot['representativeSales']->isNotEmpty()) {
            $name = (string) $snapshot['representativeSales']->keys()->last();
            $value = (float) $snapshot['representativeSales']->last();
            $recommendations[] = ['title' => "Review performance with $name", 'category' => 'Representative Performance', 'priority' => 'High', 'insight' => "$name has the lowest filtered representative revenue.", 'action' => 'Review assigned accounts and agree on measurable coaching actions.', 'impact' => 'Improve representative execution and revenue contribution.', 'metric' => '₱'.number_format($value, 2).' representative revenue', 'status' => 'New', 'team' => 'Sales Manager', 'type' => 'risk'];
        }
        if ($forecast['growthRate'] !== 0.0) {
            $recommendations[] = ['title' => 'Align the sales plan with the current forecast', 'category' => 'Sales Strategy', 'priority' => abs($forecast['growthRate']) >= 10 ? 'High' : 'Medium', 'insight' => 'The filtered sales history produces a '.$forecast['growthRate'].'% projected growth rate.', 'action' => 'Review targets, staffing, and inventory against the generated forecast.', 'impact' => 'Keep operating plans aligned with expected demand.', 'metric' => $forecast['growthRate'].'% forecast growth', 'status' => 'New', 'team' => 'Sales Manager', 'type' => $forecast['growthRate'] > 0 ? 'opportunity' : 'risk'];
        }

        return $recommendations;
    }

    /** @return array<int, array{type: string, text: string}> */
    private function salesInsights(array $snapshot, array $forecast): array
    {
        if ($snapshot['orders']->isEmpty()) {
            return [['type' => 'warning', 'text' => 'No sales orders match the selected filters.']];
        }

        $insights = [
            ['type' => 'information', 'text' => number_format($snapshot['totalOrders']).' orders generated ₱'.number_format($snapshot['totalRevenue'], 2).' in filtered revenue.'],
            ['type' => $forecast['growthRate'] >= 0 ? 'success' : 'warning', 'text' => 'Recent filtered revenue produces a '.$forecast['growthRate'].'% forecast growth rate.'],
        ];
        if ($snapshot['productSales']->isNotEmpty()) {
            $insights[] = ['type' => 'success', 'text' => $snapshot['productSales']->keys()->first().' is the highest-revenue product in the selected data.'];
        }
        if ($snapshot['regionalSales']->isNotEmpty()) {
            $insights[] = ['type' => 'information', 'text' => $snapshot['regionalSales']->keys()->first().' is the leading assigned customer region in the selected data.'];
        }

        return $insights;
    }

    private function performanceInsights(array $snapshot, array $rows): array
    {
        if ($snapshot['targets']->isEmpty()) {
            return [['type' => 'warning', 'text' => 'No sales targets match the selected period. Add a target below to calculate achievement.']];
        }

        $best = collect($rows)->sortByDesc('achievement')->first();
        $lowest = collect($rows)->sortBy('achievement')->first();

        return array_values(array_filter([
            $best ? ['type' => 'success', 'text' => $best['employee_name'].' has the highest achievement at '.number_format($best['achievement'], 1).'%.'] : null,
            $lowest ? ['type' => 'warning', 'text' => $lowest['employee_name'].' has the lowest achievement at '.number_format($lowest['achievement'], 1).'%.'] : null,
        ]));
    }

    private function historicalRows(array $snapshot): array
    {
        return collect($snapshot['labels'])->map(function (string $label, int $index) use ($snapshot): array {
            $actual = (float) $snapshot['monthlyRevenue'][$index];
            $previous = $index > 0 ? (float) $snapshot['monthlyRevenue'][$index - 1] : 0;
            $target = (float) ($snapshot['monthlyTargets'][$index] ?? 0);

            return ['period' => $label, 'actual' => $actual, 'previous' => $previous, 'growth' => $previous > 0 ? (($actual - $previous) / $previous) * 100 : 0, 'target' => $target, 'achievement' => $target > 0 ? ($actual / $target) * 100 : 0];
        })->reverse()->values()->all();
    }

    private function forecastBreakdown(Collection $values, float $factor, float $growth): array
    {
        return $values->take(5)->map(fn ($value, string $name): array => ['name' => $name, 'revenue' => (float) $value * $factor, 'growth' => $growth, 'status' => $this->direction($growth)])->values()->all();
    }

    private function relativePerformance(Collection $values): array
    {
        $average = (float) ($values->average() ?? 0);

        return ['labels' => $values->keys()->all(), 'values' => $values->map(fn ($value): float => $average > 0 ? ((float) $value / $average) * 100 : 0)->values()->all()];
    }

    private function collectionChart(Collection $values): array
    {
        return ['labels' => $values->keys()->all(), 'values' => $values->values()->all()];
    }

    private function chart(array $labels, array $values): array
    {
        return ['labels' => $labels, 'values' => array_map('floatval', $values)];
    }

    private function ranked(Collection $values, int $limit): array
    {
        return $values->take($limit)->map(fn ($value, string $name): array => ['name' => $name, 'value' => (float) $value])->values()->all();
    }

    private function direction(float $growth): string
    {
        return $growth > 1 ? 'Upward' : ($growth < -1 ? 'Downward' : 'Stable');
    }

    private function matches(string $actual, ?string $filter, string $all): bool
    {
        return ! $filter || $filter === $all || Str::slug($actual) === $filter;
    }

    private function employeeId(Request $request): ?int
    {
        if (! Schema::hasTable('employees')) {
            return null;
        }

        $employeeId = $request->session()->get('employee_id') ?? Employee::query()->value('employee_id');
        if (! $employeeId) {
            $employeeId = Employee::query()->create([
                'username' => 'forecast-system',
                'password_hash' => password_hash(Str::random(48), PASSWORD_BCRYPT),
                'first_name' => 'Forecast',
                'last_name' => 'System',
                'department' => 'Management',
                'role' => 'System',
                'employee_status' => 'Active',
            ])->employee_id;
        }

        return $employeeId ? (int) $employeeId : null;
    }
}
