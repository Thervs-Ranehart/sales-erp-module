@props(['initialData' => null, 'chartId', 'kind', 'title', 'description' => null, 'periodLabel' => '6 months'])
<article class="card border-0 shadow-sm rounded-4 h-100" data-component="dashboard-overview-chart" data-chart-id="{{ $chartId }}" data-chart-kind="{{ $kind }}" data-chart-initial-data="{!! e(json_encode($initialData)) !!}" aria-labelledby="{{ $chartId }}-title"><div class="card-body p-3 p-md-4"><div class="d-flex justify-content-between align-items-start gap-3 mb-3"><div><h2 id="{{ $chartId }}-title" class="fs-5 fw-bold mb-1">{{ $title }}</h2>@if($description)<p class="small text-muted mb-0">{{ $description }}</p>@endif</div><span class="badge rounded-pill text-bg-light border">{{ $periodLabel }}</span></div><div class="position-relative" style="height:280px"><canvas id="{{ $chartId }}" role="img" aria-label="{{ $title }} chart"></canvas></div></div></article>
@once
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/dashboard-overview-chart.js') }}"></script>
@endonce
