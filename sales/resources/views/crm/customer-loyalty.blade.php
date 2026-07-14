@extends('layouts.app')

@section('title', 'Loyalty Program')
@section('page-title', 'Loyalty Program')

@section('content')

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

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



<div class="d-flex gap-2">

<button type="button" class="btn btn-outline-primary"
style="border-radius:8px;"
data-bs-toggle="modal" data-bs-target="#enrollCustomerModal">

<i class="bi bi-person-plus"></i>
Enroll Customer

</button>

<button type="button" class="btn text-white"
style="background:#5347CE;border-radius:8px;"
data-bs-toggle="modal" data-bs-target="#createRewardModal">

<i class="bi bi-plus-lg"></i>
Create Reward

</button>

</div>


</div>







{{-- Statistics --}}

<div class="row g-3 mb-4">


<div class="col-md-3">

<div class="stat-box">

<div class="stat-label">
Active Members
</div>

<div class="stat-value">
{{ number_format($activeMembers ?? 0) }}
</div>

</div>

</div>



<div class="col-md-3">

<div class="stat-box">

<div class="stat-label">
Available Points
</div>

<div class="stat-value">
{{ number_format($availablePoints ?? 0) }}
</div>

</div>

</div>




<div class="col-md-3">

<div class="stat-box">

<div class="stat-label">
Rewards Claimed
</div>

<div class="stat-value">
{{ number_format($rewardsClaimed ?? 0) }}
</div>

</div>

</div>




<div class="col-md-3">

<div class="stat-box">

<div class="stat-label">
Expiring Points
</div>

<div class="stat-value">
{{ number_format($expiringPoints ?? 0) }}
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
{{ number_format(collect($loyalties ?? collect())->where('membership_level', 'Gold')->count()) }} Members
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
{{ number_format(collect($loyalties ?? collect())->where('membership_level', 'Silver')->count()) }} Members
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
{{ number_format(collect($loyalties ?? collect())->where('membership_level', 'Bronze')->count()) }} Members
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



<button type="button" class="btn btn-outline-primary btn-sm"
data-bs-toggle="modal" data-bs-target="#manageRewardsModal">
Manage
</button>


</div>





<div class="row g-3">

@forelse(($rewards ?? collect()) as $reward)

<div class="col-md-4">

<div class="reward-card">


<div class="reward-icon">
<i class="bi {{ $reward->icon ?? 'bi-gift' }}"></i>
</div>


<h6>
{{ $reward->name }}
</h6>

<p>
{{ number_format($reward->points_required ?? 0) }} points required
</p>


<span class="badge {{ $reward->statusBadgeClass() }}">
{{ $reward->statusLabel() }}
</span>


<button type="button" class="btn btn-sm btn-outline-primary w-100 mt-3"
data-bs-toggle="modal" data-bs-target="#viewRewardModal{{ $reward->reward_id }}">
View
</button>


</div>

</div>

@empty

<div class="col-12">
<p class="text-muted text-center mb-0 py-4">
No rewards have been created yet. Click "Create Reward" to add one.
</p>
</div>

@endforelse


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




@php
    $activityItems = $loyalties->getCollection()->take(3);
@endphp

@forelse($activityItems as $loyalty)

<div class="activity-item">

    <span>
        {{ optional($loyalty->customer)->display_name ?? ('Customer #'.($loyalty->customer_id ?? 'N/A')) }}

        @if(($loyalty->points_earned ?? 0) > 0)
            earned points
        @elseif(($loyalty->points_redeemed ?? 0) > 0)
            redeemed points
        @else
            updated loyalty points
        @endif
    </span>

    @php
        $delta = ($loyalty->points_earned ?? 0) - ($loyalty->points_redeemed ?? 0);
    @endphp

    <strong class="{{ $delta >= 0 ? 'text-success' : 'text-danger' }}">
        {{ $delta >= 0 ? '+' : '-' }}{{ number_format(abs($delta)) }} pts
    </strong>

</div>

@empty

@for($i = 0; $i < 3; $i++)

<div class="activity-item">
    <span>—</span>
    <strong class="text-success">+0 pts</strong>
</div>

@endfor

@endforelse


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



<form action="{{ route('crm.loyalty') }}" method="GET" class="mb-0">
<input type="text"
name="search"
value="{{ $search ?? '' }}"
class="form-control"
style="width:250px;"
placeholder="Search member...">
</form>


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

@foreach($loyalties as $loyalty)

<tr>


<td>
{{ optional($loyalty->customer)->display_name ?? (isset($loyalty->customer_id) ? 'Customer #'.$loyalty->customer_id : 'Unknown') }}
</td>


<td>

<span class="badge"@php $level = $loyalty->membership_level ?? ''; @endphp style="background:{{ $level === 'Gold' ? '#5347CE' : ($level === 'Silver' ? '#16C8C7' : ($level === 'Bronze' ? '#6c757d' : '#adb5bd')) }};">
{{ $loyalty->membership_level ?? 'Unknown' }}
</span>

</td>


<td>
{{ number_format($loyalty->available_points ?? 0) }}
</td>


<td>
{{ number_format($loyalty->points_earned ?? ($loyalty->available_points ?? 0)) }}
</td>


<td>
@if($loyalty->enrollment_date)
    {{ \Carbon\Carbon::parse($loyalty->enrollment_date)->format('M d, Y') }}
@else
    —
@endif
</td>


<td>

<span class="badge bg-success">
{{ isset($loyalty->enrollment_date) ? 'Active' : 'Inactive' }}
</span>

</td>


<td>

<a class="btn btn-sm btn-outline-primary" href="{{ route('crm.loyalty.show', $loyalty) }}">
View
</a>

<button class="btn btn-sm btn-outline-warning" type="button" data-bs-toggle="modal" data-bs-target="#editLoyaltyModal{{ $loyalty->loyalty_id }}">
Edit
</button>

</td>


</tr>

@endforeach

</tbody>


</table>


</div>


</div>

<div class="mt-3">
    {{ $loyalties->links() }}
</div>


</div>

@foreach(($loyalties ?? collect())->items() as $loyalty)
<div class="modal fade" id="editLoyaltyModal{{ $loyalty->loyalty_id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('crm.loyalty.update', $loyalty) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Update Loyalty — {{ optional($loyalty->customer)->display_name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Membership Level</label>
                        <select name="membership_level" class="form-select" required>
                            <option value="Gold" {{ $loyalty->membership_level === 'Gold' ? 'selected' : '' }}>Gold</option>
                            <option value="Silver" {{ $loyalty->membership_level === 'Silver' ? 'selected' : '' }}>Silver</option>
                            <option value="Bronze" {{ $loyalty->membership_level === 'Bronze' ? 'selected' : '' }}>Bronze</option>
                            <option value="VIP" {{ $loyalty->membership_level === 'VIP' ? 'selected' : '' }}>VIP</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Available Points</label>
                        <input type="number" name="available_points" class="form-control" min="0" value="{{ $loyalty->available_points ?? 0 }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lifetime Points Earned</label>
                        <input type="number" name="points_earned" class="form-control" min="0" value="{{ $loyalty->points_earned ?? 0 }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Points Redeemed</label>
                        <input type="number" name="points_redeemed" class="form-control" min="0" value="{{ $loyalty->points_redeemed ?? 0 }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn text-white" style="background:#5347CE;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@if(isset($selectedLoyalty))
<div class="modal fade show" id="loyaltyDetailModal" tabindex="-1" style="display:block;background:rgba(0,0,0,.5);">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Loyalty Details — {{ optional($selectedLoyalty->customer)->display_name }}</h5>
                <a href="{{ route('crm.loyalty') }}" class="btn-close"></a>
            </div>
            <div class="modal-body">
                <p><strong>Membership Level:</strong> {{ $selectedLoyalty->membership_level }}</p>
                <p><strong>Available Points:</strong> {{ number_format($selectedLoyalty->available_points ?? 0) }}</p>
                <p><strong>Lifetime Points:</strong> {{ number_format($selectedLoyalty->points_earned ?? 0) }}</p>
                <p><strong>Points Redeemed:</strong> {{ number_format($selectedLoyalty->points_redeemed ?? 0) }}</p>
                <p><strong>Enrollment Date:</strong> {{ $selectedLoyalty->enrollment_date
    ? \Carbon\Carbon::parse($selectedLoyalty->enrollment_date)->format('M d, Y')
    : '—'
}}</p>
                <a href="{{ route('crm.profiles', ['customer_id' => $selectedLoyalty->customer_id]) }}" class="btn btn-outline-primary btn-sm">View Customer Profile</a>
            </div>
            <div class="modal-footer">
                <a href="{{ route('crm.loyalty') }}" class="btn btn-primary">Close</a>
            </div>
        </div>
    </div>
</div>
@endif


{{-- =========================================================
     LOYALTY ENROLLMENT (new)
     ========================================================= --}}

<div class="modal fade" id="enrollCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('crm.loyalty.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Enroll Customer in Loyalty Program</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    @if(($unenrolledCustomers ?? collect())->isEmpty())

                        <p class="text-muted mb-0">
                            All customers are already enrolled in the loyalty program.
                        </p>

                    @else

                        <div class="mb-3">
                            <label class="form-label">Customer</label>
                            <select name="customer_id" class="form-select" required>
                                <option value="" disabled selected>Select a customer</option>
                                @foreach($unenrolledCustomers as $customer)
                                    <option value="{{ $customer->customer_id }}" {{ old('customer_id') == $customer->customer_id ? 'selected' : '' }}>
                                        {{ $customer->display_name }} (ID: {{ $customer->customer_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Membership Level</label>
                            <select name="membership_level" class="form-select" required>
                                <option value="Bronze" {{ old('membership_level', 'Bronze') === 'Bronze' ? 'selected' : '' }}>Bronze</option>
                                <option value="Silver" {{ old('membership_level') === 'Silver' ? 'selected' : '' }}>Silver</option>
                                <option value="Gold" {{ old('membership_level') === 'Gold' ? 'selected' : '' }}>Gold</option>
                                <option value="VIP" {{ old('membership_level') === 'VIP' ? 'selected' : '' }}>VIP</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Starting Points</label>
                            <input type="number" name="available_points" class="form-control" min="0" value="{{ old('available_points', 0) }}">
                        </div>

                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    @if(($unenrolledCustomers ?? collect())->isNotEmpty())
                        <button type="submit" class="btn text-white" style="background:#5347CE;">Enroll Customer</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>


{{-- =========================================================
     REWARD MANAGEMENT (new)
     ========================================================= --}}

{{-- Create Reward Modal --}}
<div class="modal fade" id="createRewardModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('crm.rewards.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Create Reward</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Reward Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Points Required</label>
                        <input type="number" name="points_required" class="form-control" min="0" value="{{ old('points_required', 0) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon</label>
                        <select name="icon" class="form-select">
                            <option value="bi-gift">Gift</option>
                            <option value="bi-ticket-perforated">Voucher</option>
                            <option value="bi-truck">Shipping</option>
                            <option value="bi-star">Star</option>
                            <option value="bi-cup-hot">Food &amp; Drink</option>
                            <option value="bi-bag-check">Bag</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="available">Available</option>
                            <option value="limited">Limited</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn text-white" style="background:#5347CE;">Create Reward</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Manage Rewards Modal --}}
<div class="modal fade" id="manageRewardsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Rewards</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Reward</th>
                                <th>Points</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($rewards ?? collect()) as $reward)
                            <tr>
                                <td>
                                    <i class="bi {{ $reward->icon ?? 'bi-gift' }} me-1"></i>
                                    {{ $reward->name }}
                                </td>
                                <td>{{ number_format($reward->points_required ?? 0) }}</td>
                                <td>
                                    <span class="badge {{ $reward->statusBadgeClass() }}">
                                        {{ $reward->statusLabel() }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-warning"
                                        onclick="openEditReward({{ $reward->reward_id }})">
                                        Edit
                                    </button>

                                    <form method="POST" action="{{ route('crm.rewards.destroy', $reward) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('Delete this reward? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No rewards yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Per-reward View & Edit Modals --}}
@foreach(($rewards ?? collect()) as $reward)

<div class="modal fade" id="viewRewardModal{{ $reward->reward_id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $reward->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="reward-icon mb-3">
                    <i class="bi {{ $reward->icon ?? 'bi-gift' }}"></i>
                </div>
                <p><strong>Description:</strong> {{ $reward->description ?: '—' }}</p>
                <p><strong>Points Required:</strong> {{ number_format($reward->points_required ?? 0) }}</p>
                <p>
                    <strong>Status:</strong>
                    <span class="badge {{ $reward->statusBadgeClass() }}">{{ $reward->statusLabel() }}</span>
                </p>
                <p class="text-muted mb-0"><small>Created {{ $reward->created_at?->format('M d, Y') }}</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editRewardModal{{ $reward->reward_id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('crm.rewards.update', $reward) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Reward — {{ $reward->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Reward Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $reward->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2">{{ $reward->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Points Required</label>
                        <input type="number" name="points_required" class="form-control" min="0" value="{{ $reward->points_required ?? 0 }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon</label>
                        <select name="icon" class="form-select">
                            @foreach(['bi-gift' => 'Gift', 'bi-ticket-perforated' => 'Voucher', 'bi-truck' => 'Shipping', 'bi-star' => 'Star', 'bi-cup-hot' => 'Food & Drink', 'bi-bag-check' => 'Bag'] as $value => $label)
                                <option value="{{ $value }}" {{ $reward->icon === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            @foreach(['available' => 'Available', 'limited' => 'Limited', 'unavailable' => 'Unavailable'] as $value => $label)
                                <option value="{{ $value }}" {{ $reward->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn text-white" style="background:#5347CE;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Allows "Edit" inside the Manage Rewards modal to open the reward's
// edit modal without both modals fighting over the backdrop.
function openEditReward(rewardId) {
    var manageModalEl = document.getElementById('manageRewardsModal');
    var manageModal = bootstrap.Modal.getInstance(manageModalEl);
    if (manageModal) {
        manageModal.hide();
    }

    var editModalEl = document.getElementById('editRewardModal' + rewardId);
    if (editModalEl) {
        var editModal = bootstrap.Modal.getOrCreateInstance(editModalEl);
        editModal.show();
    }
}

// If validation fails on the Create Reward or Enroll Customer form,
// reopen that modal automatically so the user sees the error messages.
document.addEventListener('DOMContentLoaded', function () {
    @if ($errors->any() && old('name') !== null)
        var createModalEl = document.getElementById('createRewardModal');
        if (createModalEl) {
            bootstrap.Modal.getOrCreateInstance(createModalEl).show();
        }
    @endif

    @if ($errors->any() && old('customer_id') !== null)
        var enrollModalEl = document.getElementById('enrollCustomerModal');
        if (enrollModalEl) {
            bootstrap.Modal.getOrCreateInstance(enrollModalEl).show();
        }
    @endif
});
</script>

@endsection