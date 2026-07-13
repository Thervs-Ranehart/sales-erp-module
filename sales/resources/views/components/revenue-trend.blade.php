@php
    $chartId = $chartId ?? 'revenueTrendChart';
@endphp

<section aria-labelledby="revenue-trend-title" class="mt-4">
    <div class="max-w-full">
        <div class="flex items-center justify-between mb-3">
            <h3 id="revenue-trend-title" class="text-lg font-bold text-gray-900">Revenue Trend (Monthly Revenue)</h3>
            <!-- future: year selector / export buttons go here -->
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="w-full">
                <canvas id="{{ $chartId }}" aria-label="Monthly revenue trend chart" role="img"></canvas>
            </div>
        </div>
    </div>

    {{--
        JavaScript data and initialization.

        Architecture notes:
          - Do NOT hardcode business logic here.
          - The chart JS consumes `window.revenueTrendData`.
          - Future enhancements (year selector, date range, etc.) can update the window data
            and re-initialize or update the Chart.js instance.

        Livewire compatibility idea:
          - On filter change, emit updated payload and replace window.revenueTrendData.

        AJAX idea:
          - fetch('/.../revenue-trend?year=YYYY').then(r => r.json()).then(d => window.revenueTrendData = d)
          - then re-render.
    --}}

    <script>
        window.revenueTrendData = @json($initialData ?? null);
        window.revenueTrendChartId = @json($chartId);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/revenue-trend.js') }}"></script>
</section>

