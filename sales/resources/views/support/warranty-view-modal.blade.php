<!-- Warranty view/register modal (UI-only placeholder) -->
<div class="modal fade" id="warrantyViewModal" tabindex="-1" aria-labelledby="warrantyViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header" style="background: rgba(83,71,206,.08); border-bottom: 1px solid rgba(0,0,0,.06);">
                <div>
                    <h5 class="modal-title fw-bold" id="warrantyViewModalLabel">Warranty Details</h5>
                    <div class="text-muted small" id="warrantyViewModalSubtitle">WR-2007 • XYZ Trading</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="card p-3" style="box-shadow:none; border: 1px solid rgba(0,0,0,.06);">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                <div>
                                    <div class="text-muted small">Product</div>
                                    <div class="fw-bold fs-5">Widget B</div>
                                    <div class="text-muted small">Serial: WB-1930 • Qty: 2</div>
                                </div>
                                <span class="badge bg-primary" id="warrantyBadge">Expiring Soon</span>
                            </div>

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="text-muted small">Order</div>
                                    <div class="fw-semibold">SO-9034</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-muted small">Warranty Start</div>
                                    <div class="fw-semibold">2025-07-01</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-muted small">Warranty End</div>
                                    <div class="fw-semibold">2026-08-15</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-muted small">Coverage Type</div>
                                    <div class="fw-semibold">Parts + Labor</div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="text-muted small mb-2">Coverage Progress</div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 46%" aria-valuenow="46" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="text-muted small mt-2">Placeholder for computed coverage remaining.</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="card p-3" style="box-shadow:none; border: 1px solid rgba(0,0,0,.06);">
                            <div class="fw-bold mb-2">Recent Actions</div>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item">
                                    <div class="text-muted small">2026-07-10 09:12</div>
                                    <div class="fw-semibold">Claim eligibility checked</div>
                                    <div class="text-muted small">Result: pending document verification.</div>
                                </div>
                                <div class="list-group-item">
                                    <div class="text-muted small">2026-07-06 15:38</div>
                                    <div class="fw-semibold">Warranty document uploaded</div>
                                    <div class="text-muted small">Invoice PDF and serial verification.</div>
                                </div>
                                <div class="list-group-item">
                                    <div class="text-muted small">2026-07-03 10:04</div>
                                    <div class="fw-semibold">Customer notified</div>
                                    <div class="text-muted small">Next inspection scheduled.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-1">
                    <div class="col-12">
                        <div class="card p-3" style="box-shadow:none; border: 1px solid rgba(0,0,0,.06);">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="fw-bold">Actions (Placeholder)</div>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-sm btn-outline-primary"><i class="bi bi-file-earmark-text me-1"></i> Create Claim</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary"><i class="bi bi-download me-1"></i> Download Coverage</button>
                                </div>
                            </div>
                            <div class="text-muted small">UI-only placeholder. Backend wiring will be added in later tasks.</div>
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

