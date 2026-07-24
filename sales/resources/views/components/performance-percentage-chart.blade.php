@props([
    'initialData' => null,
    'chartId',
    'title',
    'type' => 'bar',
    'description' => null,
    'icon' => 'bar-chart-line',
])

<article
    class="{{ $type === 'bar' ? 'performance-chart-card' : 'card border-0 shadow-sm rounded-4' }} h-100"
    data-component="performance-percentage-chart"
    data-chart-id="{{ $chartId }}"
    data-chart-type="{{ $type }}"
    data-chart-initial-data="{!! e(json_encode($initialData)) !!}"
>
    <div class="{{ $type === 'bar' ? 'performance-chart-card__body' : 'card-body p-3 p-md-4' }}">
        @if ($type === 'bar')
            <div class="performance-chart-card__header">
                <span class="performance-chart-card__icon" aria-hidden="true">
                    <i class="bi bi-{{ $icon }}"></i>
                </span>
                <div>
                    <h3 id="{{ $chartId }}-title">{{ $title }}</h3>
                    <p>{{ $description ?? 'Achievement compared with the assigned target.' }}</p>
                </div>
            </div>
            <div class="performance-chart-legend" aria-label="Achievement color ranges">
                <span><i class="is-low"></i>0–30% Low</span>
                <span><i class="is-mid"></i>31–69% Progressing</span>
                <span><i class="is-high"></i>70%+ Strong</span>
            </div>
        @else
            <div class="d-flex justify-content-between align-items-start gap-3"><h3 id="{{ $chartId }}-title" class="fs-6 fw-bold mb-1">{{ $title }}</h3><span class="badge rounded-pill text-bg-light border">%</span></div>
            @if ($description)
                <p class="small text-muted mb-3">{{ $description }}</p>
            @endif
        @endif
        <div class="{{ $type === 'bar' ? 'performance-chart-card__canvas' : 'position-relative w-100 mt-3' }}" style="height: {{ $type === 'line' ? 'clamp(300px, 38vw, 380px)' : '230px' }};">
            <canvas id="{{ $chartId }}" role="img" aria-labelledby="{{ $chartId }}-title"></canvas>
        </div>
    </div>
</article>

@once
    <style>
        .performance-chart-card{position:relative;overflow:hidden;border:1px solid #e2e8f0;border-radius:20px;background:#fff;box-shadow:0 12px 30px rgba(15,23,42,.065);transition:transform .2s ease,box-shadow .2s ease}
        .performance-chart-card::before{content:"";position:absolute;inset:0 0 auto;height:4px;background:linear-gradient(90deg,#128b99,#2dd4bf)}
        .performance-chart-card:hover{transform:translateY(-2px);box-shadow:0 17px 38px rgba(15,23,42,.1)}
        .performance-chart-card__body{padding:22px 22px 20px}
        .performance-chart-card__header{display:flex;align-items:flex-start;gap:12px;min-height:55px}
        .performance-chart-card__icon{flex:0 0 auto;width:40px;height:40px;display:grid;place-items:center;border:1px solid #ccfbf1;border-radius:12px;color:#0f766e;background:#f0fdfa;font-size:17px}
        .performance-chart-card__header h3{margin:1px 0 4px;color:#172033;font-size:15px;font-weight:750}
        .performance-chart-card__header p{margin:0;color:#7b879a;font-size:11px;line-height:1.45}
        .performance-chart-legend{display:flex;flex-wrap:wrap;gap:7px 11px;margin:17px 0 6px;padding:10px 11px;border:1px solid #edf2f7;border-radius:10px;background:#f8fafc}
        .performance-chart-legend span{display:inline-flex;align-items:center;gap:5px;color:#64748b;font-size:9px;font-weight:650;white-space:nowrap}
        .performance-chart-legend i{width:7px;height:7px;border-radius:50%}
        .performance-chart-legend .is-low{background:#ef4444}
        .performance-chart-legend .is-mid{background:#f4b400}
        .performance-chart-legend .is-high{background:#10b981}
        .performance-chart-card__canvas{position:relative;width:100%;margin-top:8px}
        @media(max-width:575px){.performance-chart-card__body{padding:20px 17px 16px}}
    </style>
@endonce

@once
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/performance-percentage-chart.js') }}"></script>
@endonce
