@extends('layouts.app')

@section('content')
    @php($title = 'Support Tickets')
    @php($subtitle = 'Manage support tickets and case assignments')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

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
            <form method="GET" action="{{ route('support.tickets') }}">
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
                            <option value="Pending" {{ ($status ?? '') === 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="In Progress" {{ ($status ?? '') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Resolved" {{ ($status ?? '') === 'Resolved' ? 'selected' : '' }}>Resolved</option>
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
                        <option value="ABC Corporation" {{ ($customer ?? 'all') === 'ABC Corporation' ? 'selected' : '' }}>ABC Corporation</option>
                        <option value="XYZ Trading" {{ ($customer ?? 'all') === 'XYZ Trading' ? 'selected' : '' }}>XYZ Trading</option>
                        <option value="Northwind Retail" {{ ($customer ?? 'all') === 'Northwind Retail' ? 'selected' : '' }}>Northwind Retail</option>
                        <option value="Greenfield Industries" {{ ($customer ?? 'all') === 'Greenfield Industries' ? 'selected' : '' }}>Greenfield Industries</option>
                    </select>

                </div>

                <div class="col-6 col-lg-1">
                    <label class="form-label small text-muted">From</label>
                    <input type="date" class="form-control form-control-sm" aria-label="Start date" />
                </div>
                <div class="col-6 col-lg-1">
                    <label class="form-label small text-muted">To</label>
                    <input type="date" class="form-control form-control-sm" aria-label="End date" />
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
            <table class="table table-hover align-middle mb-0 tickets-table" style="width: 100%;">
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
                            <td>{{ $ticket->customer->customer_name ?? '—' }}</td>
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
                                @if(strtolower((string)$statusBadge) === 'pending')
                                    <span class="badge bg-warning text-dark px-2 py-1 fs-6">{{ $ticket->status }}</span>
                                @elseif(strtolower((string)$statusBadge) === 'in progress')
                                    <span class="badge bg-primary px-2 py-1 fs-6">{{ $ticket->status }}</span>
                                @elseif(strtolower((string)$statusBadge) === 'resolved')
                                    <span class="badge bg-success px-2 py-1 fs-6">{{ $ticket->status }}</span>

                                @elseif(strtolower((string)$statusBadge) === 'escalated')
                                    <span class="badge bg-danger px-2 py-1 fs-6">{{ $ticket->status }}</span>
                                @else
                                    <span class="badge bg-secondary px-2 py-1 fs-6">{{ $ticket->status ?? '—' }}</span>
                                @endif
                            </td>
                            <td>
                                {{ optional($ticket->ticketAssignments->first())->employee?->getFullNameAttribute() ?? '—' }}
                            </td>
                            <td class="text-muted">{{ optional($ticket->due_date)->format('Y-m-d') }}</td>
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
                    {{ $tickets->links() }}
                </nav>

        </div>
    </div>

<script>
    (function () {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

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
            if (lower === 'pending') cls = 'bg-warning text-dark';
            else if (lower === 'in progress') cls = 'bg-primary';
            else if (lower === 'resolved') cls = 'bg-success';
            else if (lower === 'escalated') cls = 'bg-danger';

            return `<span class="badge ${cls} px-2 py-1 fs-6">${s || '—'}</span>`;
        }

        // VIEW
        document.querySelectorAll('.js-ticket-view').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const ticketId = e.currentTarget.getAttribute('data-ticket-id');
                if (!ticketId) return;

                try {
                    console.log('VIEW clicked ticketId=', ticketId);
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
                    document.getElementById('ticketDetailsSubtitle').textContent = `TK-${t.ticket_id ?? ticketId} • ${t.customer?.customer_name || '—'}`;
                    document.getElementById('ticketDetailsSubject').textContent = t.subject || '—';

                    // Update status badge
                    const ticketDetailsStatusEl = document.getElementById('ticketDetailsStatus');
                    if (ticketDetailsStatusEl) {
                        const lower = (t.status || '').toLowerCase();
                        let badgeClass = 'badge bg-secondary';
                        if (lower === 'pending') badgeClass = 'badge bg-warning text-dark';
                        else if (lower === 'in progress') badgeClass = 'badge bg-primary';
                        else if (lower === 'resolved') badgeClass = 'badge bg-success';
                        else if (lower === 'escalated') badgeClass = 'badge bg-danger';
                        ticketDetailsStatusEl.className = badgeClass;
                        ticketDetailsStatusEl.textContent = t.status || '—';
                    }

                    // Update description
                    const descEl = document.querySelector('#ticketDetailsModal .text-muted.mb-3');
                    if (descEl) descEl.textContent = t.description || '—';

                    // Update assigned employee / due date / priority via label lookup
                    const detailsRoot = document.getElementById('ticketDetailsModal');
                    if (detailsRoot) {
                        const findValueByLabel = (labelText) => {
                            const label = Array.from(detailsRoot.querySelectorAll('.text-muted.small')).find(el => el.textContent.trim() === labelText);
                            if (!label) return null;
                            const container = label.closest('.col-sm-6');
                            if (!container) return null;
                            return container.querySelector('.fw-semibold');
                        };

                        const assignedValue = findValueByLabel('Assigned Employee');
                        if (assignedValue) assignedValue.textContent = data.assignedEmployee?.employee_name || '—';

                        const dueValue = findValueByLabel('Due Date');
                        if (dueValue) dueValue.textContent = t.due_date ? t.due_date : '—';

                        const priorityValue = findValueByLabel('Priority');
                        if (priorityValue) priorityValue.textContent = t.priority || '—';
                    }



                } catch (err) {
                    // no-op; modal remains
                    console.error(err);
                }
            });
        });

        // ASSIGN
        const assignModal = document.getElementById('ticketsAssignModal');
        let currentAssignTicketId = null;

        document.querySelectorAll('.js-ticket-assign').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const ticketId = e.currentTarget.getAttribute('data-ticket-id');
                currentAssignTicketId = ticketId;
                if (!ticketId) return;

                try {
                    console.log('ASSIGN clicked ticketId=', ticketId);
                    const res = await fetch(`{{ url('/support/tickets') }}/${ticketId}/assign`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            ...(csrf ? {'X-CSRF-TOKEN': csrf} : {})
                        }
                    });
                    if (!res.ok) throw new Error('Failed to load assign data');
                    const data = await res.json();


                    // Fill ticket number
                    const ticketNoInput = assignModal.querySelector('input[readonly]');
                    if (ticketNoInput) ticketNoInput.value = `TK-${data.ticket?.ticket_id ?? ticketId}`;

                    // Fill assigned employee select options
                    const selects = assignModal.querySelectorAll('select.form-select');
                    const employeeSelect = selects && selects.length >= 1 ? selects[0] : null; // [0]=Employee

                    if (employeeSelect) {
                        employeeSelect.innerHTML = '';
                        const currentId = data.currentEmployeeId;
                        (data.employees || []).forEach(emp => {
                            const opt = document.createElement('option');
                            opt.value = emp.employee_id;
                            opt.textContent = emp.employee_name;
                            if (currentId != null && String(currentId) === String(emp.employee_id)) opt.selected = true;
                            employeeSelect.appendChild(opt);
                        });
                    }

                    // set priority
                    const prioritySelect = selects && selects.length >= 3 ? selects[2] : null; // [2]=Priority
                    if (prioritySelect && data.ticket?.priority) prioritySelect.value = data.ticket.priority;

                    // due date
                    const dueInput = assignModal.querySelector('input[type="date"]');
                    if (dueInput && data.ticket?.due_date) dueInput.value = data.ticket.due_date;

                } catch (err) {
                    console.error(err);
                }
            });
        });

        // Ensure assign modal save handler uses the stable button
        const ticketsAssignSaveBtn = document.getElementById('ticketsAssignSaveBtn');

        if (ticketsAssignSaveBtn) {
            ticketsAssignSaveBtn.addEventListener('click', async () => {
                console.log('ASSIGN save clicked, currentAssignTicketId=', currentAssignTicketId);
                if (!currentAssignTicketId) return;

                const employeeSelect = assignModal?.querySelector('select[aria-label="Assigned employee"]');
                const employeeId = employeeSelect?.value;

                // Basic client-side guard
                if (!employeeId) {
                    notify('error', 'Please select an employee.');
                    return;
                }

                try {
                    const res = await fetch(`{{ url('/support/tickets') }}/${currentAssignTicketId}/assign`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            ...(csrf ? {'X-CSRF-TOKEN': csrf} : {})
                        },
                        body: JSON.stringify({ employee_id: employeeId })
                    });

                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) {
                        console.error(data);
                        notify('error', 'Unable to assign ticket.');
                        return;
                    }

                    // Update Assigned Employee column in the table (column index 5)
                    const trigger = document.querySelector(`.js-ticket-assign[data-ticket-id="${currentAssignTicketId}"]`);
                    const row = trigger?.closest('tr');
                    if (row) {
                        const tds = row.querySelectorAll('td');
                        if (tds && tds.length >= 6) {
                            tds[5].textContent = data.assignedEmployee?.employee_name || '—';
                        }
                    }

                    // Hide modal
                    const bsModal = bootstrap.Modal.getInstance(assignModal);
                    if (bsModal) bsModal.hide();

                    notify('success', data.message || 'Assigned');

                } catch (err) {
                    console.error('ASSIGN save error', err);
                    notify('error', 'Unable to assign ticket.');
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

                try {
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
                    console.error(err);
                    notify('error', 'Failed to update status.');
                }

            });
        }
    })();
</script>

@endsection



