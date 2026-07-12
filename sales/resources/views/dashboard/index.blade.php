@extends('layouts.app')

@section('content')
    @php($title = 'Dashboard')
    @php($subtitle = 'Overview of sales, CRM, and support activity')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    <div class="row g-4">
        <div class="col-md-3">
            <x-stat-card title="Total Revenue" value="?2,540,000" description="12% this month" icon="currency-dollar" tone="primary" />
        </div>
        <div class="col-md-3">
            <x-stat-card title="Total Orders" value="432" description="45 new orders" icon="cart-check" tone="success" />
        </div>
        <div class="col-md-3">
            <x-stat-card title="Active Customers" value="185" description="18 new customers" icon="people-fill" tone="warning" />
        </div>
        <div class="col-md-3">
            <x-stat-card title="Open Support Cases" value="14" description="Needs immediate action" icon="headset" tone="danger" />
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">Target Achievement</h5>
                <div class="progress" style="height: 12px;">
                    <div class="progress-bar bg-success" style="width: 82%">82%</div>
                </div>
                <p class="mt-3 mb-0 text-muted">Current sales performance has reached 82% of the monthly target.</p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">Notifications</h5>
                <ul class="mb-0">
                    <li>New Sales Order Approved</li>
                    <li>5 New CRM Leads</li>
                    <li>Support Ticket Assigned</li>
                    <li>Monthly Sales Report Ready</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">Recent Activities</h5>
                <div class="border-bottom py-2">Sales Order #SO-1023 Created</div>
                <div class="border-bottom py-2">New Customer Registered</div>
                <div class="border-bottom py-2">Support Ticket Opened</div>
                <div class="py-2">Payment Successfully Received</div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">System Summary</h5>
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr><td>New Orders</td><td><strong>38</strong></td></tr>
                        <tr><td>New Customers</td><td><strong>21</strong></td></tr>
                        <tr><td>Support Tickets</td><td><strong>7</strong></td></tr>
                        <tr><td>CRM Leads</td><td><strong>13</strong></td></tr>
                        <tr><td>Forecast Accuracy</td><td><strong>91%</strong></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
