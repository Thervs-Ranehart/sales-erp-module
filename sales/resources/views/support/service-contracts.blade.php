@extends('layouts.app')

@section('content')
    @php($title = 'Service Contracts')
    @php($subtitle = 'Support staff: verify contract coverage during case management')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    {{-- Read-only contract coverage modal (UI only) --}}
    @include('support.service-contract-view-modal')

    {{-- Contract statistics (support verification focus) --}}
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Active Coverage</div>
                        <div class="display-6 fw-bold">{{ $activeCoverageCount ?? 0 }}</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(22,200,199,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-shield-check" style="color:#16C8C7; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-success">Eligible</span></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Expiring Soon</div>
                        <div class="display-6 fw-bold">{{ $expiringSoonCount ?? 0 }}</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(245,158,11,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-hourglass-split" style="color:#F59E0B; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-warning text-dark">Within window</span></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Expired</div>
                        <div class="display-6 fw-bold">{{ $expiredCount ?? 0 }}</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(239,68,68,.10); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-x-circle" style="color:#EF4444; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-danger">Not eligible</span></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Coverage Verification Rate</div>
                        <div class="display-6 fw-bold">{{ $coverageVerificationRatePct ?? 0 }}%</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(83,71,206,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-check2-circle" style="color:#5347CE; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-primary">Eligible coverage</span></div>
            </div>
        </div>
    </div>


    {{-- Search + Filters (read-only) --}}
    <form method="GET">
        <div class="card p-3 mt-4" style="background: rgba(255,255,255,.7); border: 1px solid rgba(0,0,0,.06); box-shadow: none;">
        <div class="row g-3">

            <div class="col-12 col-lg-4">
                <label class="form-label small text-muted">Search</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="background: rgba(83,71,206,.08); border-color: rgba(83,71,206,.2);">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control" placeholder="Contract Number, Customer, Product..." aria-label="Search contracts" value="{{ $search ?? '' }}" />
                </div>
            </div>

            <div class="col-6 col-lg-2">
                <label class="form-label small text-muted">Status</label>
                <select class="form-select form-select-sm" aria-label="Status filter" name="status">
                    <option value="all" {{ ($status ?? null) === null || ($status ?? '') === 'all' ? 'selected' : '' }}>Status: All</option>
                    <option value="Active" {{ ($status ?? '') === 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Expiring" {{ ($status ?? '') === 'Expiring' ? 'selected' : '' }}>Expiring</option>
                    <option value="Expired" {{ ($status ?? '') === 'Expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>

            <div class="col-6 col-lg-2">
                <label class="form-label small text-muted">Customer</label>
                <select class="form-select form-select-sm" aria-label="Customer filter" name="customer">
                    <option value="all" {{ ($customer ?? null) === null || ($customer ?? '') === 'all' ? 'selected' : '' }}>All customers</option>
                    <option value="XYZ Trading" {{ ($customer ?? '') === 'XYZ Trading' ? 'selected' : '' }}>XYZ Trading</option>
                    <option value="ABC Corporation" {{ ($customer ?? '') === 'ABC Corporation' ? 'selected' : '' }}>ABC Corporation</option>
                    <option value="Northwind Retail" {{ ($customer ?? '') === 'Northwind Retail' ? 'selected' : '' }}>Northwind Retail</option>
                    <option value="Greenfield Industries" {{ ($customer ?? '') === 'Greenfield Industries' ? 'selected' : '' }}>Greenfield Industries</option>
                </select>
            </div>

            <div class="col-12 col-lg-4 d-flex align-items-end">
                <button class="btn btn-sm" type="submit" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
                    <i class="bi bi-funnel me-1"></i> Apply Filters
                </button>
            </div>
        </div>
        </div>
    </form>

    {{-- Contract table --}}

    <div class="card p-4 mt-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold mb-0">Contract Coverage</h5>
            <div class="text-muted small">Verify coverage details for support cases.</div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="min-width: 160px;">Contract Number</th>
                        <th style="min-width: 220px;">Customer</th>
                        <th>Product</th>
                        <th style="min-width: 220px;">Coverage Period</th>
                        <th style="min-width: 150px;">Status</th>
                        <th class="text-end" style="min-width: 160px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($serviceContracts as $contract)
                        <tr>
                            <td class="fw-semibold">{{ $contract->contract_number ?? ('SC-' . $contract->contract_id) }}</td>
                            <td>{{ $contract->customer->customer_name ?? '—' }}</td>
                            <td>{{ $contract->product->product_name ?? '—' }}</td>
                            <td class="text-muted">
                                {{ $contract->service_start ? $contract->service_start->format('Y-m-d') : '—' }}
                                →
                                {{ $contract->service_end ? $contract->service_end->format('Y-m-d') : '—' }}
                            </td>
                            <td>
                                @php($cs = strtolower((string)($contract->contract_status ?? '')))
                                @if($cs === 'active')
                                    <span class="badge bg-success">{{ $contract->contract_status }}</span>
                                @elseif($cs === 'expiring')
                                    <span class="badge bg-warning text-dark">{{ $contract->contract_status }}</span>
                                @elseif($cs === 'expired')
                                    <span class="badge bg-danger">{{ $contract->contract_status }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $contract->contract_status ?? '—' }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#serviceContractModal">
                                    <i class="bi bi-eye me-1"></i> View Coverage
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No service contracts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mt-4">
            <div class="text-muted small">Showing results.</div>

            <nav aria-label="Contracts pagination">
                {{ $serviceContracts->links() }}
            </nav>
        </div>
    </div>
@endsection


