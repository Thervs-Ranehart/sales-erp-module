@extends('layouts.app')

@section('title', 'Follow-Ups')
@section('page-title', 'Follow-Ups')

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

.action-btn {
    width: 36px;
    height: 34px;
    padding: 0;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-main {
    background: #5347CE;
    color: white;
    border-radius: 8px;
}

.btn-main:hover {
    background: #463bb5;
    color: white;
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
        <h4 class="fw-semibold mb-1">Follow-Ups</h4>
        <p class="text-muted mb-0">
            Manage customer follow-up activities.
        </p>
    </div>

    <button class="btn btn-main px-4" type="button" data-bs-toggle="modal" data-bs-target="#createFollowUpModal">
        <i class="bi bi-plus-lg"></i>
        Create Follow-Up
    </button>
</div>


{{-- Summary Cards --}}
<div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Today's Follow-Ups</div>
            <div class="stat-value">{{ $todayCount }}</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Pending</div>
            <div class="stat-value">{{ $pendingCount }}</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Overdue</div>
            <div class="stat-value">{{ $overdueCount }}</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Completed</div>
            <div class="stat-value">{{ $completedCount }}</div>
        </div>
    </div>

</div>


{{-- Filters --}}
<div class="card crm-card p-3 mb-4">
    <form method="GET" action="{{ route('crm.followups') }}">
        <div class="row g-3">

            <div class="col-md-6">

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

            <div class="col-md-3">
                <select class="form-select" name="status">
                    <option value="" {{ empty($status) ? 'selected' : '' }}>Status</option>
                    <option value="Pending" {{ ($status ?? '') === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Completed" {{ ($status ?? '') === 'Completed' ? 'selected' : '' }}>Completed</option>
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


{{-- Table --}}
<div class="card crm-card p-4">

    <h5 class="fw-semibold mb-3">
        Scheduled Follow-Ups
    </h5>

    <div class="table-responsive">

        <table class="table align-middle">

            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Subject</th>
                    <th>Channel</th>
                    <th>Follow-Up Date</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($followUps as $followUp)
                    @php
                        $customerName = 'Unknown';
                        if (!empty($followUp->customer)) {
                            $first = trim((string) ($followUp->customer->first_name ?? ''));
                            $last = trim((string) ($followUp->customer->last_name ?? ''));
                            $customerName = trim($first.' '.$last) ?: 'Unknown';
                        }

                        $status = $followUp->communication_status;
                        $badgeClass = $status === 'Pending' ? 'bg-warning text-dark' : ($status === 'Completed' ? 'bg-success' : 'bg-secondary');
                    @endphp

                    <tr>
                        <td>{{ $customerName }}</td>
                        <td>{{ $followUp->subject }}</td>
                        <td>{{ $followUp->communication_channel }}</td>
                        <td>{{ optional($followUp->follow_up_date)->format('M j, Y') }}</td>

                        <td class="text-center">
                            <span class="badge {{ $badgeClass }}">
                                {{ $status }}
                            </span>
                        </td>

                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">

                                <button type="button"
                                        class="btn btn-sm btn-outline-primary action-btn"
                                        title="View"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewFollowUpModal{{ $followUp->communication_id }}">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <form method="POST" action="{{ route('crm.followups.destroy', $followUp) }}" class="m-0" onsubmit="return confirm('Delete this follow-up?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger action-btn" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('crm.followups.status.update', $followUp) }}" class="m-0">
                                    @csrf
                                    <div class="d-flex justify-content-center gap-2">
                                        <select name="communication_status" class="form-select form-select-sm" style="width: 130px;">
                                            <option value="Pending" {{ $status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Completed" {{ $status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-outline-secondary action-btn" title="Update">
                                            <i class="bi bi-check2"></i>
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No follow-ups found.</td>
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

@foreach($followUps as $followUp)
<div class="modal fade" id="viewFollowUpModal{{ $followUp->communication_id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Follow-Up Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Customer:</strong> {{ optional($followUp->customer)->display_name ?? 'Unknown' }}</p>
                <p><strong>Subject:</strong> {{ $followUp->subject }}</p>
                <p><strong>Channel:</strong> {{ $followUp->communication_channel }}</p>
                <p><strong>Follow-up Date:</strong> {{ optional($followUp->follow_up_date)->format('M j, Y') }}</p>
                <p><strong>Status:</strong> {{ $followUp->communication_status }}</p>
                <p><strong>Notes:</strong> {{ $followUp->notes ?: '—' }}</p>
            </div>
        </div>
    </div>
</div>
@endforeach

<div class="modal fade" id="createFollowUpModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('crm.followups.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Create Follow-Up</h5>
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
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Follow-up Date</label>
                            <input type="date" name="follow_up_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="communication_status" class="form-select" required>
                                <option value="Pending">Pending</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-main">Create Follow-Up</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection
