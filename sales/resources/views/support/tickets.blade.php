@extends('layouts.app')

@section('content')
    @php($title = 'Support Tickets')
    @php($subtitle = 'Manage support tickets and case assignments')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    <div class="card p-4">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Ticket Management</h5>
                <div class="text-muted small">Track requests, collaborate with teams, and monitor SLA progress.</div>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <div class="input-group input-group-sm" style="min-width: 260px;">
                    <span class="input-group-text" style="background: rgba(83,71,206,.08); border-color: rgba(83,71,206,.2);">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Search tickets (e.g., TK-1002)" aria-label="Search tickets" />
                </div>

                <select class="form-select form-select-sm" style="min-width: 190px;" aria-label="Filter by status">
                    <option selected>Status: All</option>
                    <option>Pending</option>
                    <option>In Progress</option>
                    <option>Resolved</option>
                    <option>Escalated</option>
                </select>

                <button class="btn btn-sm" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="min-width: 130px;">Ticket No.</th>
                        <th>Customer</th>
                        <th>Issue / Category</th>
                        <th style="min-width: 190px;">SLA</th>
                        <th style="min-width: 150px;">Status</th>
                        <th class="text-end" style="min-width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-semibold">TK-1002</td>
                        <td>XYZ Trading</td>
                        <td>
                            <div class="fw-semibold">Product Repair</div>
                            <div class="text-muted small">Category: Electronics • Priority: High</div>
                        </td>
                        <td>
                            <div class="small text-muted mb-1">Due in <span class="fw-semibold">6h</span></div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-primary">In Progress</span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('support.tickets') }}">View</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">TK-1005</td>
                        <td>Greenfield Industries</td>
                        <td>
                            <div class="fw-semibold">Warranty Inspection</div>
                            <div class="text-muted small">Category: Machinery • Priority: Medium</div>
                        </td>
                        <td>
                            <div class="small text-muted mb-1">Due in <span class="fw-semibold">1d</span></div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 42%" aria-valuenow="42" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-warning text-dark">Pending Review</span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-warning" href="{{ route('support.warranty-claims') }}">Review</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">TK-1012</td>
                        <td>ABC Corporation</td>
                        <td>
                            <div class="fw-semibold">Replacement Request</div>
                            <div class="text-muted small">Category: Appliances • Priority: Low</div>
                        </td>
                        <td>
                            <div class="small text-muted mb-1">Resolved <span class="fw-semibold">2d ago</span></div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-success">Resolved</span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-success" href="{{ route('support.resolution-tracking') }}">Track</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">TK-1020</td>
                        <td>Northwind Retail</td>
                        <td>
                            <div class="fw-semibold">Escalation: Repeat Failure</div>
                            <div class="text-muted small">Category: Electronics • Priority: High</div>
                        </td>
                        <td>
                            <div class="small text-muted mb-1">Due in <span class="fw-semibold">3h</span></div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 78%" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-danger">Escalated</span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-danger" href="{{ route('support.resolution-tracking') }}">Open</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mt-4">
            <div class="text-muted small">Showing 4 of 128 tickets (placeholder).</div>
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


