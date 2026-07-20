<div class="modal fade" id="ticketDetailsModal" tabindex="-1" aria-labelledby="ticketDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header" style="background: rgba(83,71,206,.08); border-bottom: 1px solid rgba(0,0,0,.06);">
                <div>
                    <h5 class="modal-title fw-bold" id="ticketDetailsModalLabel">Ticket Details</h5>
                    <div class="text-muted small" id="ticketDetailsSubtitle">—</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="ticketDetailsLoading" class="text-center py-4" aria-live="polite">Loading ticket details…</div>
                <div id="ticketDetailsContent" class="d-none">
                    <div class="row g-4">
                        <div class="col-lg-7">
                            <div class="card p-3" style="box-shadow:none; border: 1px solid rgba(0,0,0,.06);">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                    <div>
                                        <div class="text-muted small">Subject</div>
                                        <div class="fw-bold" id="ticketDetailsSubject">—</div>
                                    </div>
                                    <span class="badge bg-secondary" id="ticketDetailsStatus">—</span>
                                </div>
                                <div class="text-muted small mb-3" id="ticketDetailsDescription">—</div>
                                <div class="row g-3">
                                    <div class="col-sm-6"><div class="text-muted small">Customer</div><div class="fw-semibold" id="ticketDetailsCustomer">—</div></div>
                                    <div class="col-sm-6"><div class="text-muted small">Customer Contact</div><div class="fw-semibold" id="ticketDetailsCustomerContact">—</div></div>
                                    <div class="col-sm-6"><div class="text-muted small">Product</div><div class="fw-semibold" id="ticketDetailsProduct">—</div></div>
                                    <div class="col-sm-6"><div class="text-muted small">Order</div><div class="fw-semibold" id="ticketDetailsOrder">—</div></div>
                                    <div class="col-sm-6"><div class="text-muted small">Priority</div><div class="fw-semibold" id="ticketDetailsPriority">—</div></div>
                                    <div class="col-sm-6"><div class="text-muted small">Created</div><div class="fw-semibold" id="ticketDetailsCreatedAt">—</div></div>
                                    <div class="col-sm-6"><div class="text-muted small">Resolved</div><div class="fw-semibold" id="ticketDetailsResolvedAt">—</div></div>
                                    <div class="col-sm-6"><div class="text-muted small">Closed</div><div class="fw-semibold" id="ticketDetailsClosedAt">—</div></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="card p-3" style="box-shadow:none; border: 1px solid rgba(0,0,0,.06);">
                                <div class="fw-bold mb-2">Current Assignment</div>
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item"><div class="text-muted small">Employee</div><div class="fw-semibold" id="ticketDetailsAssignedEmployee">—</div></div>
                                    <div class="list-group-item"><div class="text-muted small">Department</div><div class="fw-semibold" id="ticketDetailsAssignedDepartment">—</div></div>
                                    <div class="list-group-item"><div class="text-muted small">Assigned</div><div class="fw-semibold" id="ticketDetailsAssignedAt">—</div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card p-3 mt-4" style="box-shadow:none; border: 1px solid rgba(0,0,0,.06);">
                        <div class="fw-bold mb-2">Assignment History</div>
                        <div id="ticketDetailsAssignmentHistory" class="small text-muted">—</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid rgba(0,0,0,.06);"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>
