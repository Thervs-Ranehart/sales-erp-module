<!-- Assign Ticket modal (UI-only placeholder) -->
<div class="modal fade" id="ticketsAssignModal" tabindex="-1" aria-labelledby="ticketsAssignModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header" style="background: rgba(83,71,206,.08); border-bottom: 1px solid rgba(0,0,0,.06);">
                <div>
                    <h5 class="modal-title fw-bold" id="ticketsAssignModalLabel">Assign Ticket</h5>
                    <div class="text-muted small" id="ticketsAssignModalSubtitle">Placeholder assignment workflow</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="card p-3" style="box-shadow:none; border: 1px solid rgba(0,0,0,.06);">
                            <div class="fw-bold mb-3">Assignment Details</div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label small text-muted">Ticket Number</label>
                                    <input type="text" class="form-control" value="{{ isset($ticket) ? ('TK-' . $ticket->ticket_id) : 'TK-—' }}" readonly aria-label="Ticket number" />

                                </div>



                                <div class="col-12 col-md-6">
                                    <label class="form-label small text-muted">Assigned Employee</label>
                                    <select class="form-select" aria-label="Assigned employee">
                                        @php($employeesSafe = $employees ?? collect())
                                        @foreach($employeesSafe as $employee)
                                            <option value="{{ $employee->employee_id }}" {{ (isset($currentEmployeeId) && (int)$currentEmployeeId === (int)$employee->employee_id) ? 'selected' : '' }}>
                                                {{ $employee->getFullNameAttribute() }}
                                            </option>
                                        @endforeach
                                        @if($employeesSafe->isEmpty())
                                            <option selected>—</option>
                                        @endif
                                    </select>
                                </div>


                                <div class="col-12 col-md-6">
                                    <label class="form-label small text-muted">Priority</label>
                                    <select class="form-select" aria-label="Priority">
                                        <option selected>High</option>
                                        <option>Medium</option>
                                        <option>Low</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label small text-muted">Due Date</label>
                                    <input type="date" class="form-control" value="2026-07-13" aria-label="Due date" />
                                </div>

                                <div class="col-12">
                                    <label class="form-label small text-muted">Notes</label>
                                    <textarea class="form-control" rows="4" placeholder="Add internal assignment notes (placeholder, no submit)" aria-label="Internal notes"></textarea>
                                </div>
                            </div>

                            <div class="alert alert-info mt-3 mb-0" role="alert">
                                UI-only placeholder modal. Assignment actions are not submitted to backend.
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="card p-3" style="box-shadow:none; border: 1px solid rgba(0,0,0,.06);">
                            <div class="fw-bold mb-2">Related Information</div>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item">
                                    <div class="text-muted small">Customer</div>
                                    <div class="fw-semibold">XYZ Trading</div>
                                </div>
                                <div class="list-group-item">
                                    <div class="text-muted small">Product</div>
                                    <div class="fw-semibold">Widget A</div>
                                </div>
                                <div class="list-group-item">
                                    <div class="text-muted small">Current Status</div>
                                    <div class="fw-semibold">In Progress</div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="text-muted small">Next steps (placeholder)</div>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    <span class="badge bg-primary">Notify team</span>
                                    <span class="badge bg-warning text-dark">Update SLA</span>
                                    <span class="badge bg-success">Confirm availability</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="border-top: 1px solid rgba(0,0,0,.06);">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn" id="ticketsAssignSaveBtn" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
                    Save
                </button>
            </div>


        </div>
    </div>
</div>

