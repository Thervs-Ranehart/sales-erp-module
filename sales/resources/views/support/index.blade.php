@extends('layouts.app')

@section('content')
    @php($title = 'After-Sales Support & Case Management')
    @php($subtitle = 'Monitor support tickets, warranty claims, and customer service activity')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    {{-- Summary Cards --}}
    <div class="row g-4">
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Open Tickets</div>
                        <div class="display-6 fw-bold">{{ $ticketCount ?? 0 }}</div>

                    </div>
                    <div class="rounded-3" style="background:rgba(83,71,206,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-ticket-perforated" style="color:#5347CE; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-primary">Awaiting response</span></div>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>

                    </div>
                    <div class="rounded-3" style="background:rgba(245,158,11,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-shield-check" style="color:#F59E0B; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-warning text-dark">Pending review</span></div>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Active Warranties</div>
                        <div class="display-6 fw-bold">{{ $activeWarrantyCount ?? 0 }}</div>

                    </div>
                    <div class="rounded-3" style="background:rgba(22,200,199,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-clipboard-check" style="color:#16C8C7; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-success">Coverage active</span></div>
            </div>
        </div>



        <div class="col-md-4 col-lg-3">
            <div class="card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Customer Satisfaction</div>
                        <div class="display-6 fw-bold">{{ $fiveStarPct ?? 0 }}%</div>

                    </div>
                    <div class="rounded-3" style="background:rgba(239,68,68,.10); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-star" style="color:#EF4444; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-danger">CSAT target met</span></div>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Notifications</div>
                        <div class="display-6 fw-bold">{{ $notificationsCount ?? 0 }}</div>

                    </div>
                    <div class="rounded-3" style="background:rgba(83,71,206,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-bell" style="color:#5347CE; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-primary">Action required</span></div>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Resolved Cases</div>
                        <div class="display-6 fw-bold">{{ $resolvedCaseCount ?? 0 }}</div>

                    </div>
                    <div class="rounded-3" style="background:rgba(245,158,11,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-check2-circle" style="color:#F59E0B; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-warning text-dark">Completed this month</span></div>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card p-3 p-md-4">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div>
                        <h5 class="fw-bold mb-1">Quick Actions</h5>
                        <div class="text-muted small">Create new items and route them into the correct workflow.</div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                    </div>


                </div>
            </div>
        </div>
    </div>

    {{-- Tables + Overviews --}}
    <div class="row mt-4 g-4">
        <div class="col-lg-6">
            <div class="card p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-bold mb-0">Recent Support Tickets</h5>
                    <a class="text-decoration-none" style="color:#5347CE;font-weight:700;" href="{{ route('support.tickets') }}">
                        View all <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Ticket</th>
                                <th>Customer</th>
                                <th>Issue</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTickets as $ticket)
                                <tr>
                                    <td class="fw-semibold">TK-{{ $ticket->ticket_id }}</td>
                                    <td>{{ $ticket->customer->customer_name ?? '—' }}</td>
                                    <td>{{ $ticket->subject ?? '—' }}</td>
                                    <td>
                                        @php($st = strtolower((string)($ticket->status ?? '')))
                                        @if($st === 'pending')
                                            <span class="badge bg-warning text-dark">{{ $ticket->status }}</span>
                                        @elseif($st === 'in progress')
                                            <span class="badge bg-primary">{{ $ticket->status }}</span>
                                        @elseif($st === 'resolved')
                                            <span class="badge bg-success">{{ $ticket->status }}</span>
                                        @elseif($st === 'escalated')
                                            <span class="badge bg-danger">{{ $ticket->status }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $ticket->status ?? '—' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No recent tickets.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card p-4 h-100">


                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Claim</th>
                                <th>Customer</th>
                                <th>Reason</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentWarrantyRecords as $warranty)
                                <tr>
                                    <td class="fw-semibold">{{ $warranty->warranty_number ?? ('WR-' . $warranty->warranty_id) }}</td>
                                    <td>{{ $warranty->order->customer->customer_name ?? '—' }}</td>
                                    <td>{{ $warranty->warranty_status ?? '—' }}</td>
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No recent warranty records.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card p-4 h-100">


                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Request</th>
                                <th>Ticket</th>
                                <th>Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentServiceRequests as $contract)
                                <tr>
                                    <td class="fw-semibold">{{ $contract->contract_number ?? ('SC-' . $contract->contract_id) }}</td>
                                    <td>{{ $contract->customer->customer_name ?? '—' }}</td>
                                    <td>{{ $contract->service_type ?? '—' }}</td>
                                    <td>
                                        @php($cs = strtolower((string)($contract->contract_status ?? '')))
                                        @if($cs === 'active')
                                            <span class="badge bg-success">{{ $contract->contract_status }}</span>
                                        @elseif($cs === 'expiring')
                                            <span class="badge bg-warning text-dark">{{ $contract->contract_status }}</span>
                                        @elseif($cs === 'expired')
                                            <span class="badge bg-danger">{{ $contract->contract_status }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $contract->contract_status ?? '—' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No recent service contracts.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-bold mb-0">Customer Satisfaction Overview</h5>
                    <a class="text-decoration-none" style="color:#5347CE;font-weight:700;" href="{{ route('support.customer-satisfaction') }}">
                        Details <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-4">
                        <div class="p-3 rounded-3" style="background: rgba(22,200,199,.10);">
                            <div class="text-muted small">Avg Rating</div>
                        <div class="fw-bold fs-5">{{ $satisfactionAvg ?? 0 }}</div>

                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="p-3 rounded-3" style="background: rgba(83,71,206,.08);">
                            <div class="text-muted small">Promoters</div>
                        <div class="fw-bold fs-5">{{ $fiveStarPct ?? 0 }}%</div>

                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="p-3 rounded-3" style="background: rgba(245,158,11,.10);">
                            <div class="text-muted small">Neutrals</div>
                        <div class="fw-bold fs-5">{{ max(0, 100 - (int)($fiveStarPct ?? 0) - (int)($minRatingPct ?? 0)) }}%</div>

                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-muted small">CSAT Theme</div>
                        <span class="badge bg-primary"><i class="bi bi-chat-dots me-1"></i> Communication</span>
                    </div>

                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="text-muted small mt-2">Insight summary based on recent feedback.</div>
                </div>


                <div class="list-group">
                    <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="{{ route('support.customer-satisfaction') }}">
                        <span class="badge bg-success">{{ (int)($fiveStarPct ?? 0) }}</span>

                    </a>
                    <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="{{ route('support.customer-satisfaction') }}">
                        <span class="badge bg-warning text-dark">{{ (int)($totalSatisfaction ?? 0) }}</span>

                    </a>
                    <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="{{ route('support.customer-satisfaction') }}">
                        <span class="badge bg-danger">{{ (int)($minRatingPct ?? 0) }}</span>

                    </a>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card p-4">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
                    <h5 class="fw-bold mb-0">Recent Notifications</h5>
                    <div class="d-flex gap-2 flex-wrap">
                        <a class="btn btn-sm" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);" href="#">
                            <i class="bi bi-check2-all me-1"></i> Mark all read
                        </a>
                        <a class="btn btn-sm btn-outline-primary" href="#">
                            <i class="bi bi-bell me-1"></i> Notification settings
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Type</th>
                                <th>Reference</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-muted">2026-07-12 10:15</td>
                                <td><span class="badge bg-primary"><i class="bi bi-ticket me-1"></i> Ticket</span></td>
                                <td class="fw-semibold">TK-1020</td>
                                <td><span class="badge bg-danger">Escalation needed</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">2026-07-12 09:02</td>
                                <td><span class="badge bg-warning text-dark"><i class="bi bi-shield-check me-1"></i> Claim</span></td>
                                <td class="fw-semibold">WC-3001</td>
                                <td><span class="badge bg-warning text-dark">Pending verification</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">2026-07-11 16:40</td>
                                <td><span class="badge bg-success"><i class="bi bi-tools me-1"></i> Service</span></td>
                                <td class="fw-semibold">SR-5001</td>
                                <td><span class="badge bg-success">Scheduled visit</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">2026-07-10 13:20</td>
                                <td><span class="badge bg-secondary"><i class="bi bi-person-workspace me-1"></i> QC</span></td>
                                <td class="fw-semibold">RS-6001</td>
                                <td><span class="badge bg-secondary">QC passed</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


