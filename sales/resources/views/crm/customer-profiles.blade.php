@extends('layouts.app')

@section('title', 'Customer Profiles')
@section('page-title', 'Customer Profiles')

@section('content')

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<style>

.profile-card {
    background:#fff;
    border:1px solid #e9ecef;
    border-radius:14px;
}


.avatar {

width:70px;
height:70px;
border-radius:50%;
background:#5347CE;
color:#fff;
display:flex;
align-items:center;
justify-content:center;
font-size:22px;
font-weight:600;

}


.stat-mini {

background:#f8f9fa;
border-radius:10px;
padding:18px;

}


.stat-mini small {

color:#6c757d;
font-size:13px;

}


.stat-mini h5 {

font-weight:600;
margin-top:5px;

}



.info-box {

background:#f8f9fa;
border-radius:10px;
padding:15px;

}


.label {

font-size:13px;
color:#6c757d;

}


.value {

font-weight:600;

}



.section-title {

font-weight:600;
font-size:16px;
margin-bottom:15px;

}



.search-box {

position:relative;

}


.search-box input {

height:45px;
padding-left:40px;
border-radius:10px;

}


.search-icon {

position:absolute;
left:15px;
top:12px;
color:#6c757d;

}



.table th {

background:#f8f9fa;
font-size:13px;

}


.table td {

padding:14px;

}



.tag {

    background:#f1f0ff;
    color:#5347CE;
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    cursor:pointer;
    transition:0.2s;
    display:inline-flex;
    align-items:center;
    gap:5px;

}


.tag:hover {

    background:#5347CE;
    color:white;

}


.tag.active {

    background:#5347CE;
    color:white;

}


.tag i {

    display:none;

}


.tag.active i {

    display:inline-block;

}

.customer-card{
    position:relative;
    cursor:pointer;
    transition:.2s ease;
    border:1px solid #dee2e6;
}

.customer-card:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(83,71,206,.15);
}

.customer-card.selected{
    border:2px solid #5347CE !important;
    background:#f5f3ff;
    box-shadow:0 12px 24px rgba(83,71,206,.20);
}

.customer-card.selected::after{
    content:"✓";
    position:absolute;
    top:12px;
    right:12px;
    width:24px;
    height:24px;
    border-radius:50%;
    background:#5347CE;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:13px;
    font-weight:bold;
}

.search-input{
    height:45px;
    border:2px solid #5347CE;
    border-radius:10px;
    padding-left:40px;
}

.search-input:focus{
    border-color:#5347CE;
    box-shadow:0 0 0 .2rem rgba(83,71,206,.15);
}

.btn-search{
    height:45px;
    background:#5347CE;
    color:#fff;
    border:none;
    border-radius:10px;
    font-weight:600;
    transition:.2s;
}

.btn-search:hover{
    background:#463bb5;
    color:#fff;
}

</style>




{{-- Header --}}

<div class="d-flex justify-content-between align-items-center mb-4">


<div>

<h4 class="fw-semibold mb-1">
Customer Profiles
</h4>


<p class="text-muted mb-0">
View complete customer information and relationship history.
</p>


</div>



@if(!empty($customer))
    @if(!empty($editMode))
        <a href="{{ route('crm.profiles', ['customer_id' => $customer['id']]) }}" class="btn btn-outline-secondary">
            Cancel Edit
        </a>
    @else
        <a href="{{ route('crm.profiles.edit', ['customer' => $customer['id']]) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil"></i>
            Edit Profile
        </a>
    @endif
@endif



</div>




{{-- Search Customer --}}

<div class="card profile-card p-4 mb-4">


<h6 class="fw-semibold mb-3">
Select Customer
</h6>



<form method="GET" action="{{ route('crm.profiles') }}" class="row g-2 mb-3">

    <div class="col-md-10">
        <div class="search-box">
            <i class="bi bi-search search-icon"></i>

            <input
                type="text"
                name="search"
                value="{{ $search ?? '' }}"
                class="form-control search-input"
                placeholder="Search customer name, ID, or email...">
        </div>
    </div>

    <div class="col-md-2 d-grid">
        <button type="submit" class="btn btn-search">
            <i class="bi bi-search"></i>
            Search
        </button>
    </div>

</form>




<div class="row g-3">

@foreach($customers as $item)

<div class="col-md-4">


<a href="{{ route('crm.profiles', [
    'customer_id' => $item->customer_id,
    'search' => request('search')
]) }}" style="text-decoration:none; color:inherit;">

<div class="customer-card p-3 border rounded {{ !empty($customer['id']) && $customer['id'] == $item->customer_id ? 'selected' : '' }}">


<div class="d-flex gap-3 align-items-center">


<div class="avatar">
{{ strtoupper(substr($item->first_name ?? '', 0, 1) . substr($item->last_name ?? '', 0, 1)) }}
</div>


<div>

<h6 class="mb-1">
{{ $item->display_name }}
</h6>


<small class="text-muted">
ID: {{ $item->customer_id }}
</small>


<br>


<small class="text-muted">
{{ $item->email }}
</small>


</div>


</div>

</div>

</a>

</div>


@endforeach


</div>

<div class="mt-3">
    {{ $customers->links() }}
</div>

</div>


{{-- Customer Overview --}}

<div class="card profile-card p-4 mb-4">


@if(empty($customer))

<div class="d-flex align-items-center gap-3">

<div class="avatar">
?
</div>



<div>

<h4 class="mb-1">
No customer selected
</h4>

<span class="badge bg-secondary">
—
</span>


<p class="text-muted mb-0 mt-2">
Customer ID: —
</p>


</div>



</div>

@else

<div class="d-flex align-items-center gap-3">


<div class="avatar">
{{ $customer['initials'] ?? '' }}
</div>



<div>

<h4 class="mb-1">
{{ $customer['name'] ?? '' }}
</h4>

<span class="badge bg-success">
{{ $customer['status'] ?? '' }}
</span>


<p class="text-muted mb-0 mt-2">
Customer ID: {{ $customer['id'] ?? '' }}
</p>


</div>



</div>







<div class="row g-3 mt-3">


<div class="col-md-3">

<div class="stat-mini">

<small>
Total Orders
</small>

<h5>
{{ $customer['orders'] ?? 0 }}
</h5>

</div>

</div>





<div class="col-md-3">

<div class="stat-mini">

<small>
Total Spending
</small>

<h5>
₱{{ $customer['spending'] ?? 0 }}
</h5>

</div>

</div>





<div class="col-md-3">

<div class="stat-mini">

<small>
Loyalty Points
</small>

<h5>
{{ number_format($customer['loyalty'] ?? 0) }}
</h5>

</div>

</div>





<div class="col-md-3">

<div class="stat-mini">

<small>
Customer Since
</small>

<h5>
{{ $customer['since'] ?? '' }}
</h5>

</div>

</div>



</div>

@endif


</div>




@if(!empty($customer) && !empty($editMode))

<div class="card profile-card p-4 mb-4">
    <div class="section-title">Edit Customer Profile</div>

    <form method="POST" action="{{ route('crm.profiles.update', ['customer' => $customer['id']]) }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $customer['first_name'] ?? '') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $customer['last_name'] ?? '') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $customer['email'] ?? '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="text" name="contact_no" class="form-control" value="{{ old('contact_no', $customer['phone'] ?? '') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="2">{{ old('address', $customer['address'] ?? '') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Preferences</label>
                <textarea name="preferences" class="form-control" rows="2">{{ old('preferences', $customer['preferences'] ?? '') }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select">
                    <option value="">Select gender</option>
                    <option value="Male" {{ old('gender', $customer['gender'] ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', $customer['gender'] ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('gender', $customer['gender'] ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Birth Date</label>
                <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $customer['birthdate'] ?? '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Preferred Contact</label>
                <select name="preferred_contact" class="form-select">
                    <option value="">Select method</option>
                    <option value="Email" {{ old('preferred_contact', $customer['preferred_contact'] ?? '') === 'Email' ? 'selected' : '' }}>Email</option>
                    <option value="Phone" {{ old('preferred_contact', $customer['preferred_contact'] ?? '') === 'Phone' ? 'selected' : '' }}>Phone</option>
                    <option value="SMS" {{ old('preferred_contact', $customer['preferred_contact'] ?? '') === 'SMS' ? 'selected' : '' }}>SMS</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Preferred Product Category</label>
                <input type="text" name="preferred_product_category" class="form-control" value="{{ old('preferred_product_category', $customer['preferred_product'] ?? '') }}">
            </div>
            <div class="col-md-6">
                <div class="form-check mt-4">
                    <input class="form-check-input" type="checkbox" name="marketing_consent" value="1" id="marketing_consent" {{ old('marketing_consent', $customer['marketing_consent'] ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="marketing_consent">Marketing consent approved</label>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">Save Profile</button>
            <a href="{{ route('crm.profiles', ['customer_id' => $customer['id']]) }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>

@elseif(!empty($customer))

<div class="row g-4">





{{-- Personal Information --}}

<div class="col-md-6">


<div class="card profile-card p-4">


<div class="section-title">
Personal Information
</div>



<div class="row g-3">


<div class="col-md-6">

<div class="info-box">

<div class="label">
Email
</div>

<div class="value">
{{ $customer['email'] ?? '—' }}
</div>

</div>

</div>



<div class="col-md-6">

<div class="info-box">

<div class="label">
Phone
</div>

<div class="value">
{{ $customer['phone'] ?? '—' }}
</div>

</div>

</div>




<div class="col-md-6">

<div class="info-box">

<div class="label">
Gender
</div>

<div class="value">
{{ $customer['gender'] ?? '—' }}
</div>

</div>

</div>




<div class="col-md-6">

<div class="info-box">

<div class="label">
Birth Date
</div>

<div class="value">
{{ $customer['birthdate'] ? \Carbon\Carbon::parse($customer['birthdate'])->toFormattedDateString() : '—' }}
</div>

</div>

</div>



</div>


</div>


</div>









{{-- Preferences --}}

<div class="col-md-6">


<div class="card profile-card p-4">


<div class="section-title">
Customer Preferences
</div>




<div class="row g-3">


<div class="col-md-6">

<div class="info-box">

<div class="label">
Preferred Contact
</div>

<div class="value">
{{ $customer['preferred_contact'] ?? '—' }}
</div>

</div>

</div>





<div class="col-md-6">

<div class="info-box">

<div class="label">
Preferred Product
</div>

<div class="value">
{{ $customer['preferred_product'] ?? '—' }}
</div>

</div>

</div>





<div class="col-md-6">

<div class="info-box">

<div class="label">
Customer Type
</div>

<div class="value">
{{ $customer['type'] ?? '—' }}
</div>

</div>

</div>





<div class="col-md-6">

<div class="info-box">

<div class="label">
Marketing Consent
</div>

<div class="value text-success">
{{ $customer['marketing'] ?? 'Not Approved' }}
</div>

</div>

</div>



</div>



</div>


</div>





</div>









{{-- Recent Activity --}}

<div class="row g-4 mt-1">



<div class="col-md-6">


<div class="card profile-card p-4">


<div class="section-title">
Recent Purchases
</div>



<table class="table">

@forelse(($purchases ?? []) as $purchase)

<tr>

<td>
{{ $purchase['product'] ?? '' }}
</td>


<td>
₱{{ number_format($purchase['price'] ?? 0, 2) }}
</td>


<td>
{{ $purchase['date'] ?? '' }}
</td>

</tr>

@empty

<tr>

<td colspan="3" class="text-muted">No purchases found.</td>

</tr>

@endforelse


</table>


</div>


</div>







<div class="col-md-6">




</div>



</div>

@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

document.querySelectorAll('.tag').forEach(tag => {

    tag.addEventListener('click', function(){

        this.classList.toggle('active');

    });

});

</script>




@endsection
