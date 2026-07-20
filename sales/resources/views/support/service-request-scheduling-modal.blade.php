<div class="modal fade" id="serviceRequestScheduleModal" tabindex="-1" aria-labelledby="serviceRequestScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content" style="border-radius:16px;">
        <div class="modal-header" style="background:rgba(83,71,206,.08);"><div><h5 class="modal-title fw-bold" id="serviceRequestScheduleModalLabel">Schedule Service Request</h5><div class="text-muted small" id="serviceRequestScheduleModalSubtitle">—</div></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="serviceRequestScheduleForm">
            <div class="modal-body">
                <div id="serviceRequestScheduleAlert" class="alert d-none" role="alert"></div>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label small text-muted" for="serviceRequestTechnician">Technician</label><select class="form-select" id="serviceRequestTechnician" required></select></div>
                    <div class="col-md-6"><label class="form-label small text-muted" for="serviceRequestPriority">Priority</label><select class="form-select" id="serviceRequestPriority" required><option value="High">High</option><option value="Medium">Medium</option><option value="Low">Low</option></select></div>
                    <div class="col-md-4"><label class="form-label small text-muted" for="serviceRequestScheduleDate">Scheduled Date</label><input class="form-control" type="date" id="serviceRequestScheduleDate" required></div>
                    <div class="col-md-4"><label class="form-label small text-muted" for="serviceRequestScheduleTime">Start Time</label><input class="form-control" type="time" id="serviceRequestScheduleTime" required></div>
                    <div class="col-md-4"><label class="form-label small text-muted" for="serviceRequestScheduleEnd">End Time <span class="text-muted">(optional)</span></label><input class="form-control" type="time" id="serviceRequestScheduleEnd"></div>
                    <div class="col-12"><label class="form-label small text-muted" for="serviceRequestScheduleNotes">Scheduling Notes</label><textarea class="form-control" id="serviceRequestScheduleNotes" rows="3" maxlength="2000"></textarea></div>
                </div>
                <div class="border rounded p-3 mt-3 bg-light"><div class="row g-3"><div class="col-md-4"><div class="text-muted small">Customer</div><div class="fw-semibold" id="serviceRequestScheduleCustomer">—</div></div><div class="col-md-4"><div class="text-muted small">Ticket</div><div class="fw-semibold" id="serviceRequestScheduleTicket">—</div></div><div class="col-md-4"><div class="text-muted small">Coverage</div><div class="fw-semibold" id="serviceRequestScheduleCoverage">No Linked Contract</div></div></div><div class="mt-2"><span class="text-muted small">Service Contract: </span><a class="small link-primary d-none" id="serviceRequestScheduleContractLink" href="#">View contract</a><span class="small" id="serviceRequestScheduleContract">No linked contract</span></div></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn" id="serviceRequestScheduleSave" style="background:#5347CE;color:#fff;">Save Schedule</button></div>
        </form>
    </div></div>
</div>
