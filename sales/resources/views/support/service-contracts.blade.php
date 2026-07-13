@extends('layouts.app')

@section('content')
    @php($title = 'Service Contracts')
    @php($subtitle = 'Manage maintenance and service coverage')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    <div class="card p-4">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Maintenance Coverage Management</h5>
                <div class="text-muted small">Track contract status, renewals, and service entitlements.</div>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <div class="input-group input-group-sm" style="min-width: 260px;">
                    <span class="input-group-text" style="background: rgba(83,71,206,.08); border-color: rgba(83,71,206,.2);">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Search contract (e.g., SC-4001)" aria-label="Search contracts" />
                </div>

                <select class="form-select form-select-sm" style="min-width: 190px;" aria-label="Filter by contract status">
                    <option selected>Status: Active</option>
                    <option>Status: Expiring</option>
                    <option>Status: Suspended</option>
                    <option>Status: Expired</option>
                </select>

                <button class="btn btn-sm" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
                    <i class="bi bi-arrow-clockwise me-1"></i> Renew
                </button>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(22,200,199,.10);">
                    <div class="text-muted small">Active Contracts</div>
                    <div class="fw-bold fs-5">76</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(245,158,11,.10);">
                    <div class="text-muted small">Expiring (30d)</div>
                    <div class="fw-bold fs-5">11</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(83,71,206,.08);">
                    <div class="text-muted small">On Hold</div>
                    <div class="fw-bold fs-5">3</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(239,68,68,.08);">
                    <div class="text-muted small">Expired</div>
                    <div class="fw-bold fs-5">7</div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="min-width: 150px;">Contract #</th>
                        <th>Customer</th>
                        <th>Service Type</th>
                        <th style="min-width: 180px;">Coverage Window</th>
                        <th style="min-width: 160px;">Status</th>
                        <th class="text-end" style="min-width: 160px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-semibold">SC-4001</td>
                        <td>XYZ Trading</td>
                        <td>
                            <div class="fw-semibold">Extended Support</div>
                            <div class="text-muted small">Includes remote diagnostics</div>
                        </td>
                        <td>
                            <div class="small text-muted mb-1">Started 2026-01-01</div>
                            <div class="small">Ends in <span class="fw-semibold">210d</span></div>
                        </td>
                        <td><span class="badge bg-success">Active</span></td>
                        <td class="text-end"><a class="btn btn-sm btn-outline-success" href="{{ route('support.service-requests') }}">Manage</a></td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">SC-4007</td>
                        <td>ABC Corporation</td>
                        <td>
                            <div class="fw-semibold">Premium Maintenance</div>
                            <div class="text-muted small">Quarterly on-site visits</div>
                        </td>
                        <td>
                            <div class="small text-muted mb-1">Started 2025-10-15</div>
                            <div class="small">Ends in <span class="fw-semibold">19d</span></div>
                        </td>
                        <td><span class="badge bg-warning text-dark">Expiring</span></td>
                        <td class="text-end"><a class="btn btn-sm btn-outline-warning" href="{{ route('support.service-requests') }}">Renew</a></td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">SC-4011</td>
                        <td>Northwind Retail</td>
                        <td>
                            <div class="fw-semibold">Standard Support</div>
                            <div class="text-muted small">Includes parts replacement</div>
                        </td>
                        <td>
                            <div class="small text-muted mb-1">Started 2025-02-01</div>
                            <div class="small">Ended <span class="fw-semibold">11d ago</span></div>
                        </td>
                        <td><span class="badge bg-danger">Expired</span></td>
                        <td class="text-end"><a class="btn btn-sm btn-outline-danger" href="{{ route('support.service-contracts') }}">Reinstate</a></td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">SC-4018</td>
                        <td>Greenfield Industries</td>
                        <td>
                            <div class="fw-semibold">On-demand Service</div>
                            <div class="text-muted small">Dispatch based on SLA tiers</div>
                        </td>
                        <td>
                            <div class="small text-muted mb-1">Started 2026-03-20</div>
                            <div class="small">Ends in <span class="fw-semibold">143d</span></div>
                        </td>
                        <td><span class="badge bg-primary">Review</span></td>
                        <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('support.service-requests') }}">Review</a></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mt-4">
            <div class="text-muted small">Showing 4 of 103 contracts (placeholder).</div>
            <nav aria-label="Contracts pagination">
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


