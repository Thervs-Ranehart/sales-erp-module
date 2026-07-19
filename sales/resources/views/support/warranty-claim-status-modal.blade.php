<!-- Warranty claim status update modal -->
<div class="modal fade" id="warrantyClaimStatusModal" tabindex="-1" aria-labelledby="warrantyClaimStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header" style="background: rgba(83,71,206,.08); border-bottom: 1px solid rgba(0,0,0,.06);">
                <div>
                    <h5 class="modal-title fw-bold" id="warrantyClaimStatusModalLabel">Update Claim Status</h5>
                    <div class="text-muted small" id="warrantyClaimStatusSubtitle">Select a new status</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="warrantyClaimStatusTicketId" value="" />

                <div class="mb-3">
                    <label class="form-label small text-muted">Status</label>
                    <select class="form-select" id="warrantyClaimStatusSelect" aria-label="Claim status">
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                        <option value="Completed">Completed</option>
                    </select>
                    <div class="invalid-feedback d-block" id="warrantyClaimStatusError" style="display:none;"></div>
                </div>
            </div>

            <div class="modal-footer" style="border-top: 1px solid rgba(0,0,0,.06);">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn" id="warrantyClaimStatusSaveBtn" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
                    Save
                </button>
            </div>
        </div>
    </div>
</div>

