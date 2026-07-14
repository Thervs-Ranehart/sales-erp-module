<?php

namespace App\Http\Controllers;

use App\Services\SalesAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ForecastingController extends Controller
{
    public function __construct(private readonly SalesAnalyticsService $analytics) {}

    public function index()
    {
        $dashboardKpis = [
            'totalRevenue' => 11185000,
            'salesTarget' => 10890000,
            'achievementRate' => 103,
            'revenueGrowth' => 8.4,
            'nextMonthForecast' => 1210000,
            'highPriorityRecommendations' => 4,
        ];

        $revenueTrend = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'values' => [780000, 760000, 910000, 880000, 905000, 980000],
        ];

        $targetVsActualSummary = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'targetSeries' => [830000, 790000, 860000, 820000, 900000, 940000],
            'actualSeries' => [780000, 760000, 910000, 880000, 905000, 980000],
            'target' => 10890000,
            'actual' => 11185000,
            'achievement' => 103,
            'gap' => 295000,
            'status' => 'Exceeded Target',
        ];

        $forecastSummary = [
            'latestActual' => 980000,
            'nextMonthForecast' => 1040000,
            'growthRate' => 6.1,
            'direction' => 'Upward',
            'confidence' => 87,
        ];

        $topProducts = [
            ['name' => 'Product A', 'value' => 680000],
            ['name' => 'Product B', 'value' => 520000],
            ['name' => 'Product C', 'value' => 460000],
        ];

        $topRegions = [
            ['name' => 'NCR', 'value' => 420000],
            ['name' => 'CALABARZON', 'value' => 350000],
            ['name' => 'Western Visayas', 'value' => 310000],
        ];

        $topRepresentatives = [
            ['name' => 'Thervin', 'value' => 560000],
            ['name' => 'San Goku', 'value' => 430000],
            ['name' => 'Gojo Satoru', 'value' => 375000],
        ];

        $priorityRecommendations = [
            ['title' => 'Increase Inventory for Product A', 'category' => 'Inventory Planning', 'priority' => 'High', 'insight' => 'Product A exceeded its target and is expected to continue growing.', 'action' => 'Review reorder levels with the inventory team.'],
            ['title' => 'Launch a Campaign in Bicol', 'category' => 'Marketing', 'priority' => 'High', 'insight' => 'Bicol remains below its regional target at 85%.', 'action' => 'Review local demand and prepare a targeted campaign.'],
            ['title' => 'Provide Representative Coaching', 'category' => 'Sales Strategy', 'priority' => 'Medium', 'insight' => 'The lowest representative achieved 72% of target.', 'action' => 'Review account assignments and agree on coaching actions.'],
        ];

        $recentInsights = [
            ['type' => 'success', 'text' => 'Revenue increased by 8.4% compared with the previous period.'],
            ['type' => 'success', 'text' => 'Overall actual revenue is currently above the total sales target.'],
            ['type' => 'information', 'text' => 'Product A remains the strongest revenue contributor.'],
            ['type' => 'warning', 'text' => 'Bicol remains below its regional target at 85%.'],
            ['type' => 'information', 'text' => 'Next-month revenue is forecasted to grow by 6.1%.'],
            ['type' => 'risk', 'text' => 'Four high-priority recommendations require management review.'],
        ];

        $databaseSnapshot = $this->analytics->snapshot(now()->year);
        if ($databaseSnapshot && collect($databaseSnapshot['monthlyRevenue'])->contains(fn ($value): bool => (float) $value > 0)) {
            $forecast = $this->analytics->forecast($databaseSnapshot['monthlyRevenue']);
            $targetTotal = (float) $databaseSnapshot['targets']->sum('revenue_target');
            $dashboardKpis['totalRevenue'] = $databaseSnapshot['totalRevenue'];
            $dashboardKpis['salesTarget'] = $targetTotal;
            $dashboardKpis['achievementRate'] = $targetTotal > 0 ? round(($databaseSnapshot['totalRevenue'] / $targetTotal) * 100, 1) : 0;
            $dashboardKpis['revenueGrowth'] = $forecast['growthRate'];
            $dashboardKpis['nextMonthForecast'] = $forecast['nextMonth'];
            $revenueTrend = ['labels' => array_slice($databaseSnapshot['labels'], -6), 'values' => array_slice($databaseSnapshot['monthlyRevenue'], -6)];
            $targetVsActualSummary['actual'] = $databaseSnapshot['totalRevenue'];
            $targetVsActualSummary['target'] = $targetTotal;
            $targetVsActualSummary['achievement'] = $dashboardKpis['achievementRate'];
            $targetVsActualSummary['gap'] = $databaseSnapshot['totalRevenue'] - $targetTotal;
            $targetVsActualSummary['status'] = $targetTotal <= 0 ? 'No Target Set' : ($dashboardKpis['achievementRate'] >= 100 ? 'Exceeded Target' : 'Below Target');
            $monthlyTargets = collect(range(1, 12))->map(fn (int $month): float => (float) $databaseSnapshot['targets']->where('target_month', $month)->sum('revenue_target'))->all();
            $targetVsActualSummary['labels'] = array_slice($databaseSnapshot['labels'], -6);
            $targetVsActualSummary['targetSeries'] = array_slice($monthlyTargets, -6);
            $targetVsActualSummary['actualSeries'] = array_slice($databaseSnapshot['monthlyRevenue'], -6);
            $forecastSummary = ['latestActual' => (float) collect($databaseSnapshot['monthlyRevenue'])->filter()->last(), 'nextMonthForecast' => $forecast['nextMonth'], 'growthRate' => $forecast['growthRate'], 'direction' => $forecast['growthRate'] > 1 ? 'Upward' : ($forecast['growthRate'] < -1 ? 'Downward' : 'Stable'), 'confidence' => $forecast['confidence']];
            if ($databaseSnapshot['productSales']->isNotEmpty()) {
                $topProducts = $databaseSnapshot['productSales']->take(3)->map(fn ($value, $name): array => ['name' => $name, 'value' => (float) $value])->values()->all();
            }
            if ($databaseSnapshot['regionalSales']->isNotEmpty()) {
                $topRegions = $databaseSnapshot['regionalSales']->take(3)->map(fn ($value, $name): array => ['name' => $name, 'value' => (float) $value])->values()->all();
            }
            if ($databaseSnapshot['representativeSales']->isNotEmpty()) {
                $topRepresentatives = $databaseSnapshot['representativeSales']->take(3)->map(fn ($value, $name): array => ['name' => $name, 'value' => (float) $value])->values()->all();
            }
            $recentInsights[0]['text'] = 'Dashboard metrics are calculated from current sales-order database records.';
        }

        return view('forecasting.index', compact(
            'dashboardKpis',
            'revenueTrend',
            'targetVsActualSummary',
            'forecastSummary',
            'topProducts',
            'topRegions',
            'topRepresentatives',
            'priorityRecommendations',
            'recentInsights',
        ));
    }

    public function reports(Request $request)
    {
        $year = (int) $request->query('year', now()->year);

        // UI-only fallback dataset (no DB calls).
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $values = [
            400000,
            360000,
            420000,
            390000,
            450000,
            480000,
            520000,
            500000,
            470000,
            530000,
            600000,
            680000,
        ];

        $orders = [92, 88, 97, 91, 105, 112, 121, 116, 109, 124, 137, 148];

        $monthlyRevenue = [
            'labels' => $labels,
            'values' => array_map(fn ($v) => (float) $v, $values),
        ];

        // UI-only fallback dataset for Sales by Product
        $topProducts = [
            'labels' => [
                'Product A',
                'Product B',
                'Product C',
                'Product D',
                'Product E',
            ],
            'values' => [
                680000,
                520000,
                460000,
                310000,
                240000,
            ],
        ];

        // UI-only fallback dataset for Sales by Region
        $salesByRegion = [
            'labels' => [
                'NCR',
                'CALABARZON',
                'Western Visayas',
                'Davao',
                'Bicol',
            ],
            'values' => [
                420000,
                350000,
                310000,
                280000,
                150000,
            ],
        ];

        // UI-only fallback dataset for Sales by Representative
        $salesByRepresentative = [
            'labels' => [
                'Thervin',
                'San Goku',
                'Gojo Satoru',
                'Elon Musk',
                'Peter Parker',
            ],
            'values' => [
                560000,
                430000,
                375000,
                220000,
                185000,
            ],
        ];

        $periodLength = match ($request->query('period')) {
            'this-month', 'last-month' => 1,
            'quarter' => 3,
            default => 12,
        };
        if ($periodLength < 12) {
            $labels = array_slice($labels, -$periodLength);
            $values = array_slice($values, -$periodLength);
            $orders = array_slice($orders, -$periodLength);
        }

        $monthlyRevenue = [
            'labels' => $labels,
            'values' => array_map(fn ($value) => (float) $value, $values),
        ];

        $selectedProduct = ['category-a' => 'Product A', 'category-b' => 'Product B', 'category-c' => 'Product C'][$request->query('product')] ?? null;
        if ($selectedProduct) {
            $index = array_search($selectedProduct, $topProducts['labels'], true);
            $topProducts = ['labels' => [$selectedProduct], 'values' => [$topProducts['values'][$index]]];
        }

        $selectedRegion = ['ncr' => 'NCR', 'visayas' => 'Western Visayas', 'mindanao' => 'Davao'][$request->query('region')] ?? null;
        if ($selectedRegion) {
            $index = array_search($selectedRegion, $salesByRegion['labels'], true);
            $salesByRegion = ['labels' => [$selectedRegion], 'values' => [$salesByRegion['values'][$index]]];
        }

        $selectedRepresentative = ['rep-1' => 'Thervin', 'rep-2' => 'San Goku', 'rep-3' => 'Gojo Satoru'][$request->query('representative')] ?? null;
        if ($selectedRepresentative) {
            $index = array_search($selectedRepresentative, $salesByRepresentative['labels'], true);
            $salesByRepresentative = ['labels' => [$selectedRepresentative], 'values' => [$salesByRepresentative['values'][$index]]];
        }

        $totalRevenue = array_sum($values);
        $totalOrders = array_sum($orders);
        $averageOrderValue = $totalRevenue / ($totalOrders ?: 1);
        $latestRevenue = $values[array_key_last($values)];
        $salesGrowth = count($values) > 1 ? (($latestRevenue - $values[0]) / ($values[0] ?: 1)) * 100 : 0;

        $reportKpis = [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'averageOrderValue' => $averageOrderValue,
            'activeCustomers' => 342,
            'salesGrowth' => $salesGrowth,
            'bestMonth' => $labels[array_keys($values, max($values), true)[0]],
        ];

        $monthlyReportRows = array_map(function (string $month, int $revenue, int $orderCount, int $index) use ($values): array {
            $previousRevenue = $index > 0 ? $values[$index - 1] : null;
            $growth = $previousRevenue ? (($revenue - $previousRevenue) / $previousRevenue) * 100 : null;

            return [
                'period' => $month,
                'revenue' => $revenue,
                'orders' => $orderCount,
                'averageOrderValue' => $revenue / ($orderCount ?: 1),
                'growth' => $growth,
            ];
        }, $labels, $values, $orders, array_keys($labels));

        $reportInsights = [
            ['type' => 'success', 'text' => 'December generated the highest monthly revenue at ₱680,000.'],
            ['type' => 'info', 'text' => 'Product A remains the largest product contributor at ₱680,000.'],
            ['type' => 'info', 'text' => 'NCR leads regional sales with ₱420,000 in recorded revenue.'],
            ['type' => 'warning', 'text' => 'Revenue softened in September before recovering through the fourth quarter.'],
        ];

        $databaseSnapshot = $this->analytics->snapshot($year);
        if ($databaseSnapshot) {
            $labels = $databaseSnapshot['labels'];
            $values = $databaseSnapshot['monthlyRevenue'];
            $orders = $databaseSnapshot['monthlyOrders'];
            if ($periodLength < 12) {
                $labels = array_slice($labels, -$periodLength);
                $values = array_slice($values, -$periodLength);
                $orders = array_slice($orders, -$periodLength);
            }

            $monthlyRevenue = ['labels' => $labels, 'values' => $values];
            if ($databaseSnapshot['productSales']->isNotEmpty()) {
                $topProducts = ['labels' => $databaseSnapshot['productSales']->keys()->take(5)->values()->all(), 'values' => $databaseSnapshot['productSales']->values()->take(5)->all()];
            }
            if ($databaseSnapshot['regionalSales']->isNotEmpty()) {
                $salesByRegion = ['labels' => $databaseSnapshot['regionalSales']->keys()->take(5)->values()->all(), 'values' => $databaseSnapshot['regionalSales']->values()->take(5)->all()];
            }
            if ($databaseSnapshot['representativeSales']->isNotEmpty()) {
                $salesByRepresentative = ['labels' => $databaseSnapshot['representativeSales']->keys()->take(5)->values()->all(), 'values' => $databaseSnapshot['representativeSales']->values()->take(5)->all()];
            }

            $totalRevenue = array_sum($values);
            $totalOrders = array_sum($orders);
            $latestRevenue = (float) ($values[array_key_last($values)] ?? 0);
            $reportKpis = [
                'totalRevenue' => $totalRevenue,
                'totalOrders' => $totalOrders,
                'averageOrderValue' => $totalRevenue / ($totalOrders ?: 1),
                'activeCustomers' => $databaseSnapshot['activeCustomers'],
                'salesGrowth' => count($values) > 1 && (float) $values[0] > 0 ? (($latestRevenue - (float) $values[0]) / (float) $values[0]) * 100 : 0,
                'bestMonth' => $labels[array_keys($values, max($values), true)[0]],
            ];
            $monthlyReportRows = array_map(function (string $month, $revenue, $orderCount, int $index) use ($values): array {
                $previous = $index > 0 ? (float) $values[$index - 1] : null;

                return ['period' => $month, 'revenue' => (float) $revenue, 'orders' => (int) $orderCount, 'averageOrderValue' => (float) $revenue / ((int) $orderCount ?: 1), 'growth' => $previous > 0 ? (((float) $revenue - $previous) / $previous) * 100 : null];
            }, $labels, $values, $orders, array_keys($labels));
            $reportInsights[0]['text'] = $reportKpis['bestMonth'].' generated the highest recorded revenue for '.$year.'.';
        }

        return view('forecasting.reports', [
            'monthlyRevenue' => $monthlyRevenue,
            'topProducts' => $topProducts,
            'salesByRegion' => $salesByRegion,
            'salesByRepresentative' => $salesByRepresentative,
            'selectedYear' => $year,
            'reportKpis' => $reportKpis,
            'monthlyReportRows' => $monthlyReportRows,
            'reportInsights' => $reportInsights,
        ]);
    }

    public function performance(Request $request)
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        // Placeholder: monthly target vs actual revenue.
        $targetMonthly = [830000, 790000, 860000, 820000, 900000, 940000, 980000, 960000, 920000, 990000, 1050000, 1120000];
        $actualMonthly = [780000, 760000, 910000, 880000, 905000, 980000, 1020000, 980000, 950000, 1040000, 1100000, 1160000];

        $achievementMonthly = array_map(function ($actual, $target) {
            $t = (float) ($target ?: 1);

            return round(((float) $actual / $t) * 100, 0);
        }, $actualMonthly, $targetMonthly);

        $monthlyTargetVsActual = [
            'labels' => $months,
            'target' => $targetMonthly,
            'actual' => $actualMonthly,
        ];

        $achievementTrend = [
            'labels' => $months,
            'values' => array_map(fn ($v) => (float) $v, $achievementMonthly),
        ];

        $achievementByProduct = [
            'labels' => ['Product A', 'Product B', 'Product C', 'Product D', 'Product E'],
            'values' => [118, 112, 104, 96, 86],
        ];

        $achievementByRegion = [
            'labels' => ['NCR', 'CALABARZON', 'Western Visayas', 'Davao', 'Bicol'],
            'values' => [112, 103, 99, 92, 85],
        ];

        $achievementByRepresentative = [
            'labels' => ['Thervin', 'San Goku', 'Gojo Satoru', 'Elon Musk', 'Peter Parker'],
            'values' => [118, 106, 102, 88, 72],
        ];

        $salesTargetTotal = array_sum($targetMonthly);
        $actualRevenueTotal = array_sum($actualMonthly);
        $achievementRate = (int) round(($actualRevenueTotal / ($salesTargetTotal ?: 1)) * 100, 0);
        $salesGap = $actualRevenueTotal - $salesTargetTotal;

        $bestPerformer = ['name' => 'Thervin', 'achievement' => 118];
        $lowestPerformer = ['name' => 'Peter Parker', 'achievement' => 72];

        $targetVsActualRows = [
            ['employee_name' => 'Thervin', 'target' => 500000, 'actual' => 530000, 'achievement' => 106, 'gap' => 30000, 'status' => 'Exceeded'],
            ['employee_name' => 'San Goku', 'target' => 420000, 'actual' => 455000, 'achievement' => 108, 'gap' => 35000, 'status' => 'Exceeded'],
            ['employee_name' => 'Gojo Satoru', 'target' => 400000, 'actual' => 360000, 'achievement' => 90, 'gap' => -40000, 'status' => 'Below'],
            ['employee_name' => 'Elon Musk', 'target' => 380000, 'actual' => 399000, 'achievement' => 105, 'gap' => 19000, 'status' => 'Exceeded'],
            ['employee_name' => 'Peter Parker', 'target' => 460000, 'actual' => 331200, 'achievement' => 72, 'gap' => -128800, 'status' => 'Below'],
        ];

        $selectedStatus = $request->query('status', 'all');
        if ($selectedStatus !== 'all') {
            $targetStatus = match ($selectedStatus) {
                'exceeded' => 'Exceeded',
                'on-target' => 'On Target',
                default => 'Below',
            };
            $targetVsActualRows = array_values(array_filter($targetVsActualRows, fn (array $row): bool => $row['status'] === $targetStatus));
        }

        $performanceInsights = [
            ['type' => 'success', 'text' => 'NCR exceeded target by 12%.'],
            ['type' => 'success', 'text' => 'Product A exceeded target by 18%.'],
            ['type' => 'warning', 'text' => 'Peter Parker achieved only 72%.'],
            ['type' => 'warning', 'text' => 'Region B is below target by 15%.'],
        ];

        $databaseSnapshot = $this->analytics->snapshot((int) $request->query('year', now()->year));
        if ($databaseSnapshot && $databaseSnapshot['targets']->isNotEmpty()) {
            $targetMonthly = collect(range(1, 12))->map(fn (int $month): float => (float) $databaseSnapshot['targets']->where('target_month', $month)->sum('revenue_target'))->all();
            $actualMonthly = $databaseSnapshot['monthlyRevenue'];
            $monthlyTargetVsActual = ['labels' => $databaseSnapshot['labels'], 'target' => $targetMonthly, 'actual' => $actualMonthly];
            $achievementValues = array_map(fn ($actual, $target): float => (float) $target > 0 ? round(((float) $actual / (float) $target) * 100, 1) : 0, $actualMonthly, $targetMonthly);
            $achievementTrend = ['labels' => $databaseSnapshot['labels'], 'values' => $achievementValues];

            $salesTargetTotal = array_sum($targetMonthly);
            $actualRevenueTotal = array_sum($actualMonthly);
            $achievementRate = (int) round(($actualRevenueTotal / ($salesTargetTotal ?: 1)) * 100);
            $salesGap = $actualRevenueTotal - $salesTargetTotal;

            $targetVsActualRows = $databaseSnapshot['targets']->groupBy('employee_id')->map(function (Collection $targets) use ($databaseSnapshot): array {
                $target = (float) $targets->sum('revenue_target');
                $employeeName = trim(($targets->first()->first_name ?? '').' '.($targets->first()->last_name ?? '')) ?: 'Unassigned Representative';
                $actual = (float) ($databaseSnapshot['representativeSales'][$employeeName] ?? 0);
                $achievement = $target > 0 ? round(($actual / $target) * 100, 1) : 0;

                return ['employee_name' => $employeeName, 'target' => $target, 'actual' => $actual, 'achievement' => $achievement, 'gap' => $actual - $target, 'status' => $achievement > 100 ? 'Exceeded' : ($achievement >= 95 ? 'On Target' : 'Below')];
            })->values()->all();
            $bestRow = collect($targetVsActualRows)->sortByDesc('achievement')->first();
            $lowestRow = collect($targetVsActualRows)->sortBy('achievement')->first();
            $bestPerformer = ['name' => $bestRow['employee_name'] ?? 'N/A', 'achievement' => $bestRow['achievement'] ?? 0];
            $lowestPerformer = ['name' => $lowestRow['employee_name'] ?? 'N/A', 'achievement' => $lowestRow['achievement'] ?? 0];
            $achievementByRepresentative = [
                'labels' => collect($targetVsActualRows)->pluck('employee_name')->all(),
                'values' => collect($targetVsActualRows)->pluck('achievement')->all(),
            ];

            if ($selectedStatus !== 'all') {
                $targetStatus = match ($selectedStatus) {'exceeded' => 'Exceeded', 'on-target' => 'On Target', default => 'Below'};
                $targetVsActualRows = array_values(array_filter($targetVsActualRows, fn (array $row): bool => $row['status'] === $targetStatus));
            }
            $performanceInsights[0]['text'] = 'Actual revenue and targets are calculated from database records for the selected year.';
        }

        return view('forecasting.performance', [
            'monthlyTargetVsActual' => $monthlyTargetVsActual,
            'achievementTrend' => $achievementTrend,
            'achievementByProduct' => $achievementByProduct,
            'achievementByRegion' => $achievementByRegion,
            'achievementByRepresentative' => $achievementByRepresentative,
            'kpis' => [
                'salesTargetTotal' => $salesTargetTotal,
                'actualRevenueTotal' => $actualRevenueTotal,
                'achievementRate' => $achievementRate,
                'salesGap' => $salesGap,
                'bestPerformer' => $bestPerformer,
                'lowestPerformer' => $lowestPerformer,
            ],
            'targetVsActualRows' => $targetVsActualRows,
            'performanceInsights' => $performanceInsights,
        ]);
    }

    public function forecast(Request $request)
    {
        $historicalAndForecast = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
            'actual' => [400000, 420000, 450000, 480000, 510000, 540000, null, null, null],
            'forecast' => [null, null, null, null, null, 540000, 570000, 605000, 640000],
            'forecastLow' => [null, null, null, null, null, 540000, 545000, 570000, 595000],
            'forecastHigh' => [null, null, null, null, null, 540000, 595000, 640000, 685000],
        ];

        $forecastKpis = [
            'latestActualRevenue' => 540000,
            'nextMonthForecast' => 570000,
            'growthRate' => 5.6,
            'quarterForecast' => 1815000,
            'confidence' => 87,
            'status' => 'Growth Expected',
        ];

        $scenarioMultiplier = match ($request->query('scenario')) {
            'optimistic' => 1.08,
            'conservative' => 0.92,
            default => 1.0,
        };
        if ($scenarioMultiplier !== 1.0) {
            $historicalAndForecast['forecast'] = array_map(fn ($value) => is_null($value) ? null : round($value * $scenarioMultiplier), $historicalAndForecast['forecast']);
            $historicalAndForecast['forecastLow'] = array_map(fn ($value) => is_null($value) ? null : round($value * $scenarioMultiplier), $historicalAndForecast['forecastLow']);
            $historicalAndForecast['forecastHigh'] = array_map(fn ($value) => is_null($value) ? null : round($value * $scenarioMultiplier), $historicalAndForecast['forecastHigh']);
            $forecastKpis['nextMonthForecast'] = round($forecastKpis['nextMonthForecast'] * $scenarioMultiplier);
            $forecastKpis['quarterForecast'] = round($forecastKpis['quarterForecast'] * $scenarioMultiplier);
            $forecastKpis['status'] = $scenarioMultiplier > 1 ? 'Strong Growth Expected' : 'Conservative Outlook';
        }

        $forecastSummary = [
            'period' => 'Next 3 months',
            'expectedRevenue' => 1815000,
            'expectedGrowth' => 8.5,
            'projectedSalesGap' => 135000,
            'direction' => 'Upward',
            'confidence' => 87,
        ];
        if ($scenarioMultiplier !== 1.0) {
            $forecastSummary['expectedRevenue'] = round($forecastSummary['expectedRevenue'] * $scenarioMultiplier);
            $forecastSummary['projectedSalesGap'] = round($forecastSummary['projectedSalesGap'] * $scenarioMultiplier);
            $forecastSummary['direction'] = $scenarioMultiplier > 1 ? 'Strong Upward' : 'Conservative';
        }

        $forecastByProduct = [
            ['name' => 'Product A', 'revenue' => 520000, 'growth' => 12, 'status' => 'Grow'],
            ['name' => 'Product B', 'revenue' => 410000, 'growth' => 7, 'status' => 'Grow'],
            ['name' => 'Product C', 'revenue' => 335000, 'growth' => 1, 'status' => 'Stable'],
            ['name' => 'Product D', 'revenue' => 265000, 'growth' => -4, 'status' => 'Decline'],
        ];

        $forecastByRegion = [
            ['name' => 'NCR', 'revenue' => 590000, 'growth' => 11, 'status' => 'Low Risk'],
            ['name' => 'CALABARZON', 'revenue' => 470000, 'growth' => 9, 'status' => 'Low Risk'],
            ['name' => 'Western Visayas', 'revenue' => 360000, 'growth' => 3, 'status' => 'Moderate'],
            ['name' => 'Davao', 'revenue' => 295000, 'growth' => -2, 'status' => 'High Risk'],
        ];

        $forecastByRepresentative = [
            ['name' => 'Thervin', 'revenue' => 485000, 'growth' => 108, 'status' => 'Upward'],
            ['name' => 'San Goku', 'revenue' => 420000, 'growth' => 104, 'status' => 'Upward'],
            ['name' => 'Gojo Satoru', 'revenue' => 350000, 'growth' => 99, 'status' => 'Stable'],
            ['name' => 'Peter Parker', 'revenue' => 240000, 'growth' => 86, 'status' => 'Decline'],
        ];

        $historicalRows = [
            ['period' => 'June', 'actual' => 540000, 'previous' => 510000, 'growth' => 5.9, 'target' => 525000, 'achievement' => 102.9],
            ['period' => 'May', 'actual' => 510000, 'previous' => 480000, 'growth' => 6.3, 'target' => 500000, 'achievement' => 102.0],
            ['period' => 'April', 'actual' => 480000, 'previous' => 450000, 'growth' => 6.7, 'target' => 470000, 'achievement' => 102.1],
            ['period' => 'March', 'actual' => 450000, 'previous' => 420000, 'growth' => 7.1, 'target' => 440000, 'achievement' => 102.3],
            ['period' => 'February', 'actual' => 420000, 'previous' => 400000, 'growth' => 5.0, 'target' => 425000, 'achievement' => 98.8],
        ];

        $forecastInsights = [
            ['type' => 'success', 'text' => 'Revenue has increased for three consecutive months.'],
            ['type' => 'info', 'text' => 'Product A is expected to remain the strongest revenue contributor.'],
            ['type' => 'success', 'text' => 'CALABARZON is projected to grow by 9% next quarter.'],
            ['type' => 'warning', 'text' => 'One sales representative shows a possible decline in performance.'],
        ];

        $planningRecommendations = [
            'Increase inventory for products with strong projected demand.',
            'Reduce excess inventory for products with declining forecasts.',
            'Launch regional marketing campaigns where growth is expected.',
            'Provide sales support to representatives with declining trends.',
            'Coordinate forecasted demand with inventory and procurement teams.',
            'Review targets when forecasts consistently exceed planned values.',
        ];

        $databaseSnapshot = $this->analytics->snapshot((int) $request->query('year', now()->year));
        if ($databaseSnapshot && collect($databaseSnapshot['monthlyRevenue'])->contains(fn ($value): bool => (float) $value > 0)) {
            $forecast = $this->analytics->forecast($databaseSnapshot['monthlyRevenue']);
            $observedIndexes = collect($databaseSnapshot['monthlyRevenue'])->filter(fn ($value): bool => (float) $value > 0)->keys()->take(-6)->values();
            $actualLabels = $observedIndexes->map(fn (int $index): string => $databaseSnapshot['labels'][$index])->all();
            $actualValues = $observedIndexes->map(fn (int $index): float => (float) $databaseSnapshot['monthlyRevenue'][$index])->all();
            $futureLabels = collect([1, 2, 3])->map(fn (int $offset): string => now()->startOfMonth()->addMonths($offset)->format('M'))->all();
            $growthFactor = 1 + ($forecast['growthRate'] / 100);
            $adjustedNextMonth = $forecast['nextMonth'] * $scenarioMultiplier;
            $futureValues = collect([0, 1, 2])->map(fn (int $offset): float => round($adjustedNextMonth * ($growthFactor ** $offset), 2))->all();
            $adjustedQuarter = array_sum($futureValues);
            $labels = array_merge($actualLabels, $futureLabels);
            $connectionValue = $actualValues[array_key_last($actualValues)] ?? 0;
            $historicalAndForecast = [
                'labels' => $labels,
                'actual' => array_merge($actualValues, array_fill(0, 3, null)),
                'forecast' => array_merge(array_fill(0, max(0, count($actualValues) - 1), null), [$connectionValue], $futureValues),
                'forecastLow' => array_merge(array_fill(0, max(0, count($actualValues) - 1), null), [$connectionValue], array_map(fn ($value): float => $value * .9, $futureValues)),
                'forecastHigh' => array_merge(array_fill(0, max(0, count($actualValues) - 1), null), [$connectionValue], array_map(fn ($value): float => $value * 1.1, $futureValues)),
            ];
            $forecastKpis = ['latestActualRevenue' => $connectionValue, 'nextMonthForecast' => $adjustedNextMonth, 'growthRate' => $forecast['growthRate'], 'quarterForecast' => $adjustedQuarter, 'confidence' => $forecast['confidence'], 'status' => $forecast['growthRate'] > 1 ? 'Growth Expected' : ($forecast['growthRate'] < -1 ? 'Decline Expected' : 'Stable')];
            $forecastSummary = ['period' => 'Next 3 months', 'expectedRevenue' => $adjustedQuarter, 'expectedGrowth' => $forecast['growthRate'], 'projectedSalesGap' => $adjustedQuarter - ($connectionValue * 3), 'direction' => $forecast['growthRate'] > 1 ? 'Upward' : ($forecast['growthRate'] < -1 ? 'Downward' : 'Stable'), 'confidence' => $forecast['confidence']];
            $forecastByProduct = $databaseSnapshot['productSales']->take(4)->map(fn ($revenue, $name): array => ['name' => $name, 'revenue' => (float) $revenue * $growthFactor, 'growth' => $forecast['growthRate'], 'status' => $forecast['growthRate'] >= 0 ? 'Grow' : 'Decline'])->values()->all();
            $forecastByRegion = $databaseSnapshot['regionalSales']->take(4)->map(fn ($revenue, $name): array => ['name' => $name, 'revenue' => (float) $revenue * $growthFactor, 'growth' => $forecast['growthRate'], 'status' => abs($forecast['growthRate']) < 2 ? 'Moderate' : ($forecast['growthRate'] > 0 ? 'Low Risk' : 'High Risk')])->values()->all();
            $forecastByRepresentative = $databaseSnapshot['representativeSales']->take(4)->map(fn ($revenue, $name): array => ['name' => $name, 'revenue' => (float) $revenue * $growthFactor, 'growth' => 100 + $forecast['growthRate'], 'status' => $forecast['growthRate'] > 1 ? 'Upward' : 'Stable'])->values()->all();
            $historicalRows = $observedIndexes->map(function (int $index) use ($databaseSnapshot): array {
                $actual = (float) $databaseSnapshot['monthlyRevenue'][$index];
                $previous = $index > 0 ? (float) $databaseSnapshot['monthlyRevenue'][$index - 1] : 0;
                $target = (float) $databaseSnapshot['targets']->where('target_month', $index + 1)->sum('revenue_target');

                return [
                    'period' => $databaseSnapshot['labels'][$index],
                    'actual' => $actual,
                    'previous' => $previous,
                    'growth' => $previous > 0 ? (($actual - $previous) / $previous) * 100 : 0,
                    'target' => $target,
                    'achievement' => $target > 0 ? ($actual / $target) * 100 : 0,
                ];
            })->reverse()->values()->all();
            $forecastInsights[0]['text'] = 'The forecast uses recent monthly revenue stored in the sales database.';
        }

        return view('forecasting.forecast', compact(
            'forecastKpis',
            'historicalAndForecast',
            'forecastSummary',
            'forecastByProduct',
            'forecastByRegion',
            'forecastByRepresentative',
            'historicalRows',
            'forecastInsights',
            'planningRecommendations',
        ));
    }

    public function recommendations(Request $request)
    {
        $recommendationKpis = [
            'total' => 12,
            'highPriority' => 4,
            'opportunities' => 5,
            'risks' => 3,
            'inventoryActions' => 3,
            'marketingActions' => 4,
        ];

        $priorityRecommendations = [
            ['title' => 'Increase Inventory for Product A', 'category' => 'Inventory Planning', 'priority' => 'High', 'insight' => 'Product A exceeded its target by 18% and is forecasted to continue growing.', 'action' => 'Increase reorder levels and coordinate demand with inventory and procurement teams.', 'impact' => 'Reduce stockout risk and support continued revenue growth.', 'metric' => '118% target achievement', 'status' => 'New'],
            ['title' => 'Launch a Marketing Campaign in Bicol', 'category' => 'Marketing', 'priority' => 'High', 'insight' => 'Bicol achieved only 85% of its regional sales target.', 'action' => 'Create a targeted promotion and review customer demand in the region.', 'impact' => 'Improve regional awareness, pipeline, and sales conversion.', 'metric' => '15% below target', 'status' => 'Under Review'],
            ['title' => 'Provide Sales Coaching to Peter Parker', 'category' => 'Representative Performance', 'priority' => 'Medium', 'insight' => 'The representative achieved 72% of the assigned sales target.', 'action' => 'Review account assignments, sales activity, and provide focused coaching.', 'impact' => 'Improve representative execution and target attainment.', 'metric' => '72% achievement', 'status' => 'In Progress'],
            ['title' => 'Review Next-Quarter Sales Targets', 'category' => 'Target Adjustment', 'priority' => 'Medium', 'insight' => 'Actual revenue exceeded target across the latest three periods.', 'action' => 'Assess whether next-quarter targets should be increased responsibly.', 'impact' => 'Keep targets challenging, realistic, and aligned with recent demand.', 'metric' => '3 periods above target', 'status' => 'New'],
        ];

        $recommendationCategories = [
            ['name' => 'Sales Strategy', 'count' => 3, 'priority' => 'Medium', 'issue' => 'Representative workload and target alignment', 'icon' => 'bullseye', 'tone' => 'primary'],
            ['name' => 'Inventory Planning', 'count' => 3, 'priority' => 'High', 'issue' => 'Product A stockout exposure', 'icon' => 'boxes', 'tone' => 'success'],
            ['name' => 'Marketing', 'count' => 4, 'priority' => 'High', 'issue' => 'Low regional demand in Bicol', 'icon' => 'megaphone', 'tone' => 'info'],
            ['name' => 'Regional Performance', 'count' => 2, 'priority' => 'High', 'issue' => 'Uneven achievement across territories', 'icon' => 'geo-alt', 'tone' => 'warning'],
            ['name' => 'Representative Performance', 'count' => 2, 'priority' => 'Medium', 'issue' => 'Individual achievement gaps', 'icon' => 'people', 'tone' => 'danger'],
        ];

        $recommendationRows = [
            ['title' => 'Increase Product A inventory', 'category' => 'Inventory Planning', 'basis' => '+18% target achievement', 'priority' => 'High', 'impact' => 'Prevent stockouts', 'responsible_team' => 'Inventory Team', 'status' => 'New'],
            ['title' => 'Promote the Bicol region', 'category' => 'Marketing', 'basis' => '85% regional achievement', 'priority' => 'High', 'impact' => 'Improve regional sales', 'responsible_team' => 'Marketing Team', 'status' => 'Under Review'],
            ['title' => 'Coach Peter Parker', 'category' => 'Sales Strategy', 'basis' => '72% achievement', 'priority' => 'Medium', 'impact' => 'Improve rep performance', 'responsible_team' => 'Sales Manager', 'status' => 'In Progress'],
            ['title' => 'Recognize top representative', 'category' => 'Representative Performance', 'basis' => '118% achievement', 'priority' => 'Low', 'impact' => 'Reinforce strong performance', 'responsible_team' => 'Sales Manager', 'status' => 'Approved'],
            ['title' => 'Review next-quarter target', 'category' => 'Target Adjustment', 'basis' => 'Three periods above target', 'priority' => 'Medium', 'impact' => 'Improve target relevance', 'responsible_team' => 'Sales Manager', 'status' => 'New'],
            ['title' => 'Increase CALABARZON campaign support', 'category' => 'Regional Performance', 'basis' => '9% forecast growth', 'priority' => 'Medium', 'impact' => 'Capture projected demand', 'responsible_team' => 'Marketing Team', 'status' => 'Approved'],
        ];

        $category = $request->query('category');
        $priority = $request->query('priority');
        $status = $request->query('status');
        $recommendationRows = array_values(array_filter($recommendationRows, function (array $row) use ($category, $priority, $status): bool {
            return (!$category || $category === 'all-categories' || \Illuminate\Support\Str::slug($row['category']) === $category)
                && (!$priority || $priority === 'all-priorities' || \Illuminate\Support\Str::slug($row['priority']) === $priority)
                && (!$status || $status === 'all-statuses' || \Illuminate\Support\Str::slug($row['status']) === $status);
        }));

        $supportingInsights = [
            ['type' => 'success', 'text' => 'Product A exceeded its target by 18%.'],
            ['type' => 'success', 'text' => 'NCR is the highest-performing region at 112%.'],
            ['type' => 'warning', 'text' => 'Bicol is below target at 85%.'],
            ['type' => 'risk', 'text' => 'Peter Parker is the lowest-performing representative at 72%.'],
            ['type' => 'information', 'text' => 'Revenue is forecasted to grow during the next quarter.'],
            ['type' => 'information', 'text' => 'Actual sales exceeded target in the latest three months.'],
        ];

        $actionPlan = [
            'Immediate Actions' => [
                ['action' => 'Review high-priority risks and Product A stock levels.', 'team' => 'Sales Manager / Inventory Team', 'timeline' => 'Within 7 days', 'result' => 'Reduce immediate revenue and stockout exposure.'],
                ['action' => 'Contact representatives currently below target.', 'team' => 'Sales Manager', 'timeline' => 'Within 1 week', 'result' => 'Agree on coaching and recovery actions.'],
            ],
            'Short-Term Actions' => [
                ['action' => 'Launch targeted regional marketing campaigns.', 'team' => 'Marketing Team', 'timeline' => 'Within 30 days', 'result' => 'Increase demand in underperforming regions.'],
                ['action' => 'Coordinate forecast demand with purchasing plans.', 'team' => 'Inventory Team', 'timeline' => 'Next planning cycle', 'result' => 'Align reorder levels with expected sales.'],
            ],
            'Long-Term Actions' => [
                ['action' => 'Evaluate sales target realism and forecast accuracy.', 'team' => 'Sales Manager', 'timeline' => 'Quarterly', 'result' => 'Improve future targets and planning quality.'],
                ['action' => 'Measure results from completed recommendations.', 'team' => 'Regional Sales Team', 'timeline' => 'Every quarter', 'result' => 'Retain actions that create measurable improvement.'],
            ],
        ];

        $databaseSnapshot = $this->analytics->snapshot((int) $request->query('year', now()->year));
        if ($databaseSnapshot) {
            $topProduct = $databaseSnapshot['productSales']->keys()->first() ?? 'the top product';
            $topRegion = $databaseSnapshot['regionalSales']->keys()->first() ?? 'the leading warehouse';
            $lowestRepresentative = $databaseSnapshot['representativeSales']->sort()->keys()->first() ?? 'the lowest-performing representative';
            $priorityRecommendations[0]['title'] = 'Review Inventory for '.$topProduct;
            $priorityRecommendations[0]['insight'] = $topProduct.' is the strongest product based on recorded sales-order item revenue.';
            $priorityRecommendations[1]['title'] = 'Support Growth in '.$topRegion;
            $priorityRecommendations[1]['insight'] = $topRegion.' currently has the highest warehouse-based sales contribution.';
            $priorityRecommendations[2]['title'] = 'Review Performance with '.$lowestRepresentative;
            $priorityRecommendations[2]['insight'] = $lowestRepresentative.' has the lowest recorded representative revenue in the selected year.';
            $supportingInsights[0]['text'] = $topProduct.' is the current top product in the sales database.';
            $supportingInsights[1]['text'] = $topRegion.' is the current leading operational region/warehouse.';
        }

        return view('forecasting.recommendations', compact(
            'recommendationKpis',
            'priorityRecommendations',
            'recommendationCategories',
            'recommendationRows',
            'supportingInsights',
            'actionPlan',
        ));
    }

    public function salesAnalysis(Request $request)
    {
        $tab = $request->query('tab', 'product');
        $tab = in_array($tab, ['product', 'region', 'representative'], true) ? $tab : 'product';

        $topProducts = [
            'labels' => ['Product A', 'Product B', 'Product C', 'Product D', 'Product E'],
            'values' => [680000, 520000, 460000, 310000, 240000],
        ];

        $salesByRegion = [
            'labels' => ['NCR', 'CALABARZON', 'Western Visayas', 'Davao', 'Bicol'],
            'values' => [420000, 350000, 310000, 280000, 150000],
        ];

        $salesByRepresentative = [
            'labels' => ['Thervin', 'San Goku', 'Gojo Satoru', 'Elon Musk', 'Peter Parker'],
            'values' => [560000, 430000, 375000, 220000, 185000],
        ];

        return view('forecasting.sales-analysis', [
            'activeTab' => $tab,
            'topProducts' => $topProducts,
            'salesByRegion' => $salesByRegion,
            'salesByRepresentative' => $salesByRepresentative,
        ]);
    }
}
