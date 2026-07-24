@php
    $chartId = $chartId ?? 'topProductsHorizontalBarChart';
@endphp

<section
    aria-labelledby="top-products-horizontal-bar-title"
    class="w-100 h-100 sales-breakdown-chart"
    data-component="top-products-horizontal-bar"
    data-chart-id="{{ $chartId }}"
    data-chart-initial-data="{!! e(json_encode($initialData ?? null)) !!}"
>

    <div class="sales-breakdown-chart__card h-100">
        <div class="sales-breakdown-chart__body">
            <div class="sales-breakdown-chart__header">
                <span class="sales-breakdown-chart__icon" aria-hidden="true"><i class="bi bi-box-seam"></i></span>
                <div><h3 id="top-products-horizontal-bar-title">Sales by Product</h3><p>Revenue contribution of the five leading products.</p></div>
            </div>
            <div class="sales-breakdown-chart__legend" aria-label="Revenue ranking colors">
                <span><i class="is-low"></i>Lower</span><span><i class="is-mid"></i>Mid-range</span><span><i class="is-high"></i>Top</span>
            </div>
                <div class="sales-breakdown-chart__canvas">
                    <canvas
                        id="{{ $chartId }}"
                        class="w-full"
                        aria-label="Top products horizontal bar chart"
                        role="img"
                    ></canvas>
                </div>
        </div>
    </div>

    @include('components.horizontal-bar-chart-styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/top-products-horizontal-bar.js') }}"></script>
</section>



