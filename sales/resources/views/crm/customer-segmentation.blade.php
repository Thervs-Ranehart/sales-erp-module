@extends('layouts.app')

@section('title', 'Customer Segmentation')
@section('page-title', 'Customer Segmentation')

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


.segment-card {

border-radius:14px;
padding:20px;
color:white;
height:100%;

}


.high-value {

background:linear-gradient(135deg,#5347CE,#756cff);

}


.regular {

background:linear-gradient(135deg,#16C8C7,#58dedb);

}


.risk {

background:linear-gradient(135deg,#dc3545,#ff6b7a);

}



.segment-title {

font-weight:600;
font-size:17px;

}


.segment-number {

font-size:32px;
font-weight:700;

}


.segment-desc {

font-size:13px;
opacity:.9;

}



.insight-box {

border-left:4px solid #5347CE;
background:#f8f9fa;
padding:15px;
border-radius:8px;

}



.table th {

background:#f8f9fa;
font-size:13px;

}


.table td {

padding:15px 12px;
vertical-align:middle;

}


.action-btn {

font-size:13px;
border-radius:8px;

}


</style>





{{-- Header --}}

<div class="d-flex justify-content-between align-items-center mb-4">


<div>

<h4 class="fw-semibold mb-1">
Customer Segmentation
</h4>

<p class="text-muted mb-0">
Analyze customer groups based on purchasing behavior and value.
</p>

</div>



<button class="btn text-white"
style="background:#5347CE;border-radius:8px;">

<i class="bi bi-bar-chart"></i>
Generate Report

</button>


</div>








{{-- Segment Overview --}}

<div class="row g-3 mb-4">


<div class="col-md-4">


<div class="segment-card high-value">


<div class="segment-title">
High-Value Customers
</div>


<div class="segment-number">
212
</div>


<div class="segment-desc">
Customers with high spending and frequent purchases.
</div>


</div>


</div>






<div class="col-md-4">


<div class="segment-card regular">


<div class="segment-title">
Regular Customers
</div>


<div class="segment-number">
698
</div>


<div class="segment-desc">
Consistent buyers with average purchase activity.
</div>


</div>


</div>






<div class="col-md-4">


<div class="segment-card risk">


<div class="segment-title">
At-Risk Customers
</div>


<div class="segment-number">
95
</div>


<div class="segment-desc">
Customers with declining activity requiring attention.
</div>


</div>


</div>



</div>









{{-- Insights --}}

<div class="card crm-card p-4 mb-4">


<h5 class="fw-semibold mb-3">
Customer Insights
</h5>



<div class="row g-3">


<div class="col-md-4">


<div class="insight-box">

<strong>
Premium Buyers
</strong>

<p class="text-muted mb-0 mt-1">
High-value customers contribute the largest revenue share.
</p>

</div>


</div>




<div class="col-md-4">


<div class="insight-box">

<strong>
Frequent Purchasers
</strong>

<p class="text-muted mb-0 mt-1">
Regular customers maintain stable buying patterns.
</p>

</div>


</div>





<div class="col-md-4">


<div class="insight-box">

<strong>
Retention Needed
</strong>

<p class="text-muted mb-0 mt-1">
At-risk customers may need promotions or follow-ups.
</p>

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
placeholder="Search customer or segment">


</div>




<div class="col-md-3">


<select class="form-select">

<option>
All Segments
</option>

<option>
High-Value
</option>

<option>
Regular
</option>

<option>
At-Risk
</option>


</select>


</div>




<div class="col-md-2">


<select class="form-select">


<option>
Purchase Frequency
</option>

<option>
Weekly
</option>

<option>
Monthly
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









{{-- Records --}}

<div class="card crm-card p-4">


<div class="mb-3">

<h5 class="fw-semibold mb-1">
Segmentation Records
</h5>


<small class="text-muted">
Customer classification based on purchasing behavior.
</small>


</div>





<div class="table-responsive">


<table class="table align-middle">


<thead>

<tr>

<th>
Segment ID
</th>

<th>
Customer
</th>

<th>
Segment
</th>

<th>
Spending Category
</th>

<th>
Purchase Frequency
</th>

<th>
Last Updated
</th>

<th>
Action
</th>


</tr>

</thead>





<tbody>



<tr>

<td>
6001
</td>


<td>
Juan Dela Cruz
</td>


<td>

<span class="badge"
style="background:#5347CE">

High-Value

</span>

</td>


<td>
Premium
</td>


<td>
Weekly
</td>


<td>
July 1, 2026
</td>


<td>

<button class="btn btn-sm btn-outline-primary action-btn">
View
</button>

</td>


</tr>







<tr>

<td>
6002
</td>


<td>
Maria Santos
</td>


<td>

<span class="badge bg-info">
Regular
</span>

</td>


<td>
Standard
</td>


<td>
Monthly
</td>


<td>
June 20, 2026
</td>


<td>

<button class="btn btn-sm btn-outline-primary action-btn">
View
</button>

</td>


</tr>






<tr>

<td>
6003
</td>


<td>
Pedro Reyes
</td>


<td>

<span class="badge bg-danger">
At-Risk
</span>

</td>


<td>
Low Activity
</td>


<td>
Rarely
</td>


<td>
June 15, 2026
</td>


<td>

<button class="btn btn-sm btn-outline-primary action-btn">
View
</button>

</td>


</tr>



</tbody>



</table>


</div>



</div>






@endsection