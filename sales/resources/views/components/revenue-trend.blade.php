@php
    $chartId = $chartId ?? 'revenueTrendChart';
@endphp

<section
    aria-labelledby="revenue-trend-title"
    class="mt-5"
    data-component="revenue-trend"
    data-chart-id="{{ $chartId }}"
    data-chart-initial-data="{!! e(json_encode($initialData ?? null)) !!}"
>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
                <div><h2 id="revenue-trend-title" class="fs-5 fw-bold mb-1">Monthly Revenue Trend</h2><p class="small text-muted mb-0">Track revenue movement and seasonality throughout the selected year.</p></div>
                <span class="badge rounded-pill text-bg-light border text-success px-3 py-2"><i class="bi bi-arrow-up-right me-1"></i>Year overview</span>
            </div>
                <div class="w-full position-relative" style="height: clamp(320px, 42vw, 440px);">
                    <canvas
                        id="{{ $chartId }}"
                        class="w-full h-full"
                        aria-label="Monthly revenue trend chart"
                        role="img"
                    ></canvas>
                </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/revenue-trend.js') }}"></script>

</section>
