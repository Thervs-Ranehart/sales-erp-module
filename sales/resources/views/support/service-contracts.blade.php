@extends('layouts.app')

@section('content')
    @php($title = 'Service Contracts')
    @php($subtitle = 'Manage maintenance and service coverage')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    @include('support.service-contract-view-modal')

    {{-- Contract summary cards --}}
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Active</div>
                        <div class="display-6 fw-bold">76</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(22,200,199,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-shield-check" style="color:#16C8C7; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-success">Coverage enabled</span></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Expiring</div>
                        <div class="display-6 fw-bold">11</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(245,158,11,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-hourglass-split" style="color:#F59E0B; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-warning text-dark">Renewal window</span></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Suspended</div>
                        <div class="display-6 fw-bold">3</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(83,71,206,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-pause-circle" style="color:#5347CE; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-primary">On hold</span></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Expired</div>
                        <div class="display-6 fw-bold">7</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(239,68,68,.10); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-x-circle" style="color:#EF4444; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-danger">Expired terms</span></div>
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
                    <input type="text" class="form-control" placeholder="Contract number, customer, service type..." aria-label="Search contracts" />
                </div>
            </div>

            <div class="col-6 col-lg-2">
                <label class="form-label small text-muted">Status</label>
                <select class="form-select form-select-sm" aria-label="Status filter">
                    <option selected>Status: All</option>
                    <option>Active</option>
                    <option>Expiring</option>
                    <option>Suspended</option>
                    <option>Expired</option>
                </select>
            </div>

            <div class="col-6 col-lg-2">
                <label class="form-label small text-muted">Customer</label>
                <select class="form-select form-select-sm" aria-label="Customer filter">
                    <option selected>All customers</option>
                    <option>XYZ Trading</option>
                    <option>ABC Corporation</option>
                    <option>Northwind Retail</option>
                    <option>Greenfield Industries</option>
                </select>
            </div>

            <div class="col-12 col-lg-4 d-flex align-items-end justify-content-lg-end">
                <div class="d-flex gap-2 flex-wrap">
                    {{-- Service Coordinator: view-only contracts (no create/renew/edit/delete) --}}
                </div>

            </div>
        </div>
    </div>

    {{-- Contract table --}}
    <div class="card p-4 mt-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold mb-0">Contracts</h5>
            <div class="text-muted small">View contract details and status.</div>
        </div>


        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th style="min-width: 160px;">Contract Number</th>
                        <th style="min-width: 220px;">Customer</th>
                        <th>Product</th>
                        <th style="min-width: 200px;">Service Type</th>
                        <th style="min-width: 160px;">Start Date</th>
                        <th style="min-width: 160px;">End Date</th>
                        <th style="min-width: 150px;">Status</th>
                        <th class="text-end" style="min-width: 220px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-semibold">SC-4001</td>
                        <td>XYZ Trading</td>
                        <td>Widget A</td>
                        <td>Extended Support</td>
                        <td class="text-muted">2026-01-01</td>
                        <td class="text-muted">2026-08-20</td>
                        <td><span class="badge bg-success">Active</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#serviceContractModal">
                                <i class="bi bi-eye me-1"></i> View
                            </button>
                            <button class="btn btn-sm btn-outline-warning ms-1"><i class="bi bi-pencil me-1"></i> Edit</button>
                            <button class="btn btn-sm btn-outline-danger ms-1"><i class="bi bi-trash me-1"></i> Delete</button>
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">SC-4007</td>
                        <td>ABC Corporation</td>
                        <td>Industrial Pump X</td>
                        <td>Premium Maintenance</td>
                        <td class="text-muted">2025-10-15</td>
                        <td class="text-muted">2026-04-03</td>
                        <td><span class="badge bg-warning text-dark">Expiring</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#serviceContractModal">
                                <i class="bi bi-eye me-1"></i> View
                            </button>
                            <button class="btn btn-sm btn-outline-warning ms-1"><i class="bi bi-pencil me-1"></i> Edit</button>
                            <button class="btn btn-sm btn-outline-danger ms-1"><i class="bi bi-trash me-1"></i> Delete</button>
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">SC-4011</td>
                        <td>Northwind Retail</td>
                        <td>Appliance Z</td>
                        <td>Standard Support</td>
                        <td class="text-muted">2025-02-01</td>
                        <td class="text-muted">2026-01-10</td>
                        <td><span class="badge bg-danger">Expired</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#serviceContractModal">
                                <i class="bi bi-eye me-1"></i> View
                            </button>
                            <button class="btn btn-sm btn-outline-warning ms-1"><i class="bi bi-pencil me-1"></i> Edit</button>
                            <button class="btn btn-sm btn-outline-danger ms-1"><i class="bi bi-trash me-1"></i> Delete</button>
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">SC-4018</td>
                        <td>Greenfield Industries</td>
                        <td>Widget B</td>
                        <td>On-demand Service</td>
                        <td class="text-muted">2026-03-20</td>
                        <td class="text-muted">2026-09-30</td>
                        <td><span class="badge bg-primary">Review</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#serviceContractModal">
                                <i class="bi bi-eye me-1"></i> View
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



