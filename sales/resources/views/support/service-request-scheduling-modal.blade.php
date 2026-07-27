<style>
    #serviceRequestScheduleModal .modal-content { border:0; border-radius:20px; box-shadow:0 18px 50px rgba(31,41,55,.18); overflow:hidden; }
    #serviceRequestScheduleModal .sr-schedule-header { align-items:flex-start; background:linear-gradient(135deg, rgba(83,71,206,.12), rgba(136,124,253,.05)); border-bottom:1px solid rgba(83,71,206,.1); padding:1.35rem 1.5rem; }
    #serviceRequestScheduleModal .sr-schedule-title { color:#1f2937; font-size:1.1rem; font-weight:700; letter-spacing:-.02em; margin:0; }
    #serviceRequestScheduleModal .sr-request-id { background:rgba(83,71,206,.12); border-radius:999px; color:#4b3fc1; display:inline-flex; font-size:.72rem; font-weight:700; letter-spacing:.03em; margin-top:.48rem; padding:.28rem .6rem; }
    #serviceRequestScheduleModal .btn-close { margin:.15rem 0 0 auto; }
    #serviceRequestScheduleModal .sr-schedule-body { padding:1.5rem; }
    #serviceRequestScheduleModal .sr-form-section-title { color:#374151; font-size:.82rem; font-weight:700; letter-spacing:.04em; margin:0 0 1.1rem; text-transform:uppercase; }
    #serviceRequestScheduleModal .sr-field-label { color:#4b5563; font-size:.78rem; font-weight:600; margin-bottom:.45rem; }
    #serviceRequestScheduleModal .form-control, #serviceRequestScheduleModal .form-select { border-color:#dfe1ea; border-radius:9px; font-size:.88rem; min-height:42px; padding:.55rem .75rem; }
    #serviceRequestScheduleModal .form-control:focus, #serviceRequestScheduleModal .form-select:focus { border-color:#5347ce; box-shadow:0 0 0 .2rem rgba(83,71,206,.12); }
    #serviceRequestScheduleModal textarea.form-control { line-height:1.55; min-height:126px; padding:.75rem; resize:vertical; }
    #serviceRequestScheduleModal .sr-info-card { background:#f8f9fc; border:1px solid #e8eaf0; border-radius:14px; margin-top:1.5rem; padding:1.2rem; }
    #serviceRequestScheduleModal .sr-info-label { color:#7a8291; font-size:.7rem; font-weight:700; letter-spacing:.04em; margin-bottom:.32rem; text-transform:uppercase; }
    #serviceRequestScheduleModal .sr-info-value { color:#374151; font-size:.88rem; font-weight:600; overflow-wrap:anywhere; }
    #serviceRequestScheduleModal .sr-contract-row { align-items:center; border-top:1px solid #e3e5ed; display:flex; flex-wrap:wrap; gap:.65rem 1rem; margin-top:1rem; padding-top:1rem; }
    #serviceRequestScheduleModal .sr-contract-button { align-items:center; border-color:#c9c5f2; color:#5347ce; display:inline-flex; font-size:.78rem; font-weight:600; gap:.35rem; min-height:32px; padding:.3rem .65rem; }
    #serviceRequestScheduleModal .sr-contract-button:hover, #serviceRequestScheduleModal .sr-contract-button:focus { background:#f0efff; border-color:#9289e3; color:#4338ca; }
    #serviceRequestScheduleModal .sr-schedule-footer { border-top:1px solid #edf0f4; display:flex; gap:.75rem; justify-content:flex-end; padding:1rem 1.5rem; }
    #serviceRequestScheduleModal .sr-footer-button { align-items:center; border-radius:8px; display:inline-flex; font-size:.86rem; font-weight:600; height:40px; justify-content:center; padding-inline:1rem; }
    #serviceRequestScheduleModal .sr-save-button { background:#5347ce; border-color:#5347ce; box-shadow:0 5px 12px rgba(83,71,206,.2); color:#fff; }
    #serviceRequestScheduleModal .sr-save-button:hover, #serviceRequestScheduleModal .sr-save-button:focus { background:#4338ca; border-color:#4338ca; color:#fff; }
    @media (max-width: 575.98px) { #serviceRequestScheduleModal .sr-schedule-header, #serviceRequestScheduleModal .sr-schedule-body { padding:1.2rem; } #serviceRequestScheduleModal .sr-schedule-footer { padding:1rem 1.2rem; } #serviceRequestScheduleModal .sr-schedule-footer .sr-footer-button { flex:1; padding-inline:.65rem; } }
</style>

<div class="modal fade" id="serviceRequestScheduleModal" tabindex="-1" aria-labelledby="serviceRequestScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered"><div class="modal-content">
        <div class="modal-header sr-schedule-header"><div><h5 class="sr-schedule-title" id="serviceRequestScheduleModalLabel">Schedule Service Request</h5><div class="sr-request-id" id="serviceRequestScheduleModalSubtitle">—</div></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="serviceRequestScheduleForm">
            <div class="modal-body sr-schedule-body">
                <div id="serviceRequestScheduleAlert" class="alert d-none" role="alert"></div>
                <h6 class="sr-form-section-title">Scheduling details</h6>
                <div class="row g-4">
                    <div class="col-md-6"><label class="form-label sr-field-label" for="serviceRequestTechnician">Technician</label><select class="form-select" id="serviceRequestTechnician" required></select></div>
                    <div class="col-md-6"><label class="form-label sr-field-label" for="serviceRequestPriority">Priority</label><select class="form-select" id="serviceRequestPriority" required><option value="High">High</option><option value="Medium">Medium</option><option value="Low">Low</option></select></div>
                    <div class="col-md-4"><label class="form-label sr-field-label" for="serviceRequestScheduleDate">Scheduled Date</label><input class="form-control" type="date" id="serviceRequestScheduleDate" required></div>
                    <div class="col-md-4"><label class="form-label sr-field-label" for="serviceRequestScheduleTime">Start Time</label><input class="form-control" type="time" id="serviceRequestScheduleTime" required></div>
                    <div class="col-md-4"><label class="form-label sr-field-label" for="serviceRequestScheduleEnd">End Time <span class="text-muted fw-normal">(optional)</span></label><input class="form-control" type="time" id="serviceRequestScheduleEnd"></div>
                    <div class="col-12"><label class="form-label sr-field-label" for="serviceRequestScheduleNotes">Scheduling Notes</label><textarea class="form-control" id="serviceRequestScheduleNotes" rows="5" maxlength="2000" placeholder="Add any scheduling notes or instructions..."></textarea></div>
                </div>
                <section class="sr-info-card" aria-label="Service request information"><div class="row g-4"><div class="col-md-4"><div class="sr-info-label">Customer</div><div class="sr-info-value" id="serviceRequestScheduleCustomer">—</div></div><div class="col-md-4"><div class="sr-info-label">Ticket</div><div class="sr-info-value" id="serviceRequestScheduleTicket">—</div></div><div class="col-md-4"><div class="sr-info-label">Coverage</div><div class="sr-info-value" id="serviceRequestScheduleCoverage">No Linked Contract</div></div></div><div class="sr-contract-row"><div><div class="sr-info-label">Service Contract</div><div class="sr-info-value" id="serviceRequestScheduleContract">No linked contract</div></div><a class="btn btn-sm btn-outline-primary sr-contract-button d-none" id="serviceRequestScheduleContractLink" href="#"><i class="bi bi-file-earmark-text" aria-hidden="true"></i>View Contract</a></div></section>
            </div>
            <div class="modal-footer sr-schedule-footer"><button type="button" class="btn btn-outline-secondary sr-footer-button" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn sr-footer-button sr-save-button" id="serviceRequestScheduleSave">Save Schedule</button></div>
        </form>
    </div></div>
</div>
