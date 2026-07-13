@php
    $chartId = $chartId ?? 'salesByRegionHorizontalBarChart';
@endphp

<section aria-labelledby="sales-by-region-horizontal-bar-title" class="mt-4">
    <div class="max-w-full">
        <div class="flex items-center justify-between mb-3">
            <h3 id="sales-by-region-horizontal-bar-title" class="text-lg font-bold text-gray-900">Sales by Region</h3>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="w-full">
                <canvas id="{{ $chartId }}" aria-label="Sales by region horizontal bar chart" role="img"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Expected: { labels: [...], values: [...] }
        window.salesByRegionHorizontalBarData = @json($initialData ?? null);
        window.salesByRegionHorizontalBarChartId = @json($chartId);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/sales-by-region-horizontal-bar.js') }}"></script>
</section>

