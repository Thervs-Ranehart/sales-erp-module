@extends('layouts.app')

@section('title', 'Customer Directory')
@section('page-title', 'Customer Directory')

@section('content')

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<style>

.crm-card {
    background:#fff;
    border:1px solid #e9ecef;
    border-radius:12px;
}


.stat-card {
    background:#fff;
    border:1px solid #e9ecef;
    border-radius:12px;
    padding:22px;
}


.stat-label {
    font-size:13px;
    color:#6c757d;
}


.stat-value {
    font-size:28px;
    font-weight:600;
}



.customer-avatar {

width:42px;
height:42px;
background:#5347CE;
color:#fff;
border-radius:50%;
display:flex;
align-items:center;
justify-content:center;
font-weight:600;

}



.type-card {

padding:18px;
border-radius:12px;
background:#f8f9fa;

}



.type-number {

font-size:22px;
font-weight:600;

}



.table th {

background:#f8f9fa;
font-size:13px;
color:#495057;

}



.table td {

padding:15px 12px;
vertical-align:middle;

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



.action-btn {

width:32px;
height:32px;
padding:0;
display:inline-flex;
align-items:center;
justify-content:center;
border-radius:8px;

}

.search-box{
    position:relative;
}

.search-icon{
    position:absolute;
    left:15px;
    top:50%;
    transform:translateY(-50%);
    color:#5347CE;
    font-size:15px;
}

.search-box .form-control{
    height:45px;
    padding-left:42px;
    border:2px solid #5347CE;
    border-radius:10px;
}

.search-box .form-control:focus{
    border-color:#5347CE;
    box-shadow:0 0 0 .2rem rgba(83,71,206,.15);
}

.form-select{
    height:45px;
    border-radius:10px;
}

.btn-search{
    height:45px;
    background:#5347CE;
    color:#fff;
    border:none;
    border-radius:10px;
    font-weight:600;
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
Customer Directory
</h4>

<p class="text-muted mb-0">
Manage customer information and relationship data.
</p>

</div>


<a class="btn btn-main px-4 text-decoration-none" href="{{ route('crm.directory.create') }}">

<i class="bi bi-person-plus"></i>
Add Customer

</a>



</div>







{{-- Summary Cards --}}

<div class="row g-3 mb-4">


<div class="col-md-3">

<div class="stat-card">

<div class="stat-label">
Total Customers
</div>

<div class="stat-value">

{{ number_format($totalCustomers ?? 0) }}
</div>


</div>

</div>




<div class="col-md-3">

<div class="stat-card">

<div class="stat-label">
Active Customers
</div>

<div class="stat-value">

{{ number_format($activeCustomers ?? 0) }}
</div>


</div>

</div>





<div class="col-md-3">

<div class="stat-card">

<div class="stat-label">
New Customers
</div>

<div class="stat-value">

{{ number_format($newCustomers ?? 0) }}
</div>


</div>

</div>





<div class="col-md-3">

<div class="stat-card">

<div class="stat-label">
Inactive Accounts
</div>

<div class="stat-value">

{{ number_format($inactiveAccounts ?? 0) }}
</div>


</div>

</div>


</div>







{{-- Customer Classification --}}

<div class="card crm-card p-4 mb-4">


<h5 class="fw-semibold mb-3">
Customer Classification
</h5>



<div class="row g-3">


<div class="col-md-4">

<div class="type-card">

<small class="text-muted">

Regular Customers
</small>

<div class="type-number">

{{ number_format($regularCount ?? 0) }}
</div>


</div>

</div>




<div class="col-md-4">

<div class="type-card">

<small class="text-muted">

VIP Customers
</small>

<div class="type-number">

{{ number_format($vipCount ?? 0) }}
</div>


</div>

</div>




<div class="col-md-4">

<div class="type-card">

<small class="text-muted">

Corporate Accounts
</small>

<div class="type-number">

{{ number_format($corporateCount ?? 0) }}
</div>


</div>

</div>



</div>


</div>









{{-- Filters --}}

<div class="card crm-card p-3 mb-4">


<form method="GET" action="{{ route('crm.directory') }}">

<div class="row g-3">



<div class="col-md-5">

    <div class="search-box">

        <i class="bi bi-search search-icon"></i>

        <input
            type="text"
            name="search"
            value="{{ request('search', $search ?? '') }}"
            class="form-control"
            placeholder="Search customer name, email, contact number, or ID">

    </div>

</div>






<div class="col-md-2">


<select class="form-select" name="status">

<option value="" {{ (request('status', $appliedStatus ?? '') == '') ? 'selected' : '' }}>
Status
</option>

<option value="Active" {{ (request('status', $appliedStatus ?? '') === 'Active') ? 'selected' : '' }}>
Active
</option>

<option value="Inactive" {{ (request('status', $appliedStatus ?? '') === 'Inactive') ? 'selected' : '' }}>
Inactive
</option>


</select>


</div>






<div class="col-md-2">


<select class="form-select" name="type">

<option value="" {{ (request('type', $appliedType ?? '') == '') ? 'selected' : '' }}>
Customer Type
</option>

<option value="Regular" {{ (request('type', $appliedType ?? '') === 'Regular') ? 'selected' : '' }}>
Regular
</option>

<option value="VIP" {{ (request('type', $appliedType ?? '') === 'VIP') ? 'selected' : '' }}>
VIP
</option>

<option value="Corporate" {{ (request('type', $appliedType ?? '') === 'Corporate') ? 'selected' : '' }}>
Corporate
</option>


</select>


</div>






<div class="col-md-3">


<button class="btn btn-search w-100" type="submit">

<i class="bi bi-search me-1"></i>
Search

</button>



</div>




</div>


</div>

</form>



{{-- Customer List --}}


<div class="card crm-card p-4">


<div class="mb-3">

<h5 class="fw-semibold mb-1">
Customer List
</h5>


<small class="text-muted">
Customer account information and activity records.
</small>


</div>







<div class="table-responsive">


<table class="table align-middle">


<thead>


<tr>

<th>
Customer
</th>

<th>
Contact
</th>

<th>
Type
</th>

<th>
Purchases
</th>

<th>
Last Activity
</th>

<th>
Status
</th>

<th class="text-center">
Actions
</th>


</tr>


</thead>






<tbody>


@foreach ($customers as $customer)


<tr>


<td>


<div class="d-flex align-items-center gap-3">


<div class="customer-avatar">
{{ strtoupper(substr($customer->display_name ?? '', 0, 2)) }}
</div>




<div>


<div class="fw-semibold">
{{ $customer->display_name }}
</div>


<small class="text-muted">
ID: {{ $customer->customer_id }}
</small>


</div>




</div>


</td>







<td>


<div>
{{ $customer->email }}
</div>


<small class="text-muted">
{{ $customer->contact_no }}
</small>


</td>







<td>

@if(optional($customer->loyaltyProgram)->membership_level === 'VIP')

<span class="badge"
style="background:#5347CE">

VIP

</span>

@elseif(optional($customer->loyaltyProgram)->membership_level === 'Corporate')

<span class="badge bg-primary">
Corporate
</span>

@else

<span class="badge bg-secondary">
Regular
</span>

@endif


</td>







<td>

{{ method_exists($customer,'salesOrders') ? $customer->salesOrders()->count() : 0 }} Orders
</td>







<td>


{{ optional($customer->communicationLogs()->orderByDesc('communication_date')->first())->communication_date ? optional($customer->communicationLogs()->orderByDesc('communication_date')->first())->communication_date->format('M d, Y') : '-' }}
</td>







<td>


@php
$latestStatus = optional($customer->communicationLogs()->orderByDesc('communication_date')->first())->communication_status;
@endphp

@if($latestStatus && $latestStatus !== 'Inactive')

<span class="badge bg-success">
Active
</span>

@else

<span class="badge bg-secondary">
Inactive
</span>

@endif


</td>







<td class="text-center">
    <div class="d-flex justify-content-center align-items-center gap-2">
        <a class="btn btn-sm btn-outline-primary action-btn" href="{{ route('crm.directory.show', ['customer' => $customer->customer_id] + request()->query()) }}" aria-label="View">
            <i class="bi bi-eye"></i>
        </a>

        <a class="btn btn-sm btn-outline-warning action-btn" href="{{ route('crm.profiles', ['customer_id' => $customer->customer_id] + request()->query()) }}" aria-label="Edit">
            <i class="bi bi-pencil"></i>
        </a>

        @if(($customer->customer_status ?? 'Active') === 'Archived')
            <form method="POST" action="{{ route('crm.directory.restore', $customer) }}" class="m-0">
                @csrf @method('PATCH')
                <button class="btn btn-sm btn-outline-success action-btn" title="Restore customer"><i class="bi bi-arrow-counterclockwise"></i></button>
            </form>
        @else
            <form method="POST" action="{{ route('crm.directory.archive', $customer) }}" class="m-0" onsubmit="return confirm('Archive this customer while retaining all history?')">
                @csrf @method('PATCH')
                <input type="hidden" name="archive_reason" value="Archived by CRM staff">
                <button class="btn btn-sm btn-outline-danger action-btn" title="Archive customer"><i class="bi bi-archive"></i></button>
            </form>
        @endif
    </div>
</td>





</tr>


@endforeach


</tbody>



</table>


</div>







<div class="d-flex justify-content-between align-items-center mt-3">


<small class="text-muted">
{{ $customers->firstItem() }}-{{ $customers->lastItem() }} of {{ $customers->total() }} customers
</small>

<div>
{{ $customers->links() }}
</div>



</div>





</div>



@endsection
