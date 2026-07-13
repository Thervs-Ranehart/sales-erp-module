<!-- Warranty claim details modal (UI-only placeholder) -->
<div class="modal fade" id="warrantyClaimModal" tabindex="-1" aria-labelledby="warrantyClaimModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header" style="background: rgba(83,71,206,.08); border-bottom: 1px solid rgba(0,0,0,.06);">
                <div>
                    <h5 class="modal-title fw-bold" id="warrantyClaimModalLabel">Warranty Claim Details</h5>
                    <div class="text-muted small" id="warrantyClaimModalSubtitle">WC-3044 • John Smith</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="card p-3" style="box-shadow:none; border: 1px solid rgba(0,0,0,.06);">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                <div>
                                    <div class="text-muted small">Claim Reason</div>
                                    <div class="fw-bold fs-5">Battery Failure</div>
                                    <div class="text-muted small">Warranty eligibility: serial matches coverage window (placeholder).</div>
                                </div>
                                <span class="badge bg-success" id="warrantyClaimModalStatus">Approved</span>
                            </div>

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="text-muted small">Warranty Number</div>
                                    <div class="fw-semibold">WR-2001</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-muted small">Claim Date</div>
                                    <div class="fw-semibold">2026-07-10</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-muted small">Product</div>
                                    <div class="fw-semibold">Widget A</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-muted small">Assigned Staff</div>
                                    <div class="fw-semibold">Warranty Desk • Senior Agent</div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="text-muted small mb-2">Review progress (placeholder)</div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="text-muted small mt-2">Last update: approved for processing.</div>
                            </div>

                            <div class="mt-3">
                                <div class="text-muted small mb-2">Outcome / Notes</div>
                                <div class="alert alert-success mb-0" role="alert">
                                    Claim approved. Replacement/repair steps can proceed (placeholder).
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="card p-3" style="box-shadow:none; border: 1px solid rgba(0,0,0,.06);">
                            <div class="fw-bold mb-2">Documents & Actions</div>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item">
                                    <div class="fw-semibold">Evidence Uploads</div>
                                    <div class="text-muted small">Invoice, serial photos, and diagnostic report (placeholder).</div>
                                    <span class="badge bg-primary mt-2">3 files</span>
                                </div>
                                <div class="list-group-item">
                                    <div class="fw-semibold">Verification</div>
                                    <div class="text-muted small">Serial number check and warranty window validation.</div>
                                    <span class="badge bg-success mt-2">Verified</span>
                                </div>
                                <div class="list-group-item">
                                    <div class="fw-semibold">Next Step</div>
                                    <div class="text-muted small">Send claim outcome message to customer.</div>
                                    <span class="badge bg-warning text-dark mt-2">Pending notification</span>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <button type="button" class="btn btn-sm btn-outline-primary"><i class="bi bi-chat-left-text me-1"></i> Add note</button>
                                <button type="button" class="btn btn-sm btn-outline-success"><i class="bi bi-check2 me-1"></i> Approve</button>
                                <button type="button" class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle me-1"></i> Reject</button>
                            </div>

                            <div class="text-muted small mt-3">
                                UI-only placeholder modal—connect to claim workflow in later tasks.
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

