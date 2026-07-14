@extends('layouts.app')

@section('content')
    @php($title = 'Dashboard')
    @php($subtitle = 'Overview of sales, CRM, and support activity')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    <div class="row g-4">
        <div class="col-md-3">
            <x-stat-card
                title="Total Revenue"
                value="₱{{ number_format($totalRevenue, 2) }}"
                description="{{ $revenueChangePercent >= 0 ? '+' : '' }}{{ $revenueChangePercent }}% this month"
                icon="currency-dollar"
                tone="primary"
            />
        </div>
        <div class="col-md-3">
            <x-stat-card
                title="Total Orders"
                value="{{ number_format($totalOrders) }}"
                description="{{ $newOrdersThisMonth }} new orders this month"
                icon="cart-check"
                tone="success"
            />
        </div>
        <div class="col-md-3">
            <x-stat-card
                title="Active Customers"
                value="{{ number_format($totalCustomers) }}"
                description="{{ $newCustomersThisMonth }} new customers this month"
                icon="people-fill"
                tone="warning"
            />
        </div>
        <div class="col-md-3">
            <x-stat-card
                title="Open Support Cases"
                value="{{ number_format($openSupportCases) }}"
                description="{{ $openSupportCases > 0 ? 'Needs immediate action' : 'All caught up' }}"
                icon="headset"
                tone="danger"
            />
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">Target Achievement</h5>
                <div class="progress" style="height: 12px;">
                    <div
                        class="progress-bar bg-success"
                        style="width: {{ $targetPercent }}%"
                    >
                        {{ $targetPercent }}%
                    </div>
                </div>
                <p class="mt-3 mb-0 text-muted">
                    Current sales performance has reached {{ $targetPercent }}% of the monthly target.
                </p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">Notifications</h5>
                @if($notifications->isEmpty())
                    <p class="text-muted mb-0">No notifications yet.</p>
                @else
                    <ul class="mb-0">
                        @foreach($notifications as $notification)
                            <li>{{ $notification->title ?? $notification->message }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">Recent Activities</h5>
                @if($recentActivities->isEmpty())
                    <p class="text-muted mb-0">No recent activity yet.</p>
                @else
                    @foreach($recentActivities as $activity)
                        <div class="{{ !$loop->last ? 'border-bottom' : '' }} py-2">
                            {{ $activity['text'] }}
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">System Summary</h5>
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td>New Orders</td>
                            <td><strong>{{ $newOrdersThisMonth }}</strong></td>
                        </tr>
                        <tr>
                            <td>New Customers</td>
                            <td><strong>{{ $newCustomersThisMonth }}</strong></td>
                        </tr>
                        <tr>
                            <td>Support Tickets</td>
                            <td><strong>{{ $openSupportCases }}</strong></td>
                        </tr>
                        <tr>
                            <td>CRM Leads</td>
                            <td><strong>{{ $crmLeads }}</strong></td>
                        </tr>
                        <tr>
                            <td>Forecast Accuracy</td>
                            <td>
                                <strong>
                                    {{ $forecastAccuracy !== null ? $forecastAccuracy . '%' : 'N/A' }}
                                </strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
