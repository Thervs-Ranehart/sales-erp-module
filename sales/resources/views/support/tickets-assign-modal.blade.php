<div class="modal fade" id="ticketsAssignModal" tabindex="-1" aria-labelledby="ticketsAssignModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content" style="border-radius:16px;">
        <div class="modal-header" style="background:rgba(83,71,206,.08);"><div><h5 class="modal-title fw-bold" id="ticketsAssignModalLabel">Assign Ticket</h5><div class="text-muted small" id="ticketsAssignModalSubtitle">—</div></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <div class="modal-body">
            <div id="ticketsAssignLoading" class="text-center py-3" aria-live="polite">Loading assignment options…</div>
            <div id="ticketsAssignContent" class="d-none">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label small text-muted">Ticket Number</label><input id="ticketsAssignTicketNumber" type="text" class="form-control" readonly></div>
                    <div class="col-md-6"><label class="form-label small text-muted">Department</label><select id="ticketsAssignDepartment" class="form-select"><option value="">All departments</option></select></div>
                    <div class="col-12"><label class="form-label small text-muted">Assigned Employee</label><select id="ticketsAssignEmployee" class="form-select" aria-label="Assigned employee"></select><div class="invalid-feedback d-block" id="ticketsAssignError" style="display:none;"></div></div>
                    <div class="col-md-6"><div class="text-muted small">Customer</div><div class="fw-semibold" id="ticketsAssignCustomer">—</div></div>
                    <div class="col-md-6"><div class="text-muted small">Product</div><div class="fw-semibold" id="ticketsAssignProduct">—</div></div>
                </div>
                <div class="mt-4"><div class="fw-semibold small mb-2">Assignment History</div><div id="ticketsAssignHistory" class="small text-muted">—</div></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button type="button" class="btn" id="ticketsAssignSaveBtn" style="background:#5347CE;color:#fff;">Save</button></div>
    </div></div>
</div>
