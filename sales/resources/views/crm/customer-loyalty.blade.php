@extends('layouts.app')

@section('title', 'Loyalty Program')
@section('page-title', 'Loyalty Program')

@section('content')

<style>

.loyalty-card {
    background:#fff;
    border:1px solid #e9ecef;
    border-radius:12px;
}

.stat-box {
    background:#fff;
    border:1px solid #e9ecef;
    border-radius:12px;
    padding:22px;
}

.stat-label {
    color:#6c757d;
    font-size:13px;
}

.stat-value {
    font-size:26px;
    font-weight:600;
}


.section-title {
    font-weight:600;
    margin-bottom:4px;
}

.section-desc {
    color:#6c757d;
    font-size:13px;
}




/* Tier Cards */

.tier-card {

    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:18px;
    border-radius:12px;
    color:white;

}


.gold {

background:linear-gradient(135deg,#5347CE,#7c73ff);

}


.silver {

background:linear-gradient(135deg,#16C8C7,#5be1df);

}


.bronze {

background:linear-gradient(135deg,#6c757d,#9aa0a6);

}


.tier-benefit {

font-size:12px;
background:rgba(255,255,255,.2);
padding:6px 10px;
border-radius:20px;

}







/* Reward Cards */

.reward-card {

border:1px solid #e9ecef;
border-radius:14px;
padding:18px;
height:100%;
transition:.2s;

}


.reward-card:hover {

transform:translateY(-4px);
box-shadow:0 8px 20px rgba(0,0,0,.08);

}



.reward-icon {

width:45px;
height:45px;
border-radius:12px;
background:#f1f0ff;
color:#5347CE;
display:flex;
align-items:center;
justify-content:center;
font-size:22px;
margin-bottom:15px;

}



.reward-card h6 {

font-weight:600;

}


.reward-card p {

font-size:14px;
color:#6c757d;

}






.activity-item {

display:flex;
justify-content:space-between;
padding:12px 0;
border-bottom:1px solid #eee;

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


</style>







{{-- Header --}}

<div class="d-flex justify-content-between align-items-center mb-4">


<div>

<h4 class="fw-semibold mb-1">
Loyalty Program
</h4>

<p class="text-muted mb-0">
Manage customer rewards, membership levels, and loyalty engagement.
</p>

</div>



<button class="btn text-white"
style="background:#5347CE;border-radius:8px;">

<i class="bi bi-plus-lg"></i>
Create Reward

</button>


</div>







{{-- Statistics --}}

<div class="row g-3 mb-4">


<div class="col-md-3">

<div class="stat-box">

<div class="stat-label">
Active Members
</div>

<div class="stat-value">
654
</div>

</div>

</div>



<div class="col-md-3">

<div class="stat-box">

<div class="stat-label">
Available Points
</div>

<div class="stat-value">
74,330
</div>

</div>

</div>




<div class="col-md-3">

<div class="stat-box">

<div class="stat-label">
Rewards Claimed
</div>

<div class="stat-value">
1,245
</div>

</div>

</div>




<div class="col-md-3">

<div class="stat-box">

<div class="stat-label">
Expiring Points
</div>

<div class="stat-value">
8,420
</div>

</div>

</div>



</div>









{{-- Tier + Rewards --}}

<div class="row g-4 mb-4">





{{-- Membership --}}

<div class="col-md-5">


<div class="card loyalty-card p-4">


<div class="section-title">
Membership Levels
</div>

<div class="section-desc mb-4">
Customer classification based on loyalty status.
</div>





<div class="tier-card gold mb-3">


<div>

<h6 class="mb-1">
Gold Tier
</h6>

<small>
245 Members
</small>


</div>


<div class="tier-benefit">
20% Bonus Points
</div>


</div>





<div class="tier-card silver mb-3">


<div>

<h6 class="mb-1">
Silver Tier
</h6>

<small>
310 Members
</small>


</div>


<div class="tier-benefit">
10% Bonus Points
</div>


</div>





<div class="tier-card bronze">


<div>

<h6 class="mb-1">
Bronze Tier
</h6>

<small>
99 Members
</small>


</div>


<div class="tier-benefit">
Standard Rewards
</div>


</div>



</div>


</div>









{{-- Rewards --}}

<div class="col-md-7">


<div class="card loyalty-card p-4">


<div class="d-flex justify-content-between align-items-center mb-3">


<div>

<div class="section-title">
Available Rewards
</div>

<div class="section-desc">
Rewards customers can redeem using points.
</div>

</div>



<button class="btn btn-outline-primary btn-sm">
Manage
</button>


</div>





<div class="row g-3">



<div class="col-md-4">

<div class="reward-card">


<div class="reward-icon">
<i class="bi bi-ticket-perforated"></i>
</div>


<h6>
₱500 Voucher
</h6>

<p>
500 points required
</p>


<span class="badge bg-success">
Available
</span>


<button class="btn btn-sm btn-outline-primary w-100 mt-3">
View
</button>


</div>

</div>







<div class="col-md-4">

<div class="reward-card">


<div class="reward-icon">
<i class="bi bi-truck"></i>
</div>


<h6>
Free Shipping
</h6>


<p>
300 points required
</p>


<span class="badge bg-success">
Available
</span>


<button class="btn btn-sm btn-outline-primary w-100 mt-3">
View
</button>


</div>

</div>







<div class="col-md-4">

<div class="reward-card">


<div class="reward-icon">
<i class="bi bi-gift"></i>
</div>


<h6>
Gift Voucher
</h6>


<p>
1000 points required
</p>


<span class="badge bg-warning text-dark">
Limited
</span>


<button class="btn btn-sm btn-outline-primary w-100 mt-3">
View
</button>


</div>

</div>




</div>


</div>


</div>




</div>









{{-- Points Activity --}}

<div class="card loyalty-card p-4 mb-4">


<div class="section-title">
Recent Points Activity
</div>

<div class="section-desc mb-3">
Latest loyalty point transactions.
</div>




<div class="activity-item">

<span>
Juan Dela Cruz earned points from purchase
</span>

<strong class="text-success">
+500 pts
</strong>


</div>




<div class="activity-item">

<span>
Maria Santos redeemed discount voucher
</span>

<strong class="text-danger">
-300 pts
</strong>


</div>





<div class="activity-item">

<span>
Pedro Reyes received membership bonus
</span>

<strong class="text-success">
+200 pts
</strong>


</div>



</div>









{{-- Member Records --}}

<div class="card loyalty-card p-4">


<div class="d-flex justify-content-between align-items-center mb-3">


<div>

<h5 class="section-title">
Loyalty Member Records
</h5>

<div class="section-desc">
Customer reward accounts and membership information.
</div>


</div>



<input type="text"
class="form-control"
style="width:250px;"
placeholder="Search member...">


</div>







<div class="table-responsive">


<table class="table align-middle">


<thead>

<tr>

<th>
Customer
</th>

<th>
Membership
</th>

<th>
Current Points
</th>

<th>
Lifetime Points
</th>

<th>
Last Redemption
</th>

<th>
Status
</th>

<th>
Action
</th>


</tr>


</thead>





<tbody>


<tr>

<td>
Juan Dela Cruz
</td>


<td>

<span class="badge"
style="background:#5347CE;">
Gold
</span>

</td>


<td>
2,200
</td>


<td>
3,200
</td>


<td>
July 5, 2026
</td>


<td>

<span class="badge bg-success">
Active
</span>

</td>


<td>

<button class="btn btn-sm btn-outline-primary">
View
</button>

</td>


</tr>






<tr>

<td>
Maria Santos
</td>


<td>

<span class="badge"
style="background:#16C8C7;">
Silver
</span>

</td>


<td>
1,150
</td>


<td>
1,450
</td>


<td>
June 28, 2026
</td>


<td>

<span class="badge bg-success">
Active
</span>

</td>


<td>

<button class="btn btn-sm btn-outline-primary">
View
</button>

</td>


</tr>



</tbody>


</table>


</div>


</div>





@endsection