@extends('layouts.app')

@section('content')
    @php($title = 'Warranty Claims')
    @php($subtitle = 'Review and process warranty claims')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])
    @include('support.operations-create-modal')

    @include('support.warranty-claim-view-modal')
    @include('support.warranty-claim-status-modal')

    <div class="row g-4">
        {{-- Notification host used by JS (keep only one DOM ID) --}}
        <div id="supportWarrantyClaimsNotificationHost" class="mb-3" style="grid-column: 1 / -1;"></div>




        <div class="col-md-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Pending</div>
                        <div class="display-6 fw-bold">{{ $pendingClaims ?? 0 }}</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(245,158,11,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-hourglass-split" style="color:#F59E0B; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-warning text-dark">Needs review</span></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Approved</div>
                        <div class="display-6 fw-bold">{{ $approvedClaims ?? 0 }}</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(22,200,199,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-check2-circle" style="color:#16C8C7; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-success">Ready to fulfill</span></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Rejected</div>
                        <div class="display-6 fw-bold">{{ $rejectedClaims ?? 0 }}</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(239,68,68,.10); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-x-circle" style="color:#EF4444; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-danger">Not approved</span></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Completed</div>
                        <div class="display-6 fw-bold">{{ $completedClaims ?? 0 }}</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(83,71,206,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-clipboard-check" style="color:#5347CE; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-primary">Closed loop</span></div>
            </div>
        </div>
    </div>


    {{-- Filters + top actions --}}
    <div class="card p-3 mt-4" style="background: rgba(255,255,255,.7); border: 1px solid rgba(0,0,0,.06); box-shadow: none;">
        <form id="warrantyClaimsFiltersForm" method="GET" action="{{ route('support.warranty-claims') }}">
            <div class="row g-3" id="warrantyClaimsFilters">
                <div class="col-12 col-lg-4">
                    <label class="form-label small text-muted">Search</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="background: rgba(83,71,206,.08); border-color: rgba(83,71,206,.2);">
                            <i class="bi bi-search"></i>
                        </span>
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Claim number, warranty number, customer..."
                            aria-label="Search claims"
                            value="{{ $search ?? '' }}"
                        />
                    </div>
                </div>

                <div class="col-6 col-lg-2">
                    <label class="form-label small text-muted">Status</label>
                    <select name="status" class="form-select form-select-sm" aria-label="Status filter">
                        <option value="all" {{ ($status ?? 'all') === 'all' ? 'selected' : '' }}>Status: All</option>
                        <option value="Pending" {{ ($status ?? '') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ ($status ?? '') === 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ ($status ?? '') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="Completed" {{ ($status ?? '') === 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <div class="col-6 col-lg-2">
                    <label class="form-label small text-muted">Customer</label>
                    <select name="customer" class="form-select form-select-sm" aria-label="Customer filter">
                        <option value="" {{ ($customer ?? '') === '' ? 'selected' : '' }}>All customers</option>
                        @foreach($customers as $customerOption)
                            <option value="{{ $customerOption->customer_id }}" {{ (string) ($customer ?? '') === (string) $customerOption->customer_id ? 'selected' : '' }}>{{ $customerOption->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 col-lg-2">
                    <label class="form-label small text-muted">Warranty Record</label>
                    <select name="warranty_id" class="form-select form-select-sm" aria-label="Warranty record filter">
                        <option value="">All warranties</option>
                        @foreach($warranties as $warrantyOption)
                            <option value="{{ $warrantyOption->warranty_id }}" @selected((string) ($warrantyId ?? '') === (string) $warrantyOption->warranty_id)>
                                {{ $warrantyOption->warranty_number ?? ('WR-' . $warrantyOption->warranty_id) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 col-lg-2">
                    <label class="form-label small text-muted">Support Ticket</label>
                    <select name="ticket_id" class="form-select form-select-sm" aria-label="Support ticket filter">
                        <option value="">All tickets</option>
                        @foreach($tickets as $ticketOption)
                            <option value="{{ $ticketOption->ticket_id }}" @selected((string) ($ticketId ?? '') === (string) $ticketOption->ticket_id)>
                                TK-{{ $ticketOption->ticket_id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 col-lg-2">
                    <label class="form-label small text-muted" for="warrantyClaimsFromDate">Submitted from</label>
                    <input id="warrantyClaimsFromDate" type="date" name="from_date" class="form-control form-control-sm" value="{{ $fromDate ?? '' }}">
                </div>

                <div class="col-6 col-lg-2">
                    <label class="form-label small text-muted" for="warrantyClaimsToDate">Submitted to</label>
                    <input id="warrantyClaimsToDate" type="date" name="to_date" class="form-control form-control-sm" value="{{ $toDate ?? '' }}">
                </div>

                <div class="col-12 col-lg-4 d-flex align-items-end justify-content-lg-end">
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="submit" class="btn btn-sm" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
                            <i class="bi bi-funnel me-1"></i> Apply Filters
                        </button>
                        <a href="{{ route('support.warranty-claims') }}" class="btn btn-sm btn-outline-secondary">Reset Filters</a>
                    </div>
                </div>

            </div>
        <style>
            @media (max-width: 575.98px) {
                #warrantyClaimsFilters .col-6,
                #warrantyClaimsFilters .col-12 {
                    flex: 0 0 100%;
                    max-width: 100%;
                }
                #warrantyClaimsFilters .form-control,
                #warrantyClaimsFilters .form-select {
                    width: 100% !important;
                }
                #warrantyClaimsFilters .input-group {
                    width: 100% !important;
                }
                #warrantyClaimsFilters .btn {
                    width: 100%;
                }
            }
        </style>
        </form>
    </div>



    {{-- Claims table --}}
    <div class="card p-4 mt-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold mb-0">Claims</h5>
        </div>

        <div class="table-responsive" style="-webkit-overflow-scrolling: touch;">
            <table id="warrantyClaimsTable" class="table table-hover align-middle mb-0 warranty-claims-table" style="width:100%;">
                <style>
                    @media (max-width: 575.98px) {
                        .warranty-claims-table th, .warranty-claims-table td { white-space: nowrap; }
                        .warranty-actions .btn { padding: .25rem .35rem; }
                        .warranty-actions { gap: .25rem !important; }
                    }
                </style>

                <thead>
                    <tr>
                        <th style="min-width: 160px;">Claim Number</th>
                        <th>Warranty Number</th>
                        <th style="min-width: 220px;">Customer</th>
                        <th>Product</th>
                        <th style="min-width: 160px;">Claim Date</th>
                        <th style="min-width: 160px;">Status</th>
                        <th style="min-width: 220px;">Assigned Staff</th>
                        <th class="text-end" style="min-width: 200px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warrantyClaims as $claim)
                        <tr>
                            <td class="fw-semibold">WC-{{ $claim->claim_id }}</td>
                            <td>{{ $claim->warrantyRecord?->warranty_number ?? ('WR-' . $claim->warranty_id) }}</td>
                            <td>{{ $claim->warrantyRecord?->customer?->full_name ?? '—' }}</td>
                            <td>{{ $claim->warrantyRecord?->product?->product_name ?? '—' }}</td>
                            <td class="text-muted">{{ $claim->claim_date ? $claim->claim_date->format('Y-m-d') : '—' }}</td>
                            <td>
                                @php($cs = strtolower((string)($claim->claim_status ?? '')))
                                @if($cs === 'pending')
                                    <span class="badge bg-warning text-dark">{{ $claim->claim_status }}</span>
                                @elseif($cs === 'approved')
                                    <span class="badge bg-success">{{ $claim->claim_status }}</span>
                                @elseif($cs === 'rejected')
                                    <span class="badge bg-danger">{{ $claim->claim_status }}</span>
                                @elseif($cs === 'completed')
                                    <span class="badge bg-success">{{ $claim->claim_status }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $claim->claim_status ?? '—' }}</span>
                                @endif
                            </td>
                            <td>{{ $claim->supportTicket?->latestAssignment?->employee?->full_name ?? '—' }}</td>
                            <td class="text-end" style="white-space: nowrap;">
                                <div class="d-flex align-items-center justify-content-end flex-nowrap gap-1 warranty-actions">
                                    <button
                                        class="btn btn-sm btn-outline-primary js-warranty-claim-review"
                                        type="button"
                                        title="View Claim Details"
                                        aria-label="View Claim Details"
                                        data-claim-id="{{ $claim->claim_id }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#warrantyClaimModal">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    <button class="btn btn-sm btn-outline-primary js-warranty-claim-status" type="button" title="Update Claim Status" aria-label="Update Claim Status" data-claim-id="{{ $claim->claim_id }}" data-bs-toggle="modal" data-bs-target="#warrantyClaimStatusModal">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    @if(!in_array($claim->claim_status, ['Completed','Cancelled']))<form class="d-inline" method="POST" action="{{ route('support.warranty-claims.cancel', $claim) }}" onsubmit="return confirm('Cancel this claim?')">@csrf @method('PATCH')<input type="hidden" name="decision_reason" value="Cancelled by support staff"><button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i></button></form>@endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No warranty claims found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mt-4">
            <div class="text-muted small">Showing results.</div>
            <nav aria-label="Claims pagination">
                {{ $warrantyClaims->links('pagination::bootstrap-5') }}
            </nav>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        const csrf = '{{ csrf_token() }}';

        document.querySelectorAll('[title="View Claim Details"], [title="Update Claim Status"]').forEach((button) => {
            bootstrap.Tooltip.getOrCreateInstance(button);
        });

        async function loadWarrantyClaimIntoModal(claimId) {
            const res = await fetch(`{{ url('/support/warranty-claims') }}/${claimId}/show`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {}),
                }
            });
            if (!res.ok) throw new Error('Failed to load warranty claim');

            const data = await res.json();
            const c = data.claim || {};
            const w = data.warranty || {};
            const assigned = data.assignedEmployee || {};

            const subtitle = document.getElementById('warrantyClaimModalSubtitle');
            if (subtitle) subtitle.textContent = `WC-${c.claim_id ?? claimId} • ${w.customer?.name || '—'}`;

            const statusEl = document.getElementById('warrantyClaimModalStatus');
            if (statusEl) {
                statusEl.textContent = c.claim_status || '—';
                const lower = (c.claim_status || '').toString().toLowerCase();
                statusEl.className = 'badge';
                statusEl.className += lower === 'pending' ? ' bg-warning text-dark' :
                    lower === 'approved' ? ' bg-primary' :
                        lower === 'rejected' ? ' bg-danger' :
                            lower === 'completed' ? ' bg-success' : ' bg-secondary';
            }

            const claimReasonHeading = document.getElementById('warrantyClaimModalReasonHeading');
            if (claimReasonHeading) claimReasonHeading.textContent = c.claim_reason || '—';

            const warrantyNumberEl = document.getElementById('warrantyClaimModalWarrantyNumber');
            if (warrantyNumberEl) warrantyNumberEl.textContent = w.warranty_number || '—';

            const claimNumberEl = document.getElementById('warrantyClaimModalNumber');
            if (claimNumberEl) claimNumberEl.textContent = c.claim_id ? `WC-${c.claim_id}` : '—';

            const ticketNumberEl = document.getElementById('warrantyClaimModalTicketNumber');
            if (ticketNumberEl) ticketNumberEl.textContent = c.ticket_number || '—';

            const customerEl = document.getElementById('warrantyClaimModalCustomer');
            if (customerEl) customerEl.textContent = w.customer?.name || '—';

            const claimDateEl = document.getElementById('warrantyClaimModalClaimDate');
            if (claimDateEl) claimDateEl.textContent = c.claim_date || '—';

            const approvedDateEl = document.getElementById('warrantyClaimModalApprovedDate');
            if (approvedDateEl) approvedDateEl.textContent = c.approved_date || '—';

            const productEl = document.getElementById('warrantyClaimModalProduct');
            if (productEl) productEl.textContent = w.product?.product_name || '—';

            const assignedEl = document.getElementById('warrantyClaimModalAssignedStaff');
            if (assignedEl) assignedEl.textContent = assigned.name || '—';

            const departmentEl = document.getElementById('warrantyClaimModalAssignedDepartment');
            if (departmentEl) departmentEl.textContent = assigned.department || '—';
        }

        document.querySelectorAll('button.js-warranty-claim-review').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const claimId = e.currentTarget.getAttribute('data-claim-id');
                if (!claimId) return;

                try {
                    await loadWarrantyClaimIntoModal(claimId);
                } catch (err) {
                    console.error(err);
                }
            });
        });

        document.querySelectorAll('.js-warranty-claim-status').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const claimId = e.currentTarget.getAttribute('data-claim-id');
                if (!claimId) return;

                const row = e.currentTarget.closest('tr');

                const hiddenId = document.getElementById('warrantyClaimStatusTicketId');
                if (hiddenId) hiddenId.value = claimId;

                const err = document.getElementById('warrantyClaimStatusError');
                if (err) {
                    err.style.display = 'none';
                    err.textContent = '';
                }

                const statusBadge = row?.querySelector('td:nth-child(6) .badge');
                const currentStatus = statusBadge?.textContent?.trim();

                const select = document.getElementById('warrantyClaimStatusSelect');
                if (select && currentStatus) {
                    // Only set if option exists
                    if (Array.from(select.options).some(o => o.value === currentStatus)) {
                        select.value = currentStatus;
                    }
                }
            });
        });


        const statusSaveBtn = document.getElementById('warrantyClaimStatusSaveBtn');
        const statusModalEl = document.getElementById('warrantyClaimStatusModal');

        if (statusSaveBtn && statusModalEl) {
            statusSaveBtn.addEventListener('click', async () => {
                const claimId = document.getElementById('warrantyClaimStatusTicketId')?.value;
                const status = document.getElementById('warrantyClaimStatusSelect')?.value;
                if (!claimId || !status) return;

                try {
                    statusSaveBtn.disabled = true;
                    statusSaveBtn.setAttribute('aria-busy', 'true');
                    const res = await fetch(`{{ url('/support/warranty-claims') }}/${claimId}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {}),
                        },
                        body: JSON.stringify({ claim_status: status }),
                    });

                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) {
                        const errText = data.errors?.claim_status?.[0] || 'Failed to update status.';
                        const box = document.getElementById('warrantyClaimStatusError');
                        if (box) {
                            box.textContent = errText;
                            box.style.display = 'block';
                        }
                        return;
                    }

                    // Update badge immediately (status is column 6) - update only the selected row
                    const claimsTable = document.querySelector('table');
                    let statusTd = null;

                    if (claimsTable) {
                        // Prefer the table row whose claim-number cell matches the selected claim.
                        const wcCell = Array.from(claimsTable.querySelectorAll('td.fw-semibold')).find(td => {
                            const wcText = (td.textContent || '').trim();
                            const foundId = wcText.replace(/^WC-/, '').trim();
                            return String(foundId) === String(claimId);
                        });

                        statusTd = wcCell?.closest('tr')?.querySelector('td:nth-child(6)');
                    }

                    if (statusTd) {
                        const lower = (data.status || '').toString().toLowerCase();
                        let cls = 'bg-secondary';
                        if (lower === 'pending') cls = 'bg-warning text-dark';
                        else if (lower === 'approved') cls = 'bg-success';
                        else if (lower === 'rejected') cls = 'bg-danger';
                        else if (lower === 'completed') cls = 'bg-success';

                        statusTd.innerHTML = `<span class="badge ${cls}">${data.status}</span>`;
                    }



                    const modalInstance = bootstrap.Modal.getInstance(statusModalEl);
                    if (modalInstance) modalInstance.hide();

                    const host = document.getElementById('supportWarrantyClaimsNotificationHost');
                    if (host) {
                        host.innerHTML = `<div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                            ${data.message || 'Status updated'}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                    }

                } catch (err) {
                    const box = document.getElementById('warrantyClaimStatusError');
                    if (box) {
                        box.textContent = 'Unable to update claim status.';
                        box.style.display = 'block';
                    }
                } finally {
                    statusSaveBtn.disabled = false;
                    statusSaveBtn.removeAttribute('aria-busy');
                }
            });
        }
    })();
</script>

@endpush










