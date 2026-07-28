@extends('layouts.app')

@section('content')
    @include('components.page-header', [
        'title' => 'Notifications',
        'subtitle' => 'Review notifications assigned to you',
    ])

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card p-3 h-100">
                <div class="text-muted small fw-semibold">Unread</div>
                <div class="display-6 fw-bold">{{ $unreadCount }}</div>
                <div class="mt-2"><span class="badge bg-primary">Needs attention</span></div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-3 h-100">
                <div class="text-muted small fw-semibold">Read</div>
                <div class="display-6 fw-bold">{{ $readCount }}</div>
                <div class="mt-2"><span class="badge bg-success">Reviewed</span></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card p-3 h-100">
                <div class="row g-3 align-items-end">
                    <div class="col-md-7">
                        <form method="GET" action="{{ route('notifications.index') }}">
                            <label class="form-label small text-muted">Search</label>
                            <div class="input-group input-group-sm">
                                <input name="search" value="{{ $search }}" class="form-control" placeholder="Search notifications..." aria-label="Search notifications">
                                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-5 text-md-end">
                        <form method="POST" action="{{ route('notifications.read-all') }}">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm btn-primary" type="submit" @disabled($unreadCount === 0)>
                                <i class="bi bi-check2-all me-1"></i> Mark all read
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold mb-0">Notification List</h5>
            <div class="text-muted small">{{ $notifications->count() }} notification{{ $notifications->count() === 1 ? '' : 's' }}</div>
        </div>

        <div class="list-group">
            @forelse ($notifications as $notification)
                <div class="list-group-item d-flex gap-3 align-items-start {{ ! $notification->is_read ? 'list-group-item-primary' : '' }}">
                    <span class="badge bg-secondary mt-1">{{ $notification->notification_type ?: 'System' }}</span>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between gap-3">
                            <div>
                                <div class="fw-semibold">{{ $notification->title ?: 'Notification' }}</div>
                                <div class="text-muted small">{{ $notification->message }}</div>
                            </div>
                            <div class="text-muted small text-nowrap">{{ $notification->created_at?->diffForHumans() }}</div>
                        </div>
                        <div class="mt-2 d-flex align-items-center justify-content-between">
                            <span class="badge {{ $notification->is_read ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $notification->is_read ? 'Read' : 'Unread' }}
                            </span>
                            @unless ($notification->is_read)
                                <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-outline-primary" type="submit">Mark as read</button>
                                </form>
                            @endunless
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="bi bi-bell-slash fs-3 d-block mb-2"></i>
                    No notifications{{ $search ? ' match your search' : ' yet' }}.
                </div>
            @endforelse
        </div>
    </div>
@endsection
