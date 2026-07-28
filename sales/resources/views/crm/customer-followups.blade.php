@extends('layouts.app')

@section('title', 'Follow-Ups')
@section('page-title', 'Follow-Ups')

@section('content')

@if (session('success'))

<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}

<button type="button"
     class="btn-close"
     data-bs-dismiss="alert"
     aria-label="Close"> </button>

</div>
@endif

@if ($errors->any())

<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<style>
.crm-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
}

.stat-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 22px;
}

.stat-label {
    font-size: 14px;
    color: #6c757d;
}

.stat-value {
    font-size: 26px;
    font-weight: 600;
}

.table th {
    background: #f8f9fa;
    color: #495057;
    font-size: 13px;
    font-weight: 600;
}

.table td {
    padding: 15px 12px;
    vertical-align: middle;
}

.follow-up-date {
    min-width: 130px;
    white-space: nowrap;
}

.action-btn {
    width: 36px;
    height: 34px;
    padding: 0;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.search-box {
    position: relative;
}

.search-box .search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #5347CE;
    font-size: 15px;
}

.search-box .form-control {
    height: 45px;
    padding-left: 42px;
    border: 2px solid #5347CE;
    border-radius: 10px;
}

.search-box .form-control:focus {
    border-color: #5347CE;
    box-shadow: 0 0 0 .2rem rgba(83, 71, 206, .15);
}

.btn-search {
    height: 45px;
    background: #5347CE;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 600;
}

.btn-search:hover {
    background: #463bb5;
    color: #fff;
}

.high-priority-open {
    background-color: #fff5f5;
}
</style>

{{-- Header --}}

<div class="d-flex justify-content-between align-items-center mb-4">

<div>
    <h4 class="fw-semibold mb-1">
        Follow-Ups
    </h4>

<p class="text-muted mb-0">
    Manage customer follow-up activities.
</p>

</div>

</div>

{{-- Summary Cards --}}

<div class="row g-3 mb-4">

<div class="col-md-3">
    <div class="stat-card">

    <div class="stat-label">
        Today's Follow-Ups
    </div>

    <div class="stat-value">
        {{ $todayCount }}
    </div>

</div>

</div>

<div class="col-md-3">
    <div class="stat-card">

    <div class="stat-label">
        Pending
    </div>

    <div class="stat-value">
        {{ $pendingCount }}
    </div>

</div>

</div>

<div class="col-md-3">
    <div class="stat-card">

    <div class="stat-label">
        Overdue
    </div>

    <div class="stat-value">
        {{ $overdueCount }}
    </div>

</div>

</div>

<div class="col-md-3">
    <div class="stat-card">

    <div class="stat-label">
        Completed
    </div>

    <div class="stat-value">
        {{ $completedCount }}
    </div>

</div>

</div>

</div>

{{-- Filters --}}

<div class="card crm-card p-3 mb-4">

<form method="GET"
      action="{{ route('crm.followups') }}"
      data-crm-auto-filter>

<div class="row g-3">

    {{-- Search --}}
    <div class="col-md-8">

        <div class="search-box">

            <i class="bi bi-search search-icon"></i>

            <input
                type="text"
                class="form-control"
                name="search"
                value="{{ $search ?? '' }}"
                placeholder="Search customer or subject">

        </div>

    </div>


    {{-- Status --}}
    <div class="col-md-4">

        <select class="form-select"
                name="status">

            <option value=""
                {{ empty($status) ? 'selected' : '' }}>
                Status
            </option>

            <option value="Pending"
                {{ ($status ?? '') === 'Pending' ? 'selected' : '' }}>
                Pending
            </option>

            <option value="Completed"
                {{ ($status ?? '') === 'Completed' ? 'selected' : '' }}>
                Completed
            </option>

        </select>

    </div>

</div>

</form>

</div>

{{-- Follow-Ups Table --}}

@if ($openHighPriorityCount > 0)
    <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
        <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
        <span><strong>High-priority follow-up warning:</strong> {{ $openHighPriorityCount }} high-priority {{ Str::plural('follow-up', $openHighPriorityCount) }} {{ $openHighPriorityCount === 1 ? 'is' : 'are' }} still unresolved. Complete or update {{ $openHighPriorityCount === 1 ? 'it' : 'them' }} as soon as possible.</span>
    </div>
@endif

<div class="card crm-card p-4" data-crm-live-results>

<h5 class="fw-semibold mb-3">
    Scheduled Follow-Ups
</h5>

<div class="table-responsive">

<table class="table align-middle">

    <thead>

        <tr>

            <th>
                Customer
            </th>

            <th>
                Agent
            </th>

            <th>
                Subject
            </th>

            <th>
                Channel
            </th>

            <th>
                Priority
            </th>

            <th class="follow-up-date">
                Follow-Up Date
            </th>

            <th class="text-center">
                Status
            </th>

            <th class="text-center">
                Actions
            </th>

        </tr>

    </thead>


    <tbody>

        @forelse ($followUps as $followUp)

            @php

                $customerName = 'Unknown';

                if (!empty($followUp->customer)) {

                    $first = trim(
                        (string) ($followUp->customer->first_name ?? '')
                    );

                    $last = trim(
                        (string) ($followUp->customer->last_name ?? '')
                    );

                    $customerName = trim(
                        $first . ' ' . $last
                    ) ?: 'Unknown';

                }

                $status =
                    $followUp->communication_status;

                $badgeClass =
                    $status === 'Pending'
                        ? 'bg-warning text-dark'
                        : (
                            $status === 'Completed'
                                ? 'bg-success'
                                : 'bg-secondary'
                        );

                $isOpenHighPriority = $followUp->priority === 'High'
                    && $status !== 'Completed';

            @endphp


            <tr @class(['high-priority-open' => $isOpenHighPriority])>

                {{-- Customer --}}
                <td>

                    {{ $customerName }}

                </td>


                {{-- Agent --}}
                <td>

                    {{ $followUp->agent->full_name ?? 'Unassigned' }}

                </td>


                {{-- Subject --}}
                <td>

                    {{ $followUp->subject }}

                    @if ($isOpenHighPriority)
                        <div class="text-danger small mt-1">
                            <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                            Unresolved high-priority follow-up
                        </div>
                    @endif

                </td>


                {{-- Channel --}}
                <td>

                    {{ $followUp->communication_channel }}

                </td>


                {{-- Priority --}}
                <td>

                    @if ($followUp->priority === 'High')

                        <span class="badge bg-danger">
                            High
                        </span>

                    @elseif ($followUp->priority === 'Medium')

                        <span class="badge bg-warning text-dark">
                            Medium
                        </span>

                    @else

                        <span class="badge bg-success">
                            Low
                        </span>

                    @endif

                </td>


                {{-- Follow-Up Date --}}
                <td class="follow-up-date">

                    {{ optional($followUp->follow_up_date)->format('M j, Y') }}

                </td>


                {{-- Status --}}
                <td class="text-center">

                    <span class="badge {{ $badgeClass }}">

                        {{ $status }}

                    </span>

                </td>


                {{-- Actions --}}

                <td class="text-center">

                    <div class="d-flex justify-content-center gap-2">


                        {{-- View --}}

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary action-btn"
                            title="View"
                            data-bs-toggle="modal"
                            data-bs-target="#viewFollowUpModal{{ $followUp->communication_id }}">

                            <i class="bi bi-eye"></i>

                        </button>


                        <button
                            type="button"
                            class="btn btn-sm btn-outline-warning action-btn"
                            title="Edit"
                            data-bs-toggle="modal"
                            data-bs-target="#editFollowUpModal{{ $followUp->communication_id }}">

                            <i class="bi bi-pencil"></i>

                        </button>


                        {{-- Assigned Agents --}}

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-info action-btn"
                            title="Assigned Agents"
                            data-bs-toggle="modal"
                            data-bs-target="#assignAgentModal{{ $followUp->communication_id }}">

                            <i class="bi bi-person-badge"></i>

                        </button>


                        {{-- Delete --}}

                        <form
                            method="POST"
                            action="{{ route('crm.followups.destroy', $followUp) }}"
                            class="m-0"
                            onsubmit="return confirm('Delete this follow-up?');">

                            @csrf

                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-sm btn-outline-danger action-btn"
                                title="Delete">

                                <i class="bi bi-trash"></i>

                            </button>

                        </form>


                        {{-- Update Status --}}

                        <form
                            method="POST"
                            action="{{ route('crm.followups.status.update', $followUp) }}"
                            class="m-0">

                            @csrf

                            <input
                                type="hidden"
                                name="communication_status"
                                value="{{ $status === 'Completed' ? 'Pending' : 'Completed' }}">

                            <button
                                type="submit"
                                class="btn btn-sm {{ $status === 'Completed' ? 'btn-outline-secondary' : 'btn-outline-success' }} action-btn"
                                title="{{ $status === 'Completed' ? 'Reopen follow-up' : 'Mark follow-up as completed' }}">

                                <i class="bi {{ $status === 'Completed' ? 'bi-arrow-counterclockwise' : 'bi-check2-circle' }}"></i>

                            </button>

                        </form>

                    </div>

                </td>

            </tr>


        @empty

            <tr>

                <td
                    colspan="8"
                    class="text-center text-muted py-4">

                    No follow-ups found.

                </td>

            </tr>

        @endforelse

    </tbody>

</table>


{{-- Pagination --}}
<div class="mt-3">

    {{ $followUps->links() }}

</div>

</div>

</div>

{{-- View Follow-Up Modals --}}
@foreach ($followUps as $followUp)

<div
    class="modal fade"
    id="viewFollowUpModal{{ $followUp->communication_id }}"
    tabindex="-1">

<div class="modal-dialog">

<div class="modal-content">


    {{-- Modal Header --}}
    <div class="modal-header">

        <h5 class="modal-title">
            Follow-Up Details
        </h5>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal">

        </button>

    </div>


    {{-- Modal Body --}}
    <div class="modal-body">


        <p>

            <strong>
                Customer:
            </strong>

            {{ optional($followUp->customer)->display_name ?? 'Unknown' }}

        </p>


        <p>

            <strong>
                Assigned Agent:
            </strong>

            {{ $followUp->agent->full_name ?? 'Unassigned' }}

        </p>


        <p>

            <strong>
                Priority:
            </strong>


            @if ($followUp->priority === 'High')

                <span class="badge bg-danger">
                    High
                </span>

            @elseif ($followUp->priority === 'Medium')

                <span class="badge bg-warning text-dark">
                    Medium
                </span>

            @else

                <span class="badge bg-success">
                    Low
                </span>

            @endif

        </p>


        <p>

            <strong>
                Subject:
            </strong>

            {{ $followUp->subject }}

        </p>


        <p>

            <strong>
                Channel:
            </strong>

            {{ $followUp->communication_channel }}

        </p>


        <p>

            <strong>
                Follow-Up Date:
            </strong>

            {{ optional($followUp->follow_up_date)->format('M j, Y') }}

        </p>


        <p>

            <strong>
                Status:
            </strong>

            {{ $followUp->communication_status }}

        </p>


        <p>

            <strong>
                Notes:
            </strong>

            {{ $followUp->notes ?: '—' }}

        </p>

    </div>

</div>

</div>

</div>

@endforeach

{{-- Edit Follow-Up Modals --}}
@foreach ($followUps as $followUp)
    <div class="modal fade" id="editFollowUpModal{{ $followUp->communication_id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" method="POST" action="{{ route('crm.followups.update', $followUp) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Follow-Up</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Customer</label>
                            <select class="form-select" name="customer_id" required>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->customer_id }}" @selected($followUp->customer_id === $customer->customer_id)>{{ $customer->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Assigned Agent</label>
                            <select class="form-select" name="agent_id">
                                <option value="">Unassigned</option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->agent_id }}" @selected($followUp->agent_id === $agent->agent_id)>{{ $agent->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Channel</label>
                            <select class="form-select" name="communication_channel" required>
                                @foreach (['Email', 'Phone', 'SMS', 'Meeting'] as $channel)
                                    <option value="{{ $channel }}" @selected($followUp->communication_channel === $channel)>{{ $channel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Follow-Up Date</label>
                            <input class="form-control" type="date" name="follow_up_date" value="{{ $followUp->follow_up_date?->toDateString() }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Priority</label>
                            <select class="form-select" name="priority" required>
                                @foreach (['Low', 'Medium', 'High'] as $priority)
                                    <option value="{{ $priority }}" @selected($followUp->priority === $priority)>{{ $priority }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">High-priority follow-ups are automatically assigned to the recommended available agent.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="communication_status" required>
                                @foreach (['Pending', 'Completed'] as $followUpStatus)
                                    <option value="{{ $followUpStatus }}" @selected($followUp->communication_status === $followUpStatus)>{{ $followUpStatus }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Subject</label>
                            <input class="form-control" name="subject" value="{{ $followUp->subject }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" rows="4">{{ $followUp->notes }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
@endforeach


{{-- Assigned Agents Modals --}}
@foreach ($followUps as $followUp)

<div
    class="modal fade"
    id="assignAgentModal{{ $followUp->communication_id }}"
    tabindex="-1">

<div class="modal-dialog">

<div class="modal-content">


    {{-- Modal Header --}}
    <div class="modal-header">

        <h5 class="modal-title">
            Assigned Agents
        </h5>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal">

        </button>

    </div>


    {{-- Modal Body --}}
    <div class="modal-body">

        <p>

            <strong>
                Customer:
            </strong>

            {{ optional($followUp->customer)->display_name ?? 'Unknown' }}

        </p>


        <p>

            <strong>
                Currently Assigned:
            </strong>

            {{ $followUp->agent->full_name ?? 'Unassigned' }}

        </p>


        <p>

            <strong>
                Follow-Up Priority:
            </strong>


            @if ($followUp->priority === 'High')

                <span class="badge bg-danger">
                    High
                </span>

            @elseif ($followUp->priority === 'Medium')

                <span class="badge bg-warning text-dark">
                    Medium
                </span>

            @else

                <span class="badge bg-success">
                    Low
                </span>

            @endif

        </p>


        <hr>


        <form
            method="POST"
            action="{{ route('crm.followups.assign-agent', $followUp) }}">

            @csrf

            <label class="form-label">
                Available Agents
            </label>

            <div class="list-group mb-3">

                @forelse (($agentAssignmentOptions[$followUp->communication_id] ?? []) as $option)

                    <label class="list-group-item d-flex justify-content-between align-items-center">

                        <span>

                            <input
                                type="radio"
                                name="agent_id"
                                value="{{ $option['agent_id'] }}"
                                class="form-check-input me-2"
                                {{ ($followUp->agent_id === $option['agent_id'] || (! $followUp->agent_id && $option['recommended'])) ? 'checked' : '' }}
                                required>

                            {{ $option['name'] }}

                            @if ($option['department'])
                                <small class="text-muted">
                                    ({{ $option['department'] }})
                                </small>
                            @endif

                        </span>

                        <span>

                            @if ($option['recommended'])
                                <span class="badge bg-primary">
                                    Recommended
                                </span>
                            @endif

                            <span class="badge bg-secondary">
                                {{ $option['workload'] }} pending
                            </span>

                        </span>

                    </label>

                @empty

                    <p class="text-muted mb-0">
                        No active agents available.
                    </p>

                @endforelse

            </div>

            <button
                type="submit"
                class="btn btn-sm btn-primary">

                Assign Agent

            </button>

        </form>

    </div>

</div>

</div>

</div>

@endforeach


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@include('components.crm-auto-filter')

@endsection
