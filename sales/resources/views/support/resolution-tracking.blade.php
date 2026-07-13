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

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="min-width: 160px;">Resolution #</th>
                        <th>Ticket</th>
                        <th style="min-width: 320px;">Summary</th>
                        <th style="min-width: 180px;">Resolved At</th>
                        <th style="min-width: 170px;">Outcome</th>
                        <th class="text-end" style="min-width: 160px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-semibold">RS-6001</td>
                        <td><a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-1003</a></td>
                        <td>
                            <div class="fw-semibold">Replacement issued for warranty claim</div>
                            <div class="text-muted small">Corrective action: revised QC checklist for serial validation.</div>
                        </td>
                        <td>2026-07-10</td>
                        <td><span class="badge bg-success">Closed</span></td>
                        <td class="text-end"><a class="btn btn-sm btn-outline-success" href="{{ route('support.customer-satisfaction') }}">CSAT</a></td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">RS-6014</td>
                        <td><a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-1012</a></td>
                        <td>
                            <div class="fw-semibold">Repair completed and device tested</div>
                            <div class="text-muted small">Corrective action: update parts substitution rules.</div>
                        </td>
                        <td>2026-07-09</td>
                        <td><span class="badge bg-primary">Resolved</span></td>
                        <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('support.customer-satisfaction') }}">Request rating</a></td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">RS-6022</td>
                        <td><a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-1005</a></td>
                        <td>
                            <div class="fw-semibold">Parts replaced; awaiting final QC sign-off</div>
                            <div class="text-muted small">Corrective action: verify supplier batch documentation.</div>
                        </td>
                        <td>2026-07-08</td>
                        <td><span class="badge bg-warning text-dark">Pending QC</span></td>
                        <td class="text-end"><a class="btn btn-sm btn-outline-warning" href="#">Open QC</a></td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">RS-6031</td>
                        <td><a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-1020</a></td>
                        <td>
                            <div class="fw-semibold">Case reopened due to recurring issue</div>
                            <div class="text-muted small">Corrective action: escalate to engineering root cause analysis.</div>
                        </td>
                        <td>2026-07-06</td>
                        <td><span class="badge bg-danger">Reopened</span></td>
                        <td class="text-end"><a class="btn btn-sm btn-outline-danger" href="#">Escalate</a></td>
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


