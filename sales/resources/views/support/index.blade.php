@extends('layouts.app')

@section('content')
    @php($title = 'After-Sales Support & Case Management')
    @php($subtitle = 'Monitor support tickets, warranty claims, and customer service activity')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    <div class="row g-4">
        <div class="col-md-3"><x-stat-card title="Open Tickets" value="48" description="Awaiting response" icon="ticket-perforated" tone="primary" /></div>
        <div class="col-md-3"><x-stat-card title="Warranty Claims" value="16" description="Pending review" icon="shield-check" tone="warning" /></div>
        <div class="col-md-3"><x-stat-card title="Service Requests" value="27" description="In queue" icon="tools" tone="success" /></div>
        <div class="col-md-3"><x-stat-card title="Resolved Cases" value="132" description="Completed this month" icon="check2-circle" tone="danger" /></div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">Recent Support Cases</h5>
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>Ticket No.</th><th>Customer</th><th>Issue</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>TK-1001</td><td>ABC Corporation</td><td>Warranty Claim</td><td><span class="badge bg-warning">Pending</span></td></tr>
                        <tr><td>TK-1002</td><td>XYZ Trading</td><td>Product Repair</td><td><span class="badge bg-primary">In Progress</span></td></tr>
                        <tr><td>TK-1003</td><td>John Smith</td><td>Replacement Request</td><td><span class="badge bg-success">Resolved</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">Support Summary</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">New Tickets <strong class="float-end">12</strong></li>
                    <li class="list-group-item">Assigned Cases <strong class="float-end">8</strong></li>
                    <li class="list-group-item">Customer Satisfaction <strong class="float-end">94%</strong></li>
                    <li class="list-group-item">Closed Today <strong class="float-end">9</strong></li>
                </ul>
            </div>
        </div>
    </div>
@endsection
