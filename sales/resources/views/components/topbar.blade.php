@props(['title' => 'Dashboard', 'subtitle' => 'Enterprise dashboard'])

<nav class="topbar px-4 py-3 d-flex justify-content-between align-items-center">
    <div class="flex-grow-1 d-flex justify-content-center">
        <form action="{{ route('support.tickets') }}" method="GET" class="w-100" style="max-width: 520px;">
            <div class="input-group">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search tickets, claims, contracts..."
                    aria-label="Search"
                    value="{{ request('search') ?? '' }}"
                />
                <button
                    class="btn"
                    type="submit"
                    style="background: #5347CE; color: #fff; border: 1px solid rgba(255,255,255,.25);"
                    aria-label="Search"
                    title="Search"
                >
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>

    @php
        $notificationsUnread = 0;
        try {
            $employee = auth()->user()->employee ?? null;
            if ($employee && method_exists($employee, 'notifications')) {
                $notificationsUnread = (int) $employee->notifications()->where('is_read', false)->count();
            }
            if (!$notificationsUnread && class_exists(\App\Models\Notification::class)) {
                $notificationsUnread = (int) \App\Models\Notification::query()
                    ->where('employee_id', auth()->user()->employee_id ?? 0)
                    ->where('is_read', false)
                    ->count();
            }
        } catch (\Throwable $e) {
            $notificationsUnread = 0;
        }
    @endphp

    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('notifications.index') }}" class="text-decoration-none text-dark" title="See notifications">
            <span class="position-relative d-inline-flex align-items-center">
                <i class="bi bi-bell fs-4"></i>
                @if($notificationsUnread > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:10px;">{{ $notificationsUnread }}</span>
                @endif
            </span>
        </a>

        {{-- Messages not implemented in existing routes; disable gracefully. --}}
        <span class="text-muted" title="Messaging not available">
            <i class="bi bi-envelope fs-4" aria-disabled="true"></i>
        </span>

        @php
            $helpUrl = null;
            if (\Illuminate\Support\Facades\Route::has('help.index')) {
                $helpUrl = route('help.index');
            }
        @endphp
        @if($helpUrl)
            <a href="{{ $helpUrl }}" class="text-decoration-none text-dark" title="Need help?">
                <i class="bi bi-question-circle fs-4"></i>
            </a>
        @else
            <span class="text-muted" title="Help is not available">
                <i class="bi bi-question-circle fs-4" aria-disabled="true"></i>
            </span>
        @endif

        @php
            $employeeName = auth()->check() && isset(auth()->user()->employee)
                ? auth()->user()->employee->getFullNameAttribute()
                : (auth()->user()->name ?? '');

            $profileUrl = route('profile.index');
            $logoutUrl = route('logout');
        @endphp
        <div class="dropdown">
            <a class="d-flex align-items-center text-decoration-none text-dark" href="#" data-bs-toggle="dropdown" aria-expanded="false" title="Check your account">
                <i class="bi bi-person-circle fs-3"></i>
                @if(!empty($employeeName))
                    <span class="ms-2 d-none d-md-inline" style="font-weight:600;">{{ $employeeName }}</span>
                @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ $profileUrl }}">Profile</a>
                </li>
                <li>
                    <form method="POST" action="{{ $logoutUrl }}">
                        @csrf
                        <button type="submit" class="dropdown-item" style="width:100%; text-align:left;">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
