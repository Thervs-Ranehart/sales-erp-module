@extends('layouts.app')

@php
    $title = 'Customer Directory';
    $subtitle = 'Manage customer information and relationship data.';
@endphp

@section('content')

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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

.communication-card {
    background:#fff;
    border:1px solid #e9ecef;
    border-radius:12px;
}


.summary-box {
    padding:20px;
    border:1px solid #e9ecef;
    border-radius:12px;
    background:#fff;
}


.summary-label {
    font-size:13px;
    color:#6c757d;
}


.summary-value {
    font-size:26px;
    font-weight:600;
}



.channel-item {
    display:flex;
    justify-content:space-between;
    padding:12px 0;
    border-bottom:1px solid #eee;
}


.channel-item:last-child {
    border-bottom:none;
}



.timeline {
    border-left:2px solid #dee2e6;
    padding-left:20px;
}


.timeline-item {
    position:relative;
    margin-bottom:20px;
}


.timeline-item::before {

    content:"";
    position:absolute;
    left:-28px;
    top:5px;
    width:12px;
    height:12px;
    border-radius:50%;
    background:#5347CE;

}



.table th {

    background:#f8f9fa;
    color:#495057;
    font-size:13px;
    font-weight:600;

}


.table td {

    padding:15px 12px;
    vertical-align:middle;

}



.action-btn {

    font-size:13px;
    padding:6px 12px;
    border-radius:8px;
    margin:2px;

}



.btn-main {

    background:#5347CE;
    color:white;
    border-radius:8px;

}


.btn-main:hover {

    background:#463bb5;
    color:white;

}

.search-box{
    position:relative;
}

.search-box .search-icon{
    position:absolute;
    left:14px;
    top:50%;
    transform:translateY(-50%);
    color:#5347CE;
    font-size:15px;
}

.search-box .form-control{
    height:45px;
    padding-left:42px;
    border:2px solid #5347CE;
    border-radius:10px;
}

.search-box .form-control:focus{
    border-color:#5347CE;
    box-shadow:0 0 0 .2rem rgba(83,71,206,.15);
}
</style>





{{-- Header --}}

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h4 class="fw-semibold mb-1">
Communication Logs
</h4>

<p class="text-muted mb-0">
Track customer conversations, inquiries, and follow-up activities.
</p>

</div>


<button class="btn btn-main px-4" type="button" data-bs-toggle="modal" data-bs-target="#newCommunicationModal">

<i class="bi bi-plus-lg"></i>
New Communication

</button>


</div>







{{-- Summary --}}

@php
    $logsCollection = collect($logs->items());
    $totalForPage = $logsCollection->count();

    $emailCount = $logsCollection->where('communication_channel', 'Email')->count();
    $phoneCount = $logsCollection->where('communication_channel', 'Phone')->count();
    $smsCount = $logsCollection->where('communication_channel', 'SMS')->count();

    $emailPct = $totalForPage > 0 ? round(($emailCount / $totalForPage) * 100) : 0;
    $phonePct = $totalForPage > 0 ? round(($phoneCount / $totalForPage) * 100) : 0;
    $smsPct = $totalForPage > 0 ? round(($smsCount / $totalForPage) * 100) : 0;
@endphp

<div class="row g-3 mb-4">


<div class="col-md-3">

<div class="summary-box">

<div class="summary-label">
Total Conversations
</div>

<div class="summary-value">
{{ number_format($totalConversations) }}
</div>

</div>

</div>



<div class="col-md-3">

<div class="summary-box">

<div class="summary-label">
Pending Follow-Ups
</div>

<div class="summary-value">
{{ number_format($pendingFollowUps) }}
</div>

</div>

</div>




<div class="col-md-3">

<div class="summary-box">

<div class="summary-label">
Resolved
</div>

<div class="summary-value">
{{ number_format($resolved) }}
</div>

</div>

</div>



<div class="col-md-3">

<div class="summary-box">

<div class="summary-label">
Response Rate
</div>

<div class="summary-value">
{{ $responseRate }}%
</div>

</div>

</div>


</div>







{{-- Activity + Channel --}}

<div class="row g-4 mb-4">


<div class="col-md-7">


<div class="card communication-card p-4">


<h6 class="fw-semibold mb-3">
Recent Customer Activity
</h6>



<div class="timeline">


@php
    $recentLogs = $logsCollection->sortByDesc(fn($l) => $l->communication_date ?? null)->take(2);
@endphp

@foreach($recentLogs as $log)

<div class="timeline-item">

<strong>
{{ optional($log->customer)->display_name ?? trim(($log->customer->first_name ?? '').' '.($log->customer->last_name ?? '')) }}
</strong>

<p class="mb-1 text-muted">
{{ $log->subject }}
</p>

<small>
{{ $log->communication_channel }} • {{ $log->communication_date ? $log->communication_date->format('M j, Y') : '—' }}
</small>

</div>

@endforeach



</div>


</div>


</div>





<div class="col-md-5">


<div class="card communication-card p-4">


<h6 class="fw-semibold mb-3">
Communication Channels
</h6>



<div class="channel-item">

<span>Email</span>

<strong>{{ $emailPct }}%</strong>

</div>



<div class="channel-item">

<span>Phone</span>

<strong>{{ $phonePct }}%</strong>

</div>



<div class="channel-item">

<span>SMS</span>

<strong>{{ $smsPct }}%</strong>

</div>



</div>


</div>


</div>







{{-- Filters --}}

<div class="card communication-card p-3 mb-4">


<form method="GET" action="{{ route('crm.logs') }}">

<div class="row g-3">


<div class="col-md-5">

    <div class="search-box">

        <i class="bi bi-search search-icon"></i>

        <input
            type="text"
            name="search"
            value="{{ $search }}"
            class="form-control"
            placeholder="Search customer, subject, or log ID">

    </div>

</div>



<div class="col-md-2">

<select class="form-select" name="channel">

<option value="" {{ empty($channel) ? 'selected' : '' }}>
Channel
</option>

<option value="Email" {{ $channel === 'Email' ? 'selected' : '' }}>Email</option>
<option value="Phone" {{ $channel === 'Phone' ? 'selected' : '' }}>Phone</option>
<option value="SMS" {{ $channel === 'SMS' ? 'selected' : '' }}>SMS</option>

</select>

</div>




<div class="col-md-2">

<select class="form-select" name="status">

<option value="" {{ empty($status) ? 'selected' : '' }}>
Status
</option>

<option value="Pending" {{ $status === 'Pending' ? 'selected' : '' }}>Pending</option>
<option value="Resolved" {{ $status === 'Resolved' ? 'selected' : '' }}>Resolved</option>

</select>

</div>




<div class="col-md-3">

<button class="btn btn-outline-secondary w-100" type="submit">
    <i class="bi bi-funnel"></i>
    Filter
</button>

</div>


</div>


</form>


</div>








{{-- Records --}}

<div class="card communication-card p-4">


<h5 class="fw-semibold mb-3">
Interaction Records
</h5>




<div class="table-responsive">


<table class="table align-middle">


<thead>

<tr>

<th>
Log ID
</th>

<th>
Customer
</th>

<th>
Channel
</th>

<th>
Subject
</th>

<th class="text-center">
Priority
</th>

<th>
Follow-Up
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


@forelse($logs as $log)


<tr>

<td>
{{ $log->communication_id }}
</td>



<td>
{{ optional($log->customer)->display_name ?? trim(optional($log->customer)->first_name.' '.optional($log->customer)->last_name) }}
</td>


<td>
{{ $log->communication_channel }}
</td>


<td>
{{ $log->subject }}
</td>


<td class="text-center">

@if($log->communication_status === 'Pending')
<span class="badge bg-danger">High</span>
@else
<span class="badge bg-secondary">Normal</span>
@endif

</td>


<td>
{{ $log->follow_up_date ? $log->follow_up_date->format('M j, Y') : '—' }}
</td>


<td class="text-center">

@if($log->communication_status === 'Pending')
<span class="badge bg-warning text-dark">Pending</span>
@elseif($log->communication_status === 'Resolved')
<span class="badge bg-success">Resolved</span>
@else
<span class="badge bg-secondary">{{ $log->communication_status }}</span>
@endif

</td>


<td class="text-center">


<button class="btn btn-sm btn-outline-primary action-btn" type="button" data-bs-toggle="modal" data-bs-target="#viewLogModal{{ $log->communication_id }}">
<i class="bi bi-eye"></i>

</button>


<form method="POST" action="{{ route('crm.logs.status.update', $log) }}" class="d-inline">
@csrf
<select name="communication_status" class="form-select form-select-sm d-inline-block" style="width:auto;" onchange="this.form.submit()">
<option value="Pending" {{ $log->communication_status === 'Pending' ? 'selected' : '' }}>Pending</option>
<option value="Resolved" {{ $log->communication_status === 'Resolved' ? 'selected' : '' }}>Resolved</option>
<option value="Completed" {{ $log->communication_status === 'Completed' ? 'selected' : '' }}>Completed</option>
</select>
</form>


<form method="POST" action="{{ route('crm.logs.destroy', $log) }}" style="display:inline" onsubmit="return confirm('Delete this log?')">
    @csrf
    @method('DELETE')
    <button class="btn btn-sm btn-outline-danger action-btn" type="submit">
        <i class="bi bi-trash"></i>
    </button>
</form>


</td>


</tr>

@empty
<tr>
<td colspan="8" class="text-center text-muted">No communication logs found.</td>
</tr>
@endforelse



</tbody>


</table>


</div>


</div>


<div class="mt-3">
    {{ $logs->links() }}
</div>


</div>



@foreach($logs as $log)
<div class="modal fade" id="viewLogModal{{ $log->communication_id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Log #{{ $log->communication_id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Customer:</strong> {{ optional($log->customer)->display_name ?? '—' }}</p>
                <p><strong>Channel:</strong> {{ $log->communication_channel }}</p>
                <p><strong>Subject:</strong> {{ $log->subject }}</p>
                <p><strong>Status:</strong> {{ $log->communication_status }}</p>
                <p><strong>Follow-up Date:</strong> {{ $log->follow_up_date ? $log->follow_up_date->format('M j, Y') : '—' }}</p>
                <p><strong>Details:</strong></p>
                <p class="text-muted">{{ $log->notes ?: 'No additional details.' }}</p>
            </div>
        </div>
    </div>
</div>
@endforeach

<div class="modal fade" id="newCommunicationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('crm.logs.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">New Communication Log</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Customer</label>
                            <select name="customer_id" class="form-select" required>
                                <option value="">Select customer</option>
                                @foreach($customers as $cust)
                                <option value="{{ $cust->customer_id }}">{{ $cust->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Channel</label>
                            <select name="communication_channel" class="form-select" required>
                                <option value="Email">Email</option>
                                <option value="Phone">Phone</option>
                                <option value="SMS">SMS</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message / Details</label>
                            <textarea name="notes" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="communication_status" class="form-select" required>
                                <option value="Pending">Pending</option>
                                <option value="Resolved">Resolved</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Follow-up Date</label>
                            <input type="date" name="follow_up_date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-main">Save Log</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>




@endsection
