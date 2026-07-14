@php
    $chartId = $chartId ?? 'salesByRegionHorizontalBarChart';
@endphp

<section
    aria-labelledby="sales-by-region-horizontal-bar-title"
    class="w-100"
    data-component="sales-by-region-horizontal-bar"
    data-chart-id="{{ $chartId }}"
    data-chart-initial-data="{!! e(json_encode($initialData ?? null)) !!}"
>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex justify-content-between align-items-start gap-3 mb-3"><div><h3 id="sales-by-region-horizontal-bar-title" class="fs-6 fw-bold mb-1">Sales by Region</h3><p class="small text-muted mb-0">Revenue concentration across operating regions.</p></div><i class="bi bi-geo-alt fs-4 text-info" aria-hidden="true"></i></div>
                <div class="w-full position-relative" style="height: 300px;">
                    <canvas
                        id="{{ $chartId }}"
                        class="w-full"
                        aria-label="Sales by Region horizontal bar chart"
                        role="img"
                    ></canvas>
                </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/sales-by-region-horizontal-bar.js') }}"></script>

</section>
