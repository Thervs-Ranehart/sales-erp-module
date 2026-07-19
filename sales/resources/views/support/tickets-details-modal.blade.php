<!-- Ticket details modal (UI-only but prefilled from selected ticket on page load) -->
<div class="modal fade" id="ticketDetailsModal" tabindex="-1" aria-labelledby="ticketDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header" style="background: rgba(83,71,206,.08); border-bottom: 1px solid rgba(0,0,0,.06);">
                <div>
                    <h5 class="modal-title fw-bold" id="ticketDetailsModalLabel">Ticket Details</h5>
                    <div class="text-muted small" id="ticketDetailsSubtitle">
                        {{ isset($ticket) ? ('TK-' . $ticket->ticket_id . ' • ' . ($ticket->customer->customer_name ?? '—')) : '—' }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="card p-3" style="box-shadow:none; border: 1px solid rgba(0,0,0,.06);">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                <div>
                                    <div class="text-muted small">Subject</div>
                                    <div class="fw-bold" id="ticketDetailsSubject">
                                        {{ $ticket->subject ?? '—' }}
                                    </div>
                                </div>
                                <span class="badge bg-danger" id="ticketDetailsStatus">
                                    {{ $ticket->status ?? '—' }}
                                </span>
                            </div>

                            <div class="text-muted small mb-3">
                                {{ $ticket->description ?? '—' }}
                            </div>

                            <div class="mb-3">
                                <div class="text-muted small mb-2">Progress</div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 78%" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="text-muted small">Assigned Employee</div>
                                    <div class="fw-semibold">{{ isset($ticket) ? (optional($ticket->ticketAssignments->first())->employee->getFullNameAttribute() ?? '—') : '—' }}</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-muted small">Due Date</div>
                                    <div class="fw-semibold">{{ isset($ticket) ? (optional($ticket->due_date)->format('Y-m-d H:i') ?? '—') : '—' }}</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-muted small">Priority</div>
                                    <div class="fw-semibold">{{ $ticket->priority ?? '—' }}</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-muted small">Priority Status</div>
                                    <div class="fw-semibold">SLA Tier 1</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="card p-3" style="box-shadow:none; border: 1px solid rgba(0,0,0,.06);">
                            <div class="fw-bold mb-2">Latest Notes</div>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item">
                                    <div class="text-muted small">2026-07-12 10:15</div>
                                    <div class="fw-semibold">Engineer instructions requested</div>
                                    <div class="text-muted small">Waiting for field availability confirmation.</div>
                                </div>
                                <div class="list-group-item">
                                    <div class="text-muted small">2026-07-12 09:02</div>
                                    <div class="fw-semibold">Escalation approved</div>
                                    <div class="text-muted small">Marked as repeat failure and escalated to engineering.</div>
                                </div>
                                <div class="list-group-item">
                                    <div class="text-muted small">2026-07-11 16:40</div>
                                    <div class="fw-semibold">Customer follow-up sent</div>
                                    <div class="text-muted small">Requested updated failure logs and photos.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-3">
                    <div class="col-12">
                        <div class="card p-3" style="box-shadow:none; border: 1px solid rgba(0,0,0,.06);">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="fw-bold">Attachments & Actions (Placeholder)</div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary"><i class="bi bi-paperclip me-1"></i> Add note</button>
                                    <button type="button" class="btn btn-sm btn-outline-success"><i class="bi bi-check2 me-1"></i> Mark resolved</button>
                                </div>
                            </div>
                            <div class="text-muted small">
                                UI-only placeholder: attachment list, message threads, and workflow actions will connect to backend later.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="border-top: 1px solid rgba(0,0,0,.06);">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">Save (placeholder)</button>
            </div>
        </div>
    </div>
</div>

