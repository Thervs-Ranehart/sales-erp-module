@props([
    'initialData' => null,
    'chartId' => 'targetVsActualChart',
])

<section
    aria-labelledby="target-vs-actual-title"
    class="mt-4"
    data-component="target-vs-actual-chart"
    data-chart-id="{{ $chartId }}"
    data-chart-initial-data="{!! e(json_encode($initialData)) !!}"
>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4"><div><h2 id="target-vs-actual-title" class="fs-5 fw-bold mb-1">Target vs. Actual Revenue</h2><p class="small text-muted mb-0">Compare planned revenue with completed monthly sales.</p></div><span class="badge rounded-pill text-bg-light border px-3 py-2">Monthly comparison</span></div>
                <div class="w-full position-relative" style="height: clamp(320px, 42vw, 440px);">
                    <canvas
                        id="{{ $chartId }}"
                        class="w-full h-full"
                        aria-label="Target vs. Actual revenue chart"
                        role="img"
                    ></canvas>
                </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    {{-- Target vs. Actual Chart --}}
    <script src="{{ asset('js/target-vs-actual-chart.js') }}"></script>

</section>
