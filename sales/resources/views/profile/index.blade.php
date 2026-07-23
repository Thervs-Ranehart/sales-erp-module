@extends('layouts.app')

@section('content')
    @php
        $title = 'Profile';
        $subtitle = 'View your account details and access information';
        $initials = collect([$employee->first_name, $employee->last_name])
            ->filter()
            ->map(fn ($name) => strtoupper(mb_substr($name, 0, 1)))
            ->join('');
        $initials = $initials ?: strtoupper(mb_substr($employee->username, 0, 2));
        $isActive = strtolower((string) $employee->employee_status) === 'active';
    @endphp

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    <div class="profile-hero mb-4">
        <div class="profile-avatar" aria-hidden="true">{{ $initials }}</div>
        <div class="flex-grow-1">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                <h4 class="fw-bold mb-0">{{ $employee->full_name ?: $employee->username }}</h4>
                <span class="profile-status {{ $isActive ? 'is-active' : 'is-inactive' }}">
                    <span class="profile-status-dot"></span>
                    {{ $employee->employee_status ?: 'Not specified' }}
                </span>
            </div>
            <p class="mb-1 profile-role">{{ $employee->role ?: 'Role not assigned' }}</p>
            <p class="mb-0 profile-username"><i class="bi bi-at"></i>{{ $employee->username }}</p>
        </div>
        <div class="profile-id">
            <span>Employee ID</span>
            <strong>#{{ str_pad((string) $employee->employee_id, 4, '0', STR_PAD_LEFT) }}</strong>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <section class="card profile-card h-100" aria-labelledby="account-information-heading">
                <div class="profile-card-header">
                    <span class="profile-section-icon"><i class="bi bi-person-vcard"></i></span>
                    <div>
                        <h5 id="account-information-heading" class="fw-bold mb-1">Account Information</h5>
                        <p class="text-muted small mb-0">Your employee and access details.</p>
                    </div>
                </div>

                <div class="profile-details">
                    <div class="profile-detail">
                        <span class="profile-detail-icon"><i class="bi bi-person"></i></span>
                        <div>
                            <small>Full name</small>
                            <strong>{{ $employee->full_name ?: 'Not provided' }}</strong>
                        </div>
                    </div>
                    <div class="profile-detail">
                        <span class="profile-detail-icon"><i class="bi bi-at"></i></span>
                        <div>
                            <small>Username</small>
                            <strong>{{ $employee->username }}</strong>
                        </div>
                    </div>
                    <div class="profile-detail">
                        <span class="profile-detail-icon"><i class="bi bi-briefcase"></i></span>
                        <div>
                            <small>Role</small>
                            <strong>{{ $employee->role ?: 'Not assigned' }}</strong>
                        </div>
                    </div>
                    <div class="profile-detail">
                        <span class="profile-detail-icon"><i class="bi bi-building"></i></span>
                        <div>
                            <small>Department</small>
                            <strong>{{ $employee->department ?: 'Not assigned' }}</strong>
                        </div>
                    </div>
                    <div class="profile-detail">
                        <span class="profile-detail-icon"><i class="bi bi-diagram-3"></i></span>
                        <div>
                            <small>Hierarchy level</small>
                            <strong>{{ $employee->hierarchy_level ?: 'Not specified' }}</strong>
                        </div>
                    </div>
                    <div class="profile-detail">
                        <span class="profile-detail-icon"><i class="bi bi-shield-check"></i></span>
                        <div>
                            <small>Account status</small>
                            <strong>{{ $employee->employee_status ?: 'Not specified' }}</strong>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="col-xl-4">
            <section class="card profile-card h-100" aria-labelledby="account-activity-heading">
                <div class="profile-card-header">
                    <span class="profile-section-icon"><i class="bi bi-clock-history"></i></span>
                    <div>
                        <h5 id="account-activity-heading" class="fw-bold mb-1">Account Activity</h5>
                        <p class="text-muted small mb-0">Basic account and security status.</p>
                    </div>
                </div>

                <div class="profile-activity-list">
                    <div>
                        <span>Member since</span>
                        <strong>{{ $employee->created_at?->format('M d, Y') ?? 'Not available' }}</strong>
                    </div>
                    <div>
                        <span>Profile updated</span>
                        <strong>{{ $employee->updated_at?->diffForHumans() ?? 'Not available' }}</strong>
                    </div>
                    <div>
                        <span>Failed login attempts</span>
                        <strong>{{ $employee->failed_login_attempts ?? 0 }}</strong>
                    </div>
                    <div>
                        <span>Security lock</span>
                        <strong class="{{ $employee->isLocked() ? 'text-danger' : 'text-success' }}">
                            {{ $employee->isLocked() ? 'Temporarily locked' : 'No active lock' }}
                        </strong>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <style>
        .profile-hero {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 28px;
            overflow: hidden;
            position: relative;
            border-radius: 22px;
            color: #fff;
            background: linear-gradient(125deg, #4338ca 0%, #5347ce 45%, #887cfd 100%);
            box-shadow: 0 18px 40px rgba(83, 71, 206, .22);
        }

        .profile-hero::after {
            content: '';
            position: absolute;
            width: 210px;
            height: 210px;
            top: -125px;
            right: 90px;
            border: 35px solid rgba(255, 255, 255, .08);
            border-radius: 50%;
        }

        .profile-avatar {
            width: 78px;
            height: 78px;
            flex: 0 0 78px;
            display: grid;
            place-items: center;
            border: 3px solid rgba(255, 255, 255, .7);
            border-radius: 22px;
            background: rgba(255, 255, 255, .18);
            font-size: 25px;
            font-weight: 800;
            letter-spacing: .04em;
            box-shadow: 0 12px 30px rgba(30, 27, 75, .2);
        }

        .profile-role,
        .profile-username {
            color: rgba(255, 255, 255, .82);
            font-size: 13px;
        }

        .profile-username {
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .profile-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 9px;
            border: 1px solid rgba(255, 255, 255, .24);
            border-radius: 999px;
            background: rgba(15, 23, 42, .15);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .profile-status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #fca5a5;
        }

        .profile-status.is-active .profile-status-dot {
            background: #5eead4;
            box-shadow: 0 0 0 4px rgba(94, 234, 212, .16);
        }

        .profile-id {
            min-width: 130px;
            position: relative;
            z-index: 1;
            padding: 12px 16px;
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 14px;
            background: rgba(15, 23, 42, .12);
            text-align: right;
        }

        .profile-id span {
            display: block;
            color: rgba(255, 255, 255, .72);
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .profile-id strong {
            font-size: 18px;
        }

        .profile-card {
            padding: 0;
            overflow: hidden;
            border: 1px solid #edf0f5;
            box-shadow: 0 10px 28px rgba(15, 23, 42, .06);
        }

        .profile-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 22px 24px;
            border-bottom: 1px solid #eef2f7;
        }

        .profile-section-icon,
        .profile-detail-icon {
            display: grid;
            place-items: center;
            color: #5347ce;
            background: #f0efff;
        }

        .profile-section-icon {
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            border-radius: 13px;
            font-size: 18px;
        }

        .profile-details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0;
            padding: 6px 24px 18px;
        }

        .profile-detail {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
            padding: 18px 10px;
            border-bottom: 1px solid #f1f5f9;
        }

        .profile-detail:nth-last-child(-n+2) {
            border-bottom: 0;
        }

        .profile-detail-icon {
            width: 36px;
            height: 36px;
            flex: 0 0 36px;
            border-radius: 11px;
        }

        .profile-detail small,
        .profile-detail strong {
            display: block;
        }

        .profile-detail small,
        .profile-activity-list span {
            margin-bottom: 3px;
            color: #64748b;
            font-size: 11px;
        }

        .profile-detail strong {
            overflow: hidden;
            color: #1e293b;
            font-size: 13px;
            text-overflow: ellipsis;
        }

        .profile-activity-list {
            padding: 10px 24px 18px;
        }

        .profile-activity-list > div {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .profile-activity-list > div:last-child {
            border-bottom: 0;
        }

        .profile-activity-list span,
        .profile-activity-list strong {
            margin: 0;
            font-size: 12px;
        }

        .profile-activity-list strong {
            color: #334155;
            text-align: right;
        }

        @media (max-width: 767.98px) {
            .profile-hero {
                align-items: flex-start;
                flex-wrap: wrap;
                padding: 22px;
            }

            .profile-avatar {
                width: 64px;
                height: 64px;
                flex-basis: 64px;
                border-radius: 18px;
                font-size: 21px;
            }

            .profile-id {
                width: 100%;
                text-align: left;
            }

            .profile-details {
                grid-template-columns: 1fr;
            }

            .profile-detail:nth-last-child(2) {
                border-bottom: 1px solid #f1f5f9;
            }
        }
    </style>
@endsection
