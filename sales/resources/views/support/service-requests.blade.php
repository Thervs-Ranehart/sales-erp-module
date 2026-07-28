@extends('layouts.app')

@section('content')
    <style>
        .support-module .sr-table-card { border-radius:16px; overflow:hidden; }.support-module .sr-table { min-width:1040px; }.support-module .sr-table thead th { background:#f8f9fc; border-bottom:1px solid #e8eaf0; color:#697386; font-size:.7rem; font-weight:700; letter-spacing:.04em; padding:.85rem 1rem; text-transform:uppercase; white-space:nowrap; }.support-module .sr-table tbody td { border-color:#f0f1f5; font-size:.84rem; padding:1rem; vertical-align:middle; }.support-module .sr-table tbody tr { transition:background-color .16s ease; }.support-module .sr-table tbody tr:hover { background:#fafaff; }
        .support-module .sr-action-header, .support-module .sr-action-cell { min-width:168px; text-align:center; }.support-module .sr-action-cell { padding-inline:1.15rem!important; }.support-module .sr-action-stack { align-items:center; display:flex; flex-direction:column; gap:.65rem; justify-content:flex-start; min-height:36px; }.support-module .sr-action-stack form { margin:0; width:132px; }.support-module .sr-action-btn { align-items:center; border-radius:8px; display:inline-flex; font-size:.8rem; font-weight:600; height:34px; justify-content:center; padding:.4rem .7rem; transition:background-color .16s ease, border-color .16s ease, box-shadow .16s ease, transform .16s ease; width:132px; }.support-module .sr-action-btn i { margin-right:.4rem; }.support-module .sr-action-btn:hover { box-shadow:0 4px 10px rgba(31,41,55,.1); transform:translateY(-1px); }.support-module .sr-action-btn:focus-visible { box-shadow:0 0 0 .2rem rgba(83,71,206,.2); outline:0; }
        .support-module .sr-view-btn:hover { background:#eef2ff; border-color:#7c73df; color:#4338ca; }.support-module .sr-schedule-btn { background:#5347ce; border-color:#5347ce; color:#fff; }.support-module .sr-schedule-btn:hover, .support-module .sr-schedule-btn:focus { background:#4338ca; border-color:#4338ca; color:#fff; }.support-module .sr-edit-btn:hover { background:#fff8e6; border-color:#f2b94b; color:#a16207; }.support-module .sr-cancel-btn:hover { background:#fff0f0; border-color:#f87171; color:#b91c1c; }
        @media (max-width: 767.98px) { .support-module .sr-action-header, .support-module .sr-action-cell { min-width:154px; }.support-module .sr-action-btn, .support-module .sr-action-stack form { width:122px; } }
    </style>
    @include('components.page-header', ['title' => 'Service Requests', 'subtitle' => 'Schedule and manage service requests'])
    @include('support.service-request-scheduling-modal')
    @include('support.service-request-view-modal')
    @include('support.service-contract-view-modal')

    <div class="row g-4">
        @foreach ([['Pending', $pendingServiceRequestsCount ?? 0, 'warning'], ['Scheduled', $scheduledServiceRequestsCount ?? 0, 'primary'], ['In Progress', $inProgressServiceRequestsCount ?? 0, 'info'], ['Completed', $completedServiceRequestsCount ?? 0, 'success']] as [$label, $count, $color])
            <div class="col-md-3"><div class="card p-3 h-100"><div class="text-muted small fw-semibold">{{ $label }}</div><div class="display-6 fw-bold">{{ $count }}</div><div class="mt-2"><span class="badge bg-{{ $color }} {{ $color === 'warning' ? 'text-dark' : '' }}">{{ $label }}</span></div></div></div>
        @endforeach
    </div>

    <div class="card p-3 mt-4"><form method="GET" action="{{ route('support.service-requests') }}"><div class="row g-3">
        <div class="col-lg-4"><label class="form-label small text-muted">Search</label><input name="search" class="form-control form-control-sm" value="{{ $search ?? '' }}" placeholder="Request, ticket, customer, product..." /></div>
        <div class="col-6 col-lg-2"><label class="form-label small text-muted">Status</label><select name="status" class="form-select form-select-sm"><option value="all">All statuses</option>@foreach(['Pending', 'Scheduled', 'In Progress', 'Completed', 'Cancelled', 'Failed', 'Rejected'] as $statusOption)<option value="{{ $statusOption }}" @selected(($status ?? '') === $statusOption)>{{ $statusOption }}</option>@endforeach</select></div>
        <div class="col-6 col-lg-2"><label class="form-label small text-muted">Technician</label><select name="technician" class="form-select form-select-sm"><option value="">All technicians</option>@foreach($technicians as $technicianOption)<option value="{{ $technicianOption->employee_id }}" @selected((string) ($technician ?? '') === (string) $technicianOption->employee_id)>{{ $technicianOption->full_name }}</option>@endforeach</select></div>
        <div class="col-6 col-lg-2"><label class="form-label small text-muted">Scheduled Date</label><input type="date" name="date" class="form-control form-control-sm" value="{{ $date ?? '' }}" /></div>
        <div class="col-6 col-lg-2 d-flex align-items-end"><button class="btn btn-sm w-100" style="background:#5347CE;color:#fff;">Apply</button></div>
    </div></form></div>

    <div class="card p-4 mt-4 sr-table-card"><div class="table-responsive after-sales-table-responsive"><table class="table sr-table align-middle mb-0"><thead><tr><th>Request</th><th>Ticket</th><th>Customer</th><th>Technician</th><th>Schedule</th><th>Priority</th><th>Status</th><th class="sr-action-header">Actions</th></tr></thead><tbody>
        @forelse($serviceRequests as $req)
            @php($requestStatus = strtolower((string) $req->service_status))
            <tr id="service-request-row-{{ $req->request_id }}">
                <td class="fw-semibold">SR-{{ $req->request_id }}</td><td>TK-{{ $req->supportTicket?->ticket_id ?? '—' }}</td><td>{{ $req->supportTicket?->customer?->full_name ?? '—' }}</td>
                <td class="js-technician">{{ $req->technician?->full_name ?? '—' }}</td><td class="js-schedule">{{ $req->scheduled_date?->format('Y-m-d H:i') ?? '—' }}</td><td class="js-priority">{{ $req->supportTicket?->priority ?? '—' }}</td>
                <td class="js-status"><span class="badge {{ in_array($requestStatus, ['failed', 'rejected', 'cancelled', 'critical', 'overdue']) ? 'bg-danger' : ($requestStatus === 'completed' ? 'bg-success' : ($requestStatus === 'scheduled' ? 'bg-primary' : ($requestStatus === 'pending' ? 'bg-warning text-dark' : 'bg-info'))) }}">{{ $req->service_status ?? '—' }}</span></td>
                <td class="sr-action-cell support-action-cell"><div class="sr-action-stack support-action-group"><button type="button" class="btn btn-outline-primary sr-action-btn sr-view-btn support-action-button js-service-request-view" data-request-id="{{ $req->request_id }}" data-bs-toggle="modal" data-bs-target="#serviceRequestViewModal" title="View service request" aria-label="View service request"><i class="bi bi-eye" aria-hidden="true"></i><span class="visually-hidden">View</span></button>@if(in_array($requestStatus, ['pending', 'scheduled']))<button type="button" class="btn btn-outline-primary sr-action-btn sr-schedule-btn support-action-button support-action-schedule js-service-request-schedule" data-request-id="{{ $req->request_id }}" data-bs-toggle="modal" data-bs-target="#serviceRequestScheduleModal" title="{{ $requestStatus === 'scheduled' ? 'Reschedule service request' : 'Schedule service request' }}" aria-label="{{ $requestStatus === 'scheduled' ? 'Reschedule service request' : 'Schedule service request' }}"><i class="bi bi-calendar3" aria-hidden="true"></i><span class="visually-hidden">{{ $requestStatus === 'scheduled' ? 'Reschedule' : 'Schedule' }}</span></button><button type="button" class="btn btn-outline-warning sr-action-btn sr-edit-btn support-action-button" data-bs-toggle="modal" data-bs-target="#editRequest{{ $req->request_id }}" title="Edit service request" aria-label="Edit service request"><i class="bi bi-pencil" aria-hidden="true"></i><span class="visually-hidden">Edit</span></button><form class="support-action-destructive" method="POST" action="{{ route('support.service-requests.cancel', $req) }}" onsubmit="return confirm('Cancel this service request?')">@csrf @method('PATCH')<input type="hidden" name="service_result" value="Cancelled by support staff"><button class="btn btn-outline-danger sr-action-btn sr-cancel-btn support-action-button" type="submit" title="Cancel service request" aria-label="Cancel service request"><i class="bi bi-x-circle" aria-hidden="true"></i><span class="visually-hidden">Cancel</span></button></form>@endif</div></td>
            </tr>
        @empty<tr><td colspan="8" class="text-center text-muted py-4">No service requests found.</td></tr>@endforelse
    </tbody></table></div><x-support-pagination :paginator="$serviceRequests" /></div>

@foreach($serviceRequests as $req)<div class="modal fade" id="editRequest{{ $req->request_id }}" tabindex="-1"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('support.service-requests.update', $req) }}">@csrf @method('PUT')<div class="modal-header"><h5 class="modal-title">Update SR-{{ $req->request_id }}</h5><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">
<label class="form-label">Request Type</label><input class="form-control mb-3" name="request_type" value="{{ $req->request_type }}" required><label class="form-label">Technician</label><select class="form-select mb-3" name="technician_id"><option value="">Unassigned</option>@foreach($technicians as $technicianOption)<option value="{{ $technicianOption->employee_id }}" @selected($req->technician_id===$technicianOption->employee_id)>{{ $technicianOption->full_name }}</option>@endforeach</select><label class="form-label">Status</label><select class="form-select mb-3" name="service_status">@foreach(['Pending','Scheduled','In Progress','Completed','Cancelled','Failed','Rejected'] as $option)<option @selected($req->service_status===$option)>{{ $option }}</option>@endforeach</select><label class="form-label">Schedule Notes</label><textarea class="form-control mb-3" name="schedule_notes">{{ $req->schedule_notes }}</textarea><label class="form-label">Service Result</label><textarea class="form-control" name="service_result">{{ $req->service_result }}</textarea><div class="form-text">A service result is required when marking the request completed.</div>
</div><div class="modal-footer"><button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button><button class="btn support-primary">Save Changes</button></div></form></div></div>@endforeach
@endsection

@push('scripts')
<script>
(() => {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const scheduleModal = document.getElementById('serviceRequestScheduleModal');
    const scheduleForm = document.getElementById('serviceRequestScheduleForm');
    const alertBox = document.getElementById('serviceRequestScheduleAlert');
    const text = (id, value) => { const el = document.getElementById(id); if (el) el.textContent = value || '—'; };
    const fetchRequest = async (requestId) => { const response = await fetch(`{{ url('/support/service-requests') }}/${requestId}/show`, {headers: {'X-Requested-With': 'XMLHttpRequest'}}); if (!response.ok) throw new Error('Unable to load this service request.'); return response.json(); };
    const contractDetails = (ticket) => ticket.service_contract ? `${ticket.service_contract.contract_number || '—'} (${ticket.service_contract.status || '—'})` : 'No linked contract';

    document.querySelectorAll('.js-service-request-view').forEach((button) => button.addEventListener('click', async () => {
        try { const data = await fetchRequest(button.dataset.requestId); const r = data.request || {}; const t = data.ticket || {}; text('serviceRequestViewModalSubtitle', `SR-${r.request_id || button.dataset.requestId}`); text('serviceRequestViewCustomer', t.name); text('serviceRequestViewTicket', t.ticket_id ? `TK-${t.ticket_id}` : null); text('serviceRequestViewProduct', t.product); text('serviceRequestViewIssue', r.request_type); text('serviceRequestViewDescription', t.description); text('serviceRequestViewContract', contractDetails(t)); text('serviceRequestViewCoverage', t.service_contract?.coverage || 'No Linked Contract'); text('serviceRequestViewScheduled', r.scheduled_at); text('serviceRequestViewCompleted', r.completion_date); text('serviceRequestViewTechnician', r.technician?.name); text('serviceRequestViewPriority', r.priority); text('serviceRequestViewStatus', r.service_status); text('serviceRequestViewNotes', r.schedule_notes); } catch (error) { text('serviceRequestViewModalSubtitle', error.message); }
    }));

    document.querySelectorAll('.js-service-request-schedule').forEach((button) => button.addEventListener('click', async () => {
        scheduleModal.dataset.requestId = button.dataset.requestId; alertBox.className = 'alert d-none';
        try { const data = await fetchRequest(button.dataset.requestId); const r = data.request || {}; const t = data.ticket || {}; text('serviceRequestScheduleModalSubtitle', `SR-${r.request_id || button.dataset.requestId}`); text('serviceRequestScheduleCustomer', t.name); text('serviceRequestScheduleTicket', t.ticket_id ? `TK-${t.ticket_id}` : null); text('serviceRequestScheduleCoverage', t.service_contract?.coverage || 'No Linked Contract'); text('serviceRequestScheduleContract', contractDetails(t)); const contractLink = document.getElementById('serviceRequestScheduleContractLink'); if (t.service_contract?.contract_id) { contractLink.dataset.contractId = t.service_contract.contract_id; contractLink.classList.remove('d-none'); } else { delete contractLink.dataset.contractId; contractLink.classList.add('d-none'); }
            document.getElementById('serviceRequestScheduleDate').value = r.scheduled_date || ''; document.getElementById('serviceRequestScheduleTime').value = r.scheduled_time || ''; document.getElementById('serviceRequestScheduleEnd').value = r.scheduled_end ? r.scheduled_end.slice(-5) : ''; document.getElementById('serviceRequestPriority').value = r.priority || 'Medium'; document.getElementById('serviceRequestScheduleNotes').value = r.schedule_notes || '';
            const technician = document.getElementById('serviceRequestTechnician'); technician.innerHTML = '<option value="">Select technician</option>'; (r.technicians || []).forEach((item) => { const option = document.createElement('option'); option.value = item.employee_id; option.textContent = item.department ? `${item.name} — ${item.department}` : item.name; option.selected = String(item.employee_id) === String(r.technician_id || ''); technician.appendChild(option); });
        } catch (error) { alertBox.className = 'alert alert-danger'; alertBox.textContent = error.message; }
    }));

    scheduleForm?.addEventListener('submit', async (event) => { event.preventDefault(); const requestId = scheduleModal.dataset.requestId; if (!requestId) return; const save = document.getElementById('serviceRequestScheduleSave'); const payload = {technician_id: document.getElementById('serviceRequestTechnician').value, scheduled_date: document.getElementById('serviceRequestScheduleDate').value, scheduled_time: document.getElementById('serviceRequestScheduleTime').value, scheduled_end: document.getElementById('serviceRequestScheduleEnd').value, priority: document.getElementById('serviceRequestPriority').value, schedule_notes: document.getElementById('serviceRequestScheduleNotes').value}; save.disabled = true; save.setAttribute('aria-busy', 'true');
        try { const response = await fetch(`{{ url('/support/service-requests') }}/${requestId}/schedule`, {method: 'PATCH', headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf}, body: JSON.stringify(payload)}); const data = await response.json().catch(() => ({})); if (!response.ok) throw new Error(Object.values(data.errors || {})[0]?.[0] || 'Unable to save the schedule.'); alertBox.className = 'alert alert-success'; alertBox.textContent = data.message; const row = document.getElementById(`service-request-row-${requestId}`); if (row) { row.querySelector('.js-technician').textContent = data.technician || '—'; row.querySelector('.js-schedule').textContent = data.scheduled_date || '—'; row.querySelector('.js-priority').textContent = data.priority || '—'; row.querySelector('.js-status').innerHTML = '<span class="badge bg-primary">Scheduled</span>'; const scheduleButton = row.querySelector('.js-service-request-schedule'); if (scheduleButton) scheduleButton.innerHTML = '<i class="bi bi-calendar3" aria-hidden="true"></i>Reschedule'; } } catch (error) { alertBox.className = 'alert alert-danger'; alertBox.textContent = error.message; } finally { save.disabled = false; save.removeAttribute('aria-busy'); }
    });
})();
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const contractLink = document.getElementById('serviceRequestScheduleContractLink');
    const scheduleModal = document.getElementById('serviceRequestScheduleModal');

    contractLink?.addEventListener('click', async (event) => {
        event.preventDefault();
        const contractId = contractLink.dataset.contractId;
        const alertBox = document.getElementById('serviceRequestScheduleAlert');
        if (!contractId) { if (alertBox) { alertBox.className = 'alert alert-danger'; alertBox.textContent = 'No Service Contract is associated with this request.'; } return; }

        try {
            const response = await fetch(`{{ url('/support/service-contracts') }}/${contractId}/show`, {headers: {'X-Requested-With': 'XMLHttpRequest'}});
            const data = await response.json();
            if (!response.ok || !data.contract) throw new Error('No Service Contract is associated with this request.');
            const contract = data.contract;
            document.getElementById('serviceContractModalSubtitle').textContent = contract.contract_number ? `${contract.contract_number} • ${contract.customer?.name ?? '—'}` : '—';
            document.getElementById('serviceContractCustomer').textContent = contract.customer?.name ?? '—';
            document.getElementById('serviceContractProduct').textContent = contract.product?.product_name ?? '—';
            document.getElementById('serviceContractServiceType').textContent = contract.service_type ?? '—';
            document.getElementById('serviceContractStartDate').textContent = contract.service_start ?? '—';
            document.getElementById('serviceContractEndDate').textContent = contract.service_end ?? '—';
            document.getElementById('serviceContractCreatedDate').textContent = contract.created_at ?? '—';
            const statusBadge = document.getElementById('serviceContractStatusBadge'); statusBadge.textContent = contract.contract_status ?? '—'; statusBadge.className = `badge ${contract.contract_status === 'Active' ? 'bg-success' : 'bg-secondary'}`;
            bootstrap.Modal.getInstance(scheduleModal)?.hide();
            bootstrap.Modal.getOrCreateInstance(document.getElementById('serviceContractModal')).show();
        } catch (error) {
            if (alertBox) { alertBox.className = 'alert alert-danger'; alertBox.textContent = error.message; }
        }
    });
});
</script>
@endpush
