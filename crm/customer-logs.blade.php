@extends('layouts.app')

@section('title', 'Communication Logs')
@section('page-title', 'Communication Logs')

@section('content')

<style>

.communication-card {
    background:#fff;
    border:1px solid #e9ecef;
    border-radius:12px;
}


.summary-box {
    padding:20px;
    border:1px solid #e9ecef;
    border-radius:12px;
    background:#fff;
}


.summary-label {
    font-size:13px;
    color:#6c757d;
}


.summary-value {
    font-size:26px;
    font-weight:600;
}



.channel-item {
    display:flex;
    justify-content:space-between;
    padding:12px 0;
    border-bottom:1px solid #eee;
}


.channel-item:last-child {
    border-bottom:none;
}



.timeline {
    border-left:2px solid #dee2e6;
    padding-left:20px;
}


.timeline-item {
    position:relative;
    margin-bottom:20px;
}


.timeline-item::before {

    content:"";
    position:absolute;
    left:-28px;
    top:5px;
    width:12px;
    height:12px;
    border-radius:50%;
    background:#5347CE;

}



.table th {

    background:#f8f9fa;
    color:#495057;
    font-size:13px;
    font-weight:600;

}


.table td {

    padding:15px 12px;
    vertical-align:middle;

}



.action-btn {

    font-size:13px;
    padding:6px 12px;
    border-radius:8px;
    margin:2px;

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





{{-- Header --}}

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h4 class="fw-semibold mb-1">
Communication Logs
</h4>

<p class="text-muted mb-0">
Track customer conversations, inquiries, and follow-up activities.
</p>

</div>


<button class="btn btn-main px-4">

<i class="bi bi-plus-lg"></i>
New Communication

</button>


</div>







{{-- Summary --}}

<div class="row g-3 mb-4">


<div class="col-md-3">

<div class="summary-box">

<div class="summary-label">
Total Conversations
</div>

<div class="summary-value">
2,845
</div>

</div>

</div>



<div class="col-md-3">

<div class="summary-box">

<div class="summary-label">
Pending Follow-Ups
</div>

<div class="summary-value">
42
</div>

</div>

</div>




<div class="col-md-3">

<div class="summary-box">

<div class="summary-label">
Resolved
</div>

<div class="summary-value">
2,540
</div>

</div>

</div>



<div class="col-md-3">

<div class="summary-box">

<div class="summary-label">
Response Rate
</div>

<div class="summary-value">
94%
</div>

</div>

</div>


</div>








{{-- Activity + Channel --}}

<div class="row g-4 mb-4">


<div class="col-md-7">


<div class="card communication-card p-4">


<h6 class="fw-semibold mb-3">
Recent Customer Activity
</h6>



<div class="timeline">


<div class="timeline-item">

<strong>
Juan Dela Cruz
</strong>

<p class="mb-1 text-muted">
Order confirmation follow-up
</p>

<small>
Email • Today, 10:30 AM
</small>

</div>




<div class="timeline-item">

<strong>
Maria Santos
</strong>

<p class="mb-1 text-muted">
Product inquiry resolved
</p>

<small>
Phone • July 5, 2026
</small>

</div>


</div>


</div>


</div>





<div class="col-md-5">


<div class="card communication-card p-4">


<h6 class="fw-semibold mb-3">
Communication Channels
</h6>



<div class="channel-item">

<span>Email</span>

<strong>55%</strong>

</div>



<div class="channel-item">

<span>Phone</span>

<strong>30%</strong>

</div>



<div class="channel-item">

<span>SMS</span>

<strong>15%</strong>

</div>



</div>


</div>



</div>








{{-- Filters --}}

<div class="card communication-card p-3 mb-4">


<div class="row g-3">


<div class="col-md-5">

<input type="text"
class="form-control"
placeholder="Search customer, subject, or log ID">

</div>



<div class="col-md-2">

<select class="form-select">

<option>
Channel
</option>

<option>Email</option>
<option>Phone</option>
<option>SMS</option>

</select>

</div>




<div class="col-md-2">

<select class="form-select">

<option>
Status
</option>

<option>Pending</option>
<option>Resolved</option>

</select>

</div>




<div class="col-md-3">

<button class="btn btn-outline-secondary w-100">

Filter Records

</button>

</div>


</div>


</div>









{{-- Records --}}

<div class="card communication-card p-4">


<h5 class="fw-semibold mb-3">
Interaction Records
</h5>




<div class="table-responsive">


<table class="table align-middle">


<thead>

<tr>

<th>
Log ID
</th>

<th>
Customer
</th>

<th>
Channel
</th>

<th>
Subject
</th>

<th class="text-center">
Priority
</th>

<th>
Follow-Up
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
4001
</td>


<td>
Juan Dela Cruz
</td>


<td>
Email
</td>


<td>
Order confirmation follow-up
</td>


<td class="text-center">

<span class="badge bg-danger">
High
</span>

</td>


<td>
July 10, 2026
</td>


<td class="text-center">

<span class="badge bg-warning text-dark">
Pending
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
4002
</td>


<td>
Maria Santos
</td>


<td>
Phone
</td>


<td>
Product inquiry
</td>


<td class="text-center">

<span class="badge bg-secondary">
Normal
</span>

</td>


<td>
—
</td>


<td class="text-center">

<span class="badge bg-success">
Resolved
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


</div>




@endsection