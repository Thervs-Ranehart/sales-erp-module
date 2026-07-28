@extends('layouts.app')

@section('content')
    @php($title = 'Support Tickets')
    @php($subtitle = 'Manage support tickets and case assignments')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])
    @include('support.tickets-details-modal')
    @include('support.tickets-assign-modal')
    @include('support.ticket-status-modal')

    <style>
        .support-tickets-page { --tt-primary:#5347ce; --tt-border:#e7eaf0; --tt-muted:#6b7280; }
        .support-tickets-page .tt-card { background:#fff; border:1px solid var(--tt-border); border-radius:16px; box-shadow:0 8px 22px rgba(31,41,55,.055); padding:1.35rem; }
        .support-tickets-page .tt-filter-card { background:#fafbfe; border:1px solid var(--tt-border); border-radius:12px; margin-bottom:1.35rem; padding:1rem 1.1rem; }
        .support-tickets-page .tt-filter-grid { align-items:end; display:grid; gap:1rem; grid-template-columns:minmax(230px,1.8fr) repeat(4,minmax(115px,.8fr)) minmax(125px,.85fr) minmax(125px,.85fr) auto; }
        .support-tickets-page .tt-filter-grid > :nth-child(1) { order:1; }.support-tickets-page .tt-filter-grid > :nth-child(2) { order:2; }.support-tickets-page .tt-filter-grid > :nth-child(3) { order:3; }.support-tickets-page .tt-filter-grid > :nth-child(4) { order:4; }.support-tickets-page .tt-assigned-employee { order:5; }.support-tickets-page .tt-from-date { order:6; }.support-tickets-page .tt-to-date { order:7; }.support-tickets-page .tt-filter-actions { order:8; }
        .support-tickets-page .tt-filter-label { color:var(--tt-muted); font-size:.75rem; font-weight:600; margin-bottom:.4rem; }
        .support-tickets-page .tt-filter-grid .form-control, .support-tickets-page .tt-filter-grid .form-select, .support-tickets-page .tt-filter-grid .input-group-text, .support-tickets-page .tt-filter-actions .btn { height:38px; }
        .support-tickets-page .tt-filter-grid .form-control, .support-tickets-page .tt-filter-grid .form-select { border-color:#dfe3eb; font-size:.84rem; }
        .support-tickets-page .tt-filter-grid .form-control:focus, .support-tickets-page .tt-filter-grid .form-select:focus { border-color:var(--tt-primary); box-shadow:0 0 0 .2rem rgba(83,71,206,.12); }
        .support-tickets-page .tt-search .input-group-text { background:#fff; border-color:#dfe3eb; color:#878e9c; }
        .support-tickets-page .tt-filter-actions { display:flex; gap:.5rem; white-space:nowrap; }.support-tickets-page .tt-filter-actions .btn { align-items:center; display:inline-flex; font-size:.83rem; font-weight:600; justify-content:center; padding-inline:.9rem; }
        .support-tickets-page .tt-apply { background:var(--tt-primary); border-color:var(--tt-primary); box-shadow:0 4px 10px rgba(83,71,206,.18); color:#fff; }.support-tickets-page .tt-reset { border-color:#d4d8e1; color:#4b5563; }
        .support-tickets-page .tt-table-wrap { border:1px solid var(--tt-border); border-radius:13px; overflow:hidden; }.support-tickets-page .tickets-table { margin:0; min-width:1080px; }
        .support-tickets-page .tickets-table thead th { background:#f8f9fc; border-bottom:1px solid var(--tt-border); color:var(--tt-muted); font-size:.71rem; font-weight:700; letter-spacing:.035em; padding:.9rem 1.1rem; text-transform:uppercase; white-space:nowrap; }
        .support-tickets-page .tickets-table tbody td { border-color:#eff1f5; color:#374151; font-size:.84rem; padding:.82rem 1.1rem; vertical-align:middle; }.support-tickets-page .tickets-table tbody tr { transition:background-color .16s ease; }.support-tickets-page .tickets-table tbody tr:hover { background:#fafaff; }
        .support-tickets-page .tt-number { color:var(--tt-primary); font-weight:700; }
        .support-tickets-page .tt-badge { align-items:center; border-radius:999px; display:inline-flex; font-size:.72rem; font-weight:700; height:26px; justify-content:center; min-width:80px; padding:0 .65rem; }
        .support-tickets-page .tickets-table td:nth-child(4) .badge, .support-tickets-page .tickets-table td:nth-child(5) .badge { align-items:center; border-radius:999px; display:inline-flex; font-size:.72rem!important; font-weight:700; height:26px; justify-content:center; min-width:80px; padding:0 .65rem!important; }
        .support-tickets-page .tt-action-head, .support-tickets-page .tt-action-cell { min-width:180px; text-align:center; }.support-tickets-page .tt-action-group { align-items:center; display:flex; gap:.5rem; justify-content:center; white-space:nowrap; }.support-tickets-page .tt-action-group form { margin:0; order:4; }.support-tickets-page .tt-edit-btn { order:2; }.support-tickets-page .js-ticket-assign { order:3; }
        .support-tickets-page .tt-action-btn, .support-tickets-page .tt-action-group form .btn { align-items:center; border-radius:8px; display:inline-flex; font-size:.8rem; height:34px; justify-content:center; min-width:34px; padding:.4rem .65rem; }.support-tickets-page .tt-action-btn:hover, .support-tickets-page .tt-action-group form .btn:hover { box-shadow:0 4px 10px rgba(31,41,55,.1); transform:translateY(-1px); }
        @media (max-width:1599.98px) { .support-tickets-page .tt-filter-grid { grid-template-columns:minmax(230px,1.6fr) repeat(3,minmax(135px,1fr)); } }
        @media (max-width:991.98px) { .support-tickets-page .tt-filter-grid { grid-template-columns:minmax(220px,1fr) minmax(160px,1fr); }.support-tickets-page .tt-filter-actions { grid-column:1 / -1; justify-content:flex-end; } }
        @media (max-width:575.98px) { .support-tickets-page .tt-card, .support-tickets-page .tt-filter-card { padding:1rem; }.support-tickets-page .tt-filter-grid { grid-template-columns:1fr; }.support-tickets-page .tt-filter-actions { grid-column:auto; }.support-tickets-page .tt-filter-actions .btn { flex:1; } }
        .ticket-edit-modal .modal-content { border:0; border-radius:16px; box-shadow:0 18px 45px rgba(31,41,55,.16); overflow:hidden; }.ticket-edit-modal .modal-header { align-items:flex-start; background:#fafbfe; border-bottom:1px solid #e8eaf0; padding:1.25rem 1.5rem; }.ticket-edit-modal .modal-title { color:#1f2937; font-size:1.05rem; font-weight:700; }.ticket-edit-modal .modal-body { padding:1.5rem; }.ticket-edit-modal .form-label { color:#596273; font-size:.78rem; font-weight:600; margin-bottom:.4rem; }.ticket-edit-modal .form-control, .ticket-edit-modal .form-select { border-color:#dfe3eb; font-size:.86rem; min-height:38px; }.ticket-edit-modal textarea.form-control { min-height:130px; padding-top:.65rem; }.ticket-edit-modal .modal-footer { border-top:1px solid #e8eaf0; padding:1rem 1.5rem; }.ticket-edit-modal .modal-footer .btn { align-items:center; display:inline-flex; font-size:.84rem; font-weight:600; height:38px; justify-content:center; padding-inline:1rem; }
    </style>

    <div class="support-tickets-page">
        <div class="tt-card" id="ticketsPageCard">
            <div id="supportTicketsNotificationHost" class="mb-3"></div>

        {{-- Search + Filters --}}
        <div class="tt-filter-card">
            <form id="ticketFiltersForm" method="GET" action="{{ route('support.tickets') }}">
                <div class="tt-filter-grid" id="ticketsFilters">
                    <div>
                        <label class="form-label tt-filter-label">Search</label>
                        <div class="input-group tt-search">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control" placeholder="Ticket number, customer, subject..." aria-label="Search tickets" value="{{ $search ?? '' }}" />
                        </div>
                    </div>

                    <div>
                        <label class="form-label tt-filter-label">Status</label>
                        <select class="form-select" aria-label="Status filter" name="status">
                            <option value="all" {{ ($status ?? '') === '' || ($status ?? '') === 'all' ? 'selected' : '' }}>Status: All</option>
                            <option value="Open" {{ ($status ?? '') === 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="Pending" {{ ($status ?? '') === 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="In Progress" {{ ($status ?? '') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Resolved" {{ ($status ?? '') === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="Closed" {{ ($status ?? '') === 'Closed' ? 'selected' : '' }}>Closed</option>
                            <option value="Escalated" {{ ($status ?? '') === 'Escalated' ? 'selected' : '' }}>Escalated</option>
                        </select>
                    </div>

                <div>
                    <label class="form-label tt-filter-label">Priority</label>
                    <select class="form-select" aria-label="Priority filter" name="priority">
                        <option value="all" {{ ($priority ?? '') === '' || ($priority ?? '') === 'all' ? 'selected' : '' }}>Priority: All</option>
                        <option value="High" {{ ($priority ?? '') === 'High' ? 'selected' : '' }}>High</option>
                        <option value="Medium" {{ ($priority ?? '') === 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="Low" {{ ($priority ?? '') === 'Low' ? 'selected' : '' }}>Low</option>
                    </select>

                </div>

                    <div>
                    <label class="form-label tt-filter-label">Customer</label>
                    <select class="form-select" aria-label="Customer filter" name="customer">
                        <option value="all" {{ ($customer ?? 'all') === 'all' ? 'selected' : '' }}>All Customers</option>
                        @foreach($customers as $customerOption)
                            <option value="{{ $customerOption->customer_id }}" {{ (string) ($customer ?? 'all') === (string) $customerOption->customer_id ? 'selected' : '' }}>{{ $customerOption->name }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="tt-from-date">
                    <label class="form-label tt-filter-label">From Date</label>
                    <input type="date" class="form-control" aria-label="Start date" name="from_date" value="{{ $fromDate ?? '' }}" />
                </div>
                <div class="tt-to-date">
                    <label class="form-label tt-filter-label">To Date</label>
                    <input type="date" class="form-control" aria-label="End date" name="to_date" value="{{ $toDate ?? '' }}" />
                </div>

                    <div class="tt-assigned-employee">
                        <label class="form-label tt-filter-label">Assigned Employee</label>
                        <select class="form-select" aria-label="Assigned employee filter" name="assigned_employee">
                            <option value="">All employees</option>
                            @foreach($employees as $employeeOption)
                                <option value="{{ $employeeOption->employee_id }}" {{ (string) ($assignedEmployee ?? '') === (string) $employeeOption->employee_id ? 'selected' : '' }}>{{ $employeeOption->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="tt-filter-actions">
                        <button class="btn tt-apply" type="submit">
                            <i class="bi bi-funnel me-1"></i> Apply Filters
                        </button>
                        <a class="btn btn-outline-secondary tt-reset" data-support-reset="1" href="{{ route('support.tickets') }}">Reset</a>
                    </div>
                </div>
                </form>
        </div>


        {{-- Main table --}}
                <div class="table-responsive after-sales-table-responsive tt-table-wrap">
            <table id="supportTicketsTable" class="table table-hover align-middle tickets-table">
                <colgroup>
                    <col style="width: 1%;">
                    <col style="width: 18%;">
                    <col style="width: 26%;">
                    <col style="width: 12%;">
                    <col style="width: 14%;">
                    <col style="width: 18%;">
                    <col style="width: 9%;">
                    <col style="width: 10%;">
                </colgroup>


                <thead>
                    <tr>
                        <th>Ticket Number</th>
                        <th>Customer</th>
                        <th style="width: 38%;">Subject</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Assigned Employee</th>
                        <th>Due Date</th>
                        <th class="tt-action-head">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr data-ticket-id="{{ $ticket->ticket_id }}">
                            <td class="tt-number">{{ 'TK-' . $ticket->ticket_id }}</td>
                            <td>{{ $ticket->customer?->full_name ?? '—' }}</td>
                            <td style="max-width: 260px;">
                                <span class="d-block text-truncate" title="{{ $ticket->subject ?? '—' }}" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $ticket->subject ?? '—' }}</span>
                            </td>
                            <td>
                                @php($priorityBadge = $ticket->priority)
                                @if(strtolower((string)$priorityBadge) === 'high')
                                    <span class="badge bg-danger tt-badge">{{ $ticket->priority }}</span>
                                @elseif(strtolower((string)$priorityBadge) === 'medium')
                                    <span class="badge bg-warning text-dark tt-badge">{{ $ticket->priority }}</span>
                                @elseif(strtolower((string)$priorityBadge) === 'low')
                                    <span class="badge bg-success tt-badge">{{ $ticket->priority }}</span>
                                @else
                                    <span class="badge bg-secondary px-2 py-1 fs-6">{{ $ticket->priority ?? '—' }}</span>
                                @endif
                            </td>
                            <td>
                                @php($statusBadge = $ticket->status)
                                @if(strtolower((string)$statusBadge) === 'open')
                                    <span class="badge bg-secondary tt-badge">{{ $ticket->status }}</span>
                                @elseif(strtolower((string)$statusBadge) === 'pending')
                                    <span class="badge bg-warning text-dark tt-badge">{{ $ticket->status }}</span>
                                @elseif(strtolower((string)$statusBadge) === 'in progress')
                                    <span class="badge bg-primary tt-badge">{{ $ticket->status }}</span>
                                @elseif(strtolower((string)$statusBadge) === 'resolved')
                                    <span class="badge bg-success tt-badge">{{ $ticket->status }}</span>

                                @elseif(strtolower((string)$statusBadge) === 'closed')
                                    <span class="badge bg-secondary tt-badge">{{ $ticket->status }}</span>

                                @elseif(strtolower((string)$statusBadge) === 'escalated')
                                    <span class="badge bg-danger tt-badge">{{ $ticket->status }}</span>
                                @else
                                    <span class="badge bg-secondary px-2 py-1 fs-6">{{ $ticket->status ?? '—' }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $ticket->latestAssignment?->employee?->full_name ?? '—' }}
                            </td>
                            <td class="text-muted">
                                {{ optional($ticket->resolution_due_at ?? $ticket->due_date)->format('Y-m-d H:i') }}
                                @if($ticket->isSlaBreached())<span class="badge bg-danger d-block mt-1">SLA Breached · L{{ $ticket->escalation_level }}</span>@endif
                                @if($ticket->archived_at)<span class="badge bg-secondary d-block mt-1">Archived</span>@endif
                            </td>
                            <td class="tt-action-cell support-action-cell">
                                <div class="tt-action-group support-action-group">
                                    <button class="btn btn-outline-primary tt-action-btn support-action-button js-ticket-view" type="button" data-ticket-id="{{ $ticket->ticket_id }}" aria-label="View ticket" title="View ticket" data-bs-toggle="modal" data-bs-target="#ticketDetailsModal">
                                        <i class="bi bi-eye" aria-hidden="true"></i><span class="visually-hidden">View</span>
                                    </button>

                                    @if($ticket->archived_at)
                                        <form method="POST" action="{{ route('support.tickets.restore', $ticket) }}">@csrf @method('PATCH')<button class="btn btn-outline-success support-action-button" type="submit" title="Restore ticket" aria-label="Restore ticket"><i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i><span class="visually-hidden">Restore</span></button></form>
                                    @else
                                        <button class="btn btn-outline-success tt-action-btn support-action-button js-ticket-assign" type="button" data-ticket-id="{{ $ticket->ticket_id }}" aria-label="Assign or reassign employee" title="Assign or reassign employee" data-bs-toggle="modal" data-bs-target="#ticketsAssignModal">
                                            <i class="bi bi-diagram-3" aria-hidden="true"></i><span class="visually-hidden">Assign</span>
                                        </button>
                                        <button class="btn btn-outline-warning tt-action-btn tt-edit-btn support-action-button" type="button" data-bs-toggle="modal" data-bs-target="#editTicket{{ $ticket->ticket_id }}" title="Edit ticket details" aria-label="Edit ticket details"><i class="bi bi-pencil" aria-hidden="true"></i><span class="visually-hidden">Edit</span></button>
                                        <form class="support-action-destructive" method="POST" action="{{ route('support.tickets.archive', $ticket) }}" onsubmit="return confirm('Archive this ticket while retaining its history?')">@csrf @method('PATCH')<input type="hidden" name="archive_reason" value="Archived by support staff"><button class="btn btn-outline-danger support-action-button" type="submit" title="Archive ticket" aria-label="Archive ticket"><i class="bi bi-archive" aria-hidden="true"></i><span class="visually-hidden">Archive</span></button></form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">No tickets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-support-pagination :paginator="$tickets" />
    </div>
    </div>

@foreach($tickets as $ticket)
<div class="modal fade ticket-edit-modal" id="editTicket{{ $ticket->ticket_id }}" tabindex="-1"><div class="modal-dialog modal-lg"><form class="modal-content" method="POST" action="{{ route('support.tickets.update', $ticket) }}">@csrf @method('PUT')
<div class="modal-header"><div><h5 class="modal-title">Edit Ticket — TK-{{ $ticket->ticket_id }}</h5><div class="text-muted small mt-1">Update the ticket details below.</div></div><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="row g-3">
<div class="col-md-6"><label class="form-label">Type</label><input class="form-control" name="ticket_type" value="{{ $ticket->ticket_type }}" required></div><div class="col-md-3"><label class="form-label">Priority</label><select class="form-select" name="priority">@foreach(['High','Medium','Low'] as $option)<option @selected($ticket->priority===$option)>{{ $option }}</option>@endforeach</select></div><div class="col-md-3"><label class="form-label">Queue</label><select class="form-select" name="department">@foreach(['After-Sales Support','Technical Support','Warranty','Field Service'] as $option)<option @selected($ticket->department===$option)>{{ $option }}</option>@endforeach</select></div>
<div class="col-12"><label class="form-label">Subject</label><input class="form-control" name="subject" value="{{ $ticket->subject }}" required></div><div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="5" required>{{ $ticket->description }}</textarea></div>
</div></div><div class="modal-footer justify-content-end gap-2"><button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button><button class="btn support-primary">Save Changes</button></div></form></div></div>
<div class="modal fade" id="ticketFiles{{ $ticket->ticket_id }}" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Attachments · TK-{{ $ticket->ticket_id }}</h5><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">
<form method="POST" action="{{ route('support.tickets.attachments.store', $ticket) }}" enctype="multipart/form-data" class="mb-3">@csrf <label class="form-label">Add evidence or supporting document</label><div class="input-group"><input class="form-control" type="file" name="attachment" required><button class="btn support-primary">Upload</button></div><div class="form-text">PDF, image, Office document, or text file up to 10 MB.</div></form>
<div class="list-group">@forelse($ticket->attachments as $attachment)<div class="list-group-item d-flex justify-content-between align-items-center"><span><i class="bi bi-file-earmark me-2"></i>{{ $attachment->original_name }}</span><form method="POST" action="{{ route('support.attachments.destroy', $attachment) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></div>@empty<div class="text-muted">No attachments yet.</div>@endforelse</div>
</div></div></div></div>
@endforeach
@endsection

@push('scripts')
<script>
    (function () {
        const csrf = '{{ csrf_token() }}';

        function notify(type, message) {
            // Bootstrap 5 alert
            const host = document.getElementById('supportTicketsNotificationHost');
            if (!host) return alert(message);
            const cls = type === 'success' ? 'alert-success' : (type === 'error' ? 'alert-danger' : 'alert-info');
            host.innerHTML = `<div class="alert ${cls} alert-dismissible fade show mb-0" role="alert">${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
        }


        function badgeHtmlForStatus(status) {
            if (status === null || status === undefined) status = '—';
            const s = (status || '').toString();
            const lower = s.toLowerCase();

            let cls = 'bg-secondary';
            if (lower === 'open') cls = 'bg-secondary';
            else if (lower === 'pending') cls = 'bg-warning text-dark';
            else if (lower === 'in progress') cls = 'bg-primary';
            else if (lower === 'resolved') cls = 'bg-success';
            else if (lower === 'closed') cls = 'bg-dark';
            else if (lower === 'escalated') cls = 'bg-danger';

            return `<span class="badge ${cls} px-2 py-1 fs-6">${s || '—'}</span>`;
        }

        // VIEW
        document.querySelectorAll('.js-ticket-view').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const ticketId = e.currentTarget.getAttribute('data-ticket-id');
                if (!ticketId) return;

                document.getElementById('ticketDetailsLoading')?.classList.remove('d-none');
                document.getElementById('ticketDetailsContent')?.classList.add('d-none');

                try {
                    const res = await fetch(`{{ url('/support/tickets') }}/${ticketId}/show`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            ...(csrf ? {'X-CSRF-TOKEN': csrf} : {})
                        }
                    });
                    if (!res.ok) throw new Error('Failed to load ticket');
                    const data = await res.json();

                    const t = data.ticket || {};

                    // Fill modal (no page reload)
                    document.getElementById('ticketDetailsNumber').textContent = `TK-${t.ticket_id ?? ticketId}`;
                    document.getElementById('ticketDetailsSubtitle').textContent = `TK-${t.ticket_id ?? ticketId} • ${t.customer?.name || '—'}`;
                    document.getElementById('ticketDetailsSubject').textContent = t.subject || '—';
                    document.getElementById('ticketDetailsDescription').textContent = t.description || '—';
                    document.getElementById('ticketDetailsCustomer').textContent = t.customer?.name || '—';
                    document.getElementById('ticketDetailsCustomerContact').textContent = t.customer?.email || '—';
                    document.getElementById('ticketDetailsProduct').textContent = t.product?.product_name || '—';
                    document.getElementById('ticketDetailsOrder').textContent = t.order_number || '—';
                    document.getElementById('ticketDetailsPriority').textContent = t.priority || '—';
                    document.getElementById('ticketDetailsCreatedAt').textContent = t.created_at || '—';
                    document.getElementById('ticketDetailsResolvedAt').textContent = t.resolved_at || '—';
                    document.getElementById('ticketDetailsClosedAt').textContent = t.closed_at || '—';
                    document.getElementById('ticketDetailsAssignedEmployee').textContent = data.assignedEmployee?.name || '—';
                    document.getElementById('ticketDetailsAssignedDepartment').textContent = data.assignedEmployee?.department || '—';
                    document.getElementById('ticketDetailsAssignedAt').textContent = data.assignedEmployee?.assigned_at || '—';
                    document.getElementById('ticketDetailsAssignmentHistory').innerHTML = (data.assignmentHistory || []).length
                        ? data.assignmentHistory.map(a => `<div>${a.name || '—'}${a.department ? ` · ${a.department}` : ''} · ${a.assigned_at || '—'} · ${a.status || '—'}</div>`).join('')
                        : 'No assignment history is available.';

                    document.getElementById('ticketDetailsAssignedAtTimeline').textContent = data.assignedEmployee?.assigned_at || '—';

                    // Update status badge
                    const ticketDetailsStatusEl = document.getElementById('ticketDetailsStatus');
                    if (ticketDetailsStatusEl) {
                        const lower = (t.status || '').toLowerCase();
                        let badgeClass = 'badge bg-secondary';
                        if (lower === 'open') badgeClass = 'badge bg-secondary';
                        else if (lower === 'pending') badgeClass = 'badge bg-warning text-dark';
                        else if (lower === 'in progress') badgeClass = 'badge bg-primary';
                        else if (lower === 'resolved') badgeClass = 'badge bg-success';
                        else if (lower === 'closed') badgeClass = 'badge bg-dark';
                        else if (lower === 'escalated') badgeClass = 'badge bg-danger';
                        ticketDetailsStatusEl.className = badgeClass;
                        ticketDetailsStatusEl.textContent = t.status || '—';
                    }

                    const ticketDetailsPriorityEl = document.getElementById('ticketDetailsPriority');
                    if (ticketDetailsPriorityEl) {
                        const priority = (t.priority || '').toLowerCase();
                        let priorityClass = 'badge td-badge bg-secondary';
                        if (priority === 'high') priorityClass = 'badge td-badge bg-danger';
                        else if (priority === 'medium') priorityClass = 'badge td-badge bg-warning text-dark';
                        else if (priority === 'low') priorityClass = 'badge td-badge bg-success';
                        ticketDetailsPriorityEl.className = priorityClass;
                    }

                    const ticketDetailsStatusAction = document.getElementById('ticketDetailsStatusAction');
                    if (ticketDetailsStatusAction) {
                        ticketDetailsStatusAction.dataset.ticketId = t.ticket_id || ticketId;
                        ticketDetailsStatusAction.dataset.currentStatus = t.status || '';
                    }

                    document.getElementById('ticketDetailsLoading')?.classList.add('d-none');
                    document.getElementById('ticketDetailsContent')?.classList.remove('d-none');
                } catch (err) {
                    document.getElementById('ticketDetailsLoading').textContent = 'Unable to load ticket details.';
                    notify('error', 'Unable to load ticket details.');
                }
            });
        });

        @if($ticketId)
            document.querySelector('.js-ticket-view[data-ticket-id="{{ $ticketId }}"]')?.click();
        @endif

        // ASSIGN
        const assignModal = document.getElementById('ticketsAssignModal');
        let currentAssignTicketId = null;

        document.querySelectorAll('.js-ticket-assign').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const ticketId = e.currentTarget.getAttribute('data-ticket-id');
                currentAssignTicketId = ticketId;
                if (!ticketId) return;

                document.getElementById('ticketsAssignLoading')?.classList.remove('d-none');
                document.getElementById('ticketsAssignContent')?.classList.add('d-none');
                document.getElementById('ticketsAssignError').style.display = 'none';

                try {
                    const res = await fetch(`{{ url('/support/tickets') }}/${ticketId}/assign`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            ...(csrf ? {'X-CSRF-TOKEN': csrf} : {})
                        }
                    });
                    if (!res.ok) throw new Error('Failed to load assign data');
                    const data = await res.json();


                    document.getElementById('ticketsAssignTicketNumber').value = `TK-${data.ticket?.ticket_id ?? ticketId}`;
                    document.getElementById('ticketsAssignModalSubtitle').textContent = `Ticket TK-${data.ticket?.ticket_id ?? ticketId}`;
                    document.getElementById('ticketsAssignCustomer').textContent = data.ticket?.customer || '—';
                    document.getElementById('ticketsAssignProduct').textContent = data.ticket?.product_name || '—';

                    const employeeSelect = document.getElementById('ticketsAssignEmployee');
                    const departmentSelect = document.getElementById('ticketsAssignDepartment');
                    const employees = data.employees || [];
                    const renderEmployees = () => {
                        const department = departmentSelect.value;
                        employeeSelect.innerHTML = '';
                        employees.filter(employee => !department || employee.department === department).forEach(employee => {
                            const option = new Option(`${employee.name}${employee.department ? ` — ${employee.department}` : ''} · ${employee.active_ticket_count || 0} active`, employee.employee_id, false, String(employee.employee_id) === String(data.currentEmployeeId ?? ''));
                            employeeSelect.add(option);
                        });
                    };
                    departmentSelect.innerHTML = '<option value="">All departments</option>';
                    [...new Set(employees.map(employee => employee.department).filter(Boolean))].forEach(department => departmentSelect.add(new Option(department, department)));
                    departmentSelect.onchange = renderEmployees;
                    renderEmployees();
                    document.getElementById('ticketsAssignHistory').innerHTML = (data.assignmentHistory || []).length
                        ? data.assignmentHistory.map(a => `<div>${a.name || '—'}${a.department ? ` · ${a.department}` : ''} · ${a.assigned_at || '—'} · ${a.status || '—'}</div>`).join('')
                        : 'No assignment history is available.';
                    document.getElementById('ticketsAssignLoading')?.classList.add('d-none');
                    document.getElementById('ticketsAssignContent')?.classList.remove('d-none');

                } catch (err) {
                    document.getElementById('ticketsAssignLoading').textContent = 'Unable to load assignment options.';
                    notify('error', 'Unable to load assignment options.');
                }
            });
        });

        // Ensure assign modal save handler uses the stable button
        const ticketsAssignSaveBtn = document.getElementById('ticketsAssignSaveBtn');

        if (ticketsAssignSaveBtn) {
            ticketsAssignSaveBtn.addEventListener('click', async () => {
                if (!currentAssignTicketId) return;

                const employeeSelect = document.getElementById('ticketsAssignEmployee');
                const employeeId = employeeSelect?.value;

                // Basic client-side guard
                if (!employeeId) {
                    notify('error', 'Please select an employee.');
                    return;
                }

                try {
                    ticketsAssignSaveBtn.disabled = true;
                    ticketsAssignSaveBtn.setAttribute('aria-busy', 'true');
                    const res = await fetch(`{{ url('/support/tickets') }}/${currentAssignTicketId}/assign`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            ...(csrf ? {'X-CSRF-TOKEN': csrf} : {})
                        },
                        body: JSON.stringify({ employee_id: employeeId, assignment_reason: document.getElementById('ticketsAssignReason')?.value || null })
                    });

                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) {
                        const error = data.errors?.employee_id?.[0] || 'Unable to assign ticket.';
                        document.getElementById('ticketsAssignError').textContent = error;
                        document.getElementById('ticketsAssignError').style.display = 'block';
                        return;
                    }

                    // Update Assigned Employee column in the table (column index 5)
                    const trigger = document.querySelector(`.js-ticket-assign[data-ticket-id="${currentAssignTicketId}"]`);
                    const row = trigger?.closest('tr');
                    if (row) {
                        const tds = row.querySelectorAll('td');
                        if (tds && tds.length >= 6) {
                            tds[5].textContent = data.assignedEmployee?.name || '—';
                        }
                    }

                    // Hide modal
                    const bsModal = bootstrap.Modal.getInstance(assignModal);
                    if (bsModal) bsModal.hide();

                    notify('success', data.message || 'Assigned');

                } catch (err) {
                    notify('error', 'Unable to assign ticket.');
                } finally {
                    ticketsAssignSaveBtn.disabled = false;
                    ticketsAssignSaveBtn.removeAttribute('aria-busy');
                }
            });
        }



        // STATUS
        const statusSaveBtn = document.getElementById('ticketStatusSaveBtn');
        const statusModal = document.getElementById('ticketStatusModal');

        document.querySelectorAll('.js-ticket-status').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const ticketId = e.currentTarget.getAttribute('data-ticket-id');
                if (!ticketId) return;

                document.getElementById('ticketStatusTicketId').value = ticketId;
                document.getElementById('ticketStatusSubtitle').textContent = `Ticket TK-${ticketId}`;
                document.getElementById('ticketStatusError').style.display = 'none';

                // Preselect using the status badge from the row.
                // Table columns in tickets.blade.php:
                // 1 Ticket #, 2 Customer, 3 Subject, 4 Priority, 5 Status, 6 Assigned Employee, 7 Due Date
                const row = e.currentTarget.closest('tr');
                const statusBadge = row?.querySelector('td:nth-child(5) .badge');
                const currentStatus = statusBadge?.textContent.trim() || e.currentTarget.dataset.currentStatus;
                const select = document.getElementById('ticketStatusSelect');
                if (select && currentStatus) select.value = currentStatus;
            });
        });

        if (statusSaveBtn) {
            statusSaveBtn.addEventListener('click', async () => {
                const ticketId = document.getElementById('ticketStatusTicketId').value;
                const status = document.getElementById('ticketStatusSelect').value;
                if (!ticketId) return;

                try {
                    statusSaveBtn.disabled = true;
                    statusSaveBtn.setAttribute('aria-busy', 'true');
                    const res = await fetch(`{{ url('/support/tickets') }}/${ticketId}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            ...(csrf ? {'X-CSRF-TOKEN': csrf} : {})
                        },
                        body: JSON.stringify({ status })
                    });

                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) {
                        const err = data.errors?.status?.[0] || 'Failed to update status.';
                        const box = document.getElementById('ticketStatusError');
                        box.textContent = err;
                        box.style.display = 'block';
                        return;
                    }

                    const host = document.getElementById('supportTicketsNotificationHost');
                    if (host) {
                        host.innerHTML = `<div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                            ${data.message || 'Status updated'}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                    }


                    // Update badge immediately (status is column 5)
                    const row = document.querySelector(`tr[data-ticket-id="${ticketId}"]`);
                    const statusTd = row?.querySelector('td:nth-child(5)');
                    if (statusTd) statusTd.innerHTML = badgeHtmlForStatus(data.status);

                    const bsModal = bootstrap.Modal.getInstance(statusModal);
                    if (bsModal) bsModal.hide();

                } catch (err) {
                    notify('error', 'Failed to update status.');
                } finally {
                    statusSaveBtn.disabled = false;
                    statusSaveBtn.removeAttribute('aria-busy');
                }

            });
        }
    })();
</script>

@endpush
