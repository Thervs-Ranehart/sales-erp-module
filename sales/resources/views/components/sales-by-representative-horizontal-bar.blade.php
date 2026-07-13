@php
    $chartId = $chartId ?? 'salesByRepHorizontalBarChart';
@endphp

<section aria-labelledby="sales-by-rep-horizontal-bar-title" class="mt-4">
    <div class="max-w-full">
        <div class="flex items-center justify-between mb-3">
            <h3 id="sales-by-rep-horizontal-bar-title" class="text-lg font-bold text-gray-900">Sales by Sales Representative</h3>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="w-full">
                <canvas id="{{ $chartId }}" aria-label="Sales by sales representative horizontal bar chart" role="img"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Expected: { labels: [...], values: [...] }
        window.salesByRepHorizontalBarData = @json($initialData ?? null);
        window.salesByRepHorizontalBarChartId = @json($chartId);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/sales-by-representative-horizontal-bar.js') }}"></script>
</section>

