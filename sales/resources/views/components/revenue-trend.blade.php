@php
    $chartId = $chartId ?? 'revenueTrendChart';
@endphp

<section
    aria-labelledby="revenue-trend-title"
    class="mt-4"
    data-component="revenue-trend"
    data-chart-id="{{ $chartId }}"
    data-chart-initial-data="{!! e(json_encode($initialData ?? null)) !!}"
>

    <div class="max-w-full">
        <div class="flex items-center justify-between mb-3">
            <h3
                id="revenue-trend-title"
                class="text-lg font-bold text-gray-900"
            >
                Revenue Trend (Monthly Revenue)
            </h3>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="w-full">
                <div
                    class="w-full relative"
                    style="height: 420px;"
                >
                    <canvas
                        id="{{ $chartId }}"
                        class="w-full h-full"
                        aria-label="Monthly revenue trend chart"
                        role="img"
                    ></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/revenue-trend.js') }}"></script>

</section>