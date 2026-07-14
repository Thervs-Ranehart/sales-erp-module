@props([
    'initialData' => null,
    'chartId',
    'title',
    'type' => 'bar',
    'description' => null,
])

<article
    class="card border-0 shadow-sm rounded-4 h-100"
    data-component="performance-percentage-chart"
    data-chart-id="{{ $chartId }}"
    data-chart-type="{{ $type }}"
    data-chart-initial-data="{!! e(json_encode($initialData)) !!}"
>
    <div class="card-body p-3 p-md-4">
        <div class="d-flex justify-content-between align-items-start gap-3"><h3 id="{{ $chartId }}-title" class="fs-6 fw-bold mb-1">{{ $title }}</h3><span class="badge rounded-pill text-bg-light border">%</span></div>
        @if ($description)
            <p class="small text-muted mb-3">{{ $description }}</p>
        @endif
        <div class="position-relative w-100 mt-3" style="height: {{ $type === 'line' ? 'clamp(300px, 38vw, 380px)' : '300px' }};">
            <canvas id="{{ $chartId }}" role="img" aria-labelledby="{{ $chartId }}-title"></canvas>
        </div>
    </div>
</article>

@once
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/performance-percentage-chart.js') }}"></script>
@endonce
