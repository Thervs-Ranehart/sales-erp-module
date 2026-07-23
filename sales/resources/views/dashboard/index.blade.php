@extends('layouts.app')

@section('content')
    @php
        $title = 'Dashboard';
        $subtitle = 'Sales, customer, forecasting, and support overview';
        $employeeName = session('employee_name', 'Team');
        $hour = now()->hour;
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
        $achievementWidth = $targetAchievement === null ? 0 : min(100, max(0, $targetAchievement));
        $targetStatus = $targetAchievement === null
            ? 'No target set'
            : ($targetAchievement >= 100 ? 'Target exceeded' : ($targetAchievement >= 80 ? 'On track' : 'Needs attention'));
        $orderAchievementTone = $ordersAchievement === null
            ? 'is-neutral'
            : ($ordersAchievement >= 70 ? 'is-green' : ($ordersAchievement > 30 ? 'is-yellow' : 'is-red'));
    @endphp

    <header class="dashboard-header mb-4">
        <div>
            <span class="dashboard-eyebrow">{{ now()->format('l, F j, Y') }}</span>
            <h2 class="fw-bold mb-1">{{ $greeting }}, {{ $employeeName }}</h2>
            <p class="text-muted mb-0">Here is what is happening across the business for {{ $periodLabel }}.</p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <form method="GET" action="{{ route('dashboard') }}" class="dashboard-period-form">
                <label for="dashboard-period" class="visually-hidden">Reporting period</label>
                <i class="bi bi-calendar3"></i>
                <select id="dashboard-period" name="period" onchange="this.form.submit()">
                    <option value="this-month" @selected($period === 'this-month')>This month</option>
                    <option value="quarter" @selected($period === 'quarter')>Current quarter</option>
                    <option value="last-6-months" @selected($period === 'last-6-months')>Last 6 months</option>
                    <option value="year" @selected($period === 'year')>This year</option>
                </select>
            </form>
            <a href="{{ route('sales.create') }}" class="btn btn-primary dashboard-action">
                <i class="bi bi-plus-lg"></i> New Sales Order
            </a>
        </div>
    </header>

    <section class="dashboard-quick-actions mb-4" aria-label="Quick actions">
        <span>Quick actions</span>
        <a href="{{ route('sales.create') }}"><i class="bi bi-cart-plus"></i>Create order</a>
        <a href="{{ route('quotations.create') }}"><i class="bi bi-file-earmark-plus"></i>Create quotation</a>
        <a href="{{ route('crm.directory.create') }}"><i class="bi bi-person-plus"></i>Add customer</a>
        <a href="{{ route('support.tickets') }}"><i class="bi bi-headset"></i>Support cases</a>
    </section>

    <section class="row g-3 mb-4" aria-label="Key performance indicators">
        <div class="col-sm-6 col-xl-4 col-xxl-2">
            <a href="{{ route('sales.index') }}" class="dashboard-kpi">
                <span class="dashboard-kpi-icon is-primary"><i class="bi bi-graph-up-arrow"></i></span>
                <span class="dashboard-kpi-label">Sales Value</span>
                <strong>₱{{ number_format($salesValue, 0) }}</strong>
                <small class="{{ $salesChangePercent >= 0 ? 'text-success' : 'text-danger' }}">
                    <i class="bi bi-arrow-{{ $salesChangePercent >= 0 ? 'up' : 'down' }}-right"></i>
                    {{ abs($salesChangePercent) }}% vs previous period
                </small>
            </a>
        </div>
        <div class="col-sm-6 col-xl-4 col-xxl-2">
            <a href="{{ route('invoices.index') }}" class="dashboard-kpi">
                <span class="dashboard-kpi-icon is-success"><i class="bi bi-cash-coin"></i></span>
                <span class="dashboard-kpi-label">Paid Revenue</span>
                <strong>₱{{ number_format($paidRevenue, 0) }}</strong>
                <small class="{{ $paidRevenueChangePercent >= 0 ? 'text-success' : 'text-danger' }}">
                    <i class="bi bi-arrow-{{ $paidRevenueChangePercent >= 0 ? 'up' : 'down' }}-right"></i>
                    {{ abs($paidRevenueChangePercent) }}% vs previous period
                </small>
            </a>
        </div>
        <div class="col-sm-6 col-xl-4 col-xxl-2">
            <a href="{{ route('sales.index') }}" class="dashboard-kpi">
                <span class="dashboard-kpi-icon is-info"><i class="bi bi-cart-check"></i></span>
                <span class="dashboard-kpi-label">Sales Orders</span>
                <strong>{{ number_format($totalOrders) }}</strong>
                <small>{{ $ordersTarget > 0 ? number_format($ordersTarget).' target orders' : 'No order target set' }}</small>
            </a>
        </div>
        <div class="col-sm-6 col-xl-4 col-xxl-2">
            <a href="{{ route('forecasting.performance') }}" class="dashboard-kpi">
                <span class="dashboard-kpi-icon is-warning"><i class="bi bi-bullseye"></i></span>
                <span class="dashboard-kpi-label">Target Achievement</span>
                <strong>{{ $targetAchievement === null ? 'N/A' : number_format($targetAchievement, 1).'%' }}</strong>
                <small>{{ $targetStatus }}</small>
            </a>
        </div>
        <div class="col-sm-6 col-xl-4 col-xxl-2">
            <a href="{{ route('crm.directory') }}" class="dashboard-kpi">
                <span class="dashboard-kpi-icon is-purple"><i class="bi bi-people"></i></span>
                <span class="dashboard-kpi-label">Active Customers</span>
                <strong>{{ number_format($activeCustomers) }}</strong>
                <small>of {{ number_format($totalCustomers) }} total customers</small>
            </a>
        </div>
        <div class="col-sm-6 col-xl-4 col-xxl-2">
            <a href="{{ route('support.tickets') }}" class="dashboard-kpi">
                <span class="dashboard-kpi-icon is-danger"><i class="bi bi-headset"></i></span>
                <span class="dashboard-kpi-label">Open Support Cases</span>
                <strong>{{ number_format($openSupportCases) }}</strong>
                <small>{{ $openSupportCases > 0 ? 'Requires attention' : 'All cases resolved' }}</small>
            </a>
        </div>
    </section>

    <section class="row g-4 mb-4">
        <div class="col-xl-8">
            <x-dashboard-overview-chart
                chart-id="main-dashboard-sales-target-chart"
                kind="target"
                title="Sales Performance"
                description="Booked sales value compared with assigned revenue targets."
                :period-label="$periodLabel"
                :initial-data="$trendChart"
            />
        </div>
        <div class="col-xl-4">
            <article class="card dashboard-panel h-100">
                <div class="dashboard-panel-header">
                    <div>
                        <span class="dashboard-panel-kicker">Action center</span>
                        <h5 class="fw-bold mb-0">Requires Attention</h5>
                    </div>
                    <span class="dashboard-count-badge">{{ $attentionCount }}</span>
                </div>
                <div class="dashboard-attention-list">
                    @forelse($attentionItems->where('count', '>', 0) as $item)
                        <a href="{{ route($item['route']) }}" class="dashboard-attention-item">
                            <span class="dashboard-list-icon text-{{ $item['tone'] }} bg-{{ $item['tone'] }} bg-opacity-10">
                                <i class="bi bi-{{ $item['icon'] }}"></i>
                            </span>
                            <span class="flex-grow-1">
                                <strong>{{ $item['label'] }}</strong>
                                <small>{{ $item['description'] }}</small>
                            </span>
                            <span class="dashboard-item-count">{{ $item['count'] }}</span>
                        </a>
                    @empty
                        <div class="dashboard-empty">
                            <span><i class="bi bi-check2-circle"></i></span>
                            <strong>No items require attention</strong>
                            <small>All tracked operational queues are clear.</small>
                        </div>
                    @endforelse
                </div>
            </article>
        </div>
    </section>

    <section class="row g-4 mb-4">
        <div class="col-lg-7">
            <article class="card dashboard-panel h-100">
                <div class="dashboard-panel-header">
                    <div>
                        <span class="dashboard-panel-kicker">Current period</span>
                        <h5 class="fw-bold mb-0">Target Progress</h5>
                    </div>
                    <a href="{{ route('forecasting.performance') }}" class="dashboard-panel-link">Full report <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="p-4">
                    <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
                        <div>
                            <small class="text-muted d-block">Actual sales</small>
                            <strong class="fs-4">₱{{ number_format($salesValue, 2) }}</strong>
                        </div>
                        <div class="text-md-end">
                            <small class="text-muted d-block">Revenue target</small>
                            <strong class="fs-4">₱{{ number_format($salesTarget, 2) }}</strong>
                        </div>
                    </div>
                    <div class="dashboard-progress-track" role="progressbar" aria-valuenow="{{ round($achievementWidth) }}" aria-valuemin="0" aria-valuemax="100">
                        <span style="width: {{ $achievementWidth }}%"></span>
                    </div>
                    <div class="d-flex justify-content-between mt-2 small">
                        <span class="fw-semibold text-primary">{{ $targetAchievement === null ? 'No target configured' : number_format($targetAchievement, 1).'% achieved' }}</span>
                        <span class="text-muted">
                            {{ $salesTarget > $salesValue ? '₱'.number_format($salesTarget - $salesValue, 2).' remaining' : ($salesTarget > 0 ? 'Target reached' : 'Add a sales target') }}
                        </span>
                    </div>
                    <hr class="my-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="dashboard-mini-stat dashboard-order-achievement {{ $orderAchievementTone }}">
                                <span>Order target</span>
                                <strong>{{ $ordersTarget > 0 ? $totalOrders.' / '.$ordersTarget : 'Not set' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="dashboard-mini-stat dashboard-order-achievement {{ $orderAchievementTone }}">
                                <span>Order achievement</span>
                                <strong>{{ $ordersAchievement === null ? 'N/A' : number_format($ordersAchievement, 1).'%' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
        <div class="col-lg-5">
            <article class="card dashboard-panel dashboard-forecast h-100">
                <div class="dashboard-panel-header">
                    <div>
                        <span class="dashboard-panel-kicker">Forward outlook</span>
                        <h5 class="fw-bold mb-0">Forecast Snapshot</h5>
                    </div>
                    <a href="{{ route('forecasting.forecast') }}" class="dashboard-panel-link">Details <i class="bi bi-arrow-right"></i></a>
                </div>
                @if($forecast)
                    <div class="p-4">
                        <div class="dashboard-forecast-value">
                            <span>Predicted revenue</span>
                            <strong>₱{{ number_format((float) $forecast->predicted_revenue, 0) }}</strong>
                            <small>{{ $forecast->forecast_period_start?->format('M Y') }} – {{ $forecast->forecast_period_end?->format('M Y') }}</small>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-4">
                                <div class="dashboard-forecast-stat"><i class="bi bi-cart-check"></i><strong>{{ number_format($forecast->predicted_orders) }}</strong><span>Orders</span></div>
                            </div>
                            <div class="col-4">
                                <div class="dashboard-forecast-stat"><i class="bi bi-graph-up"></i><strong>{{ number_format((float) $forecast->predicted_growth, 1) }}%</strong><span>Growth</span></div>
                            </div>
                            <div class="col-4">
                                <div class="dashboard-forecast-stat"><i class="bi bi-shield-check"></i><strong>{{ number_format((float) $forecast->confidence_level, 0) }}%</strong><span>Confidence</span></div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="dashboard-empty flex-grow-1">
                        <span><i class="bi bi-graph-up-arrow"></i></span>
                        <strong>No forecast generated</strong>
                        <small>Open forecasting to create the first outlook.</small>
                    </div>
                @endif
            </article>
        </div>
    </section>

    <section class="row g-4 mb-4">
        <div class="col-xl-4">
            <x-top-products-horizontal-bar chart-id="dashboard-top-products" :initial-data="$topProductsChart" />
        </div>
        <div class="col-xl-4">
            <x-sales-by-representative-horizontal-bar chart-id="dashboard-representatives" :initial-data="$representativesChart" />
        </div>
        <div class="col-xl-4">
            <x-sales-by-region-horizontal-bar chart-id="dashboard-regions" :initial-data="$regionsChart" />
        </div>
    </section>

    <section class="row g-4">
        <div class="col-xl-7">
            <article class="card dashboard-panel h-100">
                <div class="dashboard-panel-header">
                    <div>
                        <span class="dashboard-panel-kicker">Across all modules</span>
                        <h5 class="fw-bold mb-0">Recent Activity</h5>
                    </div>
                </div>
                <div class="dashboard-activity-list">
                    @forelse($recentActivities as $activity)
                        <a href="{{ $activity['url'] }}" class="dashboard-activity-item">
                            <span class="dashboard-list-icon text-{{ $activity['tone'] }} bg-{{ $activity['tone'] }} bg-opacity-10">
                                <i class="bi bi-{{ $activity['icon'] }}"></i>
                            </span>
                            <span class="flex-grow-1">
                                <strong>{{ $activity['title'] }}</strong>
                                <small>{{ $activity['description'] }}</small>
                            </span>
                            <span class="dashboard-activity-meta">
                                <em>{{ $activity['status'] }}</em>
                                <small>{{ $activity['at']?->diffForHumans() ?? 'Recently' }}</small>
                            </span>
                        </a>
                    @empty
                        <div class="dashboard-empty">
                            <span><i class="bi bi-clock-history"></i></span>
                            <strong>No recent activity</strong>
                            <small>New transactions will appear here.</small>
                        </div>
                    @endforelse
                </div>
            </article>
        </div>
        <div class="col-xl-5">
            <article class="card dashboard-panel h-100">
                <div class="dashboard-panel-header">
                    <div>
                        <span class="dashboard-panel-kicker">Your account</span>
                        <h5 class="fw-bold mb-0">Notifications</h5>
                    </div>
                    @if($unreadNotifications > 0)<span class="dashboard-count-badge">{{ $unreadNotifications }} new</span>@endif
                </div>
                <div class="dashboard-notification-list">
                    @forelse($notifications as $notification)
                        <a href="{{ route('notifications.index') }}" class="dashboard-notification-item {{ ! $notification->is_read ? 'is-unread' : '' }}">
                            <span class="dashboard-notification-dot"></span>
                            <span class="flex-grow-1">
                                <strong>{{ $notification->title ?: 'Notification' }}</strong>
                                <small>{{ $notification->message }}</small>
                                <time>{{ $notification->created_at?->diffForHumans() }}</time>
                            </span>
                        </a>
                    @empty
                        <div class="dashboard-empty">
                            <span><i class="bi bi-bell"></i></span>
                            <strong>You are all caught up</strong>
                            <small>No notifications for this account.</small>
                        </div>
                    @endforelse
                </div>
                <a href="{{ route('notifications.index') }}" class="dashboard-view-all">View all notifications <i class="bi bi-arrow-right"></i></a>
            </article>
        </div>
    </section>

    <style>
        .dashboard-header{display:flex;align-items:center;justify-content:space-between;gap:24px}.dashboard-eyebrow,.dashboard-panel-kicker{display:block;margin-bottom:5px;color:#5347ce;font-size:10px;font-weight:800;letter-spacing:.09em;text-transform:uppercase}.dashboard-period-form{height:42px;display:flex;align-items:center;gap:8px;padding:0 12px;border:1px solid #e2e8f0;border-radius:12px;background:#fff;color:#64748b}.dashboard-period-form select{border:0;outline:0;background:transparent;color:#334155;font-size:12px;font-weight:600}.dashboard-action{height:42px;display:inline-flex;align-items:center;gap:7px;border:0;border-radius:12px;background:#5347ce;font-size:12px;font-weight:700}.dashboard-quick-actions{display:flex;align-items:center;gap:12px;padding:14px 16px;border:1px solid rgba(45,212,191,.48);border-radius:16px;background:linear-gradient(120deg,#7dd3fc 0%,#a7e7e0 52%,#a7f3d0 100%);overflow-x:auto;box-shadow:0 12px 28px rgba(14,116,144,.16)}.dashboard-quick-actions>span{color:#0c4a6e;font-size:10px;font-weight:800;letter-spacing:.07em;text-transform:uppercase;white-space:nowrap}.dashboard-quick-actions a{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border:1px solid rgba(14,116,144,.2);border-radius:11px;color:#0f4c5c;background:rgba(255,255,255,.72);font-size:11px;font-weight:700;text-decoration:none;white-space:nowrap;box-shadow:0 4px 12px rgba(14,116,144,.1);backdrop-filter:blur(8px);transition:transform .2s ease,box-shadow .2s ease,color .2s ease,background .2s ease,border-color .2s ease}.dashboard-quick-actions a i{font-size:14px}.dashboard-quick-actions a:nth-of-type(2),.dashboard-quick-actions a:nth-of-type(3),.dashboard-quick-actions a:nth-of-type(4){color:#0f4c5c;background:rgba(255,255,255,.72);border-color:rgba(14,116,144,.2)}.dashboard-quick-actions a:hover,.dashboard-quick-actions a:focus-visible{color:#fff;background:#0f766e;border-color:#0f766e;box-shadow:0 10px 22px rgba(15,118,110,.26);transform:translateY(-3px)}.dashboard-kpi{height:100%;min-height:168px;display:flex;flex-direction:column;padding:18px;border:1px solid #e8eaf1;border-radius:17px;background:#fff;color:inherit;text-decoration:none;box-shadow:0 8px 22px rgba(15,23,42,.045);transition:.2s}.dashboard-kpi:hover{color:inherit;border-color:#d9d5ff;box-shadow:0 13px 28px rgba(83,71,206,.11);transform:translateY(-2px)}.dashboard-kpi-icon{width:38px;height:38px;display:grid;place-items:center;margin-bottom:14px;border-radius:11px;font-size:17px}.dashboard-kpi-icon.is-primary{color:#5347ce;background:#f0efff}.dashboard-kpi-icon.is-success{color:#059669;background:#ecfdf5}.dashboard-kpi-icon.is-info{color:#0284c7;background:#f0f9ff}.dashboard-kpi-icon.is-warning{color:#d97706;background:#fffbeb}.dashboard-kpi-icon.is-purple{color:#7c3aed;background:#f5f3ff}.dashboard-kpi-icon.is-danger{color:#dc2626;background:#fef2f2}.dashboard-kpi-label{color:#64748b;font-size:11px;font-weight:600}.dashboard-kpi>strong{margin:4px 0 8px;color:#172033;font-size:22px;line-height:1.15}.dashboard-kpi>small{margin-top:auto;color:#64748b;font-size:9px}.dashboard-panel{overflow:hidden;border:1px solid #e8eaf1;box-shadow:0 8px 22px rgba(15,23,42,.045)}.dashboard-panel-header{min-height:76px;display:flex;align-items:center;justify-content:space-between;gap:16px;padding:18px 22px;border-bottom:1px solid #eef2f7}.dashboard-count-badge{padding:5px 9px;border-radius:999px;background:#f0efff;color:#5347ce;font-size:10px;font-weight:800}.dashboard-panel-link{color:#5347ce;font-size:10px;font-weight:700;text-decoration:none}.dashboard-attention-list,.dashboard-activity-list,.dashboard-notification-list{padding:6px 20px}.dashboard-attention-item,.dashboard-activity-item,.dashboard-notification-item{display:flex;align-items:center;gap:12px;padding:13px 2px;border-bottom:1px solid #f1f5f9;color:inherit;text-decoration:none}.dashboard-attention-item:last-child,.dashboard-activity-item:last-child,.dashboard-notification-item:last-child{border-bottom:0}.dashboard-attention-item:hover,.dashboard-activity-item:hover,.dashboard-notification-item:hover{color:#4338ca}.dashboard-list-icon{width:36px;height:36px;display:grid;place-items:center;flex:0 0 36px;border-radius:11px}.dashboard-attention-item strong,.dashboard-attention-item small,.dashboard-activity-item strong,.dashboard-activity-item small,.dashboard-notification-item strong,.dashboard-notification-item small,.dashboard-notification-item time{display:block}.dashboard-attention-item strong,.dashboard-activity-item strong,.dashboard-notification-item strong{font-size:11px}.dashboard-attention-item small,.dashboard-activity-item small,.dashboard-notification-item small,.dashboard-notification-item time{margin-top:2px;color:#64748b;font-size:9px}.dashboard-item-count{min-width:27px;height:27px;display:grid;place-items:center;border-radius:8px;background:#fff7ed;color:#c2410c;font-size:11px;font-weight:800}.dashboard-empty{min-height:180px;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:28px;color:#64748b;text-align:center}.dashboard-empty>span{width:46px;height:46px;display:grid;place-items:center;margin-bottom:11px;border-radius:14px;background:#ecfdf5;color:#059669;font-size:20px}.dashboard-empty strong{color:#334155;font-size:12px}.dashboard-empty small{margin-top:4px;font-size:10px}.dashboard-progress-track{height:12px;overflow:hidden;border-radius:999px;background:#eef2f7}.dashboard-progress-track span{height:100%;display:block;border-radius:999px;background:linear-gradient(90deg,#5347ce,#887cfd);transition:width .5s ease}.dashboard-mini-stat{padding:13px;border-radius:12px;background:#f8fafc}.dashboard-mini-stat span,.dashboard-mini-stat strong{display:block}.dashboard-mini-stat span{color:#64748b;font-size:10px}.dashboard-mini-stat strong{margin-top:3px;color:#1e293b;font-size:15px}.dashboard-forecast{background:linear-gradient(150deg,#fff 50%,#f4f2ff)}.dashboard-forecast-value span,.dashboard-forecast-value strong,.dashboard-forecast-value small{display:block}.dashboard-forecast-value span{color:#64748b;font-size:10px}.dashboard-forecast-value strong{margin:4px 0;color:#312e81;font-size:28px}.dashboard-forecast-value small{color:#64748b;font-size:10px}.dashboard-forecast-stat{height:92px;display:flex;flex-direction:column;align-items:center;justify-content:center;border:1px solid #e7e5ff;border-radius:13px;background:rgba(255,255,255,.75);text-align:center}.dashboard-forecast-stat i{color:#5347ce}.dashboard-forecast-stat strong{margin-top:4px;color:#1e293b;font-size:13px}.dashboard-forecast-stat span{color:#64748b;font-size:8px}.dashboard-activity-meta{flex:0 0 auto;text-align:right}.dashboard-activity-meta em{display:inline-block;padding:3px 7px;border-radius:999px;background:#f1f5f9;color:#475569;font-size:8px;font-style:normal;font-weight:700}.dashboard-notification-item{align-items:flex-start}.dashboard-notification-dot{width:7px;height:7px;flex:0 0 7px;margin-top:5px;border-radius:50%;background:#cbd5e1}.dashboard-notification-item.is-unread .dashboard-notification-dot{background:#5347ce;box-shadow:0 0 0 4px rgba(83,71,206,.1)}.dashboard-notification-item.is-unread strong{color:#312e81}.dashboard-view-all{display:flex;align-items:center;justify-content:center;gap:8px;padding:15px;border-top:1px solid #eef2f7;color:#5347ce;font-size:13px;font-weight:700;text-decoration:none;transition:background .2s ease,color .2s ease}.dashboard-view-all:hover{color:#4338ca;background:#f5f3ff}@media(max-width:991.98px){.dashboard-header{align-items:flex-start;flex-direction:column}}@media(max-width:575.98px){.dashboard-header .d-flex{width:100%}.dashboard-period-form{flex:1}.dashboard-action{padding-inline:11px}.dashboard-quick-actions{margin-inline:-4px}.dashboard-panel-header{padding:16px}.dashboard-attention-list,.dashboard-activity-list,.dashboard-notification-list{padding-inline:14px}.dashboard-activity-meta{display:none}}
        .dashboard-quick-actions {
            border-color: rgba(125, 211, 252, .65);
            background: linear-gradient(120deg, #5347CE 0%, #6366e8 46%, #7dd3fc 100%);
            box-shadow: 0 12px 28px rgba(83, 71, 206, .22);
        }

        .dashboard-quick-actions > span {
            color: #fff;
            text-shadow: 0 1px 2px rgba(30, 27, 75, .22);
        }

        .dashboard-quick-actions a,
        .dashboard-quick-actions a:nth-of-type(2),
        .dashboard-quick-actions a:nth-of-type(3),
        .dashboard-quick-actions a:nth-of-type(4) {
            color: #fff;
            border-color: rgba(255, 255, 255, .32);
            background: rgba(255, 255, 255, .16);
            box-shadow: 0 4px 12px rgba(30, 27, 75, .14);
        }

        .dashboard-quick-actions a:hover,
        .dashboard-quick-actions a:focus-visible {
            color: #4338ca;
            border-color: #fff;
            background: #fff;
            box-shadow: 0 10px 22px rgba(30, 27, 75, .24);
        }

        .dashboard-kpi {
            min-height: 128px;
            padding: 14px 15px;
        }

        .dashboard-kpi-icon {
            width: 32px;
            height: 32px;
            margin-bottom: 9px;
            border-radius: 9px;
            font-size: 14px;
        }

        .dashboard-kpi-label {
            font-size: 10px;
        }

        .dashboard-kpi > strong {
            margin: 2px 0 5px;
            font-size: 18px;
        }

        .dashboard-kpi > small {
            font-size: 8px;
            line-height: 1.25;
        }

        .dashboard-order-achievement {
            border: 1px solid transparent;
            transition: background-color .25s ease, border-color .25s ease;
        }

        .dashboard-order-achievement.is-red {
            border-color: #fecaca;
            background: #fef2f2;
        }

        .dashboard-order-achievement.is-red span,
        .dashboard-order-achievement.is-red strong {
            color: #b91c1c;
        }

        .dashboard-order-achievement.is-yellow {
            border-color: #fde68a;
            background: #fffbeb;
        }

        .dashboard-order-achievement.is-yellow span,
        .dashboard-order-achievement.is-yellow strong {
            color: #a16207;
        }

        .dashboard-order-achievement.is-green {
            border-color: #bbf7d0;
            background: #f0fdf4;
        }

        .dashboard-order-achievement.is-green span,
        .dashboard-order-achievement.is-green strong {
            color: #15803d;
        }
    </style>
@endsection
