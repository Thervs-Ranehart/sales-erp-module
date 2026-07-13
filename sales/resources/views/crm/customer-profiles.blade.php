@extends('layouts.app')

@section('title', 'Customer Profiles')
@section('page-title', 'Customer Profiles')

@section('content')

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



<button class="btn btn-outline-primary">

<i class="bi bi-pencil"></i>
Edit Profile

</button>



</div>









{{-- Search Customer --}}

<div class="card profile-card p-4 mb-4">


<h6 class="fw-semibold mb-3">
Select Customer
</h6>



<div class="search-box mb-3">


<i class="bi bi-search search-icon"></i>


<input type="text"
class="form-control"
placeholder="Search customer name, ID, or email...">


</div>




<div class="row g-3">


<div class="col-md-4">


<div class="p-3 border rounded">


<div class="d-flex gap-3 align-items-center">


<div class="avatar">
JD
</div>


<div>

<h6 class="mb-1">
Juan Dela Cruz
</h6>


<small class="text-muted">
ID:1001
</small>

<br>

<small class="text-muted">
juan@email.com
</small>


</div>


</div>


</div>


</div>






<div class="col-md-4">


<div class="p-3 border rounded">


<div class="d-flex gap-3 align-items-center">


<div class="avatar">
MS
</div>


<div>

<h6 class="mb-1">
Maria Santos
</h6>


<small class="text-muted">
ID:1002
</small>


<br>

<small class="text-muted">
maria@email.com
</small>


</div>


</div>


</div>


</div>



</div>



</div>









{{-- Customer Overview --}}

<div class="card profile-card p-4 mb-4">


<div class="d-flex align-items-center gap-3">


<div class="avatar">
JD
</div>



<div>

<h4 class="mb-1">
Juan Dela Cruz
</h4>


<span class="badge bg-success">
Active Customer
</span>


<p class="text-muted mb-0 mt-2">
Customer ID:1001
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
24
</h5>

</div>

</div>




<div class="col-md-3">

<div class="stat-mini">

<small>
Total Spending
</small>

<h5>
₱45,800
</h5>

</div>

</div>




<div class="col-md-3">

<div class="stat-mini">

<small>
Loyalty Points
</small>

<h5>
2,200
</h5>

</div>

</div>




<div class="col-md-3">

<div class="stat-mini">

<small>
Customer Since
</small>

<h5>
2025
</h5>

</div>

</div>



</div>


</div>









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
juan@email.com
</div>

</div>

</div>



<div class="col-md-6">

<div class="info-box">

<div class="label">
Phone
</div>

<div class="value">
0917-123-4567
</div>

</div>

</div>




<div class="col-md-6">

<div class="info-box">

<div class="label">
Gender
</div>

<div class="value">
Male
</div>

</div>

</div>




<div class="col-md-6">

<div class="info-box">

<div class="label">
Birth Date
</div>

<div class="value">
March 14, 1994
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
Email
</div>

</div>

</div>





<div class="col-md-6">

<div class="info-box">

<div class="label">
Preferred Product
</div>

<div class="value">
Office Supplies
</div>

</div>

</div>





<div class="col-md-6">

<div class="info-box">

<div class="label">
Customer Type
</div>

<div class="value">
VIP
</div>

</div>

</div>





<div class="col-md-6">

<div class="info-box">

<div class="label">
Marketing Consent
</div>

<div class="value text-success">
Approved
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

<tr>

<td>
Wireless Mouse
</td>

<td>
₱900
</td>

<td>
July 7, 2026
</td>

</tr>


<tr>

<td>
Keyboard
</td>

<td>
₱1,500
</td>

<td>
June 30, 2026
</td>

</tr>


</table>


</div>


</div>








<div class="col-md-6">


{{-- Customer Tags --}}

<div class="card profile-card p-4">


<div class="section-title">
Customer Tags
</div>


<div class="d-flex flex-wrap gap-2">


<div class="d-flex flex-wrap gap-2">


<span class="tag active">
<i class="bi bi-check-circle"></i>
VIP Customer
</span>


<span class="tag">
<i class="bi bi-check-circle"></i>
Frequent Buyer
</span>


<span class="tag">
<i class="bi bi-check-circle"></i>
Office Supplies
</span>


<span class="tag">
<i class="bi bi-check-circle"></i>
High Value Customer
</span>


<span class="tag">
<i class="bi bi-check-circle"></i>
Loyalty Member
</span>


<span class="tag">
<i class="bi bi-check-circle"></i>
Email Preferred
</span>


<span class="tag">
<i class="bi bi-check-circle"></i>
Returning Customer
</span>


</div>



</div>


</div>


<script>

document.querySelectorAll('.tag').forEach(tag => {

    tag.addEventListener('click', function(){

        this.classList.toggle('active');

    });

});

</script>




@endsection