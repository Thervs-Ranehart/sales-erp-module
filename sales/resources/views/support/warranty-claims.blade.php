@extends('layouts.app')

@section('content')
    @php($title = 'Warranty Claims')
    @php($subtitle = 'Review and process warranty claims')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    @include('support.warranty-claim-view-modal')

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Pending</div>
                        <div class="display-6 fw-bold">{{ $pendingClaims ?? 0 }}</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(245,158,11,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-hourglass-split" style="color:#F59E0B; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-warning text-dark">Needs review</span></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Approved</div>
                        <div class="display-6 fw-bold">{{ $approvedClaims ?? 0 }}</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(22,200,199,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-check2-circle" style="color:#16C8C7; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-success">Ready to fulfill</span></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Rejected</div>
                        <div class="display-6 fw-bold">{{ $rejectedClaims ?? 0 }}</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(239,68,68,.10); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-x-circle" style="color:#EF4444; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-danger">No coverage</span></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Completed</div>
                        <div class="display-6 fw-bold">{{ $completedClaims ?? 0 }}</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(83,71,206,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-clipboard-check" style="color:#5347CE; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-primary">Closed loop</span></div>
            </div>
        </div>
    </div>


    {{-- Filters + top actions --}}
    <div class="card p-3 mt-4" style="background: rgba(255,255,255,.7); border: 1px solid rgba(0,0,0,.06); box-shadow: none;">
        <div class="row g-3">
            <div class="col-12 col-lg-4">
                <label class="form-label small text-muted">Search</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="background: rgba(83,71,206,.08); border-color: rgba(83,71,206,.2);">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Claim number, warranty number, customer..." aria-label="Search claims" />
                </div>
            </div>

            <div class="col-6 col-lg-2">
                <label class="form-label small text-muted">Status</label>
                <select class="form-select form-select-sm" aria-label="Status filter">
                    <option selected>Status: All</option>
                    <option>Pending</option>
                    <option>Approved</option>
                    <option>Rejected</option>
                    <option>Completed</option>
                </select>
            </div>

            <div class="col-6 col-lg-2">
                <label class="form-label small text-muted">Customer</label>
                <select class="form-select form-select-sm" aria-label="Customer filter">
                    <option selected>All customers</option>
                    <option>ABC Corporation</option>
                    <option>XYZ Trading</option>
                    <option>Northwind Retail</option>
                    <option>John Smith</option>
                </select>
            </div>

            <div class="col-12 col-lg-4 d-flex align-items-end justify-content-lg-end">
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-sm" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
                        <i class="bi bi-check2 me-1"></i> Review
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- Claims table --}}
    <div class="card p-4 mt-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold mb-0">Claims</h5>
            <div class="text-muted small">Review and process claim statuses.</div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="min-width: 160px;">Claim Number</th>
                        <th>Warranty Number</th>
                        <th style="min-width: 220px;">Customer</th>
                        <th>Product</th>
                        <th style="min-width: 160px;">Claim Date</th>
                        <th style="min-width: 160px;">Status</th>
                        <th style="min-width: 220px;">Assigned Staff</th>
                        <th class="text-end" style="min-width: 200px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warrantyClaims as $claim)
                        <tr>
                            <td class="fw-semibold">WC-{{ $claim->claim_id }}</td>
                            <td>{{ $claim->warrantyRecord->warranty_number ?? ('WR-' . $claim->warranty_id) }}</td>
                            <td>{{ $claim->warrantyRecord->order->customer->customer_name ?? '—' }}</td>
                            <td>{{ $claim->warrantyRecord->product->product_name ?? '—' }}</td>
                            <td class="text-muted">{{ $claim->claim_date ? $claim->claim_date->format('Y-m-d') : '—' }}</td>
                            <td>
                                @php($cs = strtolower((string)($claim->claim_status ?? '')))
                                @if($cs === 'pending')
                                    <span class="badge bg-warning text-dark">{{ $claim->claim_status }}</span>
                                @elseif($cs === 'approved')
                                    <span class="badge bg-primary">{{ $claim->claim_status }}</span>
                                @elseif($cs === 'rejected')
                                    <span class="badge bg-danger">{{ $claim->claim_status }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $claim->claim_status ?? '—' }}</span>
                                @endif
                            </td>
                            <td>{{ $claim->supportTicket->ticketAssignments->first()->employee->employee_name ?? '—' }}</td>
                            <td class="text-end" style="min-width: 260px; white-space: nowrap;">
                                <div class="d-flex align-items-center justify-content-end flex-nowrap gap-2">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#warrantyClaimModal">
                                        <i class="bi bi-eye me-1"></i><span class="text-nowrap"> View</span>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil me-1"></i><span class="text-nowrap"> Review</span></button>
                                    <button class="btn btn-sm btn-outline-success"><i class="bi bi-arrow-repeat me-1"></i><span class="text-nowrap"> Update Status</span></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No warranty claims found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mt-4">
            <div class="text-muted small">Showing results.</div>
            <nav aria-label="Claims pagination">
                {{ $warrantyClaims->links() }}
            </nav>
        </div>
    </div>
@endsection







