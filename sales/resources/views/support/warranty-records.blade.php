@extends('layouts.app')

@section('content')
    @php($title = 'Warranty Records')
    @php($subtitle = 'Track warranty coverage by order and product')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    @include('support.warranty-view-modal')

    <div class="card p-4">
        {{-- Header + Actions --}}
        <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3 mb-4">
            <div>
                <h5 class="fw-bold mb-1">Warranty Registry</h5>
                <div class="text-muted small">Manage warranties, eligibility windows, and status.</div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('support.index') }}" class="text-decoration-none">Support</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Warranty Records</li>
                    </ol>
                </nav>
            </div>



        </div>

        {{-- Filters --}}
        <div class="card p-3 mb-4" style="background: rgba(255,255,255,.7); border: 1px solid rgba(0,0,0,.06); box-shadow: none;">
            <div class="row g-3">
                <div class="col-12 col-lg-4">
                    <label class="form-label small text-muted">Search</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="background: rgba(83,71,206,.08); border-color: rgba(83,71,206,.2);">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Warranty number, customer, product..." aria-label="Search warranties" />
                    </div>
                </div>

                <div class="col-6 col-lg-2">
                    <label class="form-label small text-muted">Status</label>
                    <select class="form-select form-select-sm" aria-label="Status filter">
                        <option selected>Status: All</option>
                        <option>Status: Active</option>
                        <option>Status: Expiring Soon</option>
                        <option>Status: Expired</option>
                        <option>Status: On Hold</option>
                    </select>
                </div>

                <div class="col-6 col-lg-2">
                    <label class="form-label small text-muted">Product</label>
                    <select class="form-select form-select-sm" aria-label="Product filter">
                        <option selected>Product: All</option>
                        <option>Widget A</option>
                        <option>Widget B</option>
                        <option>Industrial Pump X</option>
                        <option>Appliance Z</option>
                    </select>
                </div>

                <div class="col-12 col-lg-4 d-flex align-items-end">
                    <button class="btn btn-sm" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
                        <i class="bi bi-funnel me-1"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="min-width: 150px;">Warranty Number</th>
                        <th style="min-width: 200px;">Customer</th>
                        <th>Product</th>
                        <th style="min-width: 160px;">Order</th>
                        <th style="min-width: 160px;">Warranty Start</th>
                        <th style="min-width: 160px;">Warranty End</th>
                        <th style="min-width: 150px;">Status</th>
                        <th class="text-end" style="min-width: 200px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-semibold">WR-2001</td>
                        <td>ABC Corporation</td>
                        <td>Widget A</td>
                        <td>SO-9001</td>
                        <td class="text-muted">2025-06-10</td>
                        <td class="text-muted">2026-12-12</td>
                        <td><span class="badge bg-success">Active</span></td>

                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#warrantyViewModal">
                                <i class="bi bi-eye me-1"></i> View
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">WR-2007</td>
                        <td>XYZ Trading</td>
                        <td>Widget B</td>
                        <td>SO-9034</td>
                        <td class="text-muted">2025-07-01</td>
                        <td class="text-muted">2026-08-15</td>
                        <td><span class="badge bg-warning text-dark">Expiring Soon</span></td>

                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#warrantyViewModal">
                                <i class="bi bi-eye me-1"></i> View
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">WR-2013</td>
                        <td>Northwind Retail</td>
                        <td>Industrial Pump X</td>
                        <td>SO-9098</td>
                        <td class="text-muted">2024-04-18</td>
                        <td class="text-muted">2025-06-18</td>
                        <td><span class="badge bg-danger">Expired</span></td>

                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#warrantyViewModal">
                                <i class="bi bi-eye me-1"></i> View
                            </button>


                        </td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">WR-2020</td>
                        <td>Greenfield Industries</td>
                        <td>Appliance Z</td>
                        <td>SO-9120</td>
                        <td class="text-muted">2025-09-22</td>
                        <td class="text-muted">2026-12-15</td>
                        <td><span class="badge bg-danger">Expired</span></td>


                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#warrantyViewModal">
                                <i class="bi bi-eye me-1"></i> View
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mt-4">
            <div class="text-muted small">Showing results.</div>

            <nav aria-label="Warranty pagination">
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



