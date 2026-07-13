@extends('layouts.app')

@section('content')
    @php($title = 'Service Requests')
    @php($subtitle = 'Schedule and manage service requests')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    <div class="card p-4">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Field Dispatch & Scheduling</h5>
                <div class="text-muted small">Coordinate on-site repairs, parts pickup, and service visit SLAs.</div>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <div class="input-group input-group-sm" style="min-width: 260px;">
                    <span class="input-group-text" style="background: rgba(83,71,206,.08); border-color: rgba(83,71,206,.2);">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Search request (e.g., SR-5001)" aria-label="Search service requests" />
                </div>

                <select class="form-select form-select-sm" style="min-width: 190px;" aria-label="Filter by request status">
                    <option selected>Status: In Queue</option>
                    <option>Status: Scheduled</option>
                    <option>Status: Completed</option>
                    <option>Status: Cancelled</option>
                </select>

                <button class="btn btn-sm" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
                    <i class="bi bi-calendar3 me-1"></i> Schedule
                </button>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="p-3 rounded-3" style="background: rgba(83,71,206,.08);">
                    <div class="text-muted small">Requests in Queue</div>
                    <div class="fw-bold fs-5">23</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded-3" style="background: rgba(22,200,199,.10);">
                    <div class="text-muted small">Scheduled for Next 7d</div>
                    <div class="fw-bold fs-5">14</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded-3" style="background: rgba(245,158,11,.10);">
                    <div class="text-muted small">In Transit / Parts Pending</div>
                    <div class="fw-bold fs-5">6</div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="min-width: 150px;">Request #</th>
                        <th>Ticket</th>
                        <th>Type</th>
                        <th style="min-width: 210px;">Planned Visit</th>
                        <th style="min-width: 160px;">Status</th>
                        <th class="text-end" style="min-width: 160px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-semibold">SR-5001</td>
                        <td><a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-1002</a></td>
                        <td>
                            <div class="fw-semibold">On-site Repair</div>
                            <div class="text-muted small">Priority: High • SLA Tier-1</div>
                        </td>
                        <td>
                            <div class="small text-muted mb-1">Next business day</div>
                            <div class="small">Window: <span class="fw-semibold">09:00–12:00</span></div>
                        </td>
                        <td><span class="badge bg-primary">In Queue</span></td>
                        <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('support.resolution-tracking') }}">Dispatch</a></td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">SR-5023</td>
                        <td><a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-1012</a></td>
                        <td>
                            <div class="fw-semibold">Warranty Inspection</div>
                            <div class="text-muted small">Priority: Medium • SLA Tier-2</div>
                        </td>
                        <td>
                            <div class="small text-muted mb-1">Scheduled</div>
                            <div class="small">Window: <span class="fw-semibold">14:00–16:30</span></div>
                        </td>
                        <td><span class="badge bg-success">Scheduled</span></td>
                        <td class="text-end"><a class="btn btn-sm btn-outline-success" href="{{ route('support.resolution-tracking') }}">Confirm</a></td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">SR-5040</td>
                        <td><a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-1005</a></td>
                        <td>
                            <div class="fw-semibold">Parts Replacement</div>
                            <div class="text-muted small">Parts pending approval and shipment</div>
                        </td>
                        <td>
                            <div class="small text-muted mb-1">Awaiting parts</div>
                            <div class="small">ETA: <span class="fw-semibold">2 days</span></div>
                        </td>
                        <td><span class="badge bg-warning text-dark">Parts Pending</span></td>
                        <td class="text-end"><a class="btn btn-sm btn-outline-warning" href="{{ route('support.warranty-records') }}">Check parts</a></td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">SR-5056</td>
                        <td><a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-1020</a></td>
                        <td>
                            <div class="fw-semibold">Escalated Field Service</div>
                            <div class="text-muted small">Escalation: repeat failure</div>
                        </td>
                        <td>
                            <div class="small text-muted mb-1">Cancelled</div>
                            <div class="small">Reason: customer rescheduled</div>
                        </td>
                        <td><span class="badge bg-secondary">Cancelled</span></td>
                        <td class="text-end"><a class="btn btn-sm btn-outline-secondary" href="{{ route('support.customer-satisfaction') }}">Notify</a></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mt-4">
            <div class="text-muted small">Showing 4 of 48 service requests (placeholder).</div>
            <nav aria-label="Service request pagination">
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


