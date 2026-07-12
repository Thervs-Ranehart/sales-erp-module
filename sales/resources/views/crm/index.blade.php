@extends('layouts.app')

@section('content')
    @php($title = 'Customer Relationship Management')
    @php($subtitle = 'Track customer activity, loyalty, and relationships')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    <div class="row g-4">
        <div class="col-md-3"><x-stat-card title="Total Customers" value="1,245" description="Business accounts retained" icon="people" tone="primary" /></div>
        <div class="col-md-3"><x-stat-card title="Active Customers" value="987" description="Engaged in the last 30 days" icon="person-check" tone="success" /></div>
        <div class="col-md-3"><x-stat-card title="Loyalty Members" value="654" description="Enrolled in rewards program" icon="award" tone="warning" /></div>
        <div class="col-md-3"><x-stat-card title="Pending Follow-Ups" value="18" description="Requires follow-up action" icon="calendar-check" tone="danger" /></div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">Recent Customer Activities</h5>
                <table class="table mb-0">
                    <thead>
                        <tr><th>Customer</th><th>Activity</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>ABC Corporation</td><td>Placed New Order</td><td>July 7, 2026</td></tr>
                        <tr><td>XYZ Trading</td><td>Updated Profile</td><td>July 6, 2026</td></tr>
                        <tr><td>John Smith</td><td>Redeemed Loyalty Points</td><td>July 5, 2026</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">CRM Summary</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">New Customers <strong class="float-end">15</strong></li>
                    <li class="list-group-item">Today's Follow-Ups <strong class="float-end">7</strong></li>
                    <li class="list-group-item">Support Requests <strong class="float-end">4</strong></li>
                    <li class="list-group-item">VIP Customers <strong class="float-end">82</strong></li>
                </ul>
            </div>
        </div>
    </div>
@endsection
