@extends('layouts.app')

@section('title', 'Purchase History')
@section('page-title', 'Purchase History')

@section('content')

<style>

.purchase-card {
    background:#fff;
    border:1px solid #e9ecef;
    border-radius:12px;
}

.summary-box {
    padding:22px;
    border-radius:12px;
    border:1px solid #e9ecef;
    background:#fff;
}

.summary-label {
    color:#6c757d;
    font-size:14px;
}

.summary-value {
    font-size:26px;
    font-weight:600;
}

.category-item {
    display:flex;
    justify-content:space-between;
    padding:12px 0;
    border-bottom:1px solid #eee;
}

.category-item:last-child {
    border-bottom:none;
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

.form-control,
.form-select {
    border-radius:8px;
}

.btn {
    border-radius:8px;
}

</style>

{{-- Header --}}

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h4 class="fw-semibold mb-1">
Purchase History
</h4>

<p class="text-muted mb-0">
Monitor customer buying behavior and transaction records.
</p>

</div>


<button class="btn btn-outline-secondary">

<i class="bi bi-download"></i>
Export Report

</button>


</div>


{{-- Summary Cards --}}

<div class="row g-3 mb-4">

<div class="col-md-3">

<div class="summary-box">

<div class="summary-label">
Total Transactions
</div>

<div class="summary-value">
3,482
</div>

</div>

</div>


<div class="col-md-3">

<div class="summary-box">

<div class="summary-label">
Total Sales
</div>

<div class="summary-value">
₱1.25M
</div>

</div>

</div>


<div class="col-md-3">

<div class="summary-box">

<div class="summary-label">
Monthly Orders
</div>

<div class="summary-value">
128
</div>

</div>

</div>


<div class="col-md-3">

<div class="summary-box">

<div class="summary-label">
Average Purchase
</div>

<div class="summary-value">
₱3,421
</div>

</div>

</div>

</div>



{{-- Insights and Search --}}

<div class="row g-4 mb-4">


<div class="col-md-4">

<div class="card purchase-card p-4">


<h6 class="fw-semibold mb-3">
Customer Insights
</h6>


<div class="category-item">

<span>
Top Customer
</span>

<strong>
Juan Dela Cruz
</strong>

</div>


<div class="category-item">

<span>
Highest Purchase
</span>

<strong>
₱45,800
</strong>

</div>


<div class="category-item">

<span>
Most Purchased
</span>

<strong>
Electronics
</strong>

</div>


<div class="category-item">

<span>
Purchase Frequency
</span>

<strong>
Weekly
</strong>

</div>


</div>

</div>




<div class="col-md-8">


<div class="card purchase-card p-4">


<h6 class="fw-semibold mb-3">
Search Transactions
</h6>


<div class="row g-3">


<div class="col-md-5">


<div class="input-group">


<span class="input-group-text bg-white">

<i class="bi bi-search"></i>

</span>


<input type="text"
class="form-control"
placeholder="Search customer, product, or transaction ID">


</div>


</div>



<div class="col-md-3">

<select class="form-select">

<option>
Category
</option>

<option>
Electronics
</option>

<option>
Office Supplies
</option>

<option>
Accessories
</option>

</select>

</div>




<div class="col-md-2">

<select class="form-select">

<option>
Status
</option>

<option>
Paid
</option>

<option>
Pending
</option>

<option>
Cancelled
</option>

</select>


</div>




<div class="col-md-2">

<button class="btn btn-outline-secondary w-100">

Filter

</button>

</div>



</div>


</div>


</div>


</div>



{{-- Top Categories --}}

<div class="card purchase-card p-4 mb-4">


<h6 class="fw-semibold mb-3">
Top Purchase Categories
</h6>


<div class="row">


<div class="col-md-4">

<div class="category-item">

<span>
Electronics
</span>

<strong>
45%
</strong>

</div>

</div>



<div class="col-md-4">

<div class="category-item">

<span>
Office Supplies
</span>

<strong>
35%
</strong>

</div>

</div>



<div class="col-md-4">

<div class="category-item">

<span>
Accessories
</span>

<strong>
20%
</strong>

</div>

</div>


</div>


</div>




{{-- Transaction Records --}}

<div class="card purchase-card p-4">


<div class="mb-3">

<h5 class="fw-semibold mb-1">
Transaction Records
</h5>


<small class="text-muted">
Customer purchase history and sales information
</small>


</div>




<div class="table-responsive">


<table class="table align-middle">


<thead>


<tr>

<th>
Transaction ID
</th>

<th>
Customer
</th>

<th>
Product
</th>

<th>
Category
</th>

<th class="text-center">
Qty
</th>

<th>
Amount
</th>

<th class="text-center">
Payment Status
</th>

<th>
Purchase Date
</th>

<th class="text-center">
Action
</th>

</tr>


</thead>




<tbody>


<tr>


<td>
3001
</td>


<td>
Juan Dela Cruz
</td>


<td>
Wireless Mouse
</td>


<td>
Electronics
</td>


<td class="text-center">
2
</td>


<td>
₱900.00
</td>


<td class="text-center">

<span class="badge bg-success">
Paid
</span>

</td>


<td>
July 7, 2026
</td>


<td class="text-center">


<button class="btn btn-sm btn-outline-primary">
View
</button>


<button class="btn btn-sm btn-outline-secondary">
Receipt
</button>


</td>


</tr>





<tr>


<td>
3002
</td>


<td>
Maria Santos
</td>


<td>
Bond Paper (Ream)
</td>


<td>
Office Supplies
</td>


<td class="text-center">
10
</td>


<td>
₱1,800.00
</td>


<td class="text-center">


<span class="badge bg-success">
Paid
</span>


</td>


<td>
July 6, 2026
</td>


<td class="text-center">


<button class="btn btn-sm btn-outline-primary">
View
</button>


<button class="btn btn-sm btn-outline-secondary">
Receipt
</button>


</td>


</tr>






<tr>


<td>
3003
</td>


<td>
Pedro Reyes
</td>


<td>
Keyboard
</td>


<td>
Electronics
</td>


<td class="text-center">
1
</td>


<td>
₱1,250.00
</td>


<td class="text-center">


<span class="badge bg-warning text-dark">
Pending
</span>


</td>


<td>
July 5, 2026
</td>


<td class="text-center">


<button class="btn btn-sm btn-outline-primary">
View
</button>


<button class="btn btn-sm btn-outline-secondary">
Receipt
</button>


</td>


</tr>



</tbody>


</table>


</div>


</div>




@endsection