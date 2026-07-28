@extends('layouts.app')

@section('title', 'Add Customer')
@section('page-title', 'Add Customer')

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
        <h4 class="fw-semibold mb-1">Add Customer</h4>
        <p class="text-muted mb-0">Create a new customer record in the directory.</p>
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
    <form method="POST" action="{{ route('crm.directory.store') }}">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">First Name <span class="text-danger">*</span></label>
                <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_no" class="form-control" value="{{ old('contact_no') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select">
                    <option value="">Select gender</option>
                    @foreach (['Male', 'Female', 'Other', 'Prefer not to say'] as $gender)
                        <option value="{{ $gender }}" @selected(old('gender') === $gender)>{{ $gender }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Birth Date</label>
                <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}" max="{{ now()->toDateString() }}">
            </div>

            <div class="col-12">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Sales Region</label>
                <select name="region_id" class="form-select">
                    <option value="">Select region</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->region_id }}" @selected(old('region_id') == $region->region_id)>{{ $region->region_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Customer Status <span class="text-danger">*</span></label>
                <select name="customer_status" class="form-select" required>
                    <option value="Active" @selected(old('customer_status', 'Active') === 'Active')>Active</option>
                    <option value="Inactive" @selected(old('customer_status') === 'Inactive')>Inactive</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Preferred Contact Method</label>
                <select name="preferred_contact" class="form-select">
                    <option value="">No preference</option>
                    @foreach (['Email', 'Phone', 'SMS'] as $method)
                        <option value="{{ $method }}" @selected(old('preferred_contact') === $method)>{{ $method }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Preferred Product Category</label>
                <input type="text" name="preferred_product_category" class="form-control" value="{{ old('preferred_product_category') }}" placeholder="e.g. Office equipment">
            </div>

            <div class="col-12">
                <label class="form-label">Preferences</label>
                <textarea name="preferences" class="form-control" rows="3" placeholder="Product preferences, communication notes, etc.">{{ old('preferences') }}</textarea>
            </div>

            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="marketing_consent" value="1" id="marketingConsent" @checked(old('marketing_consent'))>
                    <label class="form-check-label" for="marketingConsent">Customer agrees to receive marketing communications.</label>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-main px-4">Save Customer</button>
            <a href="{{ route('crm.directory') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>

@include('components.crm-button-styles')

@endsection
