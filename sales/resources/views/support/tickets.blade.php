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
                        <input type="text" class="form-control" placeholder="Ticket number, customer, product..." aria-label="Search tickets" />
                    </div>
                </div>

                <div class="col-6 col-lg-2">
                    <label class="form-label small text-muted">Status</label>
                    <select class="form-select form-select-sm" aria-label="Status filter">
                        <option selected>Status: All</option>
                        <option>Pending</option>
                        <option>In Progress</option>
                        <option>Resolved</option>
                        <option>Escalated</option>
                    </select>
                </div>

                <div class="col-6 col-lg-2">
                    <label class="form-label small text-muted">Priority</label>
                    <select class="form-select form-select-sm" aria-label="Priority filter">
                        <option selected>Priority: All</option>
                        <option>High</option>
                        <option>Medium</option>
                        <option>Low</option>
                    </select>
                </div>

                <div class="col-12 col-lg-2">
                    <label class="form-label small text-muted">Customer</label>
                    <select class="form-select form-select-sm" aria-label="Customer filter">
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
                    <button class="btn btn-sm" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
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
                    <tr>
                        <td class="fw-semibold">TK-1002</td>
                        <td>XYZ Trading</td>
                        <td>SO-9012</td>
                        <td>Widget A</td>
                        <td>Product Repair • crack on casing</td>
                        <td><span class="badge bg-danger">High</span></td>
                        <td><span class="badge bg-primary">In Progress</span></td>
                        <td>Support Team Lead A</td>
                        <td class="text-muted">2026-07-13</td>
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

                    <tr>
                        <td class="fw-semibold">TK-1005</td>
                        <td>Greenfield Industries</td>
                        <td>SO-9055</td>
                        <td>Industrial Pump X</td>
                        <td>Warranty Inspection • leakage detected</td>
                        <td><span class="badge bg-warning text-dark">Medium</span></td>
                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                        <td>Warranty Desk</td>
                        <td class="text-muted">2026-07-14</td>
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

                    <tr>
                        <td class="fw-semibold">TK-1012</td>
                        <td>ABC Corporation</td>
                        <td>SO-9098</td>
                        <td>Appliance Z</td>
                        <td>Replacement Request • battery failure</td>
                        <td><span class="badge bg-success">Low</span></td>
                        <td><span class="badge bg-success">Resolved</span></td>
                        <td>Resolutions</td>
                        <td class="text-muted">2026-07-10</td>
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

                    <tr>
                        <td class="fw-semibold">TK-1020</td>
                        <td>Northwind Retail</td>
                        <td>SO-9120</td>
                        <td>Widget A</td>
                        <td>Escalation • repeat failure after repair</td>
                        <td><span class="badge bg-danger">High</span></td>
                        <td><span class="badge bg-danger">Escalated</span></td>
                        <td>Support Team Lead B</td>
                        <td class="text-muted">2026-07-13</td>
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
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mt-4">
            <div class="text-muted small">Showing results.</div>

            <nav aria-label="Tickets pagination">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                    <li class="page-item active"><span class="page-link">1</span></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>

@endsection

