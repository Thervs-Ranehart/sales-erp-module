@extends('layouts.app')

@section('title', 'Follow-Ups')
@section('page-title', 'Follow-Ups')

@section('content')

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
</style>


{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-semibold mb-1">Follow-Ups</h4>
        <p class="text-muted mb-0">
            Manage customer follow-up activities.
        </p>
    </div>

    <button class="btn btn-main px-4">
        <i class="bi bi-plus-lg"></i>
        Create Follow-Up
    </button>
</div>


{{-- Summary Cards --}}
<div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Today's Follow-Ups</div>
            <div class="stat-value">7</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Pending</div>
            <div class="stat-value">18</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Overdue</div>
            <div class="stat-value">3</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Completed</div>
            <div class="stat-value">156</div>
        </div>
    </div>

</div>


{{-- Filters --}}
<div class="card crm-card p-3 mb-4">
    <div class="row g-3">

        <div class="col-md-6">
            <input
                type="text"
                class="form-control"
                placeholder="Search customer or subject">
        </div>

        <div class="col-md-3">
            <select class="form-select">
                <option>Status</option>
                <option>Pending</option>
                <option>Completed</option>
            </select>
        </div>

        <div class="col-md-3">
            <button class="btn btn-outline-secondary w-100">
                Filter
            </button>
        </div>

    </div>
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

                {{-- Juan Dela Cruz --}}
                <tr>
                    <td>Juan Dela Cruz</td>
                    <td>Order confirmation</td>
                    <td>Email</td>
                    <td>July 10, 2026</td>

                    <td class="text-center">
                        <span class="badge bg-warning text-dark">
                            Pending
                        </span>
                    </td>

                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">

                            <button type="button"
                                    class="btn btn-sm btn-outline-primary action-btn"
                                    title="View">
                                <i class="bi bi-eye"></i>
                            </button>

                            <button type="button"
                                    class="btn btn-sm btn-outline-warning action-btn"
                                    title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <button type="button"
                                    class="btn btn-sm btn-outline-danger action-btn"
                                    title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>

                        </div>
                    </td>
                </tr>


                {{-- Pedro Reyes --}}
                <tr>
                    <td>Pedro Reyes</td>
                    <td>Loyalty reminder</td>
                    <td>SMS</td>
                    <td>July 12, 2026</td>

                    <td class="text-center">
                        <span class="badge bg-warning text-dark">
                            Pending
                        </span>
                    </td>

                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">

                            <button type="button"
                                    class="btn btn-sm btn-outline-primary action-btn"
                                    title="View">
                                <i class="bi bi-eye"></i>
                            </button>

                            <button type="button"
                                    class="btn btn-sm btn-outline-warning action-btn"
                                    title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <button type="button"
                                    class="btn btn-sm btn-outline-danger action-btn"
                                    title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>

                        </div>
                    </td>
                </tr>


                {{-- Maria Santos --}}
                <tr>
                    <td>Maria Santos</td>
                    <td>Product inquiry</td>
                    <td>Phone</td>
                    <td>July 5, 2026</td>

                    <td class="text-center">
                        <span class="badge bg-success">
                            Completed
                        </span>
                    </td>

                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">

                            <button type="button"
                                    class="btn btn-sm btn-outline-primary action-btn"
                                    title="View">
                                <i class="bi bi-eye"></i>
                            </button>

                            <button type="button"
                                    class="btn btn-sm btn-outline-warning action-btn"
                                    title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <button type="button"
                                    class="btn btn-sm btn-outline-danger action-btn"
                                    title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>

                        </div>
                    </td>
                </tr>

            </tbody>

        </table>

    </div>
</div>

@endsection