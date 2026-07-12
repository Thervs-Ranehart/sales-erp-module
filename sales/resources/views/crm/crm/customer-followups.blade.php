@extends('layouts.app')

@section('title', 'Customer Directory')
@section('page-title', 'Customer Directory')

@section('content')

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
    font-weight:600;

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

    font-size:13px;
    padding:6px 12px;
    border-radius:8px;
    margin:2px;

}


</style>





{{-- Header --}}

<div class="d-flex justify-content-between align-items-center mb-4">


<div>

<h4 class="fw-semibold mb-1">
Customer Directory
</h4>

<p class="text-muted mb-0">
Manage customer information, profiles, and relationship data.
</p>

</div>



<button class="btn btn-main px-4">

<i class="bi bi-person-plus"></i>
Add Customer

</button>


</div>






{{-- Summary Cards --}}

<div class="row g-3 mb-4">


<div class="col-md-3">

<div class="stat-card">

<div class="stat-label">
Total Customers
</div>

<div class="stat-value">
1,245
</div>


</div>

</div>




<div class="col-md-3">

<div class="stat-card">

<div class="stat-label">
Active Customers
</div>

<div class="stat-value">
987
</div>


</div>

</div>




<div class="col-md-3">

<div class="stat-card">

<div class="stat-label">
New Customers
</div>

<div class="stat-value">
85
</div>


</div>

</div>




<div class="col-md-3">

<div class="stat-card">

<div class="stat-label">
Inactive Accounts
</div>

<div class="stat-value">
43
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
850
</div>

</div>

</div>




<div class="col-md-4">

<div class="type-card">

<small class="text-muted">
VIP Customers
</small>

<div class="type-number">
245
</div>

</div>

</div>




<div class="col-md-4">

<div class="type-card">

<small class="text-muted">
Corporate Accounts
</small>

<div class="type-number">
150
</div>

</div>

</div>



</div>


</div>








{{-- Filters --}}

<div class="card crm-card p-3 mb-4">


<div class="row g-3">


<div class="col-md-5">

<input type="text"
class="form-control"
placeholder="Search customer name, email, or ID">

</div>




<div class="col-md-2">

<select class="form-select">

<option>
Status
</option>

<option>
Active
</option>

<option>
Inactive
</option>

</select>

</div>




<div class="col-md-2">

<select class="form-select">

<option>
Customer Type
</option>

<option>
Regular
</option>

<option>
VIP
</option>

<option>
Corporate
</option>

</select>

</div>




<div class="col-md-3">

<button class="btn btn-outline-secondary w-100">

<i class="bi bi-search"></i>
Search

</button>

</div>



</div>


</div>









{{-- Customer List --}}

<div class="card crm-card p-4">


<div class="mb-3">

<h5 class="fw-semibold mb-1">
Customer List
</h5>


<small class="text-muted">
Customer account details and engagement information.
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


<th class="text-center">
Status
</th>


<th class="text-center">
Actions
</th>


</tr>

</thead>





<tbody>



<tr>


<td>


<div class="d-flex align-items-center gap-3">


<div class="customer-avatar">
JD
</div>



<div>

<div class="fw-semibold">
Juan Dela Cruz
</div>


<small class="text-muted">
ID: 1001
</small>


</div>


</div>


</td>





<td>

<div>
juan.delacruz@email.com
</div>

<small class="text-muted">
0917-123-4567
</small>

</td>





<td>

<span class="badge" style="background:#5347CE;">
VIP
</span>

</td>





<td>
24 Orders
</td>





<td>
July 10, 2026
</td>





<td class="text-center">

<span class="badge bg-success">
Active
</span>

</td>





<td class="text-center">


<button class="btn btn-sm btn-outline-primary action-btn">

<i class="bi bi-eye"></i>


</button>



<button class="btn btn-sm btn-outline-warning action-btn">

<i class="bi bi-pencil"></i>


</button>



<button class="btn btn-sm btn-outline-danger action-btn">

<i class="bi bi-trash"></i>


</button>


</td>



</tr>







<tr>


<td>


<div class="d-flex align-items-center gap-3">


<div class="customer-avatar">
MS
</div>



<div>

<div class="fw-semibold">
Maria Santos
</div>


<small class="text-muted">
ID: 1002
</small>


</div>


</div>


</td>





<td>

<div>
maria.santos@email.com
</div>

<small class="text-muted">
0917-987-6543
</small>

</td>





<td>

<span class="badge bg-secondary">
Regular
</span>

</td>





<td>
8 Orders
</td>





<td>
July 5, 2026
</td>





<td class="text-center">

<span class="badge bg-success">
Active
</span>

</td>





<td class="text-center">


<button class="btn btn-sm btn-outline-primary action-btn">

<i class="bi bi-eye"></i>


</button>



<button class="btn btn-sm btn-outline-warning action-btn">

<i class="bi bi-pencil"></i>


</button>



<button class="btn btn-sm btn-outline-danger action-btn">

<i class="bi bi-trash"></i>


</button>


</td>


</tr>



</tbody>


</table>


</div>





{{-- Pagination --}}

<div class="d-flex justify-content-between align-items-center mt-3">


<small class="text-muted">
Showing 1-10 of 1,245 customers
</small>



<button class="btn btn-sm btn-outline-secondary">
Next
</button>


</div>




</div>




@endsection