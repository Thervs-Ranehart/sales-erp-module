@extends('layouts.app')

@section('content')
    @php($title = 'Service Requests')
    @php($subtitle = 'Schedule and manage service requests')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    @include('support.service-request-scheduling-modal')

    {{-- Summary cards --}}
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Pending</div>
                        <div class="display-6 fw-bold">12</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(245,158,11,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-hourglass-split" style="color:#F59E0B; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-warning text-dark">Awaiting dispatch</span></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Scheduled</div>
                        <div class="display-6 fw-bold">18</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(83,71,206,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-calendar3" style="color:#5347CE; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-primary">Visits planned</span></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">In Progress</div>
                        <div class="display-6 fw-bold">9</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(22,200,199,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-tools" style="color:#16C8C7; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-success">Work underway</span></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Completed</div>
                        <div class="display-6 fw-bold">21</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(239,68,68,.10); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-check2-all" style="color:#EF4444; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-danger">Closed</span></div>
            </div>
        </div>
    </div>

    {{-- Search + Filters --}}
    <div class="card p-3 mt-4" style="background: rgba(255,255,255,.7); border: 1px solid rgba(0,0,0,.06); box-shadow: none;">
        <div class="row g-3">
            <div class="col-12 col-lg-4">
                <label class="form-label small text-muted">Search</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="background: rgba(83,71,206,.08); border-color: rgba(83,71,206,.2);">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Request number, ticket, customer..." aria-label="Search service requests" />
                </div>
            </div>

            <div class="col-6 col-lg-2">
                <label class="form-label small text-muted">Status</label>
                <select class="form-select form-select-sm" aria-label="Status filter">
                    <option selected>Status: All</option>
                    <option>Pending</option>
                    <option>Scheduled</option>
                    <option>In Progress</option>
                    <option>Completed</option>
                </select>
            </div>

            <div class="col-6 col-lg-2">
                <label class="form-label small text-muted">Technician</label>
                <select class="form-select form-select-sm" aria-label="Technician filter">
                    <option selected>All technicians</option>
                    <option>Field Engineer A</option>
                    <option>Field Engineer B</option>
                    <option>Warranty Partner</option>
                </select>
            </div>

            <div class="col-6 col-lg-2">
                <label class="form-label small text-muted">Date</label>
                <input type="date" class="form-control form-control-sm" aria-label="Request date" />
            </div>

            <div class="col-12 col-lg-2 d-flex align-items-end justify-content-lg-end">
                <button class="btn btn-sm" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
                    <i class="bi bi-funnel me-1"></i> Apply
                </button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card p-4 mt-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold mb-0">Service Requests</h5>
            <div class="text-muted small">Manage service requests and their schedules.</div>

        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="min-width: 160px;">Request Number</th>
                        <th>Ticket</th>
                        <th style="min-width: 220px;">Customer</th>
                        <th style="min-width: 220px;">Technician</th>
                        <th style="min-width: 190px;">Schedule</th>
                        <th style="min-width: 160px;">Status</th>
                        <th class="text-end" style="min-width: 200px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-semibold">SR-5001</td>
                        <td><a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-1002</a></td>
                        <td>XYZ Trading</td>
                        <td>Field Engineer A</td>
                        <td>
                            <div class="small text-muted mb-1">2026-07-14</div>
                            <div>09:00–12:00</div>
                        </td>
                        <td><span class="badge bg-primary">Pending</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#serviceRequestScheduleModal">
                                <i class="bi bi-calendar3 me-1"></i> Schedule
                            </button>
                            <button class="btn btn-sm btn-outline-warning ms-1"><i class="bi bi-pencil me-1"></i> Edit</button>
                            <button class="btn btn-sm btn-outline-danger ms-1"><i class="bi bi-trash me-1"></i> Delete</button>
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">SR-5023</td>
                        <td><a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-1012</a></td>
                        <td>ABC Corporation</td>
                        <td>Field Engineer B</td>
                        <td>
                            <div class="small text-muted mb-1">2026-07-15</div>
                            <div>14:00–16:30</div>
                        </td>
                        <td><span class="badge bg-success">Scheduled</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#serviceRequestScheduleModal">
                                <i class="bi bi-calendar3 me-1"></i> Schedule
                            </button>
                            <button class="btn btn-sm btn-outline-warning ms-1"><i class="bi bi-pencil me-1"></i> Edit</button>
                            <button class="btn btn-sm btn-outline-danger ms-1"><i class="bi bi-trash me-1"></i> Delete</button>
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">SR-5040</td>
                        <td><a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-1005</a></td>
                        <td>Greenfield Industries</td>
                        <td>Warranty Partner</td>
                        <td>
                            <div class="small text-muted mb-1">2026-07-16</div>
                            <div>10:00–13:00</div>
                        </td>
                        <td><span class="badge bg-warning text-dark">In Progress</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#serviceRequestScheduleModal">
                                <i class="bi bi-calendar3 me-1"></i> Schedule
                            </button>
                            <button class="btn btn-sm btn-outline-warning ms-1"><i class="bi bi-pencil me-1"></i> Edit</button>
                            <button class="btn btn-sm btn-outline-danger ms-1"><i class="bi bi-trash me-1"></i> Delete</button>
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">SR-5056</td>
                        <td><a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-1020</a></td>
                        <td>Northwind Retail</td>
                        <td>Field Engineer A</td>
                        <td>
                            <div class="small text-muted mb-1">2026-07-13</div>
                            <div>Completed</div>
                        </td>

                        <td><span class="badge bg-danger">Completed</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#serviceRequestScheduleModal">
                                <i class="bi bi-calendar3 me-1"></i> Schedule
                            </button>
                            <button class="btn btn-sm btn-outline-warning ms-1"><i class="bi bi-pencil me-1"></i> Edit</button>
                            <button class="btn btn-sm btn-outline-danger ms-1"><i class="bi bi-trash me-1"></i> Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mt-4">
            <div class="text-muted small">Showing results.</div>

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



