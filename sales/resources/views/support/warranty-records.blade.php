@extends('layouts.app')

@section('content')
    @php($title = 'Warranty Records')
    @php($subtitle = 'Track warranty coverage by order and product')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])
@include('support.warranty-view-modal')

    <style>
        .warranty-registry-page { --wr-primary:#5347ce; --wr-border:#e7eaf0; --wr-muted:#6b7280; }
        .warranty-registry-page .wr-card { background:#fff; border:1px solid var(--wr-border); border-radius:16px; box-shadow:0 8px 22px rgba(31,41,55,.055); padding:1.35rem; }
        .warranty-registry-page .wr-filter-card { background:#fafbfe; border:1px solid var(--wr-border); border-radius:12px; margin-bottom:1.35rem; padding:1rem 1.1rem; }
        .warranty-registry-page .wr-filter-grid { align-items:end; display:grid; gap:1rem; grid-template-columns:minmax(260px,1.7fr) minmax(135px,.72fr) minmax(150px,.85fr) minmax(165px,.95fr) auto; }
        .warranty-registry-page .wr-filter-label { color:var(--wr-muted); font-size:.75rem; font-weight:600; margin-bottom:.4rem; }
        .warranty-registry-page .wr-filter-grid .form-control, .warranty-registry-page .wr-filter-grid .form-select, .warranty-registry-page .wr-filter-grid .input-group-text, .warranty-registry-page .wr-filter-actions .btn { height:38px; }
        .warranty-registry-page .wr-filter-grid .form-control, .warranty-registry-page .wr-filter-grid .form-select { border-color:#dfe3eb; font-size:.84rem; }
        .warranty-registry-page .wr-filter-grid .form-control:focus, .warranty-registry-page .wr-filter-grid .form-select:focus { border-color:var(--wr-primary); box-shadow:0 0 0 .2rem rgba(83,71,206,.12); }
        .warranty-registry-page .wr-search .input-group-text { background:#fff; border-color:#dfe3eb; color:#878e9c; }
        .warranty-registry-page .wr-filter-actions { display:flex; gap:.5rem; white-space:nowrap; }
        .warranty-registry-page .wr-filter-actions .btn { align-items:center; display:inline-flex; font-size:.83rem; font-weight:600; justify-content:center; padding-inline:.9rem; }
        .warranty-registry-page .wr-apply { background:var(--wr-primary); border-color:var(--wr-primary); box-shadow:0 4px 10px rgba(83,71,206,.18); color:#fff; }
        .warranty-registry-page .wr-reset { border-color:#d4d8e1; color:#4b5563; }
        .warranty-registry-page .wr-table-wrap { border:1px solid var(--wr-border); border-radius:13px; overflow:hidden; }
        .warranty-registry-page .wr-table { margin:0; min-width:1120px; }
        .warranty-registry-page .wr-table thead th { background:#f8f9fc; border-bottom:1px solid var(--wr-border); color:var(--wr-muted); font-size:.71rem; font-weight:700; letter-spacing:.035em; padding: .9rem 1.1rem; text-transform:uppercase; white-space:nowrap; }
        .warranty-registry-page .wr-table tbody td { border-color:#eff1f5; color:#374151; font-size:.84rem; padding:.85rem 1.1rem; vertical-align:middle; }
        .warranty-registry-page .wr-table tbody tr { transition:background-color .16s ease; }.warranty-registry-page .wr-table tbody tr:hover { background:#fafaff; }
        .warranty-registry-page .wr-number { color:var(--wr-primary); font-weight:700; }
        .warranty-registry-page .wr-status { align-items:center; border-radius:999px; display:inline-flex; font-size:.72rem; font-weight:700; height:26px; justify-content:center; min-width:92px; padding:0 .65rem; }
        .warranty-registry-page .wr-action-head, .warranty-registry-page .wr-action-cell { min-width:230px; text-align:center; }
        .warranty-registry-page .wr-action-group { align-items:center; display:flex; gap:.5rem; justify-content:center; white-space:nowrap; }.warranty-registry-page .wr-action-group form { margin:0; }
        .warranty-registry-page .wr-action-btn { align-items:center; border-radius:8px; display:inline-flex; font-size:.8rem; font-weight:600; height:34px; justify-content:center; min-width:34px; padding:.4rem .65rem; }.warranty-registry-page .wr-action-btn:hover { box-shadow:0 4px 10px rgba(31,41,55,.1); transform:translateY(-1px); }
        @media (max-width:1199.98px) { .warranty-registry-page .wr-filter-grid { grid-template-columns:minmax(240px,1.5fr) repeat(3,minmax(140px,1fr)); }.warranty-registry-page .wr-filter-actions { grid-column:1 / -1; justify-content:flex-end; } }
        @media (max-width:991.98px) { .warranty-registry-page .wr-filter-grid { grid-template-columns:minmax(220px,1fr) minmax(150px,1fr); } }
        @media (max-width:575.98px) { .warranty-registry-page .wr-card { padding:1rem; }.warranty-registry-page .wr-filter-card { padding:1rem; }.warranty-registry-page .wr-filter-grid { grid-template-columns:1fr; }.warranty-registry-page .wr-filter-actions { grid-column:auto; }.warranty-registry-page .wr-filter-actions .btn { flex:1; } }
    </style>

    <div class="warranty-registry-page">
    <div class="wr-card">


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

        {{-- Filters --}}
        <form method="GET" action="{{ route('support.warranty-records') }}">
        <div class="wr-filter-card">
            <div class="wr-filter-grid" id="warrantyFilters">
                <div>
                    <label class="form-label wr-filter-label">Search</label>
                    <div class="input-group wr-search">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="Warranty number, customer, product..." aria-label="Search warranties" value="{{ $search ?? '' }}" />
                    </div>

                </div>

                <div>
                    <label class="form-label wr-filter-label">Status</label>
                    <select class="form-select" aria-label="Status filter" name="status">
                        <option value="all" {{ ($status ?? '') === '' || ($status ?? '') === 'all' ? 'selected' : '' }}>Status: All</option>
                        <option value="Active" {{ ($status ?? '') === 'Active' ? 'selected' : '' }}>Status: Active</option>
                        <option value="Expiring Soon" {{ ($status ?? '') === 'Expiring Soon' ? 'selected' : '' }}>Status: Expiring Soon</option>
                        <option value="Expired" {{ ($status ?? '') === 'Expired' ? 'selected' : '' }}>Status: Expired</option>
                        <option value="On Hold" {{ ($status ?? '') === 'On Hold' ? 'selected' : '' }}>Status: On Hold</option>
                    </select>

                </div>

                <div>
                    <label class="form-label wr-filter-label">Product</label>
                    <select class="form-select" aria-label="Product filter" name="product">
                        <option value="" {{ ($product ?? '') === '' ? 'selected' : '' }}>Product: All</option>
                        @foreach($products as $productOption)
                            <option value="{{ $productOption->product_id }}" @selected((string) ($product ?? '') === (string) $productOption->product_id)>{{ $productOption->product_name }}</option>
                        @endforeach
                    </select>

                </div>

                <div>
                    <label class="form-label wr-filter-label">Customer</label>
                    <select class="form-select" aria-label="Customer filter" name="customer">
                        <option value="">Customer: All</option>
                        @foreach($customers as $customerOption)
                            <option value="{{ $customerOption->customer_id }}" @selected((string) ($customer ?? '') === (string) $customerOption->customer_id)>{{ $customerOption->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="wr-filter-actions">
                    <button class="btn wr-apply" type="submit">
                        <i class="bi bi-funnel me-1"></i> Apply Filters
                    </button>
                    <a class="btn btn-outline-secondary wr-reset" data-support-reset="1" href="{{ route('support.warranty-records') }}">Reset</a>
                </div>
            </div>
        </div>
        </form>

        {{-- Table --}}

        <div class="table-responsive after-sales-table-responsive wr-table-wrap">

            <table class="table table-hover align-middle wr-table">
                <thead>
                    <tr>
                        <th style="min-width: 150px;">Warranty Number</th>
                        <th style="min-width: 200px;">Customer</th>
                        <th>Product</th>
                        <th style="min-width: 160px;">Order</th>
                        <th style="min-width: 160px;">Warranty Start</th>
                        <th style="min-width: 160px;">Warranty End</th>
                        <th style="min-width: 150px;">Status</th>
                        <th class="wr-action-head">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warrantyRecords as $warranty)
                        <tr>
                            <td class="wr-number">{{ $warranty->warranty_number ?? ('WR-' . $warranty->warranty_id) }}</td>
                            <td>{{ $warranty->customer?->full_name ?? '—' }}</td>
                            <td>{{ $warranty->product?->product_name ?? '—' }}</td>
                            <td>{{ $warranty->order?->order_number ?? ($warranty->order_id ? 'SO-' . $warranty->order_id : '—') }}</td>
                            <td class="text-muted">{{ $warranty->warranty_start ? $warranty->warranty_start->format('Y-m-d') : '—' }}</td>
                            <td class="text-muted">{{ $warranty->warranty_end ? $warranty->warranty_end->format('Y-m-d') : '—' }}</td>
                            <td>
                                @php($warrantyStatus = $warranty->currentStatus())
                                @php($ws = strtolower($warrantyStatus))
                                @if($ws === 'active')
                                    <span class="badge bg-success wr-status">{{ $warrantyStatus }}</span>
                                @elseif($ws === 'expiring soon')
                                    <span class="badge bg-warning text-dark wr-status">{{ $warrantyStatus }}</span>
                                @elseif($ws === 'expired')
                                    <span class="badge bg-danger wr-status">{{ $warrantyStatus }}</span>
                                @else
                                    <span class="badge bg-secondary wr-status">{{ $warrantyStatus }}</span>
                                @endif
                            </td>

                            <td class="wr-action-cell support-action-cell">
                                <div class="wr-action-group support-action-group">
                                <button
                                    class="btn btn-outline-primary wr-action-btn support-action-button js-warranty-view"
                                    type="button"
                                    data-warranty-id="{{ $warranty->warranty_id }}"
                                    title="View warranty"
                                    aria-label="View warranty"
                                >
                                    <i class="bi bi-eye" aria-hidden="true"></i><span class="visually-hidden">View</span>
                                </button>
                                @if(!$warranty->archived_at)<button class="btn btn-outline-warning wr-action-btn support-action-button" type="button" data-bs-toggle="modal" data-bs-target="#editWarranty{{ $warranty->warranty_id }}" title="Edit warranty" aria-label="Edit warranty"><i class="bi bi-pencil" aria-hidden="true"></i><span class="visually-hidden">Edit</span></button>
                                <form class="support-action-destructive" method="POST" action="{{ route('support.warranty-records.archive', $warranty) }}" onsubmit="return confirm('Archive this warranty?')">@csrf @method('PATCH')<input type="hidden" name="archive_reason" value="Archived by support staff"><button class="btn btn-outline-danger wr-action-btn support-action-button" type="submit" title="Archive warranty" aria-label="Archive warranty"><i class="bi bi-archive" aria-hidden="true"></i><span class="visually-hidden">Archive</span></button></form>@endif
                                </div>

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

        <x-support-pagination :paginator="$warrantyRecords" />
    </div>
    </div>
    @foreach($warrantyRecords as $warranty)<div class="modal fade" id="editWarranty{{ $warranty->warranty_id }}" tabindex="-1"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('support.warranty-records.update', $warranty) }}">@csrf @method('PUT')<div class="modal-header"><h5 class="modal-title">Edit {{ $warranty->warranty_number }}</h5><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><label class="form-label">Start</label><input class="form-control mb-3" type="date" name="warranty_start" value="{{ $warranty->warranty_start?->toDateString() }}" required><label class="form-label">End</label><input class="form-control mb-3" type="date" name="warranty_end" value="{{ $warranty->warranty_end?->toDateString() }}" required><label class="form-label">Status</label><select class="form-select" name="warranty_status">@foreach(['Active','On Hold','Expired'] as $option)<option @selected($warranty->warranty_status===$option)>{{ $option }}</option>@endforeach</select></div><div class="modal-footer"><button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button><button class="btn support-primary">Save Changes</button></div></form></div></div>@endforeach

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
