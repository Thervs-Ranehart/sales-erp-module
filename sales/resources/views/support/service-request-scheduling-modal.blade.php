<!-- Service request scheduling modal (UI-only placeholder) -->
<div class="modal fade" id="serviceRequestScheduleModal" tabindex="-1" aria-labelledby="serviceRequestScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header" style="background: rgba(83,71,206,.08); border-bottom: 1px solid rgba(0,0,0,.06);">
                <div>
                    <h5 class="modal-title fw-bold" id="serviceRequestScheduleModalLabel">Schedule Service Request</h5>
                    <div class="text-muted small" id="serviceRequestScheduleModalSubtitle">SR-5001 • TK-1002 • On-site Repair</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="card p-3" style="box-shadow:none; border: 1px solid rgba(0,0,0,.06);">
                            <div class="fw-bold mb-3">Scheduling Information</div>

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="form-label small text-muted">Technician</label>
                                    <select class="form-select" aria-label="Technician">
                                        <option selected>Field Engineer A</option>
                                        <option>Field Engineer B</option>
                                        <option>Warranty Partner (Tier-2)</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label small text-muted">Schedule Date</label>
                                    <input type="date" class="form-control" value="2026-07-14" aria-label="Schedule date" />
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label small text-muted">Time Window</label>
                                    <select class="form-select" aria-label="Time window">
                                        <option selected>09:00–12:00</option>
                                        <option>13:00–16:00</option>
                                        <option>16:00–18:00</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label small text-muted">Priority</label>
                                    <select class="form-select" aria-label="Priority">
                                        <option selected>High</option>
                                        <option>Medium</option>
                                        <option>Low</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label small text-muted">Dispatch Notes</label>
                                    <textarea class="form-control" rows="3" aria-label="Dispatch notes">Customer requested confirmation call 1 hour before arrival. Bring diagnostic kit and spare seals (placeholder).</textarea>
                                </div>
                            </div>

                            <div class="alert alert-info mt-3 mb-0" role="alert">
                                UI-only placeholder: scheduling actions will connect to backend later.
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="card p-3" style="box-shadow:none; border: 1px solid rgba(0,0,0,.06);">
                            <div class="fw-bold mb-2">Related Details</div>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item">
                                    <div class="text-muted small">Request Number</div>
                                    <div class="fw-semibold">SR-5001</div>
                                </div>
                                <div class="list-group-item">
                                    <div class="text-muted small">Ticket</div>
                                    <div class="fw-semibold">TK-1002</div>
                                </div>
                                <div class="list-group-item">
                                    <div class="text-muted small">Customer</div>
                                    <div class="fw-semibold">XYZ Trading</div>
                                </div>
                                <div class="list-group-item">
                                    <div class="text-muted small">Coverage</div>
                                    <div class="fw-semibold">Under active service contract (placeholder)</div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="text-muted small mb-2">Readiness checklist</div>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-success">Parts reserved</span>
                                    <span class="badge bg-primary">Technician assigned</span>
                                    <span class="badge bg-warning text-dark">Address confirmation</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="border-top: 1px solid rgba(0,0,0,.06);">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">Schedule (placeholder)</button>
            </div>
        </div>
    </div>
</div>

