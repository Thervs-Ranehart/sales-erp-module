@extends('layouts.app')

@section('content')
    @php($title = 'Resolution Tracking')
    @php($subtitle = 'Track resolutions and corrective actions')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    <div class="card p-4">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Resolutions & Corrective Actions</h5>
                <div class="text-muted small">Monitor case closure progress, ownership, and quality gates.</div>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <div class="input-group input-group-sm" style="min-width: 260px;">
                    <span class="input-group-text" style="background: rgba(83,71,206,.08); border-color: rgba(83,71,206,.2);">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Search resolution (e.g., RS-6001)" aria-label="Search resolutions" />
                </div>

                <select class="form-select form-select-sm" style="min-width: 190px;" aria-label="Filter by resolution status">
                    <option selected>Status: Resolved</option>
                    <option>Status: Pending QC</option>
                    <option>Status: Reopened</option>
                    <option>Status: In Review</option>
                </select>

                <button class="btn btn-sm" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
                    <i class="bi bi-lightning-charge me-1"></i> QC Summary
                </button>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(83,71,206,.08);">
                    <div class="text-muted small">Resolved This Month</div>
                    <div class="fw-bold fs-5">49</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(22,200,199,.10);">
                    <div class="text-muted small">QC Passed</div>
                    <div class="fw-bold fs-5">92%</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(245,158,11,.10);">
                    <div class="text-muted small">Pending QC</div>
                    <div class="fw-bold fs-5">7</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(239,68,68,.08);">
                    <div class="text-muted small">Reopened Cases</div>
                    <div class="fw-bold fs-5">2</div>
                </div>
            </div>
        </div>

    @include('support.resolution-details-modal')

        {{-- Resolution Table --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="min-width: 160px;">Ticket</th>
                        <th style="min-width: 170px;">Resolved By</th>
                        <th style="min-width: 260px;">Root Cause</th>
                        <th style="min-width: 260px;">Corrective Action</th>
                        <th style="min-width: 180px;">Resolution Time</th>
                        <th style="min-width: 180px;">Resolved Date</th>
                        <th style="min-width: 200px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-1003</a>
                            <div class="text-muted small">RS-6001</div>
                        </td>
                        <td>QC & Resolutions Team</td>
                        <td>Mismatch between serial database and production batch</td>
                        <td>Updated QC checklist + added cross-check step for serial-to-batch verification</td>
                        <td>
                            <span class="badge bg-success">18h 25m</span>
                        </td>
                        <td>2026-07-10</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#resolutionDetailsModal">
                                <i class="bi bi-eye me-1"></i> View details
                            </button>
                            <button class="btn btn-sm btn-outline-warning ms-1"><i class="bi bi-pencil me-1"></i> Edit</button>
                            <button class="btn btn-sm btn-outline-danger ms-1"><i class="bi bi-trash me-1"></i> Delete</button>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-1012</a>
                            <div class="text-muted small">RS-6014</div>
                        </td>
                        <td>Support Team B</td>
                        <td>Incorrect parts substitution rules during repair workflow</td>
                        <td>Revised substitution rules + added parts validation gate before closure</td>
                        <td>
                            <span class="badge bg-primary">12h 10m</span>
                        </td>
                        <td>2026-07-09</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#resolutionDetailsModal">
                                <i class="bi bi-eye me-1"></i> View details
                            </button>
                            <button class="btn btn-sm btn-outline-warning ms-1"><i class="bi bi-pencil me-1"></i> Edit</button>
                            <button class="btn btn-sm btn-outline-danger ms-1"><i class="bi bi-trash me-1"></i> Delete</button>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-1005</a>
                            <div class="text-muted small">RS-6022</div>
                        </td>
                        <td>Supplier Quality Team</td>
                        <td>Supplier batch documentation not aligned with acceptance criteria</td>
                        <td>Added documentation verification checklist for supplier batches</td>
                        <td>
                            <span class="badge bg-warning text-dark">9h 40m</span>
                        </td>
                        <td>2026-07-08</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#resolutionDetailsModal">
                                <i class="bi bi-eye me-1"></i> View details
                            </button>
                            <button class="btn btn-sm btn-outline-warning ms-1"><i class="bi bi-pencil me-1"></i> Edit</button>
                            <button class="btn btn-sm btn-outline-danger ms-1"><i class="bi bi-trash me-1"></i> Delete</button>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-1020</a>
                            <div class="text-muted small">RS-6031</div>
                        </td>
                        <td>Engineering Escalations</td>
                        <td>Recurring issue after repair indicates missing root-cause closure step</td>
                        <td>Escalated to engineering for root-cause analysis + updated escalation policy</td>
                        <td>
                            <span class="badge bg-danger">21h 05m</span>
                        </td>
                        <td>2026-07-06</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#resolutionDetailsModal">
                                <i class="bi bi-eye me-1"></i> View details
                            </button>
                            <button class="btn btn-sm btn-outline-warning ms-1"><i class="bi bi-pencil me-1"></i> Edit</button>
                            <button class="btn btn-sm btn-outline-danger ms-1"><i class="bi bi-trash me-1"></i> Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mt-4">
            <div class="text-muted small">Showing 4 of 77 resolutions (placeholder).</div>
            <nav aria-label="Resolution pagination">
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


