@extends('layouts.app')

@section('content')
    @php($title = 'Warranty Records')
    @php($subtitle = 'Track warranty coverage by order and product')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])
    @include('support.operations-create-modal')

@include('support.warranty-view-modal')

    <div class="card p-4">


    {{-- Superseded inline handler retained temporarily below only as a Blade comment. --}}
    {{--
    <script>
        (function () {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            function setText(id, value) {
                const el = document.getElementById(id);
                if (el) el.textContent = value ?? '—';
            }

            function badgeClassForStatus(status) {
                const s = (status || '').toString().toLowerCase();
                if (s === 'active') return 'bg-success';
                if (s === 'expiring soon') return 'bg-warning text-dark';
                if (s === 'expired') return 'bg-danger';
                return 'bg-secondary';
            }

            document.querySelectorAll('.js-warranty-view').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    const warrantyId = e.currentTarget.getAttribute('data-warranty-id');
                    if (!warrantyId) return;

                    ['warrantyCustomer', 'warrantyProduct', 'warrantySku', 'warrantyOrder', 'warrantyStart', 'warrantyEnd', 'warrantyPurchaseDate', 'warrantyCreatedAt', 'warrantyClaimCount'].forEach((id) => setText(id, 'Loading…'));
                    setText('warrantyViewModalSubtitle', 'Loading warranty details…');

                    try {
                        const res = await fetch(`{{ url('/support/warranty-records') }}/${warrantyId}/show`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {})
                            }
                        });

                        if (!res.ok) throw new Error('Failed to load warranty');
                        const data = await res.json();

                        const w = data.warranty || {};
                        setText('warrantyViewModalSubtitle', `${w.warranty_number || '—'} • ${w.customer?.name || '—'}`);
                        setText('warrantyCustomer', w.customer?.name);
                        setText('warrantyProduct', w.product?.product_name);
                        setText('warrantyOrder', w.order?.order_number);
                        setText('warrantyStart', w.warranty_start);
                        setText('warrantyEnd', w.warranty_end);
                        setText('warrantyPurchaseDate', w.order?.order_date);
                        setText('warrantyCreatedAt', w.created_at);
                        setText('warrantyClaimCount', w.claim_count);

                        // Badge
                        const badge = document.getElementById('warrantyBadge');
                        if (badge) {
                            badge.className = `badge ${badgeClassForStatus(w.warranty_status)}`;
                            badge.textContent = w.warranty_status || '—';
                        }

                    } catch (err) {
                        setText('warrantyViewModalSubtitle', 'Unable to load warranty details.');
                        ['warrantyCustomer', 'warrantyProduct', 'warrantySku', 'warrantyOrder', 'warrantyStart', 'warrantyEnd', 'warrantyPurchaseDate', 'warrantyCreatedAt', 'warrantyClaimCount'].forEach((id) => setText(id, '—'));
                    }
                });
            });
        })();
    </script>
    --}}

        {{-- Header + Actions --}}

        <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3 mb-4">
            <div>
                <h5 class="fw-bold mb-1">Warranty Registry</h5>
                <div class="text-muted small">Manage warranties, eligibility windows, and status.</div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item active" aria-current="page">Warranty Records</li>
                    </ol>

                </nav>
            </div>



        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('support.warranty-records') }}">
        <div class="card p-3 mb-4" style="background: rgba(255,255,255,.7); border: 1px solid rgba(0,0,0,.06); box-shadow: none;">
            <div class="row g-3" id="warrantyFilters">

                <style>
                    @media (max-width: 575.98px) {
                        /* Stack filter inputs vertically on mobile without altering desktop */
                        #warrantyFilters .col-6,
                        #warrantyFilters .col-12 {
                            flex: 0 0 100%;
                            max-width: 100%;
                        }
                        #warrantyFilters .form-control,
                        #warrantyFilters .form-select {
                            width: 100% !important;
                        }
                        #warrantyFilters .input-group {
                            width: 100% !important;
                        }
                        #warrantyFilters .btn {
                            width: 100%;
                        }
                    }
                </style>

                <div class="col-12 col-lg-4">
                    <label class="form-label small text-muted">Search</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="background: rgba(83,71,206,.08); border-color: rgba(83,71,206,.2);">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="Warranty number, customer, product..." aria-label="Search warranties" value="{{ $search ?? '' }}" />
                    </div>

                </div>

                <div class="col-6 col-lg-2">
                    <label class="form-label small text-muted">Status</label>
                    <select class="form-select form-select-sm" aria-label="Status filter" name="status">
                        <option value="all" {{ ($status ?? '') === '' || ($status ?? '') === 'all' ? 'selected' : '' }}>Status: All</option>
                        <option value="Active" {{ ($status ?? '') === 'Active' ? 'selected' : '' }}>Status: Active</option>
                        <option value="Expiring Soon" {{ ($status ?? '') === 'Expiring Soon' ? 'selected' : '' }}>Status: Expiring Soon</option>
                        <option value="Expired" {{ ($status ?? '') === 'Expired' ? 'selected' : '' }}>Status: Expired</option>
                        <option value="On Hold" {{ ($status ?? '') === 'On Hold' ? 'selected' : '' }}>Status: On Hold</option>
                    </select>

                </div>

                <div class="col-6 col-lg-2">
                    <label class="form-label small text-muted">Product</label>
                    <select class="form-select form-select-sm" aria-label="Product filter" name="product">
                        <option value="all" {{ ($product ?? '') === '' || ($product ?? '') === 'all' ? 'selected' : '' }}>Product: All</option>
                        @foreach($products as $productOption)
                            <option value="{{ $productOption->product_id }}" @selected((string) ($product ?? '') === (string) $productOption->product_id)>{{ $productOption->product_name }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="col-6 col-lg-2">
                    <label class="form-label small text-muted">Customer</label>
                    <select class="form-select form-select-sm" aria-label="Customer filter" name="customer">
                        <option value="">Customer: All</option>
                        @foreach($customers as $customerOption)
                            <option value="{{ $customerOption->customer_id }}" @selected((string) ($customer ?? '') === (string) $customerOption->customer_id)>{{ $customerOption->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-lg-4 d-flex align-items-end">
                    <button class="btn btn-sm" type="submit" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
                        <i class="bi bi-funnel me-1"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>

        {{-- Table --}}

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0" style="width:100%;">
                <thead>
                    <tr>
                        <th style="min-width: 150px;">Warranty Number</th>
                        <th style="min-width: 200px;">Customer</th>
                        <th>Product</th>
                        <th style="min-width: 160px;">Order</th>
                        <th style="min-width: 160px;">Warranty Start</th>
                        <th style="min-width: 160px;">Warranty End</th>
                        <th style="min-width: 150px;">Status</th>
                        <th class="text-end" style="min-width: 200px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warrantyRecords as $warranty)
                        <tr>
                            <td class="fw-semibold">{{ $warranty->warranty_number ?? ('WR-' . $warranty->warranty_id) }}</td>
                            <td>{{ $warranty->customer?->full_name ?? '—' }}</td>
                            <td>{{ $warranty->product?->product_name ?? '—' }}</td>
                            <td>{{ $warranty->order?->order_number ?? ($warranty->order_id ? 'SO-' . $warranty->order_id : '—') }}</td>
                            <td class="text-muted">{{ $warranty->warranty_start ? $warranty->warranty_start->format('Y-m-d') : '—' }}</td>
                            <td class="text-muted">{{ $warranty->warranty_end ? $warranty->warranty_end->format('Y-m-d') : '—' }}</td>
                            <td>
                                @php($warrantyStatus = $warranty->currentStatus())
                                @php($ws = strtolower($warrantyStatus))
                                @if($ws === 'active')
                                    <span class="badge bg-success">{{ $warrantyStatus }}</span>
                                @elseif($ws === 'expiring soon')
                                    <span class="badge bg-warning text-dark">{{ $warrantyStatus }}</span>
                                @elseif($ws === 'expired')
                                    <span class="badge bg-danger">{{ $warrantyStatus }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $warrantyStatus }}</span>
                                @endif
                            </td>

                            <td class="text-end">
                                <button
                                    class="btn btn-sm btn-outline-primary js-warranty-view"
                                    type="button"
                                    data-warranty-id="{{ $warranty->warranty_id }}"
                                    aria-label="View warranty"
                                >
                                    <i class="bi bi-eye me-1"></i> View
                                </button>
                                <button class="btn btn-sm btn-outline-warning" type="button" data-bs-toggle="modal" data-bs-target="#editWarranty{{ $warranty->warranty_id }}"><i class="bi bi-pencil"></i></button>
                                @if(!$warranty->archived_at)<form class="d-inline" method="POST" action="{{ route('support.warranty-records.archive', $warranty) }}" onsubmit="return confirm('Archive this warranty?')">@csrf @method('PATCH')<input type="hidden" name="archive_reason" value="Archived by support staff"><button class="btn btn-sm btn-outline-danger"><i class="bi bi-archive"></i></button></form>@endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No warranty records found for the selected criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mt-4">
            <div class="text-muted small">Showing results.</div>

            <nav aria-label="Warranty pagination">
                {{ $warrantyRecords->links('pagination::bootstrap-5') }}
            </nav>
        </div>
    </div>
    @foreach($warrantyRecords as $warranty)<div class="modal fade" id="editWarranty{{ $warranty->warranty_id }}" tabindex="-1"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('support.warranty-records.update', $warranty) }}">@csrf @method('PUT')<div class="modal-header"><h5 class="modal-title">Edit {{ $warranty->warranty_number }}</h5><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><label class="form-label">Start</label><input class="form-control mb-3" type="date" name="warranty_start" value="{{ $warranty->warranty_start?->toDateString() }}" required><label class="form-label">End</label><input class="form-control mb-3" type="date" name="warranty_end" value="{{ $warranty->warranty_end?->toDateString() }}" required><label class="form-label">Status</label><select class="form-select" name="warranty_status">@foreach(['Active','On Hold','Expired'] as $option)<option @selected($warranty->warranty_status===$option)>{{ $option }}</option>@endforeach</select></div><div class="modal-footer"><button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button><button class="btn support-primary">Save Changes</button></div></form></div></div>@endforeach

    </form>
@endsection

@push('scripts')
    <script>
        (function () {
            const modalElement = document.getElementById('warrantyViewModal');
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const fieldIds = ['warrantyCustomer', 'warrantyCustomerEmail', 'warrantyProduct', 'warrantyOrder', 'warrantyStart', 'warrantyEnd', 'warrantyPurchaseDate', 'warrantyCreatedAt', 'warrantyClaimCount'];

            function setText(id, value) {
                const element = document.getElementById(id);
                if (element) element.textContent = value ?? '—';
            }

            function statusClass(status) {
                switch ((status || '').toLowerCase()) {
                    case 'active': return 'bg-success';
                    case 'expiring soon': return 'bg-warning text-dark';
                    case 'expired': return 'bg-danger';
                    default: return 'bg-secondary';
                }
            }

            document.addEventListener('click', async (event) => {
                const button = event.target.closest('.js-warranty-view');
                if (!button || !modalElement) return;

                const warrantyId = button.dataset.warrantyId;
                if (!warrantyId) return;

                const errorAlert = document.getElementById('warrantyViewError');
                errorAlert?.classList.add('d-none');
                fieldIds.forEach((id) => setText(id, 'Loading…'));
                setText('warrantyViewModalSubtitle', 'Loading warranty details…');

                try {
                    const response = await fetch(`{{ url('/support/warranty-records') }}/${warrantyId}/show`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {}),
                        },
                    });

                    if (!response.ok) throw new Error('Unable to load warranty details.');

                    const warranty = (await response.json()).warranty || {};
                    setText('warrantyViewModalSubtitle', `${warranty.warranty_number || '—'} • ${warranty.customer?.name || '—'}`);
                    setText('warrantyCustomer', warranty.customer?.name);
                    setText('warrantyCustomerEmail', warranty.customer?.email);
                    setText('warrantyProduct', warranty.product?.product_name);
                    setText('warrantyOrder', warranty.order?.order_number);
                    setText('warrantyStart', warranty.warranty_start);
                    setText('warrantyEnd', warranty.warranty_end);
                    setText('warrantyPurchaseDate', warranty.order?.order_date);
                    setText('warrantyCreatedAt', warranty.created_at);
                    setText('warrantyClaimCount', warranty.claim_count);

                    const badge = document.getElementById('warrantyBadge');
                    if (badge) {
                        badge.className = `badge ${statusClass(warranty.warranty_status)}`;
                        badge.textContent = warranty.warranty_status || '—';
                    }
                } catch (requestError) {
                    fieldIds.forEach((id) => setText(id, '—'));
                    setText('warrantyViewModalSubtitle', 'Unable to load warranty details.');
                    errorAlert?.classList.remove('d-none');
                }

                bootstrap.Modal.getOrCreateInstance(modalElement).show();
            });
        })();
    </script>
@endpush
