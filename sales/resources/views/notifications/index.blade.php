@extends('layouts.app')

@section('content')
    @php($title = 'Notifications')
    @php($subtitle = 'Manage and review customer service notifications')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Unread</div>
                        <div class="display-6 fw-bold">7</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(83,71,206,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-bell" style="color:#5347CE; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-primary">Needs attention</span></div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Read</div>
                        <div class="display-6 fw-bold">26</div>
                    </div>
                    <div class="rounded-3" style="background:rgba(22,200,199,.12); width:46px; height:46px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-check2-all" style="color:#16C8C7; font-size:20px;"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-success">Reviewed</span></div>
            </div>
        </div>

        <div class="col-md-12 col-lg-6">
            <div class="card p-3 h-100" style="background: rgba(255,255,255,.7); border: 1px solid rgba(0,0,0,.06); box-shadow:none;">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-lg-6">
                        <label class="form-label small text-muted">Search</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text" style="background: rgba(83,71,206,.08); border-color: rgba(83,71,206,.2);">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Search notifications..." aria-label="Search notifications" />
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex justify-content-end gap-2">
                            <a class="btn btn-sm" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);" href="#">
                                <i class="bi bi-check2-all me-1"></i> Mark all read
                            </a>
                            <a class="btn btn-sm btn-outline-primary" href="#">
                                <i class="bi bi-bell me-1"></i> Notification settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-4 mt-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold mb-0">Notification List</h5>
            <div class="text-muted small">Placeholder notifications (UI-only).</div>
        </div>

        <div class="list-group">
            <div class="list-group-item d-flex gap-3 align-items-start">
                <div class="mt-1">
                    <span class="badge bg-primary">Support</span>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between gap-3">
                        <div>
                            <div class="fw-semibold">Support Ticket Updated: TK-1020</div>
                            <div class="text-muted small">Your ticket status has been changed to <span class="fw-semibold">Escalated</span>.</div>
                        </div>
                        <div class="text-muted small">2026-07-12 10:15</div>
                    </div>
                    <div class="mt-2 d-flex justify-content-between align-items-center">
                        <span class="badge bg-warning text-dark">Unread</span>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye me-1"></i> View</button>
                            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-star me-1"></i> Snooze</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="list-group-item d-flex gap-3 align-items-start">
                <div class="mt-1">
                    <span class="badge bg-warning text-dark">Warranty</span>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between gap-3">
                        <div>
                            <div class="fw-semibold">Warranty Claim Pending Verification: WC-3001</div>
                            <div class="text-muted small">Evidence review started. Assigned staff will notify once validated.</div>
                        </div>
                        <div class="text-muted small">2026-07-12 09:02</div>
                    </div>
                    <div class="mt-2 d-flex justify-content-between align-items-center">
                        <span class="badge bg-secondary">Read</span>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye me-1"></i> View</button>
                            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-file-earmark-text me-1"></i> Upload docs</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="list-group-item d-flex gap-3 align-items-start">
                <div class="mt-1">
                    <span class="badge bg-success">Service</span>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between gap-3">
                        <div>
                            <div class="fw-semibold">Service Request Scheduled: SR-5001</div>
                            <div class="text-muted small">Technician scheduled a visit for <span class="fw-semibold">2026-07-14 09:00–12:00</span>.</div>
                        </div>
                        <div class="text-muted small">2026-07-11 16:40</div>
                    </div>
                    <div class="mt-2 d-flex justify-content-between align-items-center">
                        <span class="badge bg-warning text-dark">Unread</span>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye me-1"></i> View</button>
                            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-telephone me-1"></i> Contact</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="list-group-item d-flex gap-3 align-items-start">
                <div class="mt-1">
                    <span class="badge bg-primary">Resolution</span>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between gap-3">
                        <div>
                            <div class="fw-semibold">Resolution Completed: RS-6001</div>
                            <div class="text-muted small">Corrective action applied and ticket marked closed.</div>
                        </div>
                        <div class="text-muted small">2026-07-10 13:20</div>
                    </div>
                    <div class="mt-2 d-flex justify-content-between align-items-center">
                        <span class="badge bg-success">Read</span>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye me-1"></i> View</button>
                            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-chat-left-quote me-1"></i> Request CSAT</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

