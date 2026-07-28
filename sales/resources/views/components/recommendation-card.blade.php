@props(['recommendation'])
<article class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden relative position-relative">
    <div class="card-body p-4 border-start border-4 {{ $recommendation['priority'] === 'High' ? 'border-danger' : ($recommendation['priority'] === 'Medium' ? 'border-warning' : 'border-info') }}">
        <span class="badge rounded-pill absolute top-6 right-6 inline-flex h-6 min-w-20 items-center justify-center px-3 position-absolute d-inline-flex align-items-center justify-content-center text-nowrap {{ $recommendation['priority'] === 'High' ? 'text-bg-danger' : ($recommendation['priority'] === 'Medium' ? 'text-bg-warning' : 'text-bg-info') }}" style="top:1.5rem;right:1.5rem;height:1.5rem;min-width:5rem;">{{ $recommendation['priority'] }}</span>
        <div class="d-flex flex-wrap justify-content-between gap-2 mb-3 pr-[6.5rem]" style="padding-right:6.5rem;"><span class="badge text-bg-light border text-dark">{{ $recommendation['category'] }}</span><div class="d-flex gap-2"><x-recommendation-status-badge :status="$recommendation['status']" /></div></div>
        <h3 class="fs-5 fw-bold">{{ $recommendation['title'] }}</h3>
        <div class="row g-3 mt-1"><div class="col-12 col-md-6"><div class="small text-muted mb-1">Detected insight</div><p class="mb-0">{{ $recommendation['insight'] }}</p></div><div class="col-12 col-md-6"><div class="small text-muted mb-1">Recommended action</div><p class="mb-0">{{ $recommendation['action'] }}</p></div></div>
        <div class="bg-light rounded-3 p-3 mt-3 d-flex flex-wrap justify-content-between gap-3"><div><div class="small text-muted">Expected impact</div><span class="fw-semibold">{{ $recommendation['impact'] }}</span></div><div class="text-md-end"><div class="small text-muted">Supporting metric</div><span class="fw-bold text-primary">{{ $recommendation['metric'] }}</span></div></div>
    </div>
</article>
