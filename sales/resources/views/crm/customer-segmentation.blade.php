@extends('layouts.app')

@section('title', 'Customer Segmentation')
@section('page-title', 'Customer Segmentation')

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

.search-box{
    position:relative;
}

.search-box .search-icon{
    position:absolute;
    left:14px;
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



<form method="POST" action="{{ route('crm.segmentation.recalculate') }}" class="mb-0">
    @csrf
    <button type="submit" class="btn text-white" style="background:#5347CE;border-radius:8px;">
        <i class="bi bi-bar-chart"></i>
        Refresh Report
    </button>
</form>


</div>








{{-- Segment Overview --}}

<div class="row g-3 mb-4">


<div class="col-md-4">


<div class="segment-card high-value">


<div class="segment-title">
High-Value Customers
</div>


<div class="segment-number">
{{ number_format($highValueCount ?? 0) }}
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
{{ number_format($regularCount ?? 0) }}
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
{{ number_format($atRiskCount ?? 0) }}
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

<form method="GET" action="{{ route('crm.segmentation') }}">



<div class="row g-3">


<div class="col-md-5">

    <div class="search-box">
        <i class="bi bi-search search-icon"></i>

        <input
            type="text"
            class="form-control"
            name="search"
            value="{{ $search ?? '' }}"
            placeholder="Search customer or segment">
    </div>

</div>





<div class="col-md-3">


<select class="form-select" name="segment">

<option value="All Segments" {{ ($segment ?? null) === 'All Segments' ? 'selected' : '' }}>
All Segments
</option>

<option value="High-Value" {{ ($segment ?? null) === 'High-Value' ? 'selected' : '' }}>
High-Value
</option>

<option value="Regular" {{ ($segment ?? null) === 'Regular' ? 'selected' : '' }}>
Regular
</option>

<option value="At-Risk" {{ ($segment ?? null) === 'At-Risk' ? 'selected' : '' }}>
At-Risk
</option>


</select>


</div>




<div class="col-md-2">


<select class="form-select" name="frequency">


<option value="" {{ empty($frequency) ? 'selected' : '' }}>
Purchase Frequency
</option>

<option value="Weekly" {{ ($frequency ?? null) === 'Weekly' ? 'selected' : '' }}>
Weekly
</option>

<option value="Monthly" {{ ($frequency ?? null) === 'Monthly' ? 'selected' : '' }}>
Monthly
</option>

<option value="Occasional" {{ ($frequency ?? null) === 'Occasional' ? 'selected' : '' }}>
Occasional
</option>


</select>


</div>




<div class="col-md-2">


<button class="btn btn-outline-secondary w-100" type="submit">
    <i class="bi bi-funnel"></i>
    Filter
</button>


</div>



</div>

</form>


</div>




<div>


{{-- end filters --}}


<div class="card crm-card p-4">



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

@forelse(($segments ?? []) as $segmentRow)

<tr>

<td>{{ $segmentRow->segment_id ?? '' }}</td>

<td>{{ optional($segmentRow->customer)->display_name ?? '' }}</td>

<td>

@php
    $segName = $segmentRow->segment_name ?? '';
@endphp

@if ($segName === 'High-Value')
    <span class="badge" style="background:#5347CE">{{ $segName }}</span>
@elseif ($segName === 'Regular')
    <span class="badge bg-info">{{ $segName }}</span>
@elseif ($segName === 'At-Risk')
    <span class="badge bg-danger">{{ $segName }}</span>
@else
    <span class="badge bg-secondary">{{ $segName }}</span>
@endif

</td>

<td>{{ $segmentRow->spending_category ?? '' }}</td>

<td>{{ $segmentRow->purchase_frequency ?? '' }}</td>

<td>
@if($segmentRow->last_updated)
    {{ \Carbon\Carbon::parse($segmentRow->last_updated)->format('F j, Y') }}
@endif
</td>

<td>
    <a class="btn btn-sm btn-outline-primary action-btn"
       href="{{ route('crm.profiles', ['customer_id' => $segmentRow->customer_id]) }}">
        View
    </a>
</td>

</tr>

@empty

<tr>
    <td colspan="7" class="text-center text-muted py-4">
        No segmentation records found. Click "Refresh Report" to classify customers based on their sales order history.
    </td>
</tr>

@endforelse

</tbody>



</table>


</div>

<div class="mt-3">
    {{ $segments->links() }}
</div>


</div>






@endsection