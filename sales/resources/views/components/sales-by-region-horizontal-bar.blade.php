@php
    $chartId = $chartId ?? 'salesByRegionHorizontalBarChart';
@endphp

<section
    aria-labelledby="sales-by-region-horizontal-bar-title"
    class="mt-4"
    data-component="sales-by-region-horizontal-bar"
    data-chart-id="{{ $chartId }}"
    data-chart-initial-data="{!! e(json_encode($initialData ?? null)) !!}"
>

    <div class="max-w-full">
        <div class="flex items-center justify-between mb-3">
            <h3 id="sales-by-region-horizontal-bar-title" class="text-lg font-bold text-gray-900">
                Sales by Region
            </h3>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="w-full">
                <div class="w-full relative" aria-hidden="true">
                    <canvas
                        id="{{ $chartId }}"
                        class="w-full"
                        aria-label="Sales by Region horizontal bar chart"
                        role="img"
                    ></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/sales-by-region-horizontal-bar.js') }}"></script>

</section>