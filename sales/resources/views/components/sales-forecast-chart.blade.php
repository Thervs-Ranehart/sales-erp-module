@props(['initialData' => null, 'chartId' => 'salesForecastChart'])

<section class="mt-5" data-component="sales-forecast-chart" data-chart-id="{{ $chartId }}" data-chart-initial-data="{!! e(json_encode($initialData)) !!}" aria-labelledby="{{ $chartId }}-title">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden"><div class="card-body p-3 p-md-4">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4"><div><h2 id="{{ $chartId }}-title" class="fs-5 fw-bold mb-1">Historical and Forecast Revenue</h2><p class="small text-muted mb-0">The dashed line and shaded range represent forecast periods and confidence limits.</p></div><span class="badge rounded-pill text-bg-light border text-primary px-3 py-2"><i class="bi bi-stars me-1"></i>Expected scenario</span></div>
        <div class="position-relative w-100" style="height: clamp(320px, 45vw, 460px);"><canvas id="{{ $chartId }}" role="img" aria-label="Historical actual and forecast revenue line chart"></canvas></div>
    </div></div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/sales-forecast-chart.js') }}"></script>
</section>
