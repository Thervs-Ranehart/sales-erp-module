@extends('layouts.app')

@section('content')
    @php($title = 'Support Tickets')
    @php($subtitle = 'Manage support tickets and case assignments')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])
    @include('support.operations-create-modal')

    @include('support.tickets-details-modal')
    @include('support.tickets-assign-modal')
    @include('support.ticket-status-modal')


        <div class="card p-4" id="ticketsPageCard">
            <div id="supportTicketsNotificationHost" class="mb-3"></div>

        {{-- Top section: page title + breadcrumb + actions --}}
        <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3 mb-4">
            <div>
                <h5 class="fw-bold mb-1">Support Tickets</h5>
                
            </div>
        </div>

        {{-- Search + Filters --}}
        <div class="card p-3 mb-4" style="background: rgba(255,255,255,.7); border: 1px solid rgba(0,0,0,.06); box-shadow: none;">
            <form id="ticketFiltersForm" method="GET" action="{{ route('support.tickets') }}">
                <div class="row g-3" id="ticketsFilters">
                    <div class="col-12 col-lg-4">
                        {{-- Keep a dense, single-row form on small screens --}}
                        <style>
                            @media (max-width: 575.98px) {
                                #ticketFiltersForm .form-label { font-size: 0.75rem !important; }
                                #ticketFiltersForm .input-group-sm .input-group-text { padding: .35rem .5rem !important; }
                            }
                        </style>
                        <label class="form-label small text-muted">Search</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text" style="background: rgba(83,71,206,.08); border-color: rgba(83,71,206,.2);">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control" placeholder="Ticket number, customer, subject..." aria-label="Search tickets" value="{{ $search ?? '' }}" />
                        </div>
                    </div>

                    <div class="col-6 col-lg-2">
                        <label class="form-label small text-muted">Status</label>
                        <select class="form-select form-select-sm" aria-label="Status filter" name="status">
                            <option value="all" {{ ($status ?? '') === '' || ($status ?? '') === 'all' ? 'selected' : '' }}>Status: All</option>
                            <option value="Open" {{ ($status ?? '') === 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="Pending" {{ ($status ?? '') === 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="In Progress" {{ ($status ?? '') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Resolved" {{ ($status ?? '') === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="Closed" {{ ($status ?? '') === 'Closed' ? 'selected' : '' }}>Closed</option>
                            <option value="Escalated" {{ ($status ?? '') === 'Escalated' ? 'selected' : '' }}>Escalated</option>
                        </select>
                    </div>

                <div class="col-6 col-lg-2">
                    <label class="form-label small text-muted">Priority</label>
                    <select class="form-select form-select-sm" aria-label="Priority filter" name="priority">
                        <option value="all" {{ ($priority ?? '') === '' || ($priority ?? '') === 'all' ? 'selected' : '' }}>Priority: All</option>
                        <option value="High" {{ ($priority ?? '') === 'High' ? 'selected' : '' }}>High</option>
                        <option value="Medium" {{ ($priority ?? '') === 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="Low" {{ ($priority ?? '') === 'Low' ? 'selected' : '' }}>Low</option>
                    </select>

                </div>

                    <div class="col-12 col-lg-2">
                    <label class="form-label small text-muted">Customer</label>
                    <select class="form-select form-select-sm" aria-label="Customer filter" name="customer">
                        <option value="all" {{ ($customer ?? 'all') === 'all' ? 'selected' : '' }}>All Customers</option>
                        @foreach($customers as $customerOption)
                            <option value="{{ $customerOption->customer_id }}" {{ (string) ($customer ?? 'all') === (string) $customerOption->customer_id ? 'selected' : '' }}>{{ $customerOption->name }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="col-6 col-lg-1">
                    <label class="form-label small text-muted">From</label>
                    <input type="date" class="form-control form-control-sm" aria-label="Start date" name="from_date" value="{{ $fromDate ?? '' }}" />
                </div>
                <div class="col-6 col-lg-1">
                    <label class="form-label small text-muted">To</label>
                    <input type="date" class="form-control form-control-sm" aria-label="End date" name="to_date" value="{{ $toDate ?? '' }}" />
                </div>

                    <div class="col-6 col-lg-2">
                        <label class="form-label small text-muted">Assigned employee</label>
                        <select class="form-select form-select-sm" aria-label="Assigned employee filter" name="assigned_employee">
                            <option value="">All employees</option>
                            @foreach($employees as $employeeOption)
                                <option value="{{ $employeeOption->employee_id }}" {{ (string) ($assignedEmployee ?? '') === (string) $employeeOption->employee_id ? 'selected' : '' }}>{{ $employeeOption->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-sm" type="submit" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
                            <i class="bi bi-funnel me-1"></i> Apply Filters
                        </button>
                    </div>
                </div>
                <style>
                    @media (max-width: 575.98px) {
                        /* Stack filter inputs vertically on mobile */
                        #ticketsFilters .col-6,
                        #ticketsFilters .col-12 {
                            flex: 0 0 100%;
                            max-width: 100%;
                        }
                        #ticketsFilters .form-control,
                        #ticketsFilters .form-select {
                            width: 100% !important;
                        }
                        #ticketsFilters .input-group {
                            width: 100% !important;
                        }
                        #ticketsFilters .btn {
                            width: 100%;
                        }
                    }
                </style>
                </form>
        </div>


        {{-- Main table --}}
                <div class="table-responsive" style="-webkit-overflow-scrolling: touch;">
            <table id="supportTicketsTable" class="table table-hover align-middle mb-0 tickets-table" style="width: 100%;">
                {{-- Keep Bootstrap table-responsive and allow horizontal scrolling on small screens --}}
                <style>
                            @media (max-width: 575.98px) {
                                .tickets-table th, .tickets-table td { white-space: nowrap; }
                                .tickets-actions .btn { padding: .25rem .35rem; }
                            }

                </style>

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
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td class="fw-semibold">{{ 'TK-' . $ticket->ticket_id }}</td>
                            <td>{{ $ticket->customer?->full_name ?? '—' }}</td>
                            <td style="max-width: 260px;">
                                <span class="d-block text-truncate" title="{{ $ticket->subject ?? '—' }}" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $ticket->subject ?? '—' }}</span>
                            </td>
                            <td>
                                @php($priorityBadge = $ticket->priority)
                                @if(strtolower((string)$priorityBadge) === 'high')
                                    <span class="badge bg-danger px-2 py-1 fs-6">{{ $ticket->priority }}</span>
                                @elseif(strtolower((string)$priorityBadge) === 'medium')
                                    <span class="badge bg-warning text-dark px-2 py-1 fs-6">{{ $ticket->priority }}</span>
                                @elseif(strtolower((string)$priorityBadge) === 'low')
                                    <span class="badge bg-success px-2 py-1 fs-6">{{ $ticket->priority }}</span>
                                @else
                                    <span class="badge bg-secondary px-2 py-1 fs-6">{{ $ticket->priority ?? '—' }}</span>
                                @endif
                            </td>
                            <td>
                                @php($statusBadge = $ticket->status)
                                @if(strtolower((string)$statusBadge) === 'open')
                                    <span class="badge bg-secondary px-2 py-1 fs-6">{{ $ticket->status }}</span>
                                @elseif(strtolower((string)$statusBadge) === 'pending')
                                    <span class="badge bg-warning text-dark px-2 py-1 fs-6">{{ $ticket->status }}</span>
                                @elseif(strtolower((string)$statusBadge) === 'in progress')
                                    <span class="badge bg-primary px-2 py-1 fs-6">{{ $ticket->status }}</span>
                                @elseif(strtolower((string)$statusBadge) === 'resolved')
                                    <span class="badge bg-success px-2 py-1 fs-6">{{ $ticket->status }}</span>

                                @elseif(strtolower((string)$statusBadge) === 'closed')
                                    <span class="badge bg-dark px-2 py-1 fs-6">{{ $ticket->status }}</span>

                                @elseif(strtolower((string)$statusBadge) === 'escalated')
                                    <span class="badge bg-danger px-2 py-1 fs-6">{{ $ticket->status }}</span>
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
                            <td class="text-end" style="white-space: nowrap;">
                                <div class="d-flex align-items-center justify-content-end flex-nowrap gap-1">
                                    <button class="btn btn-sm btn-outline-primary js-ticket-view" type="button" data-ticket-id="{{ $ticket->ticket_id }}" aria-label="View" data-bs-toggle="modal" data-bs-target="#ticketDetailsModal">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    <button class="btn btn-sm btn-outline-warning js-ticket-status" type="button" data-ticket-id="{{ $ticket->ticket_id }}" aria-label="Change Status" data-bs-toggle="modal" data-bs-target="#ticketStatusModal">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <button class="btn btn-sm btn-outline-success js-ticket-assign" type="button" data-ticket-id="{{ $ticket->ticket_id }}" aria-label="Assign" data-bs-toggle="modal" data-bs-target="#ticketsAssignModal">
                                        <i class="bi bi-diagram-3"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editTicket{{ $ticket->ticket_id }}" title="Edit ticket details"><i class="bi bi-pencil-square"></i></button>
                                    <button class="btn btn-sm btn-outline-info" type="button" data-bs-toggle="modal" data-bs-target="#ticketFiles{{ $ticket->ticket_id }}" title="Attachments"><i class="bi bi-paperclip"></i></button>
                                    @if($ticket->archived_at)
                                        <form method="POST" action="{{ route('support.tickets.restore', $ticket) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-success" title="Restore"><i class="bi bi-arrow-counterclockwise"></i></button></form>
                                    @else
                                        <form method="POST" action="{{ route('support.tickets.archive', $ticket) }}" onsubmit="return confirm('Archive this ticket while retaining its history?')">@csrf @method('PATCH')<input type="hidden" name="archive_reason" value="Archived by support staff"><button class="btn btn-sm btn-outline-danger" title="Archive"><i class="bi bi-archive"></i></button></form>
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

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mt-4">
            <div class="text-muted small">Showing results.</div>

                <nav aria-label="Tickets pagination">
                    {{ $tickets->links('pagination::bootstrap-5') }}
                </nav>

        </div>
    </div>

@foreach($tickets as $ticket)
<div class="modal fade" id="editTicket{{ $ticket->ticket_id }}" tabindex="-1"><div class="modal-dialog modal-lg"><form class="modal-content" method="POST" action="{{ route('support.tickets.update', $ticket) }}">@csrf @method('PUT')
<div class="modal-header"><h5 class="modal-title">Edit TK-{{ $ticket->ticket_id }}</h5><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="row g-3">
<div class="col-6"><label class="form-label">Type</label><input class="form-control" name="ticket_type" value="{{ $ticket->ticket_type }}" required></div><div class="col-3"><label class="form-label">Priority</label><select class="form-select" name="priority">@foreach(['High','Medium','Low'] as $option)<option @selected($ticket->priority===$option)>{{ $option }}</option>@endforeach</select></div><div class="col-3"><label class="form-label">Queue</label><select class="form-select" name="department">@foreach(['After-Sales Support','Technical Support','Warranty','Field Service'] as $option)<option @selected($ticket->department===$option)>{{ $option }}</option>@endforeach</select></div>
<div class="col-12"><label class="form-label">Subject</label><input class="form-control" name="subject" value="{{ $ticket->subject }}" required></div><div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="4" required>{{ $ticket->description }}</textarea></div>
</div></div><div class="modal-footer"><button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button><button class="btn support-primary">Save Changes</button></div></form></div></div>
<div class="modal fade" id="ticketFiles{{ $ticket->ticket_id }}" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Attachments · TK-{{ $ticket->ticket_id }}</h5><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">
<form method="POST" action="{{ route('support.tickets.attachments.store', $ticket) }}" enctype="multipart/form-data" class="mb-3">@csrf <label class="form-label">Add evidence or supporting document</label><div class="input-group"><input class="form-control" type="file" name="attachment" required><button class="btn support-primary">Upload</button></div><div class="form-text">PDF, image, Office document, or text file up to 10 MB.</div></form>
<div class="list-group">@forelse($ticket->attachments as $attachment)<div class="list-group-item d-flex justify-content-between align-items-center"><span><i class="bi bi-file-earmark me-2"></i>{{ $attachment->original_name }}</span><form method="POST" action="{{ route('support.attachments.destroy', $attachment) }}" onsubmit="return confirm('Delete this attachment?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></div>@empty<div class="text-muted">No attachments yet.</div>@endforelse</div>
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
                if (statusBadge) {
                    const currentStatus = statusBadge.textContent.trim();
                    const select = document.getElementById('ticketStatusSelect');
                    if (select && currentStatus) select.value = currentStatus;
                }
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
                    const trigger = document.querySelector(`.js-ticket-status[data-ticket-id="${ticketId}"]`);
                    const row = trigger?.closest('tr');
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
