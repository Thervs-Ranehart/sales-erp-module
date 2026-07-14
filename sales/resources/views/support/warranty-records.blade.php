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
        <form method="GET">
        <div class="card p-3 mb-4" style="background: rgba(255,255,255,.7); border: 1px solid rgba(0,0,0,.06); box-shadow: none;">
            <div class="row g-3">

                <div class="col-12 col-lg-4">
                    <label class="form-label small text-muted">Search</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="background: rgba(83,71,206,.08); border-color: rgba(83,71,206,.2);">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="Warranty number, customer, product..." aria-label="Search warranties" value="{{ $search ?? '' }}" />
                    </div>

                </div>

                <div class="col-6 col-lg-2">
                    <label class="form-label small text-muted">Status</label>
                    <select class="form-select form-select-sm" aria-label="Status filter" name="status">
                        <option value="all" {{ ($status ?? '') === '' || ($status ?? '') === 'all' ? 'selected' : '' }}>Status: All</option>
                        <option value="Active" {{ ($status ?? '') === 'Active' ? 'selected' : '' }}>Status: Active</option>
                        <option value="Expiring Soon" {{ ($status ?? '') === 'Expiring Soon' ? 'selected' : '' }}>Status: Expiring Soon</option>
                        <option value="Expired" {{ ($status ?? '') === 'Expired' ? 'selected' : '' }}>Status: Expired</option>
                        <option value="On Hold" {{ ($status ?? '') === 'On Hold' ? 'selected' : '' }}>Status: On Hold</option>
                    </select>

                </div>

                <div class="col-6 col-lg-2">
                    <label class="form-label small text-muted">Product</label>
                    <select class="form-select form-select-sm" aria-label="Product filter" name="product">
                        <option value="all" {{ ($product ?? '') === '' || ($product ?? '') === 'all' ? 'selected' : '' }}>Product: All</option>
                        <option value="Widget A" {{ ($product ?? '') === 'Widget A' ? 'selected' : '' }}>Widget A</option>
                        <option value="Widget B" {{ ($product ?? '') === 'Widget B' ? 'selected' : '' }}>Widget B</option>
                        <option value="Industrial Pump X" {{ ($product ?? '') === 'Industrial Pump X' ? 'selected' : '' }}>Industrial Pump X</option>
                        <option value="Appliance Z" {{ ($product ?? '') === 'Appliance Z' ? 'selected' : '' }}>Appliance Z</option>
                    </select>

                </div>

                <div class="col-12 col-lg-4 d-flex align-items-end">
                    <button class="btn btn-sm" type="submit" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
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
                    @forelse($warrantyRecords as $warranty)
                        <tr>
                            <td class="fw-semibold">{{ $warranty->warranty_number ?? ('WR-' . $warranty->warranty_id) }}</td>
                            <td>{{ $warranty->order->customer->customer_name ?? '—' }}</td>
                            <td>{{ $warranty->product->product_name ?? '—' }}</td>
                            <td>{{ $warranty->order->order_number ?? ('SO-' . $warranty->order_id) }}</td>
                            <td class="text-muted">{{ $warranty->warranty_start ? $warranty->warranty_start->format('Y-m-d') : '—' }}</td>
                            <td class="text-muted">{{ $warranty->warranty_end ? $warranty->warranty_end->format('Y-m-d') : '—' }}</td>
                            <td>
                                @php($ws = strtolower((string)($warranty->warranty_status ?? '')))
                                @if($ws === 'active')
                                    <span class="badge bg-success">{{ $warranty->warranty_status }}</span>
                                @elseif($ws === 'expiring soon')
                                    <span class="badge bg-warning text-dark">{{ $warranty->warranty_status }}</span>
                                @elseif($ws === 'expired')
                                    <span class="badge bg-danger">{{ $warranty->warranty_status }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $warranty->warranty_status ?? '—' }}</span>
                                @endif
                            </td>

                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#warrantyViewModal">
                                    <i class="bi bi-eye me-1"></i> View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No warranty records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mt-4">
            <div class="text-muted small">Showing results.</div>

            <nav aria-label="Warranty pagination">
                {{ $warrantyRecords->links() }}
            </nav>
        </div>
    </div>

    </form>
@endsection




