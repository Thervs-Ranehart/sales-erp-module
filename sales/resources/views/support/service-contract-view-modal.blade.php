<!-- Service contract detail modal (UI-only placeholder) -->
<div class="modal fade" id="serviceContractModal" tabindex="-1" aria-labelledby="serviceContractModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header" style="background: rgba(83,71,206,.08); border-bottom: 1px solid rgba(0,0,0,.06);">
                <div>
                    <h5 class="modal-title fw-bold" id="serviceContractModalLabel">Service Contract Details</h5>
                    <div class="text-muted small" id="serviceContractModalSubtitle">—</div>

                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <script>
                    // Populated on demand via /support/service-contracts/{id}/show
                    window.__serviceContractModalIds = {

                        serviceType: 'serviceContractServiceType',
                        startDate: 'serviceContractStartDate',
                        endDate: 'serviceContractEndDate',
                        owner: 'serviceContractOwner',
                        sla: 'serviceContractSla',
                        dispatch: 'serviceContractDispatchFrequency',
                        statusBadge: 'serviceContractStatusBadge',
                    };
                </script>

                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="card p-3" style="box-shadow:none; border: 1px solid rgba(0,0,0,.06);">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                <div>
                                    <div class="text-muted small">Service Type</div>
                                    <div class="fw-bold fs-5" id="serviceContractServiceType">—</div>
                                <div class="text-muted small">Dispatch frequency: <span id="serviceContractDispatchFrequency">—</span></div>

                                </div>
                                <span class="badge bg-warning text-dark" id="serviceContractStatusBadge">—</span>

                            </div>


                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="text-muted small">Start Date</div>
                                    <div class="fw-semibold" id="serviceContractStartDate">—</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-muted small">End Date</div>
                                    <div class="fw-semibold" id="serviceContractEndDate">—</div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="text-muted small">Assigned Account Owner</div>
                                    <div class="fw-semibold" id="serviceContractOwner">—</div>
                                </div>
                                <div class="col-sm-6">

                                    <div class="text-muted small">Response SLA Tier</div>
                                    <div class="fw-semibold" id="serviceContractSla">—</div>
                                </div>

                            </div>

                            <div class="mt-3">
                                <div class="text-muted small mb-2">Entitlement Coverage</div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 32%" aria-valuenow="32" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="text-muted small mt-2">Placeholder for coverage usage ratio.</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="card p-3" style="box-shadow:none; border: 1px solid rgba(0,0,0,.06);">
                            <div class="fw-bold mb-2">Recent Service Activity</div>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item">
                                    <div class="text-muted small">2026-07-01</div>
                                    <div class="fw-semibold">Remote diagnostics completed</div>
                                    <div class="text-muted small">Action: parts ordered for follow-up.</div>
                                </div>
                                <div class="list-group-item">
                                    <div class="text-muted small">2026-06-02</div>
                                    <div class="fw-semibold">Quarterly check-in</div>
                                    <div class="text-muted small">Status: within acceptable parameters.</div>
                                </div>
                                <div class="list-group-item">
                                    <div class="text-muted small">2026-05-12</div>
                                    <div class="fw-semibold">Maintenance window scheduled</div>
                                    <div class="text-muted small">Assigned engineer confirmed (placeholder).</div>
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
                                    <button type="button" class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-repeat me-1"></i> Renew</button>
                                    <button type="button" class="btn btn-sm btn-outline-success"><i class="bi bi-tools me-1"></i> Create Service Request</button>
                                </div>
                            </div>
                            <div class="text-muted small">UI-only placeholder modal—wire to contract workflow later.</div>
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

