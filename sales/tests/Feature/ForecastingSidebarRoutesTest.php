<?php

it('defines the forecasting submenu routes', function () {
    $this->assertStringEndsWith('/forecasting/reports', route('forecasting.reports'));
    $this->assertStringEndsWith('/forecasting/sales-analysis', route('forecasting.sales-analysis'));
    $this->assertStringEndsWith('/forecasting/performance', route('forecasting.performance'));
    $this->assertStringEndsWith('/forecasting/forecast', route('forecasting.forecast'));
    $this->assertStringEndsWith('/forecasting/recommendations', route('forecasting.recommendations'));
});

it('provides the forecasting dashboard summary data', function () {
    $this->get(route('forecasting.index'))
        ->assertOk()
        ->assertViewHasAll([
            'dashboardKpis',
            'revenueTrend',
            'targetVsActualSummary',
            'forecastSummary',
            'topProducts',
            'topRegions',
            'topRepresentatives',
            'priorityRecommendations',
            'recentInsights',
        ]);
});

it('provides the detailed sales report data', function () {
    $this->get(route('forecasting.reports'))
        ->assertOk()
        ->assertViewHasAll([
            'monthlyRevenue',
            'topProducts',
            'salesByRegion',
            'salesByRepresentative',
            'reportKpis',
            'monthlyReportRows',
            'reportInsights',
        ]);
});

it('opens each sales analysis tab with chart data', function (string $tab, string $dataKey) {
    $this->get(route('forecasting.sales-analysis', ['tab' => $tab]))
        ->assertOk()
        ->assertViewHas('activeTab', $tab)
        ->assertViewHas($dataKey);
})->with([
    'product' => ['product', 'topProducts'],
    'region' => ['region', 'salesByRegion'],
    'representative' => ['representative', 'salesByRepresentative'],
]);

it('provides the sales forecasting page data', function () {
    $this->get(route('forecasting.forecast'))
        ->assertOk()
        ->assertViewHasAll([
            'forecastKpis',
            'historicalAndForecast',
            'forecastSummary',
            'forecastByProduct',
            'forecastByRegion',
            'forecastByRepresentative',
            'historicalRows',
            'forecastInsights',
            'planningRecommendations',
        ]);
});

it('provides the recommendations page data', function () {
    $this->get(route('forecasting.recommendations'))
        ->assertOk()
        ->assertViewHasAll([
            'recommendationKpis',
            'priorityRecommendations',
            'recommendationCategories',
            'recommendationRows',
            'supportingInsights',
            'actionPlan',
        ]);
});

it('applies submenu-specific forecasting filters', function () {
    $this->get(route('forecasting.reports', ['period' => 'quarter']))
        ->assertViewHas('monthlyReportRows', fn (array $rows): bool => count($rows) <= 3);

    $this->get(route('forecasting.performance', ['status' => 'below-target']))
        ->assertViewHas('targetVsActualRows', fn (array $rows): bool => collect($rows)->every(fn (array $row): bool => $row['status'] === 'Below'));

    $this->get(route('forecasting.forecast', ['scenario' => 'optimistic']))
        ->assertViewHas('forecastKpis', fn (array $kpis): bool => $kpis['nextMonthForecast'] >= 0);

    $this->get(route('forecasting.recommendations', ['priority' => 'high']))
        ->assertViewHas('recommendationRows', fn (array $rows): bool => collect($rows)->every(fn (array $row): bool => $row['priority'] === 'High'));
});
