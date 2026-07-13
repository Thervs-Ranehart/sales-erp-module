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
                        <div class="display-6 fw-bold">48</div>
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
                        <div class="text-muted small fw-semibold">Pending Claims</div>
                        <div class="display-6 fw-bold">16</div>
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
                        <div class="display-6 fw-bold">214</div>
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
                        <div class="text-muted small fw-semibold">Active Service Contracts</div>
                        <div class="display-6 fw-bold">76</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(83,71,206,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-file-earmark" style="color:#5347CE; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-primary">Renewal ready</span></div>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Pending Requests</div>
                        <div class="display-6 fw-bold">27</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(22,200,199,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-tools" style="color:#16C8C7; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-success">In queue</span></div>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Customer Satisfaction</div>
                        <div class="display-6 fw-bold">94%</div>
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
                        <div class="display-6 fw-bold">9</div>
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
                        <div class="display-6 fw-bold">132</div>
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
                        <a class="btn btn-primary btn-sm" href="{{ route('support.tickets') }}">
                            <i class="bi bi-plus-circle me-1"></i> New Ticket
                        </a>
                        <a class="btn btn-warning btn-sm" href="{{ route('support.warranty-records') }}">
                            <i class="bi bi-shield-check me-1"></i> Register Warranty
                        </a>
                        <a class="btn btn-outline-warning btn-sm" href="{{ route('support.warranty-claims') }}">
                            <i class="bi bi-file-earmark-text me-1"></i> Create Claim
                        </a>
                        <a class="btn btn-success btn-sm" href="{{ route('support.service-requests') }}">
                            <i class="bi bi-tools me-1"></i> Create Service Request
                        </a>
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
                            <tr>
                                <td class="fw-semibold">TK-1020</td>
                                <td>Northwind Retail</td>
                                <td>Escalation: repeat failure</td>
                                <td><span class="badge bg-danger">Escalated</span></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">TK-1012</td>
                                <td>ABC Corporation</td>
                                <td>Replacement request</td>
                                <td><span class="badge bg-success">Resolved</span></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">TK-1005</td>
                                <td>Greenfield Industries</td>
                                <td>Warranty inspection</td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-bold mb-0">Recent Warranty Claims</h5>
                    <a class="text-decoration-none" style="color:#5347CE;font-weight:700;" href="{{ route('support.warranty-claims') }}">
                        View all <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

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
                            <tr>
                                <td class="fw-semibold">WC-3044</td>
                                <td>John Smith</td>
                                <td>Battery failure</td>
                                <td><span class="badge bg-success">Approved</span></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">WC-3017</td>
                                <td>XYZ Trading</td>
                                <td>Wear & tear (Tier-2)</td>
                                <td><span class="badge bg-primary">In Verification</span></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">WC-3001</td>
                                <td>ABC Corporation</td>
                                <td>Manufacturing defect</td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-bold mb-0">Recent Service Requests</h5>
                    <a class="text-decoration-none" style="color:#5347CE;font-weight:700;" href="{{ route('support.service-requests') }}">
                        View all <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

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
                            <tr>
                                <td class="fw-semibold">SR-5056</td>
                                <td>TK-1020</td>
                                <td>Escalated field service</td>
                                <td><span class="badge bg-secondary">Cancelled</span></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">SR-5040</td>
                                <td>TK-1005</td>
                                <td>Parts replacement</td>
                                <td><span class="badge bg-warning text-dark">Parts Pending</span></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">SR-5001</td>
                                <td>TK-1002</td>
                                <td>On-site repair</td>
                                <td><span class="badge bg-primary">In Queue</span></td>
                            </tr>
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
                            <div class="fw-bold fs-5">4.6</div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="p-3 rounded-3" style="background: rgba(83,71,206,.08);">
                            <div class="text-muted small">Promoters</div>
                            <div class="fw-bold fs-5">78%</div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="p-3 rounded-3" style="background: rgba(245,158,11,.10);">
                            <div class="text-muted small">Neutrals</div>
                            <div class="fw-bold fs-5">14%</div>
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
                        Very satisfied <span class="badge bg-success">56</span>
                    </a>
                    <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="{{ route('support.customer-satisfaction') }}">
                        Neutral <span class="badge bg-warning text-dark">13</span>
                    </a>
                    <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="{{ route('support.customer-satisfaction') }}">
                        Detractors <span class="badge bg-danger">7</span>
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


