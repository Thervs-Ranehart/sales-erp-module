@extends('layouts.app')

@section('content')
    @php($title = 'Support Tickets')
    @php($subtitle = 'Manage support tickets and case assignments')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    @include('support.tickets-details-modal')
    @include('support.tickets-assign-modal')

    <div class="card p-4">
        {{-- Top section: page title + breadcrumb + actions --}}
        <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3 mb-4">
            <div>
                <h5 class="fw-bold mb-1">Support Tickets</h5>
                <div class="text-muted small mb-2">ERP-style ticket workflow</div>

                {{-- Breadcrumb (UI-only) --}}
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('support.index') }}" class="text-decoration-none">Support</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tickets</li>
                    </ol>
                </nav>
            </div>



        </div>

        {{-- Search + Filters --}}
        <div class="card p-3 mb-4" style="background: rgba(255,255,255,.7); border: 1px solid rgba(0,0,0,.06); box-shadow: none;">
            <div class="row g-3">
                <div class="col-12 col-lg-4">
                    <label class="form-label small text-muted">Search</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="background: rgba(83,71,206,.08); border-color: rgba(83,71,206,.2);">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="Ticket number, customer, product..." aria-label="Search tickets" value="{{ $search ?? '' }}" />
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
                    <select class="form-select form-select-sm" aria-label="Customer filter" disabled>
                        <option selected>All Customers</option>
                        <option>ABC Corporation</option>
                        <option>XYZ Trading</option>
                        <option>Northwind Retail</option>
                        <option>Greenfield Industries</option>
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
        </div>

        {{-- Main table --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="min-width: 140px;">Ticket Number</th>
                        <th style="min-width: 180px;">Customer</th>
                        <th style="min-width: 160px;">Order</th>
                        <th>Product</th>
                        <th style="min-width: 260px;">Subject</th>
                        <th style="min-width: 130px;">Priority</th>
                        <th style="min-width: 140px;">Status</th>
                        <th style="min-width: 190px;">Assigned Employee</th>
                        <th style="min-width: 150px;">Due Date</th>
                        <th class="text-end" style="min-width: 260px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td class="fw-semibold">{{ 'TK-' . $ticket->ticket_id }}</td>
                            <td>{{ $ticket->customer->customer_name ?? '—' }}</td>
                            <td>{{ $ticket->order->order_number ?? 'SO-' . $ticket->order_id }}</td>
                            <td>{{ $ticket->product->product_name ?? '—' }}</td>
                            <td>{{ $ticket->subject ?? '—' }}</td>
                            <td>
                                @php($priorityBadge = $ticket->priority)
                                @if(strtolower((string)$priorityBadge) === 'high')
                                    <span class="badge bg-danger">{{ $ticket->priority }}</span>
                                @elseif(strtolower((string)$priorityBadge) === 'medium')
                                    <span class="badge bg-warning text-dark">{{ $ticket->priority }}</span>
                                @elseif(strtolower((string)$priorityBadge) === 'low')
                                    <span class="badge bg-success">{{ $ticket->priority }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $ticket->priority ?? '—' }}</span>
                                @endif
                            </td>
                            <td>
                                @php($statusBadge = $ticket->status)
                                @if(strtolower((string)$statusBadge) === 'pending')
                                    <span class="badge bg-warning text-dark">{{ $ticket->status }}</span>
                                @elseif(strtolower((string)$statusBadge) === 'in progress')
                                    <span class="badge bg-primary">{{ $ticket->status }}</span>
                                @elseif(strtolower((string)$statusBadge) === 'resolved')
                                    <span class="badge bg-success">{{ $ticket->status }}</span>
                                @elseif(strtolower((string)$statusBadge) === 'escalated')
                                    <span class="badge bg-danger">{{ $ticket->status }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $ticket->status ?? '—' }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $ticket->ticketAssignments->first()->employee->employee_name ?? '—' }}
                            </td>
                            <td class="text-muted">{{ optional($ticket->due_date)->format('Y-m-d') }}</td>
                            <td class="text-end" style="min-width: 260px; white-space: nowrap;">
                                <div class="d-flex align-items-center justify-content-end flex-nowrap gap-2">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#ticketDetailsModal">
                                        <i class="bi bi-eye me-1"></i><span class="text-nowrap"> View</span>
                                    </button>

                                    <button class="btn btn-sm btn-outline-warning" type="button">
                                        <i class="bi bi-pencil me-1"></i><span class="text-nowrap"> Change Status</span>
                                    </button>

                                    <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#ticketsAssignModal">
                                        <i class="bi bi-diagram-3 me-1"></i><span class="text-nowrap"> Assign</span>
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

@endsection

