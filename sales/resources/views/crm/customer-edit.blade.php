@extends('layouts.app')

@section('title', 'Edit Customer')
@section('page-title', 'Edit Customer')

@section('content')

<style>
.crm-card {
    background:#fff;
    border:1px solid #e9ecef;
    border-radius:12px;
}
.btn-main {
    background:#5347CE;
    color:white;
    border-radius:8px;
}
.btn-main:hover {
    background:#463bb5;
    color:white;
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-semibold mb-1">Edit Customer</h4>
        <p class="text-muted mb-0">Update customer information for {{ $customer->display_name }}.</p>
    </div>
    <a href="{{ route('crm.directory') }}" class="btn btn-outline-secondary">Back to Directory</a>
</div>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card crm-card p-4">
    <form method="POST" action="{{ route('crm.directory.update', $customer) }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">First Name <span class="text-danger">*</span></label>
                <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $customer->first_name) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $customer->last_name) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_no" class="form-control" value="{{ old('contact_no', $customer->contact_no) }}">
            </div>

            <div class="col-12">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="3">{{ old('address', $customer->address) }}</textarea>
            </div>

            <div class="col-12">
                <label class="form-label">Preferences</label>
                <textarea name="preferences" class="form-control" rows="3">{{ old('preferences', $customer->preferences) }}</textarea>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-main px-4">Update Customer</button>
            <a href="{{ route('crm.directory') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>

@endsection
