<!-- Resolution details modal (UI-only placeholder) -->
<div class="modal fade" id="resolutionDetailsModal" tabindex="-1" aria-labelledby="resolutionDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header" style="background: rgba(83,71,206,.08); border-bottom: 1px solid rgba(0,0,0,.06);">
                <div>
                    <h5 class="modal-title fw-bold" id="resolutionDetailsModalLabel">Resolution Details</h5>
                    <div class="text-muted small" id="resolutionDetailsSubtitle">—</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="card p-3" style="box-shadow:none; border: 1px solid rgba(0,0,0,.06);">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                <div>
                                    <div class="text-muted small">Root Cause</div>
                                    <div class="fw-bold fs-5" id="resolutionRootCauseText">Mismatch between serial database and production batch</div>
                                    <div class="text-muted small" id="resolutionRootCauseNarrativeText">—</div>
                                </div>
                                <span class="badge bg-success" id="resolutionOutcomeBadge">Closed</span>
                            </div>

                            <div class="mt-3">
                                <div class="text-muted small mb-2">Corrective Action</div>
                                <div class="alert alert-info mb-0" role="alert">
                                    <span id="resolutionCorrectiveActionText">—</span>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-sm-6">
                                    <div class="text-muted small">Resolved By</div>
                                    <div class="fw-semibold" id="resolutionResolvedByText">QC & Resolutions Team</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-muted small">Resolution Time</div>
                                    <div class="fw-semibold" id="resolutionTimeHoursText">18h 25m</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-muted small">Resolved Date</div>
                                    <div class="fw-semibold" id="resolutionResolvedDateText">2026-07-10</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-muted small">Ticket</div>
                                    <div class="fw-semibold" id="resolutionTicketNumberText">TK-1003</div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="text-muted small mb-2">Quality Notes</div>
                                <div class="text-muted small" id="resolutionQualityNotesText">—</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="card p-3" style="box-shadow:none; border: 1px solid rgba(0,0,0,.06);">
                            <div class="fw-bold mb-2">Attachments & Actions</div>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item">
                                    <div class="fw-semibold">Evidence</div>
                                    <div class="text-muted small" id="resolutionEvidenceSummaryText">—</div>
                                    <span class="badge bg-primary mt-2" id="resolutionEvidenceCountBadge">—</span>
                                </div>
                                <div class="list-group-item">
                                    <div class="fw-semibold">Status</div>
                                    <div class="text-muted small" id="resolutionWorkflowStatusText">—</div>
                                    <span class="badge bg-success mt-2" id="resolutionWorkflowOutcomeBadge">—</span>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <button type="button" class="btn btn-sm btn-outline-primary"><i class="bi bi-clipboard2 me-1"></i> Add note</button>
                                <button type="button" class="btn btn-sm btn-outline-success"><i class="bi bi-check2-circle me-1"></i> Mark closed</button>
                                <button type="button" class="btn btn-sm btn-outline-warning"><i class="bi bi-arrow-counterclockwise me-1"></i> Reopen</button>
                            </div>

                            <div class="text-muted small mt-3">
                                —
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

