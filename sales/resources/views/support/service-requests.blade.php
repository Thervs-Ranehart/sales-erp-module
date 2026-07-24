@extends('layouts.app')

@section('content')
    @include('components.page-header', ['title' => 'Service Requests', 'subtitle' => 'Schedule and manage service requests'])
    @include('support.operations-create-modal')
    @include('support.service-request-scheduling-modal')
    @include('support.service-request-view-modal')

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

    <div class="card p-4 mt-4"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>Request</th><th>Ticket</th><th>Customer</th><th>Technician</th><th>Schedule</th><th>Priority</th><th>Status</th><th class="text-end">Actions</th></tr></thead><tbody>
        @forelse($serviceRequests as $req)
            @php($requestStatus = strtolower((string) $req->service_status))
            <tr id="service-request-row-{{ $req->request_id }}">
                <td class="fw-semibold">SR-{{ $req->request_id }}</td><td>TK-{{ $req->supportTicket?->ticket_id ?? '—' }}</td><td>{{ $req->supportTicket?->customer?->full_name ?? '—' }}</td>
                <td class="js-technician">{{ $req->technician?->full_name ?? '—' }}</td><td class="js-schedule">{{ $req->scheduled_date?->format('Y-m-d H:i') ?? '—' }}</td><td class="js-priority">{{ $req->supportTicket?->priority ?? '—' }}</td>
                <td class="js-status"><span class="badge {{ in_array($requestStatus, ['failed', 'rejected', 'cancelled', 'critical', 'overdue']) ? 'bg-danger' : ($requestStatus === 'completed' ? 'bg-success' : ($requestStatus === 'scheduled' ? 'bg-primary' : ($requestStatus === 'pending' ? 'bg-warning text-dark' : 'bg-info'))) }}">{{ $req->service_status ?? '—' }}</span></td>
                <td class="text-end"><div class="d-flex justify-content-end gap-2"><button type="button" class="btn btn-sm btn-outline-primary js-service-request-view" data-request-id="{{ $req->request_id }}" data-bs-toggle="modal" data-bs-target="#serviceRequestViewModal"><i class="bi bi-eye me-1"></i>View</button><button type="button" class="btn btn-sm btn-outline-primary js-service-request-schedule" data-request-id="{{ $req->request_id }}" data-bs-toggle="modal" data-bs-target="#serviceRequestScheduleModal"><i class="bi bi-calendar3 me-1"></i>Schedule</button><button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editRequest{{ $req->request_id }}"><i class="bi bi-pencil"></i></button>@if(!in_array($req->service_status, ['Completed','Cancelled']))<form method="POST" action="{{ route('support.service-requests.cancel', $req) }}" onsubmit="return confirm('Cancel this service request?')">@csrf @method('PATCH')<input type="hidden" name="service_result" value="Cancelled by support staff"><button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i></button></form>@endif</div></td>
            </tr>
        @empty<tr><td colspan="8" class="text-center text-muted py-4">No service requests found.</td></tr>@endforelse
    </tbody></table></div><div class="mt-4">{{ $serviceRequests->links('pagination::bootstrap-5') }}</div></div>

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
        try { const data = await fetchRequest(button.dataset.requestId); const r = data.request || {}; const t = data.ticket || {}; text('serviceRequestScheduleModalSubtitle', `SR-${r.request_id || button.dataset.requestId}`); text('serviceRequestScheduleCustomer', t.name); text('serviceRequestScheduleTicket', t.ticket_id ? `TK-${t.ticket_id}` : null); text('serviceRequestScheduleCoverage', t.service_contract?.coverage || 'No Linked Contract'); text('serviceRequestScheduleContract', contractDetails(t)); const contractLink = document.getElementById('serviceRequestScheduleContractLink'); if (t.service_contract?.contract_number) { contractLink.href = `{{ route('support.service-contracts') }}?search=${encodeURIComponent(t.service_contract.contract_number)}`; contractLink.classList.remove('d-none'); } else { contractLink.classList.add('d-none'); }
            document.getElementById('serviceRequestScheduleDate').value = r.scheduled_date || ''; document.getElementById('serviceRequestScheduleTime').value = r.scheduled_time || ''; document.getElementById('serviceRequestScheduleEnd').value = r.scheduled_end ? r.scheduled_end.slice(-5) : ''; document.getElementById('serviceRequestPriority').value = r.priority || 'Medium'; document.getElementById('serviceRequestScheduleNotes').value = r.schedule_notes || '';
            const technician = document.getElementById('serviceRequestTechnician'); technician.innerHTML = '<option value="">Select technician</option>'; (r.technicians || []).forEach((item) => { const option = document.createElement('option'); option.value = item.employee_id; option.textContent = item.department ? `${item.name} — ${item.department}` : item.name; option.selected = String(item.employee_id) === String(r.technician_id || ''); technician.appendChild(option); });
        } catch (error) { alertBox.className = 'alert alert-danger'; alertBox.textContent = error.message; }
    }));

    scheduleForm?.addEventListener('submit', async (event) => { event.preventDefault(); const requestId = scheduleModal.dataset.requestId; if (!requestId) return; const save = document.getElementById('serviceRequestScheduleSave'); const payload = {technician_id: document.getElementById('serviceRequestTechnician').value, scheduled_date: document.getElementById('serviceRequestScheduleDate').value, scheduled_time: document.getElementById('serviceRequestScheduleTime').value, scheduled_end: document.getElementById('serviceRequestScheduleEnd').value, priority: document.getElementById('serviceRequestPriority').value, schedule_notes: document.getElementById('serviceRequestScheduleNotes').value}; save.disabled = true; save.setAttribute('aria-busy', 'true');
        try { const response = await fetch(`{{ url('/support/service-requests') }}/${requestId}/schedule`, {method: 'PATCH', headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf}, body: JSON.stringify(payload)}); const data = await response.json().catch(() => ({})); if (!response.ok) throw new Error(Object.values(data.errors || {})[0]?.[0] || 'Unable to save the schedule.'); alertBox.className = 'alert alert-success'; alertBox.textContent = data.message; const row = document.getElementById(`service-request-row-${requestId}`); if (row) { row.querySelector('.js-technician').textContent = data.technician || '—'; row.querySelector('.js-schedule').textContent = data.scheduled_date || '—'; row.querySelector('.js-priority').textContent = data.priority || '—'; row.querySelector('.js-status').innerHTML = '<span class="badge bg-primary">Scheduled</span>'; } } catch (error) { alertBox.className = 'alert alert-danger'; alertBox.textContent = error.message; } finally { save.disabled = false; save.removeAttribute('aria-busy'); }
    });
})();
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const contractLink = document.getElementById('serviceRequestScheduleContractLink');
    const scheduleModal = document.getElementById('serviceRequestScheduleModal');

    contractLink?.removeAttribute('href');
    contractLink?.addEventListener('click', async (event) => {
        event.preventDefault();
        const requestId = scheduleModal?.dataset.requestId;
        if (!requestId) return;

        try {
            const response = await fetch(`{{ url('/support/service-requests') }}/${requestId}/show`, {headers: {'X-Requested-With': 'XMLHttpRequest'}});
            const data = await response.json();
            if (!response.ok || !data.contract?.details_url) throw new Error('No linked service contract is available.');
            window.location.assign(data.contract.details_url);
        } catch (error) {
            const alertBox = document.getElementById('serviceRequestScheduleAlert');
            if (alertBox) { alertBox.className = 'alert alert-danger'; alertBox.textContent = error.message; }
        }
    });
});
</script>
@endpush
