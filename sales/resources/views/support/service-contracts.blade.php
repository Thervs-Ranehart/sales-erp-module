@extends('layouts.app')

@section('content')
    @php($title = 'Service Contracts')
    @php($subtitle = 'Support staff: verify contract coverage during case management')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])
    @include('support.operations-create-modal')

    {{-- Read-only contract details modal --}}
    @include('support.service-contract-view-modal')
    @if($openContract ?? null)
        <button type="button" class="d-none js-service-contract-view" data-contract-id="{{ $openContract->contract_id }}" data-bs-toggle="modal" data-bs-target="#serviceContractModal" aria-hidden="true"></button>
    @endif

    {{-- Contract statistics --}}
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Active Contracts</div>
                        <div class="display-6 fw-bold">{{ $activeContractCount ?? 0 }}</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(22,200,199,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-shield-check" style="color:#16C8C7; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-success">Current coverage</span></div>
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
                        <div class="text-muted small fw-semibold">Active Contract Rate</div>
                        <div class="display-6 fw-bold">{{ $activeContractRatePct ?? 0 }}%</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(83,71,206,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-check2-circle" style="color:#5347CE; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-primary">Of all contracts</span></div>
            </div>
        </div>
    </div>


    {{-- Search + Filters (read-only) --}}
    <form method="GET" action="{{ route('support.service-contracts') }}">
        <div class="card p-3 mt-4" style="background: rgba(255,255,255,.7); border: 1px solid rgba(0,0,0,.06); box-shadow: none;">
        <div class="row g-3" id="serviceContractsFilters">

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

    <div class="card p-4 mt-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold mb-0">Contract Coverage</h5>
            <div class="text-muted small">Verify coverage details for support cases.</div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width: 650px;">
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
                                    <span class="badge bg-success">{{ $contractStatus }}</span>
                                @elseif($cs === 'expiring soon')
                                    <span class="badge bg-warning text-dark">{{ $contractStatus }}</span>
                                @elseif($cs === 'expired')
                                    <span class="badge bg-danger">{{ $contractStatus }}</span>
                                @elseif($cs === 'terminated')
                                    <span class="badge bg-secondary">{{ $contractStatus }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $contractStatus }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary js-service-contract-view" type="button" data-contract-id="{{ $contract->contract_id }}" data-bs-toggle="modal" data-bs-target="#serviceContractModal">
                                    <i class="bi bi-eye me-1"></i> View Coverage
                                </button>
                                <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editContract{{ $contract->contract_id }}"><i class="bi bi-pencil"></i></button>
                                @if(!$contract->archived_at)<form class="d-inline" method="POST" action="{{ route('support.service-contracts.archive', $contract) }}" onsubmit="return confirm('Archive this contract?')">@csrf @method('PATCH')<input type="hidden" name="archive_reason" value="Archived by support staff"><button class="btn btn-sm btn-outline-danger"><i class="bi bi-archive"></i></button></form>@endif


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
                {{ $serviceContracts->links('pagination::bootstrap-5') }}
            </nav>
        </div>
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
