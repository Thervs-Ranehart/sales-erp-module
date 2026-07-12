@extends('layouts.app')

@section('content')
    @php($title = 'Profile')
    @php($subtitle = 'Manage your account details and preferences')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    <div class="card p-4">
        <h5 class="fw-bold mb-3">Account Information</h5>
        <p class="text-muted mb-0">This profile section can later be expanded with account settings, avatar upload, and security preferences.</p>
    </div>
@endsection
