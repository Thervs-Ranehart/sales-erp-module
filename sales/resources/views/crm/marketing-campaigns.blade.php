@extends('layouts.app')

@section('title', 'Marketing Campaigns')
@section('page-title', 'Marketing Campaigns')

@section('content')
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

<div class="d-flex justify-content-between align-items-start gap-3 mb-4">
    <div><h2 class="fw-bold mb-1">Marketing Campaigns</h2><p class="text-muted mb-0">Target consented customers using the segments and loyalty tiers your team already manages.</p></div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCampaignModal"><i class="bi bi-plus-circle me-1"></i>Create Campaign</button>
</div>

<div class="row g-4">
@forelse($campaigns as $campaign)
    <div class="col-md-6 col-xl-4">
        <div class="card h-100"><div class="card-body p-4">
            <div class="d-flex justify-content-between gap-2 mb-3"><span class="badge text-bg-primary">{{ $campaign->channel }}</span><span class="badge text-bg-light border">{{ $campaign->status }}</span></div>
            <h5 class="fw-bold">{{ $campaign->campaign_name }}</h5>
            <p class="text-muted small">{{ $campaign->objective ?: $campaign->message }}</p>
            <dl class="small mb-0">
                <div class="d-flex justify-content-between py-2 border-bottom"><dt>Segment</dt><dd class="mb-0">{{ $campaign->target_segment ?: 'All consented' }}</dd></div>
                <div class="d-flex justify-content-between py-2 border-bottom"><dt>Loyalty tier</dt><dd class="mb-0">{{ $campaign->target_loyalty_tier ?: 'All tiers' }}</dd></div>
                <div class="d-flex justify-content-between pt-2"><dt>Recipients</dt><dd class="mb-0 fw-bold">{{ $campaign->recipients->count() }}</dd></div>
            </dl>
        </div></div>
    </div>
@empty
    <div class="col-12"><div class="card"><div class="card-body text-center text-muted p-5">No campaigns yet. Create one using your existing CRM segments.</div></div></div>
@endforelse
</div>

<div class="modal fade" id="createCampaignModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
<form method="POST" action="{{ route('crm.campaigns.store') }}">@csrf
<div class="modal-header"><h5 class="modal-title">Create Marketing Campaign</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><div class="row g-3">
    <div class="col-md-6"><label class="form-label">Campaign name</label><input class="form-control" name="campaign_name" required></div>
    <div class="col-md-6"><label class="form-label">Objective</label><input class="form-control" name="objective"></div>
    <div class="col-md-4"><label class="form-label">Channel</label><select class="form-select" name="channel" required>@foreach(['Email','SMS','Phone','In-App'] as $channel)<option>{{ $channel }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Target segment</label><select class="form-select" name="target_segment"><option value="">All consented</option>@foreach($segments as $segment)<option>{{ $segment }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Loyalty tier</label><select class="form-select" name="target_loyalty_tier"><option value="">All tiers</option>@foreach(['Bronze','Silver','Gold','VIP'] as $tier)<option>{{ $tier }}</option>@endforeach</select></div>
    <div class="col-md-6"><label class="form-label">Schedule</label><input type="datetime-local" class="form-control" name="scheduled_at"></div>
    <div class="col-12"><label class="form-label">Message</label><textarea class="form-control" name="message" rows="4" required></textarea><small class="text-muted">Only customers with marketing consent are queued.</small></div>
</div></div>
<div class="modal-footer"><button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Cancel</button><button class="btn btn-primary">Create Campaign</button></div>
</form></div></div></div>
@endsection
