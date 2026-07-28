@extends('layouts.app')

@section('content')
    <style>
        .service-contracts-page { --sc-primary:#5347ce; --sc-border:#e8eaf0; --sc-muted:#697386; --sc-text:#1f2937; }
        .service-contracts-page .sc-kpi, .service-contracts-page .sc-filter-card, .service-contracts-page .sc-table-card { background:#fff; border:1px solid var(--sc-border); border-radius:16px; box-shadow:0 8px 22px rgba(31,41,55,.055); }
        .service-contracts-page .sc-kpi { height:100%; min-height:124px; padding:1.15rem; }.service-contracts-page .sc-kpi-label { color:var(--sc-muted); font-size:.74rem; font-weight:700; letter-spacing:.03em; text-transform:uppercase; }.service-contracts-page .sc-kpi-value { color:var(--sc-text); font-size:1.65rem; font-weight:700; letter-spacing:-.04em; line-height:1.1; margin-top:.45rem; }.service-contracts-page .sc-kpi-icon { align-items:center; border-radius:12px; display:inline-flex; height:42px; justify-content:center; width:42px; }.service-contracts-page .sc-kpi-badge { border-radius:999px; font-size:.7rem; font-weight:700; margin-top:.7rem; padding:.32rem .55rem; }
        .service-contracts-page .sc-filter-card { margin-top:1.5rem; padding:1.2rem 1.5rem; }.service-contracts-page .sc-filter-grid { align-items:end; display:grid; gap:1rem; grid-template-columns:minmax(240px,1.55fr) minmax(145px,.7fr) minmax(175px,.85fr) auto; padding:.1rem .25rem; }.service-contracts-page .sc-filter-label { color:var(--sc-muted); font-size:.74rem; font-weight:600; margin-bottom:.42rem; }.service-contracts-page .form-control, .service-contracts-page .form-select { border-color:#dfe1ea; font-size:.86rem; min-height:38px; }.service-contracts-page .form-control:focus, .service-contracts-page .form-select:focus { border-color:var(--sc-primary); box-shadow:0 0 0 .2rem rgba(83,71,206,.12); }.service-contracts-page .sc-search-wrap { position:relative; }.service-contracts-page .sc-search-wrap i { color:#8b93a2; left:.8rem; pointer-events:none; position:absolute; top:50%; transform:translateY(-50%); }.service-contracts-page .sc-search-wrap input { padding-left:2.35rem; }.service-contracts-page .sc-filter-actions { display:flex; gap:.75rem; justify-self:end; }.service-contracts-page .sc-apply, .service-contracts-page .sc-reset { align-items:center; display:inline-flex; font-size:.84rem; font-weight:600; height:38px; justify-content:center; padding-inline:1rem; white-space:nowrap; }.service-contracts-page .sc-apply { background:var(--sc-primary); border-color:var(--sc-primary); box-shadow:0 5px 12px rgba(83,71,206,.2); }.service-contracts-page .sc-apply:hover, .service-contracts-page .sc-apply:focus { background:#4338ca; border-color:#4338ca; }.service-contracts-page .sc-reset { border-color:#d7dae5; color:#4b5563; }.service-contracts-page .sc-reset:hover, .service-contracts-page .sc-reset:focus { background:#f7f7fb; border-color:#c8ccda; color:#374151; }
        .service-contracts-page .sc-table-card { margin-top:1.75rem; overflow:hidden; padding:0; }.service-contracts-page .sc-table-heading { align-items:center; display:flex; justify-content:space-between; padding:1.25rem 1.4rem .9rem; }.service-contracts-page .sc-table-heading h2 { color:var(--sc-text); font-size:1rem; font-weight:700; margin:0; }.service-contracts-page .sc-table-heading p { color:var(--sc-muted); font-size:.78rem; margin:.25rem 0 0; }.service-contracts-page .sc-table { margin:0; min-width:820px; }.service-contracts-page .sc-table thead th { background:#f8f9fc; border-bottom:1px solid var(--sc-border); color:var(--sc-muted); font-size:.7rem; font-weight:700; letter-spacing:.04em; padding:1rem; text-transform:uppercase; white-space:nowrap; }.service-contracts-page .sc-table tbody td { border-color:#f0f1f5; color:#374151; font-size:.85rem; padding:1.1rem 1rem; vertical-align:middle; }.service-contracts-page .sc-table tbody tr { transition:background-color .16s ease; }.service-contracts-page .sc-table tbody tr:hover { background:#fafaff; }.service-contracts-page .sc-contract-number { color:var(--sc-primary); font-weight:700; }.service-contracts-page .sc-period { align-items:center; color:#667085; display:flex; gap:.6rem; white-space:nowrap; }.service-contracts-page .sc-period-separator { color:#a0a6b2; font-size:.9rem; }.service-contracts-page .sc-status { border-radius:999px; display:inline-flex; font-size:.72rem; font-weight:700; justify-content:center; min-width:108px; padding:.38rem .65rem; }.service-contracts-page .sc-action-header, .service-contracts-page .sc-action-cell { min-width:224px; text-align:center; }.service-contracts-page .sc-action-cell { padding-inline:1.1rem!important; }.service-contracts-page .sc-action-group { align-items:center; display:flex; gap:.6rem; justify-content:center; white-space:nowrap; }.service-contracts-page .sc-action-group form { margin:0; }.service-contracts-page .sc-action-btn { align-items:center; border-radius:8px; display:inline-flex; font-size:.8rem; font-weight:600; height:34px; justify-content:center; padding:.4rem .7rem; transition:background-color .16s ease, border-color .16s ease, box-shadow .16s ease, transform .16s ease; }.service-contracts-page .sc-action-btn:hover { box-shadow:0 4px 10px rgba(31,41,55,.1); transform:translateY(-1px); }.service-contracts-page .sc-action-btn:focus-visible { box-shadow:0 0 0 .2rem rgba(83,71,206,.2); outline:0; }.service-contracts-page .sc-view-btn:hover { background:#eef2ff; border-color:#7c73df; color:#4338ca; }.service-contracts-page .sc-edit-btn:hover { background:#fff8e6; border-color:#f2b94b; color:#a16207; }.service-contracts-page .sc-delete-btn:hover { background:#fff0f0; border-color:#f87171; color:#b91c1c; }.service-contracts-page .sc-footer { align-items:center; display:flex; gap:1rem; justify-content:space-between; padding:1.25rem 1.4rem; }.service-contracts-page .sc-footer-summary { color:var(--sc-muted); font-size:.8rem; }
        .service-contracts-page #serviceContractsFilters .form-control, .service-contracts-page #serviceContractsFilters .form-select { min-height:38px; }.service-contracts-page #serviceContractsFilters > div:last-child { display:flex; gap:.75rem; justify-content:flex-end; }.service-contracts-page #serviceContractsFilters > div:last-child .btn { align-items:center; display:inline-flex; font-size:.84rem; font-weight:600; height:38px; justify-content:center; padding-inline:1rem; }.service-contracts-page #serviceContractsFilters > div:last-child .btn[type="submit"] { background:#5347ce; border-color:#5347ce; box-shadow:0 5px 12px rgba(83,71,206,.2); color:#fff; }
        @media (max-width:991.98px) { .service-contracts-page .sc-filter-grid { grid-template-columns:minmax(210px,1fr) minmax(150px,1fr); }.service-contracts-page .sc-filter-actions { grid-column:1 / -1; justify-self:end; } } @media (max-width:575.98px) { .service-contracts-page .sc-filter-card { padding:1rem; }.service-contracts-page .sc-filter-grid { grid-template-columns:1fr; padding:0; }.service-contracts-page .sc-filter-actions { grid-column:auto; justify-self:stretch; }.service-contracts-page .sc-filter-actions .btn { flex:1; }.service-contracts-page #serviceContractsFilters > div:last-child { justify-content:stretch; }.service-contracts-page #serviceContractsFilters > div:last-child .btn { flex:1; }.service-contracts-page .sc-table-heading, .service-contracts-page .sc-footer { align-items:flex-start; flex-direction:column; padding:1rem; }.service-contracts-page .sc-footer nav { width:100%; }.service-contracts-page .sc-footer nav .pagination { justify-content:flex-start; } }
    </style>
    <div class="service-contracts-page">
    @php($title = 'Service Contracts')
    @php($subtitle = 'Support staff: verify contract coverage during case management')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])
    {{-- Read-only contract details modal --}}
    @include('support.service-contract-view-modal')
    @if($openContract ?? null)
        <button type="button" class="d-none js-service-contract-view" data-contract-id="{{ $openContract->contract_id }}" data-bs-toggle="modal" data-bs-target="#serviceContractModal" aria-hidden="true"></button>
    @endif

    {{-- Contract statistics --}}
    <div class="row g-3">
        <div class="col-md-3">
            <div class="sc-kpi">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="sc-kpi-label">Active Contracts</div>
                        <div class="sc-kpi-value">{{ $activeContractCount ?? 0 }}</div>
                    </div>
                    <div class="sc-kpi-icon" style="background:rgba(22,200,199,.12);">
                        <i class="bi bi-shield-check" style="color:#16C8C7; font-size:20px;"></i>
                    </div>
                </div>
                <div><span class="badge bg-success sc-kpi-badge">Current coverage</span></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="sc-kpi">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="sc-kpi-label">Expiring Soon</div>
                        <div class="sc-kpi-value">{{ $expiringSoonCount ?? 0 }}</div>
                    </div>
                    <div class="sc-kpi-icon" style="background:rgba(245,158,11,.12);">
                        <i class="bi bi-hourglass-split" style="color:#F59E0B; font-size:20px;"></i>
                    </div>
                </div>
                <div><span class="badge bg-warning text-dark sc-kpi-badge">Within window</span></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="sc-kpi">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="sc-kpi-label">Expired</div>
                        <div class="sc-kpi-value">{{ $expiredCount ?? 0 }}</div>
                    </div>
                    <div class="sc-kpi-icon" style="background:rgba(239,68,68,.10);">
                        <i class="bi bi-x-circle" style="color:#EF4444; font-size:20px;"></i>
                    </div>
                </div>
                <div><span class="badge bg-danger sc-kpi-badge">Not eligible</span></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="sc-kpi">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="sc-kpi-label">Active Contract Rate</div>
                        <div class="sc-kpi-value">{{ $activeContractRatePct ?? 0 }}%</div>
                    </div>
                    <div class="sc-kpi-icon" style="background:rgba(83,71,206,.12);">
                        <i class="bi bi-check2-circle" style="color:#5347CE; font-size:20px;"></i>
                    </div>
                </div>
                <div><span class="badge bg-primary sc-kpi-badge">Of all contracts</span></div>
            </div>
        </div>
    </div>


    {{-- Search + Filters (read-only) --}}
    <form method="GET" action="{{ route('support.service-contracts') }}">
        <div class="sc-filter-card">
        <div class="row g-3 align-items-end" id="serviceContractsFilters">

            <div class="col-12 col-lg-4">
                <label class="form-label small text-muted">Search</label>
                <div class="sc-search-wrap"><i class="bi bi-search" aria-hidden="true"></i><input type="text" name="search" class="form-control" placeholder="Contract Number, Customer, Product..." aria-label="Search contracts" value="{{ $search ?? '' }}" /></div>
            </div>

            <div class="col-6 col-lg-2">
                <label class="form-label small text-muted">Status</label>
                <select class="form-select form-select-sm" aria-label="Status filter" name="status">
                    <option value="all" {{ ($status ?? null) === null || ($status ?? '') === 'all' ? 'selected' : '' }}>Status: All</option>
                    <option value="Active" {{ ($status ?? '') === 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Expiring Soon" {{ ($status ?? '') === 'Expiring Soon' ? 'selected' : '' }}>Expiring Soon</option>
                    <option value="Expired" {{ ($status ?? '') === 'Expired' ? 'selected' : '' }}>Expired</option>
                    <option value="Terminated" {{ ($status ?? '') === 'Terminated' ? 'selected' : '' }}>Terminated</option>
                </select>
            </div>

            <div class="col-6 col-lg-2">
                <label class="form-label small text-muted">Customer</label>
                <select class="form-select form-select-sm" aria-label="Customer filter" name="customer">
                    <option value="all" {{ ($customer ?? null) === null || ($customer ?? '') === 'all' ? 'selected' : '' }}>All customers</option>
                    @foreach(($customers ?? collect()) as $customerOption)
                        <option value="{{ $customerOption->customer_id }}" {{ (string) ($customer ?? '') === (string) $customerOption->customer_id ? 'selected' : '' }}>{{ $customerOption->full_name }}</option>
                    @endforeach
                </select>
            </div>

        <div class="col-12 col-lg-4 d-flex align-items-end" style="margin-top:0;">
                <style>
                    @media (max-width: 575.98px) {
                        #serviceContractsFilters .col-6,
                        #serviceContractsFilters .col-12 {
                            flex: 0 0 100%;
                            max-width: 100%;
                        }
                        #serviceContractsFilters .form-control,
                        #serviceContractsFilters .form-select {
                            width: 100% !important;
                        }
                        #serviceContractsFilters .input-group {
                            width: 100% !important;
                        }
                        #serviceContractsFilters .btn {
                            width: 100%;
                        }
                    }
                </style>
                <button class="btn btn-sm" type="submit" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
                    <i class="bi bi-funnel me-1"></i> Apply Filters
                </button>
            </div>
        </div>
        </div>
    </form>

    {{-- Contract table --}}

    <div class="sc-table-card">
        <div class="sc-table-heading">
            <div><h5 class="fw-bold mb-0">Contract Records</h5><div class="text-muted small mt-1">Review coverage details and contract status.</div></div>
        </div>

        <div class="table-responsive after-sales-table-responsive">
            <table class="table sc-table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="min-width: 160px;">Contract Number</th>
                        <th style="min-width: 220px;">Customer</th>
                        <th>Product</th>
                        <th style="min-width: 220px;">Coverage Period</th>
                        <th style="min-width: 150px;">Status</th>
                        <th class="sc-action-header">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($serviceContracts as $contract)
                        <tr>
                            <td><span class="sc-contract-number">{{ $contract->contract_number ?? ('SC-' . $contract->contract_id) }}</span></td>
                            <td>{{ $contract->customer?->full_name ?? '—' }}</td>
                            <td>{{ $contract->product?->product_name ?? '—' }}</td>
                            <td class="text-muted">
                                {{ $contract->service_start ? $contract->service_start->format('Y-m-d') : '—' }}
                                →
                                {{ $contract->service_end ? $contract->service_end->format('Y-m-d') : '—' }}
                            </td>
                            <td>
                                @php($contractStatus = $contract->currentStatus())
                                @php($cs = strtolower($contractStatus))
                                @if($cs === 'active')
                                    <span class="badge bg-success sc-status">{{ $contractStatus }}</span>
                                @elseif($cs === 'expiring soon')
                                    <span class="badge bg-warning text-dark sc-status">{{ $contractStatus }}</span>
                                @elseif($cs === 'expired')
                                    <span class="badge bg-danger sc-status">{{ $contractStatus }}</span>
                                @elseif($cs === 'terminated')
                                    <span class="badge bg-secondary sc-status">{{ $contractStatus }}</span>
                                @else
                                    <span class="badge bg-secondary sc-status">{{ $contractStatus }}</span>
                                @endif
                            </td>
                            <td class="sc-action-cell support-action-cell"><div class="sc-action-group support-action-group"><button class="btn btn-outline-primary sc-action-btn sc-view-btn support-action-button js-service-contract-view" type="button" data-contract-id="{{ $contract->contract_id }}" data-bs-toggle="modal" data-bs-target="#serviceContractModal" title="View service contract" aria-label="View service contract"><i class="bi bi-eye" aria-hidden="true"></i><span class="visually-hidden">View</span></button>@if(!$contract->archived_at)<button class="btn btn-outline-warning sc-action-btn sc-edit-btn support-action-button" type="button" data-bs-toggle="modal" data-bs-target="#editContract{{ $contract->contract_id }}" title="Edit service contract" aria-label="Edit service contract"><i class="bi bi-pencil" aria-hidden="true"></i><span class="visually-hidden">Edit</span></button><form class="support-action-destructive" method="POST" action="{{ route('support.service-contracts.archive', $contract) }}" onsubmit="return confirm('Archive this contract?')">@csrf @method('PATCH')<input type="hidden" name="archive_reason" value="Archived by support staff"><button class="btn btn-outline-danger sc-action-btn sc-delete-btn support-action-button" type="submit" title="Archive service contract" aria-label="Archive service contract"><i class="bi bi-archive" aria-hidden="true"></i><span class="visually-hidden">Archive</span></button></form>@endif</div></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No service contracts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-support-pagination :paginator="$serviceContracts" />
    </div>

@foreach($serviceContracts as $contract)
<div class="modal fade" id="editContract{{ $contract->contract_id }}" tabindex="-1"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('support.service-contracts.update', $contract) }}">@csrf @method('PUT')
<div class="modal-header"><h5 class="modal-title">Edit {{ $contract->contract_number }}</h5><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="row g-3">
<div class="col-6"><label class="form-label">Customer</label><select class="form-select" name="customer_id">@foreach($customers as $customerOption)<option value="{{ $customerOption->customer_id }}" @selected($contract->customer_id===$customerOption->customer_id)>{{ $customerOption->full_name }}</option>@endforeach</select></div>
<div class="col-6"><label class="form-label">Product</label><select class="form-select" name="product_id">@foreach($products as $productOption)<option value="{{ $productOption->product_id }}" @selected($contract->product_id===$productOption->product_id)>{{ $productOption->product_name }}</option>@endforeach</select></div>
<div class="col-8"><label class="form-label">Service Type</label><input class="form-control" name="service_type" value="{{ $contract->service_type }}" required></div><div class="col-4"><label class="form-label">Service Limit</label><input class="form-control" type="number" min="1" name="service_limit" value="{{ $contract->service_limit }}"></div>
<div class="col-6"><label class="form-label">Start</label><input class="form-control" type="date" name="service_start" value="{{ $contract->service_start?->toDateString() }}" required></div><div class="col-6"><label class="form-label">End</label><input class="form-control" type="date" name="service_end" value="{{ $contract->service_end?->toDateString() }}" required></div>
<div class="col-12"><label class="form-label">Status</label><select class="form-select" name="contract_status">@foreach(['Active','Suspended','Expired','Terminated'] as $option)<option @selected($contract->contract_status===$option)>{{ $option }}</option>@endforeach</select></div>
</div></div><div class="modal-footer"><button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button><button class="btn support-primary">Save Changes</button></div></form></div></div>
@endforeach

</div>

<script>
(function(){
  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  const modalEl = document.getElementById('serviceContractModal');
  if(!modalEl) return;

  document.querySelectorAll('.js-service-contract-view').forEach(btn => {
    btn.addEventListener('click', async (e) => {
      const contractId = btn.getAttribute('data-contract-id');
      if(!contractId) return;

      try {
        const res = await fetch(`/support/service-contracts/${contractId}/show`, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            ...(csrf ? {'X-CSRF-TOKEN': csrf} : {})
          }
        });
        if(!res.ok) throw new Error('Failed to load service contract');
        const data = await res.json();

        const c = data.contract || {};
        document.getElementById('serviceContractModalSubtitle').textContent = c.contract_number ? `${c.contract_number} • ${c.customer?.name ?? '—'}` : '—';

        document.getElementById('serviceContractCustomer').textContent = c.customer?.name ?? '—';
        document.getElementById('serviceContractProduct').textContent = c.product?.product_name ?? '—';
        document.getElementById('serviceContractServiceType').textContent = c.service_type ?? '—';
        document.getElementById('serviceContractStartDate').textContent = c.service_start ?? '—';
        document.getElementById('serviceContractEndDate').textContent = c.service_end ?? '—';
        document.getElementById('serviceContractCreatedDate').textContent = c.created_at ?? '—';

        const badge = document.getElementById('serviceContractStatusBadge');
        badge.textContent = c.contract_status ?? '—';

        const status = (c.contract_status ?? '').toString().toLowerCase();
        let cls = 'badge bg-secondary';
        if(status === 'active') cls = 'badge bg-success';
        else if(status === 'expiring soon') cls = 'badge bg-warning text-dark';
        else if(status === 'expired') cls = 'badge bg-danger';
        badge.className = cls;
      } catch (err) {
        console.error(err);
      }
    });
  });
  const openContractId = @json($openContractId ?? null);
  if (openContractId) document.querySelector(`.js-service-contract-view[data-contract-id="${openContractId}"]`)?.click();
})();
</script>
@endsection
