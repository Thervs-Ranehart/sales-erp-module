@extends('layouts.app')

@section('content')
    <style>
        .warranty-claims-page { --wc-primary:#5347ce; --wc-border:#e8eaf0; --wc-muted:#697386; --wc-text:#1f2937; }.warranty-claims-page .wc-kpi, .warranty-claims-page .wc-filter-card, .warranty-claims-page .wc-table-card { background:#fff; border:1px solid var(--wc-border); border-radius:16px; box-shadow:0 8px 22px rgba(31,41,55,.055); }.warranty-claims-page .wc-kpi { height:100%; min-height:124px; padding:1.15rem; }.warranty-claims-page .wc-kpi-label { color:var(--wc-muted); font-size:.74rem; font-weight:700; letter-spacing:.03em; text-transform:uppercase; }.warranty-claims-page .wc-kpi-value { color:var(--wc-text); font-size:1.65rem; font-weight:700; letter-spacing:-.04em; line-height:1.1; margin-top:.45rem; }.warranty-claims-page .wc-kpi-icon { align-items:center; border-radius:12px; display:inline-flex; height:42px; justify-content:center; width:42px; }.warranty-claims-page .wc-kpi-badge { border-radius:999px; font-size:.7rem; font-weight:700; margin-top:.7rem; padding:.32rem .55rem; }
        .warranty-claims-page .wc-filter-card { margin-top:1.5rem; padding:1.2rem 1.5rem; }.warranty-claims-page #warrantyClaimsFilters { row-gap:1rem!important; }.warranty-claims-page #warrantyClaimsFilters .form-control, .warranty-claims-page #warrantyClaimsFilters .form-select { border-color:#dfe1ea; font-size:.86rem; min-height:38px; }.warranty-claims-page #warrantyClaimsFilters .form-control:focus, .warranty-claims-page #warrantyClaimsFilters .form-select:focus { border-color:var(--wc-primary); box-shadow:0 0 0 .2rem rgba(83,71,206,.12); }.warranty-claims-page .wc-search-wrap { position:relative; }.warranty-claims-page .wc-search-wrap i { color:#8b93a2; left:.8rem; pointer-events:none; position:absolute; top:50%; transform:translateY(-50%); }.warranty-claims-page .wc-search-wrap input { padding-left:2.35rem; }.warranty-claims-page .wc-filter-actions { display:flex; gap:.75rem; }.warranty-claims-page .wc-filter-actions .btn { align-items:center; display:inline-flex; font-size:.84rem; font-weight:600; height:38px; justify-content:center; padding-inline:1rem; }.warranty-claims-page .wc-apply { background:#5347ce; border-color:#5347ce; box-shadow:0 5px 12px rgba(83,71,206,.2); color:#fff; }.warranty-claims-page .wc-reset { border-color:#d7dae5; color:#4b5563; }.warranty-claims-page .wc-reset:hover, .warranty-claims-page .wc-reset:focus { background:#f7f7fb; border-color:#c8ccda; color:#374151; }
        .warranty-claims-page .wc-table-card { margin-top:1.75rem; overflow:hidden; padding:0; }.warranty-claims-page .wc-table-heading { padding:1.25rem 1.4rem .9rem; }.warranty-claims-page .wc-table-heading h5 { color:var(--wc-text); font-size:1rem; font-weight:700; margin:0; }.warranty-claims-page .wc-table-heading p { color:var(--wc-muted); font-size:.78rem; margin:.25rem 0 0; }.warranty-claims-page .wc-table { margin:0; min-width:1040px; }.warranty-claims-page .wc-table thead th { background:#f8f9fc; border-bottom:1px solid var(--wc-border); color:var(--wc-muted); font-size:.7rem; font-weight:700; letter-spacing:.04em; padding:1rem; text-transform:uppercase; white-space:nowrap; }.warranty-claims-page .wc-table tbody td { border-color:#f0f1f5; color:#374151; font-size:.85rem; padding:1.1rem 1rem; vertical-align:middle; }.warranty-claims-page .wc-table tbody tr { transition:background-color .16s ease; }.warranty-claims-page .wc-table tbody tr:hover { background:#fafaff; }.warranty-claims-page .wc-claim-number { color:var(--wc-primary); font-weight:700; }.warranty-claims-page .wc-table tbody td:nth-child(6) .badge { border-radius:999px; display:inline-flex; font-size:.72rem; font-weight:700; justify-content:center; min-width:104px; padding:.38rem .65rem; }.warranty-claims-page .wc-action-header, .warranty-claims-page .wc-action-cell { min-width:190px; text-align:center; }.warranty-claims-page .wc-action-group { align-items:center; display:flex; gap:.6rem; justify-content:center; white-space:nowrap; }.warranty-claims-page .wc-action-group form { margin:0; }.warranty-claims-page .wc-action-btn { align-items:center; border-radius:8px; display:inline-flex; font-size:.8rem; height:34px; justify-content:center; min-width:34px; padding:.4rem .65rem; transition:background-color .16s ease, border-color .16s ease, box-shadow .16s ease, transform .16s ease; }.warranty-claims-page .wc-action-btn:hover { box-shadow:0 4px 10px rgba(31,41,55,.1); transform:translateY(-1px); }.warranty-claims-page .wc-footer { align-items:center; display:flex; gap:1rem; justify-content:space-between; padding:1.25rem 1.4rem; }.warranty-claims-page .wc-footer-summary { color:var(--wc-muted); font-size:.8rem; }
        @media (max-width:575.98px) { .warranty-claims-page .wc-filter-card { padding:1rem; }.warranty-claims-page #warrantyClaimsFilters .col-6, .warranty-claims-page #warrantyClaimsFilters .col-12 { flex:0 0 100%; max-width:100%; }.warranty-claims-page .wc-filter-actions { width:100%; }.warranty-claims-page .wc-filter-actions .btn { flex:1; }.warranty-claims-page .wc-table-heading, .warranty-claims-page .wc-footer { align-items:flex-start; flex-direction:column; padding:1rem; }.warranty-claims-page .wc-footer nav { width:100%; }.warranty-claims-page .wc-footer nav .pagination { justify-content:flex-start; } }
    </style>
    <div class="warranty-claims-page">
    @php($title = 'Warranty Claims')
    @php($subtitle = 'Review and process warranty claims')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])
    @include('support.warranty-claim-view-modal')
    @include('support.warranty-claim-status-modal')

    <div class="row g-4">
        {{-- Notification host used by JS (keep only one DOM ID) --}}
        <div id="supportWarrantyClaimsNotificationHost" class="mb-3" style="grid-column: 1 / -1;"></div>




        <div class="col-md-3">
            <div class="wc-kpi">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="wc-kpi-label">Pending</div><div class="wc-kpi-value">{{ $pendingClaims ?? 0 }}</div>
                    </div>
                    <div class="wc-kpi-icon" style="background:rgba(245,158,11,.12);">
                        <i class="bi bi-hourglass-split" style="color:#F59E0B; font-size:20px;"></i>
                    </div>
                </div>
                <div><span class="badge bg-warning text-dark wc-kpi-badge">Needs review</span></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="wc-kpi">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="wc-kpi-label">Approved</div><div class="wc-kpi-value">{{ $approvedClaims ?? 0 }}</div>
                    </div>
                    <div class="wc-kpi-icon" style="background:rgba(22,200,199,.12);">
                        <i class="bi bi-check2-circle" style="color:#16C8C7; font-size:20px;"></i>
                    </div>
                </div>
                <div><span class="badge bg-success wc-kpi-badge">Ready to fulfill</span></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="wc-kpi">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="wc-kpi-label">Rejected</div><div class="wc-kpi-value">{{ $rejectedClaims ?? 0 }}</div>
                    </div>
                    <div class="wc-kpi-icon" style="background:rgba(239,68,68,.10);">
                        <i class="bi bi-x-circle" style="color:#EF4444; font-size:20px;"></i>
                    </div>
                </div>
                <div><span class="badge bg-danger wc-kpi-badge">Not approved</span></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="wc-kpi">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="wc-kpi-label">Completed</div><div class="wc-kpi-value">{{ $completedClaims ?? 0 }}</div>
                    </div>
                    <div class="wc-kpi-icon" style="background:rgba(83,71,206,.12);">
                        <i class="bi bi-clipboard-check" style="color:#5347CE; font-size:20px;"></i>
                    </div>
                </div>
                <div><span class="badge bg-primary wc-kpi-badge">Closed loop</span></div>
            </div>
        </div>
    </div>


    {{-- Filters + top actions --}}
    <div class="wc-filter-card">
        <form id="warrantyClaimsFiltersForm" method="GET" action="{{ route('support.warranty-claims') }}">
            <div class="row g-3" id="warrantyClaimsFilters">
                <div class="col-12 col-lg-4">
                    <label class="form-label small text-muted">Search</label>
                    <div class="wc-search-wrap">
                        <i class="bi bi-search" aria-hidden="true"></i>
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
                        <a href="{{ route('support.warranty-claims') }}" class="btn btn-outline-secondary wc-reset" data-support-reset="1">Reset</a>
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
    <div class="wc-table-card">
        <div class="wc-table-heading">
            <h5>Claim Records</h5><p>Review claim status, eligibility, and assigned staff.</p>
        </div>

        <div class="table-responsive after-sales-table-responsive">
            <table id="warrantyClaimsTable" class="table wc-table align-middle mb-0 warranty-claims-table" style="width:100%;">
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
                        <th class="wc-action-header">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warrantyClaims as $claim)
                        <tr>
                            <td><span class="wc-claim-number">WC-{{ $claim->claim_id }}</span></td>
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
                                    <span class="badge bg-primary">{{ $claim->claim_status }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $claim->claim_status ?? '—' }}</span>
                                @endif
                            </td>
                            <td>{{ $claim->supportTicket?->latestAssignment?->employee?->full_name ?? '—' }}</td>
                            <td class="wc-action-cell support-action-cell">
                                <div class="wc-action-group support-action-group warranty-actions">
                                    <button
                                        class="btn btn-outline-primary wc-action-btn support-action-button js-warranty-claim-review"
                                        type="button"
                                        title="View Claim Details"
                                        aria-label="View Claim Details"
                                        data-claim-id="{{ $claim->claim_id }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#warrantyClaimModal">
                                        <i class="bi bi-eye" aria-hidden="true"></i><span class="visually-hidden">View</span>
                                    </button>

                                    <button class="btn btn-outline-warning wc-action-btn support-action-button js-warranty-claim-status" type="button" title="Update claim status" aria-label="Update claim status" data-claim-id="{{ $claim->claim_id }}" data-bs-toggle="modal" data-bs-target="#warrantyClaimStatusModal">
                                        <i class="bi bi-pencil" aria-hidden="true"></i><span class="visually-hidden">Edit</span>
                                    </button>
                                    <form class="support-action-destructive" method="POST" action="{{ route('support.warranty-claims.cancel', $claim) }}" onsubmit="return confirm('Cancel this claim?')">@csrf @method('PATCH')<input type="hidden" name="decision_reason" value="Cancelled by support staff"><button class="btn btn-outline-danger wc-action-btn support-action-button" type="submit" title="Cancel warranty claim" aria-label="Cancel warranty claim"><i class="bi bi-x-circle" aria-hidden="true"></i><span class="visually-hidden">Cancel</span></button></form>
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

        <x-support-pagination :paginator="$warrantyClaims" />
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
                    lower === 'approved' ? ' bg-success' :
                        lower === 'rejected' ? ' bg-danger' :
                            lower === 'completed' ? ' bg-primary' : ' bg-secondary';
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
                        else if (lower === 'completed') cls = 'bg-primary';

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
