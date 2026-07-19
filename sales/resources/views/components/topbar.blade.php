@props(['title' => 'Dashboard', 'subtitle' => 'Enterprise dashboard'])

@php
    $notificationsUnread = 0;
    try {
        $employee = auth()->user()->employee ?? null;
        if ($employee && method_exists($employee, 'notifications')) {
            $notificationsUnread = (int) $employee->notifications()->where('is_read', false)->count();
        }
        if (!$notificationsUnread && class_exists(\App\Models\Notification::class)) {
            $notificationsUnread = (int) \App\Models\Notification::query()->where('employee_id', auth()->user()->employee_id ?? 0)->where('is_read', false)->count();
        }
    } catch (\Throwable $exception) {
        $notificationsUnread = 0;
    }

    $employeeName = auth()->check() && isset(auth()->user()->employee)
        ? auth()->user()->employee->getFullNameAttribute()
        : (auth()->user()->name ?? 'Account');

    $searchItems = [
        ['label' => 'Main Dashboard', 'description' => 'Company overview', 'route' => 'dashboard', 'icon' => 'speedometer2'],
        ['label' => 'Sales Orders', 'description' => 'Orders and sales workflow', 'route' => 'sales.index', 'icon' => 'cart-check'],
        ['label' => 'Customer Directory', 'description' => 'Customer records', 'route' => 'crm.directory', 'icon' => 'people'],
        ['label' => 'Support Tickets', 'description' => 'Cases and customer concerns', 'route' => 'support.tickets', 'icon' => 'ticket'],
        ['label' => 'Sales Performance Dashboard', 'description' => 'Reports and forecasting overview', 'route' => 'forecasting.index', 'icon' => 'graph-up-arrow'],
        ['label' => 'Sales Reports', 'description' => 'Revenue analysis', 'route' => 'forecasting.reports', 'icon' => 'bar-chart-line'],
        ['label' => 'Target vs. Actual', 'description' => 'Performance comparison', 'route' => 'forecasting.performance', 'icon' => 'clipboard-data'],
        ['label' => 'Forecasting', 'description' => 'Future revenue outlook', 'route' => 'forecasting.forecast', 'icon' => 'graph-up'],
        ['label' => 'Recommendations', 'description' => 'Suggested business actions', 'route' => 'forecasting.recommendations', 'icon' => 'lightbulb'],
    ];
@endphp

<nav class="topbar topbar-shell px-3 px-md-4 py-2" aria-label="Application toolbar">
    <div class="d-flex align-items-center gap-3 w-100">
        <div class="topbar-search-wrap flex-grow-1 position-relative">
            <form id="global-search-form" class="topbar-search" role="search" autocomplete="off">
                <input id="global-search-input" type="search" class="form-control" placeholder="Search pages and modules..." aria-label="Search pages and modules" aria-controls="global-search-results" aria-expanded="false">
                <button class="topbar-search-button" type="submit" aria-label="Search" title="Search"><i class="bi bi-search" aria-hidden="true"></i></button>
            </form>
            <div id="global-search-results" class="topbar-search-results shadow-lg" role="listbox" hidden>
                @foreach($searchItems as $item)
                    @if(Route::has($item['route']))
                        <a href="{{ route($item['route']) }}" class="topbar-search-result" data-search-text="{{ strtolower($item['label'].' '.$item['description']) }}" role="option"><span class="topbar-result-icon"><i class="bi bi-{{ $item['icon'] }}"></i></span><span><strong>{{ $item['label'] }}</strong><small>{{ $item['description'] }}</small></span><i class="bi bi-arrow-up-right ms-auto text-muted"></i></a>
                    @endif
                @endforeach
                <div id="global-search-empty" class="p-4 text-center text-muted small" hidden>No matching page found.</div>
            </div>
        </div>

        <div class="topbar-actions d-flex align-items-center gap-1 gap-md-2">
            <a href="{{ route('notifications.index') }}" class="topbar-icon-button" title="Notifications" aria-label="Notifications">
                <i class="bi bi-bell"></i>
                @if($notificationsUnread > 0)<span class="topbar-notification-badge">{{ min($notificationsUnread, 99) }}</span>@endif
            </a>

            <div class="dropdown">
                <button class="topbar-icon-button" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Messages" aria-label="Messages"><i class="bi bi-chat-dots"></i></button>
                <div class="dropdown-menu dropdown-menu-end topbar-panel p-0">
                    <div class="p-3 border-bottom"><strong>Messages</strong><div class="small text-muted">Communication center</div></div>
                    <div class="p-4 text-center"><span class="topbar-empty-icon"><i class="bi bi-chat-square-text"></i></span><p class="small text-muted mb-0 mt-2">Messaging is not connected yet.</p></div>
                </div>
            </div>

            <div class="dropdown">
                <button class="topbar-icon-button" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Help" aria-label="Help and shortcuts"><i class="bi bi-question-circle"></i></button>
                <div class="dropdown-menu dropdown-menu-end topbar-panel p-2">
                    <div class="px-2 py-2"><strong>Help & shortcuts</strong></div>
                    <a class="dropdown-item rounded-3" href="{{ route('forecasting.index') }}"><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Performance dashboard</a>
                    <a class="dropdown-item rounded-3" href="{{ route('support.tickets') }}"><i class="bi bi-headset me-2 text-primary"></i>Support tickets</a>
                    <button class="dropdown-item rounded-3" type="button" id="topbar-search-help"><i class="bi bi-search me-2 text-primary"></i>Search navigation</button>
                </div>
            </div>

            <div class="dropdown">
                <button class="topbar-profile-button" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Open account menu">
                    <span class="topbar-avatar"><i class="bi bi-person"></i></span>
                    <span class="d-none d-lg-block text-start"><strong>{{ $employeeName }}</strong><small>Account</small></span>
                    <i class="bi bi-chevron-down d-none d-lg-inline"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end topbar-panel p-2">
                    <li><a class="dropdown-item rounded-3" href="{{ route('profile.index') }}"><i class="bi bi-person-circle me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item rounded-3" href="{{ route('notifications.index') }}"><i class="bi bi-bell me-2"></i>Notifications</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item rounded-3 text-danger" href="{{ route('logout') }}"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<style>
    .topbar-shell{min-height:72px;background:rgba(255,255,255,.96);backdrop-filter:blur(14px);position:sticky;top:0;z-index:1040}.topbar-search-wrap{max-width:640px;margin-inline:auto}.topbar-search{height:44px;display:flex;align-items:center;border:1px solid #e2e8f0;border-radius:14px;background:#f8fafc;padding-left:14px;overflow:hidden;transition:border-color .2s,box-shadow .2s,background .2s}.topbar-search:focus-within{border-color:#887CFD;background:#fff;box-shadow:0 0 0 4px rgba(83,71,206,.1)}.topbar-search .form-control{height:100%;border:0;background:transparent;box-shadow:none;padding-left:0;font-size:13px}.topbar-search-button{width:46px;height:100%;flex:0 0 46px;border:0;background:#5347CE;color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:16px;transition:background .2s}.topbar-search-button:hover,.topbar-search-button:focus-visible{background:#4338ca}.topbar-search-results{position:absolute;top:calc(100% + 8px);left:0;right:0;background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;z-index:1060;max-height:390px;overflow-y:auto}.topbar-search-result{display:flex;align-items:center;gap:12px;padding:11px 14px;text-decoration:none;color:#1e293b;border-bottom:1px solid #f1f5f9}.topbar-search-result:hover,.topbar-search-result:focus{background:#f8f7ff;color:#4338ca}.topbar-search-result small,.topbar-profile-button small{display:block;color:#64748b;font-size:10px}.topbar-result-icon,.topbar-empty-icon{width:34px;height:34px;border-radius:10px;background:rgba(83,71,206,.1);color:#5347CE;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0}.topbar-icon-button{width:40px;height:40px;border:1px solid transparent;border-radius:12px;background:transparent;color:#475569;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;position:relative;font-size:18px;transition:.2s}.topbar-icon-button:hover,.topbar-icon-button:focus-visible{background:#f1f0ff;border-color:#dedbff;color:#5347CE;transform:translateY(-1px)}.topbar-notification-badge{position:absolute;top:3px;right:2px;min-width:16px;height:16px;padding:0 4px;border-radius:8px;background:#ef4444;color:#fff;font-size:9px;font-weight:700;display:flex;align-items:center;justify-content:center;border:2px solid #fff}.topbar-profile-button{border:0;background:transparent;border-radius:14px;padding:4px 6px;display:flex;align-items:center;gap:9px;color:#1e293b}.topbar-profile-button:hover{background:#f8fafc}.topbar-avatar{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,#5347CE,#887CFD);color:#fff;display:flex;align-items:center;justify-content:center;font-size:19px}.topbar-profile-button strong{display:block;font-size:12px;max-width:130px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.topbar-panel{width:280px;border:1px solid #e2e8f0;border-radius:14px;box-shadow:0 18px 40px rgba(15,23,42,.14)}@media(max-width:767.98px){.topbar-shell{padding-inline:10px!important}.topbar-actions{flex-shrink:0}.topbar-icon-button{width:36px;height:36px}.topbar-actions>div:nth-of-type(1){display:none}}
</style>

<script src="{{ asset('js/topbar.js') }}"></script>
