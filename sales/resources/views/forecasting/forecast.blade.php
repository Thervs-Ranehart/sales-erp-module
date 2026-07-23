@extends('layouts.app')

@section('content')
    @php
        $tabs = [
            ['label' => 'Sales Reports', 'route' => 'forecasting.reports'],
            ['label' => 'Target vs. Actual', 'route' => 'forecasting.performance'],
            ['label' => 'Forecasting', 'route' => 'forecasting.forecast'],
            ['label' => 'Recommendations', 'route' => 'forecasting.recommendations'],
        ];
        $kpiCards = [
            ['label' => 'Latest Actual Revenue', 'value' => '₱'.number_format($forecastKpis['latestActualRevenue']), 'icon' => 'cash-stack', 'tone' => 'text-primary'],
            ['label' => 'Next-Month Forecast', 'value' => '₱'.number_format($forecastKpis['nextMonthForecast']), 'icon' => 'graph-up-arrow', 'tone' => 'text-success'],
            ['label' => 'Forecast Growth Rate', 'value' => ($forecastKpis['growthRate'] >= 0 ? '+' : '').number_format($forecastKpis['growthRate'], 1).'%', 'icon' => 'percent', 'tone' => $forecastKpis['growthRate'] >= 0 ? 'text-success' : 'text-danger'],
            ['label' => 'Quarter Forecast', 'value' => '₱'.number_format($forecastKpis['quarterForecast']), 'icon' => 'calendar3', 'tone' => 'text-info'],
            ['label' => 'Forecast Confidence', 'value' => number_format($forecastKpis['confidence']).'%', 'icon' => 'shield-check', 'tone' => 'text-primary'],
            ['label' => 'Forecast Status', 'value' => $forecastKpis['status'], 'icon' => 'activity', 'tone' => $forecastKpis['status'] === 'Growth Expected' ? 'text-success' : 'text-warning'],
        ];
    @endphp

    <main class="w-full flex items-center" style="margin: -1rem -1rem 0; width: calc(100% + 2rem); padding: 32px 48px; height: 258px; max-height: 258px; background: linear-gradient(90deg, #128B99 0%, #1CE5BD 100%); border-radius: 0;">
        <div class="d-flex flex-column h-100 w-100">
            <h1 class="text-white font-semibold text-[45px] leading-tight">Sales Forecasting</h1>
            <div class="flex-grow-1 d-flex align-items-center">
                <p class="text-white" style="margin: 0; font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 600; line-height: 1.6; opacity: 0.95;">Analyze historical sales trends and estimate future revenue to support sales, marketing, and inventory planning.</p>
            </div>
            <div><button type="button" class="hero-action-btn" style="padding: 0.75rem 1.6rem;"><i class="bi bi-download me-2"></i>Export Forecast</button></div>
        </div>
    </main>

    <section class="mt-4" aria-label="Forecast KPI summary">
        <div class="row g-4">
            @foreach ($kpiCards as $kpi)
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="card px-3 py-2 h-100 border-0 shadow-sm" style="background: linear-gradient(145deg, #fff 0%, #f8fafc 100%); min-height: 92px;">
                        <div class="d-flex justify-content-between align-items-center mb-1"><span class="fw-semibold small text-muted">{{ $kpi['label'] }}</span><span class="rounded-circle d-flex align-items-center justify-content-center bg-light" style="width:32px;height:32px"><i class="bi bi-{{ $kpi['icon'] }} {{ $kpi['tone'] }}"></i></span></div>
                        <div class="fw-bold fs-4 {{ $kpi['tone'] }}">{{ $kpi['value'] }}</div>
                        <p class="text-muted small mb-0 mt-1">Forecasting snapshot</p>
                    </article>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mt-4"><div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <nav class="d-flex gap-2 flex-wrap" aria-label="Forecasting navigation">
            @foreach ($tabs as $tab)
                @php($active = Route::currentRouteName() === $tab['route'])
                <a href="{{ route($tab['route']) }}" class="btn btn-sm {{ $active ? 'btn-primary' : 'btn-outline-secondary' }}" @if($active) aria-current="page" @endif>{{ $tab['label'] }}</a>
            @endforeach
        </nav>
        <button id="ff-open-filter" type="button" class="btn btn-outline-secondary btn-sm" aria-controls="ff-filter-drawer" aria-expanded="false"><i class="bi bi-funnel me-2"></i>Filter</button>
    </div></section>

    <x-sales-forecast-chart :initial-data="$historicalAndForecast" chart-id="salesForecastChart" />

    <section class="mt-5" aria-labelledby="forecast-summary-heading"><div class="card border-0 shadow-sm rounded-4"><div class="card-body p-4">
        <h2 id="forecast-summary-heading" class="fs-5 fw-bold mb-3">Forecast Summary</h2>
        <div class="row g-3">
            @foreach ([['Forecast Period', $forecastSummary['period']], ['Expected Revenue', '₱'.number_format($forecastSummary['expectedRevenue'])], ['Expected Growth', '+'.number_format($forecastSummary['expectedGrowth'], 1).'%'], ['Projected Sales Gap', '+₱'.number_format($forecastSummary['projectedSalesGap'])], ['Forecast Direction', $forecastSummary['direction']], ['Confidence Level', $forecastSummary['confidence'].'%']] as [$label, $value])
                <div class="col-6 col-lg-2"><div class="small text-muted">{{ $label }}</div><div class="fw-bold mt-1">{{ $value }}</div></div>
            @endforeach
        </div>
        <p class="text-muted mb-0 mt-4">Revenue is expected to grow during the next quarter based on recent monthly sales performance.</p>
    </div></div></section>

    <section class="mt-5" aria-labelledby="forecast-breakdown-heading">
        <h2 id="forecast-breakdown-heading" class="text-lg font-bold text-gray-900 mb-3">Forecast Breakdown</h2>
        <div class="row g-4">
            @foreach ([['Forecast by Product', $forecastByProduct, 'Expected Growth'], ['Forecast by Region', $forecastByRegion, 'Expected Growth'], ['Forecast by Sales Representative', $forecastByRepresentative, 'Expected Achievement']] as [$title, $items, $metricLabel])
                <div class="col-12 col-xl-4"><article class="card border-0 shadow-sm rounded-4 h-100"><div class="card-body p-4">
                    <h3 class="fs-6 fw-bold mb-4">{{ $title }}</h3>
                    @foreach ($items as $item)
                        @php($positive = $item['growth'] >= ($metricLabel === 'Expected Achievement' ? 100 : 0))
                        <div class="mb-4"><div class="d-flex justify-content-between gap-3"><span class="fw-semibold text-truncate">{{ $item['name'] }}</span><span class="text-nowrap">₱{{ number_format($item['revenue']) }}</span></div>
                            <div class="progress my-2" style="height:6px"><div class="progress-bar {{ $positive ? 'bg-success' : 'bg-danger' }}" style="width:{{ min(100, max(8, $item['revenue'] / 6000)) }}%"></div></div>
                            <div class="d-flex justify-content-between small"><span class="{{ $positive ? 'text-success' : 'text-danger' }}">{{ $metricLabel }}: {{ $item['growth'] > 0 && $metricLabel !== 'Expected Achievement' ? '+' : '' }}{{ $item['growth'] }}%</span><span class="text-muted">{{ $item['status'] }}</span></div>
                        </div>
                    @endforeach
                </div></article></div>
            @endforeach
        </div>
    </section>

    <section class="mt-5" aria-labelledby="historical-data-heading"><div class="card border-0 shadow-sm rounded-4"><div class="card-header bg-white border-0 px-4 pt-4"><h2 id="historical-data-heading" class="fs-5 fw-bold">Historical Sales Data</h2></div><div class="card-body px-0"><div class="table-responsive"><table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th class="ps-4">Period</th><th>Actual Revenue</th><th>Previous-Period Revenue</th><th>Growth Rate</th><th>Sales Target</th><th class="pe-4">Target Achievement</th></tr></thead>
        <tbody>@foreach ($historicalRows as $row)<tr><th class="ps-4">{{ $row['period'] }}</th><td>₱{{ number_format($row['actual']) }}</td><td>₱{{ number_format($row['previous']) }}</td><td class="{{ $row['growth'] >= 0 ? 'text-success' : 'text-danger' }}">{{ $row['growth'] >= 0 ? '+' : '' }}{{ number_format($row['growth'], 1) }}%</td><td>₱{{ number_format($row['target']) }}</td><td class="pe-4"><span class="badge rounded-pill {{ $row['achievement'] >= 100 ? 'text-bg-success' : 'text-bg-warning' }}">{{ number_format($row['achievement'], 1) }}%</span></td></tr>@endforeach</tbody>
    </table></div></div></div></section>

    <section class="mt-5" aria-labelledby="forecast-insights-heading"><h2 id="forecast-insights-heading" class="text-lg font-bold text-gray-900 mb-3">Forecast Insights</h2><div class="row g-3">
        @foreach ($forecastInsights as $insight)
            @php($alertClass = match($insight['type']) {'success' => 'alert-success', 'info' => 'alert-info', 'warning' => 'alert-warning', default => 'alert-danger'})
            <div class="col-12 col-md-6"><div class="alert {{ $alertClass }} h-100 mb-0 d-flex gap-3"><i class="bi bi-lightbulb-fill"></i><span>{{ $insight['text'] }}</span></div></div>
        @endforeach
    </div></section>

    <section class="mt-5" aria-labelledby="planning-heading"><div class="card border-0 shadow-sm rounded-4"><div class="card-body p-4"><h2 id="planning-heading" class="fs-5 fw-bold mb-3">Planning Recommendations</h2><div class="row g-3">@foreach ($planningRecommendations as $recommendation)<div class="col-12 col-lg-6"><div class="d-flex gap-3"><i class="bi bi-arrow-right-circle-fill text-primary"></i><span class="text-muted">{{ $recommendation }}</span></div></div>@endforeach</div></div></div></section>

    <aside class="alert alert-info mt-4 mb-4" aria-labelledby="forecast-method-heading"><h2 id="forecast-method-heading" class="fs-6 fw-bold"><i class="bi bi-info-circle me-2"></i>How this forecast is produced</h2><p class="mb-0">The current forecast is generated from historical monthly revenue patterns and recent growth trends. It is intended to support planning and may change as new sales data becomes available.</p></aside>

    @include('components.forecast-filter')
@endsection
