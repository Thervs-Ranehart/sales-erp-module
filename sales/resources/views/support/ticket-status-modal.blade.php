<!-- Change Status modal -->
<div class="modal fade" id="ticketStatusModal" tabindex="-1" aria-labelledby="ticketStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header" style="background: rgba(83,71,206,.08); border-bottom: 1px solid rgba(0,0,0,.06);">
                <div>
                    <h5 class="modal-title fw-bold" id="ticketStatusModalLabel">Change Ticket Status</h5>
                    <div class="text-muted small" id="ticketStatusSubtitle">Select a new status</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="ticketStatusTicketId" />

                <div class="mb-3">
                    <label class="form-label small text-muted">Status</label>
                    <select class="form-select" id="ticketStatusSelect" aria-label="Ticket status">
                        <option value="Open">Open</option>
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Resolved">Resolved</option>
                        <option value="Closed">Closed</option>
                        <option value="Escalated">Escalated</option>
                    </select>
                    <div class="invalid-feedback d-block" id="ticketStatusError" style="display:none;"></div>
                </div>
            </div>

            <div class="modal-footer" style="border-top: 1px solid rgba(0,0,0,.06);">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn" id="ticketStatusSaveBtn" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
                    Save
                </button>
            </div>
        </div>
    </div>
</div>

