@props(['recommendation'])
<article class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
    <div class="card-body p-4 border-start border-4 {{ $recommendation['priority'] === 'High' ? 'border-danger' : ($recommendation['priority'] === 'Medium' ? 'border-warning' : 'border-info') }}">
        <div class="d-flex flex-wrap justify-content-between gap-2 mb-3"><span class="badge text-bg-light border text-dark">{{ $recommendation['category'] }}</span><div class="d-flex gap-2"><x-recommendation-priority-badge :priority="$recommendation['priority']" /><x-recommendation-status-badge :status="$recommendation['status']" /></div></div>
        <h3 class="fs-5 fw-bold">{{ $recommendation['title'] }}</h3>
        <div class="row g-3 mt-1"><div class="col-12 col-md-6"><div class="small text-muted mb-1">Detected insight</div><p class="mb-0">{{ $recommendation['insight'] }}</p></div><div class="col-12 col-md-6"><div class="small text-muted mb-1">Recommended action</div><p class="mb-0">{{ $recommendation['action'] }}</p></div></div>
        <div class="bg-light rounded-3 p-3 mt-3 d-flex flex-wrap justify-content-between gap-3"><div><div class="small text-muted">Expected impact</div><span class="fw-semibold">{{ $recommendation['impact'] }}</span></div><div class="text-md-end"><div class="small text-muted">Supporting metric</div><span class="fw-bold text-primary">{{ $recommendation['metric'] }}</span></div></div>
    </div>
</article>
