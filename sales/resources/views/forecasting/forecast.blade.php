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
            <div><a href="{{ route('forecasting.export', array_merge(['type'=>'forecast','format'=>'csv'], request()->query())) }}" class="hero-action-btn" style="padding: 0.75rem 1.6rem;"><i class="bi bi-download me-2"></i>Export Forecast</a><a href="{{ route('forecasting.export', array_merge(['type'=>'forecast','format'=>'print'], request()->query())) }}" target="_blank" class="hero-secondary-btn ms-2"><i class="bi bi-printer me-2"></i>Print / PDF</a></div>
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
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-2 mb-3">
            <div>
                <h2 id="forecast-breakdown-heading" class="fs-5 fw-bold text-gray-900 mb-1">Forecast Breakdown</h2>
                <p class="small text-muted mb-0">Compare projected revenue and expected movement across key sales dimensions.</p>
            </div>
            <span class="badge rounded-pill text-bg-light border px-3 py-2">
                <i class="bi bi-stars me-1" aria-hidden="true"></i>
                Forecast overview
            </span>
        </div>
        <div class="row g-4">
            @foreach ([
                ['Forecast by Product', $forecastByProduct, 'Expected Growth', 'box-seam', 'Projected performance across product groups.'],
                ['Forecast by Region', $forecastByRegion, 'Expected Growth', 'geo-alt', 'Projected performance across sales territories.'],
                ['Forecast by Sales Representative', $forecastByRepresentative, 'Expected Achievement', 'people', 'Projected performance for individual team members.'],
            ] as [$title, $items, $metricLabel, $icon, $description])
                <div class="col-12 col-xl-4">
                    <article class="forecast-breakdown-card h-100">
                        <div class="forecast-breakdown-card__body">
                            <div class="forecast-breakdown-card__header">
                                <span class="forecast-breakdown-card__icon" aria-hidden="true">
                                    <i class="bi bi-{{ $icon }}"></i>
                                </span>
                                <div>
                                    <h3>{{ $title }}</h3>
                                    <p>{{ $description }}</p>
                                </div>
                            </div>

                            <div class="forecast-breakdown-legend" aria-label="Forecast status colors">
                                <span><i class="is-positive"></i>Positive outlook</span>
                                <span><i class="is-attention"></i>Needs attention</span>
                            </div>

                            <div class="forecast-breakdown-list {{ $title !== 'Forecast by Sales Representative' ? 'forecast-breakdown-list--fit' : '' }}">
                                @forelse ($items as $item)
                                    @php($positive = $item['growth'] >= ($metricLabel === 'Expected Achievement' ? 100 : 0))
                                    <div class="forecast-breakdown-item">
                                        <div class="forecast-breakdown-item__top">
                                            <span title="{{ $item['name'] }}">{{ $item['name'] }}</span>
                                            <strong>₱{{ number_format($item['revenue']) }}</strong>
                                        </div>
                                        <div class="forecast-breakdown-track" aria-hidden="true">
                                            <span
                                                class="{{ $positive ? 'is-positive' : 'is-attention' }}"
                                                style="width:{{ min(100, max(8, $item['revenue'] / 6000)) }}%"
                                            ></span>
                                        </div>
                                        <div class="forecast-breakdown-item__meta">
                                            <span class="{{ $positive ? 'is-positive' : 'is-attention' }}">
                                                {{ $metricLabel }}:
                                                {{ $item['growth'] > 0 && $metricLabel !== 'Expected Achievement' ? '+' : '' }}{{ $item['growth'] }}%
                                            </span>
                                            <span>{{ $item['status'] }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="forecast-breakdown-empty">
                                        <i class="bi bi-bar-chart" aria-hidden="true"></i>
                                        <span>No forecast data available.</span>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </article>
                </div>
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

    <section class="mt-5" aria-labelledby="accuracy-heading"><div class="card border-0 shadow-sm rounded-4"><div class="card-body p-4"><h2 id="accuracy-heading" class="fs-5 fw-bold mb-1">Forecast Method and Accuracy</h2><p class="small text-muted">Ensemble forecast combining recent growth, linear trend, and available seasonal history. The range uses historical residual error.</p><div class="row g-3 mt-1">@foreach([['Method',str_replace('-',' ',ucfirst($forecastAccuracy['method']))],['History Points',$forecastAccuracy['sampleSize']],['95% Prediction Range','₱'.number_format($forecastAccuracy['lower']).' – ₱'.number_format($forecastAccuracy['upper'])],['MAE','₱'.number_format($forecastAccuracy['mae'])],['MAPE',number_format($forecastAccuracy['mape'],2).'%'],['RMSE','₱'.number_format($forecastAccuracy['rmse'])]] as [$label,$value])<div class="col-md-4"><div class="border rounded-3 p-3 h-100"><div class="small text-muted">{{ $label }}</div><div class="fw-bold">{{ $value }}</div></div></div>@endforeach</div></div></div></section>

    <section class="mt-5" aria-labelledby="forecast-runs-heading"><div class="card border-0 shadow-sm rounded-4 overflow-hidden"><div class="card-header bg-white p-4"><h2 id="forecast-runs-heading" class="fs-5 fw-bold mb-1">Saved Forecast Runs</h2><p class="small text-muted mb-0">Versioned assumptions, predictions, actual outcomes, and accuracy evaluation.</p></div><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Version</th><th>Period</th><th>Method</th><th>Prediction</th><th>Actual</th><th>Accuracy</th><th>Action</th></tr></thead><tbody>@forelse($forecastRuns as $run)<tr><td>v{{ $run->version }}</td><td>{{ $run->forecast_period_start?->format('M Y') }} – {{ $run->forecast_period_end?->format('M Y') }}</td><td>{{ str_replace('-', ' ', $run->forecast_method) }}</td><td>₱{{ number_format((float)$run->predicted_revenue) }}</td><td>{{ $run->actual_revenue !== null ? '₱'.number_format((float)$run->actual_revenue) : 'Pending' }}</td><td>{{ $run->mape !== null ? number_format((float)$run->mape,2).'% MAPE' : 'Pending actuals' }}</td><td><button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#evaluateForecast{{ $run->forecast_id }}">Record Actual</button></td></tr>@empty<tr><td colspan="7" class="text-center text-muted py-4">No forecast runs saved yet.</td></tr>@endforelse</tbody></table></div></div></section>
    @foreach($forecastRuns as $run)<div class="modal fade" id="evaluateForecast{{ $run->forecast_id }}" tabindex="-1"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('forecasting.forecasts.evaluate',$run) }}">@csrf @method('PATCH')<div class="modal-header"><h5 class="modal-title">Evaluate Forecast v{{ $run->version }}</h5><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><label class="form-label">Actual revenue for forecast period</label><input class="form-control" type="number" step=".01" min="0" name="actual_revenue" value="{{ $run->actual_revenue }}" required></div><div class="modal-footer"><button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary">Calculate Accuracy</button></div></form></div></div>@endforeach

    <section class="mt-5" aria-labelledby="planning-heading"><div class="card border-0 shadow-sm rounded-4"><div class="card-body p-4"><h2 id="planning-heading" class="fs-5 fw-bold mb-3">Planning Recommendations</h2><div class="row g-3">@foreach ($planningRecommendations as $recommendation)<div class="col-12 col-lg-6"><div class="d-flex gap-3"><i class="bi bi-arrow-right-circle-fill text-primary"></i><span class="text-muted">{{ $recommendation }}</span></div></div>@endforeach</div></div></div></section>

    <aside class="alert alert-info mt-4 mb-4" aria-labelledby="forecast-method-heading"><h2 id="forecast-method-heading" class="fs-6 fw-bold"><i class="bi bi-info-circle me-2"></i>How this forecast is produced</h2><p class="mb-0">The current forecast is generated from historical monthly revenue patterns and recent growth trends. It is intended to support planning and may change as new sales data becomes available.</p></aside>

    @include('components.forecast-filter')

    <style>
        .forecast-breakdown-card{position:relative;overflow:hidden;border:1px solid #e2e8f0;border-radius:20px;background:#fff;box-shadow:0 12px 30px rgba(15,23,42,.065);transition:transform .2s ease,box-shadow .2s ease}
        .forecast-breakdown-card::before{content:"";position:absolute;inset:0 0 auto;height:4px;background:linear-gradient(90deg,#128b99,#2dd4bf)}
        .forecast-breakdown-card:hover{transform:translateY(-2px);box-shadow:0 17px 38px rgba(15,23,42,.1)}
        .forecast-breakdown-card__body{padding:22px}
        .forecast-breakdown-card__header{display:flex;align-items:flex-start;gap:12px;min-height:55px}
        .forecast-breakdown-card__icon{flex:0 0 auto;width:40px;height:40px;display:grid;place-items:center;border:1px solid #ccfbf1;border-radius:12px;color:#0f766e;background:#f0fdfa;font-size:17px}
        .forecast-breakdown-card__header h3{margin:1px 0 4px;color:#172033;font-size:15px;font-weight:750}
        .forecast-breakdown-card__header p{margin:0;color:#7b879a;font-size:11px;line-height:1.45}
        .forecast-breakdown-legend{display:flex;flex-wrap:wrap;gap:7px 13px;margin:17px 0 5px;padding:10px 11px;border:1px solid #edf2f7;border-radius:10px;background:#f8fafc}
        .forecast-breakdown-legend span{display:inline-flex;align-items:center;gap:5px;color:#64748b;font-size:9px;font-weight:650;white-space:nowrap}
        .forecast-breakdown-legend i{width:7px;height:7px;border-radius:50%}
        .forecast-breakdown-legend .is-positive{background:#10b981}
        .forecast-breakdown-legend .is-attention{background:#ef4444}
        .forecast-breakdown-list{height:230px;overflow-y:auto;margin-top:5px;padding:3px 3px 0 0;scrollbar-width:thin;scrollbar-color:#cbd5e1 transparent}
        .forecast-breakdown-list--fit{height:auto;min-height:230px;overflow-y:visible;padding-right:0}
        .forecast-breakdown-item{padding:13px 0;border-bottom:1px solid #edf2f7}
        .forecast-breakdown-item:last-child{border-bottom:0}
        .forecast-breakdown-item__top,.forecast-breakdown-item__meta{display:flex;align-items:center;justify-content:space-between;gap:12px}
        .forecast-breakdown-item__top>span{overflow:hidden;color:#334155;font-size:12px;font-weight:700;text-overflow:ellipsis;white-space:nowrap}
        .forecast-breakdown-item__top strong{flex:0 0 auto;color:#172033;font-size:11px;font-variant-numeric:tabular-nums}
        .forecast-breakdown-track{height:7px;overflow:hidden;margin:8px 0;border-radius:999px;background:#eef2f7}
        .forecast-breakdown-track span{display:block;height:100%;border-radius:inherit;box-shadow:0 2px 5px rgba(15,23,42,.1)}
        .forecast-breakdown-track .is-positive{background:linear-gradient(90deg,#10b981,#34d399)}
        .forecast-breakdown-track .is-attention{background:linear-gradient(90deg,#ef4444,#fb7185)}
        .forecast-breakdown-item__meta span{font-size:9px;font-weight:650}
        .forecast-breakdown-item__meta .is-positive{color:#059669}
        .forecast-breakdown-item__meta .is-attention{color:#dc2626}
        .forecast-breakdown-item__meta span:last-child{overflow:hidden;color:#94a3b8;font-weight:500;text-align:right;text-overflow:ellipsis;white-space:nowrap}
        .forecast-breakdown-empty{height:100%;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;color:#94a3b8;font-size:11px}
        .forecast-breakdown-empty i{font-size:24px}
        @media(max-width:575px){.forecast-breakdown-card__body{padding:20px 17px 16px}}
    </style>
@endsection
