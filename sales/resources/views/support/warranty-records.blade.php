@extends('layouts.app')

@section('content')
    @php($title = 'Warranty Records')
    @php($subtitle = 'Track warranty coverage by order and product')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    <div class="card p-4">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Warranty Coverage Registry</h5>
                <div class="text-muted small">Review active warranties, expiration dates, and coverage status.</div>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <div class="input-group input-group-sm" style="min-width: 260px;">
                    <span class="input-group-text" style="background: rgba(83,71,206,.08); border-color: rgba(83,71,206,.2);">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Search warranty (e.g., WR-2001)" aria-label="Search warranties" />
                </div>

                <select class="form-select form-select-sm" style="min-width: 190px;" aria-label="Filter by coverage status">
                    <option selected>Status: Active</option>
                    <option>Status: Expiring Soon</option>
                    <option>Status: Expired</option>
                    <option>Status: On Hold</option>
                </select>

                <button class="btn btn-sm" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
                    <i class="bi bi-download me-1"></i> Export
                </button>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(83,71,206,.08);">
                    <div class="text-muted small">Active Coverage</div>
                    <div class="fw-bold fs-5">214</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(245,158,11,.10);">
                    <div class="text-muted small">Expiring (30d)</div>
                    <div class="fw-bold fs-5">18</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(239,68,68,.08);">
                    <div class="text-muted small">Expired</div>
                    <div class="fw-bold fs-5">9</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(22,200,199,.10);">
                    <div class="text-muted small">On Hold</div>
                    <div class="fw-bold fs-5">4</div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="min-width: 130px;">Warranty #</th>
                        <th>Order</th>
                        <th>Product</th>
                        <th style="min-width: 160px;">Coverage</th>
                        <th style="min-width: 160px;">Status</th>
                        <th class="text-end" style="min-width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-semibold">WR-2001</td>
                        <td>SO-9001</td>
                        <td>
                            <div class="fw-semibold">Widget A</div>
                            <div class="text-muted small">SN: WA-8821 • Qty: 1</div>
                        </td>
                        <td>
                            <div class="small text-muted mb-1">Starts 2025-06-10</div>
                            <div class="small">Expires in <span class="fw-semibold">185d</span></div>
                        </td>
                        <td><span class="badge bg-warning text-dark">Active</span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('support.warranty-claims') }}">Claims</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">WR-2007</td>
                        <td>SO-9034</td>
                        <td>
                            <div class="fw-semibold">Widget B</div>
                            <div class="text-muted small">SN: WB-1930 • Qty: 2</div>
                        </td>
                        <td>
                            <div class="small text-muted mb-1">Starts 2025-07-01</div>
                            <div class="small">Expires in <span class="fw-semibold">28d</span></div>
                        </td>
                        <td><span class="badge bg-primary">Expiring Soon</span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('support.warranty-claims') }}">Review</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">WR-2013</td>
                        <td>SO-9098</td>
                        <td>
                            <div class="fw-semibold">Industrial Pump X</div>
                            <div class="text-muted small">SN: IP-5512 • Qty: 1</div>
                        </td>
                        <td>
                            <div class="small text-muted mb-1">Starts 2024-04-18</div>
                            <div class="small">Expired <span class="fw-semibold">12d ago</span></div>
                        </td>
                        <td><span class="badge bg-danger">Expired</span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-danger" href="{{ route('support.warranty-claims') }}">View</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="fw-semibold">WR-2020</td>
                        <td>SO-9120</td>
                        <td>
                            <div class="fw-semibold">Appliance Z</div>
                            <div class="text-muted small">SN: AZ-7703 • Qty: 1</div>
                        </td>
                        <td>
                            <div class="small text-muted mb-1">Starts 2025-09-22</div>
                            <div class="small">Expires in <span class="fw-semibold">302d</span></div>
                        </td>
                        <td><span class="badge bg-secondary">On Hold</span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('support.warranty-claims') }}">Inspect</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mt-4">
            <div class="text-muted small">Showing 4 of 245 warranties (placeholder).</div>
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


