@extends('layouts.app')

@section('content')
    @php($title = 'Warranty Claims')
    @php($subtitle = 'Review and process warranty claims')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    <div class="card p-4">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Claims Intake & Review</h5>
                <div class="text-muted small">Manage claim submissions, validation checks, and approval workflow.</div>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <div class="input-group input-group-sm" style="min-width: 260px;">
                    <span class="input-group-text" style="background: rgba(83,71,206,.08); border-color: rgba(83,71,206,.2);">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Search claim (e.g., WC-3001)" aria-label="Search claims" />
                </div>

                <select class="form-select form-select-sm" style="min-width: 190px;" aria-label="Filter by claim status">
                    <option selected>Status: Pending</option>
                    <option>Status: Approved</option>
                    <option>Status: Rejected</option>
                    <option>Status: In Verification</option>
                </select>

                <button class="btn btn-sm" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
                    <i class="bi bi-check2-circle me-1"></i> Review
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="min-width: 150px;">Claim #</th>
                        <th>Customer</th>
                        <th>Reason</th>
                        <th style="min-width: 180px;">Verification</th>
                        <th style="min-width: 160px;">Status</th>
                        <th class="text-end" style="min-width: 160px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-semibold">WC-3001</td>
                        <td>ABC Corporation</td>
                        <td>
                            <div class="fw-semibold">Manufacturing Defect</div>
                            <div class="text-muted small">Evidence: photos + serial validation</div>
                        </td>
                        <td>
                            <div class="small text-muted mb-1">Requested: 2026-07-11</div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 48%" aria-valuenow="48" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-warning" href="{{ route('support.resolution-tracking') }}">Open</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">WC-3017</td>
                        <td>XYZ Trading</td>
                        <td>
                            <div class="fw-semibold">Wear & Tear (Tier-2)</div>
                            <div class="text-muted small">Requires service report & parts lookup</div>
                        </td>
                        <td>
                            <div class="small text-muted mb-1">Requested: 2026-07-09</div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 62%" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-primary">In Verification</span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('support.resolution-tracking') }}">Verify</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">WC-3032</td>
                        <td>Northwind Retail</td>
                        <td>
                            <div class="fw-semibold">User Handling Damage</div>
                            <div class="text-muted small">No warranty coverage for this scenario</div>
                        </td>
                        <td>
                            <div class="small text-muted mb-1">Completed: 2026-07-08</div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-danger">Rejected</span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-danger" href="{{ route('support.customer-satisfaction') }}">View outcome</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">WC-3044</td>
                        <td>John Smith</td>
                        <td>
                            <div class="fw-semibold">Battery Failure</div>
                            <div class="text-muted small">Serial within coverage window</div>
                        </td>
                        <td>
                            <div class="small text-muted mb-1">Approved: 2026-07-10</div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-success">Approved</span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-success" href="{{ route('support.resolution-tracking') }}">Process</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mt-4">
            <div class="text-muted small">Showing 4 of 62 claims (placeholder).</div>
            <nav aria-label="Claims pagination">
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


