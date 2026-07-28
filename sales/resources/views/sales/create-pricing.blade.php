@extends('layouts.app')

@section('title', 'Create Pricing Rule')

@section('page-title', 'Sales Order Management')

@section('content')

<style>
    .pricing-rule-page{max-width:1040px;margin:0 auto;padding:12px 0 32px;}
    .pricing-rule-hero{display:flex;align-items:center;justify-content:space-between;gap:20px;padding:27px 29px;margin-bottom:22px;border-radius:18px;background:linear-gradient(120deg,#5347CE,#7469e8 60%,#4896FE);color:#fff;box-shadow:0 14px 30px rgba(83,71,206,.2);}
    .pricing-rule-hero__eyebrow{font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#e4e1ff;}
    .pricing-rule-hero h2{margin:5px 0 0;font-weight:750;}.pricing-rule-hero p{margin:7px 0 0;color:#f0efff;}
    .pricing-rule-card{border-radius:18px!important;overflow:hidden;box-shadow:0 8px 24px rgba(31,41,55,.07)!important;}
    .pricing-rule-card .card-body{padding:28px;}.pricing-rule-card .form-label{font-weight:700;color:#374151;}.pricing-rule-card .form-control,.pricing-rule-card .form-select{min-height:45px;border-radius:9px;}
    .pricing-rule-reference{display:flex;gap:11px;align-items:center;margin-bottom:24px;padding:14px 16px;border:1px solid #e3e0ff;border-radius:12px;background:#f7f6ff;color:#5347CE;}
    .pricing-rule-reference i{font-size:21px;}.pricing-rule-reference small{display:block;color:#6B7280;}.pricing-rule-reference strong{font-size:14px;}
    .pricing-rule-card .card-footer{padding:16px 28px!important;background:#fcfcff!important;}
    @media(max-width:767px){.pricing-rule-page{padding:0 4px 24px}.pricing-rule-hero{align-items:flex-start;flex-direction:column;padding:24px}.pricing-rule-card .card-body{padding:20px}.pricing-rule-card .card-footer{padding:14px 20px!important;}}
</style>

<div class="pricing-rule-page">

<form
    action="{{ $pricingRule->exists
        ? route('pricing-rules.update',$pricingRule)
        : route('pricing-rules.store') }}"
    method="POST"
>

    @csrf

    @if($pricingRule->exists)
        @method('PUT')
    @endif

    <div class="pricing-rule-hero">

        <div>

            <span class="pricing-rule-hero__eyebrow"><i class="bi bi-tags me-1"></i> Pricing controls</span>

            <h2 class="page-title">
                {{ $pricingRule->exists ? 'Edit Pricing Rule' : 'Create Pricing Rule' }}
            </h2>

            <p class="page-subtitle">
                {{ $pricingRule->exists ? 'Review the discount, tax, and active dates for this rule.' : 'Set clear discount and tax rules for accurate quotations and sales orders.' }}
            </p>

        </div>

       

    </div>

    <div class="card shadow-sm border-0 pricing-rule-card">

        <div class="card-body">

            <div class="pricing-rule-reference">
                <i class="bi bi-lightning-charge"></i>
                <div><small>Rule availability</small><strong>Active automatically between the selected start and end dates.</strong></div>
            </div>

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="form-label">

                        Rule Name

                    </label>

                    <input
                        type="text"
                        name="rule_name"
                        class="form-control @error('rule_name') is-invalid @enderror"
                        value="{{ old('rule_name',$pricingRule->rule_name) }}"
                        placeholder="Enter Rule Name"
                    >

                    @error('rule_name')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Discount Type

                    </label>

                    <select
                        name="discount_type"
                        class="form-select @error('discount_type') is-invalid @enderror"
                    >

                        <option value="">
                            Select Discount Type
                        </option>

                        <option
                            value="Percentage"
                            @selected(old('discount_type', $pricingRule->discount_type) == 'Percentage')
                        >
                            Percentage
                        </option>

                        <option
                            value="Fixed"
                            @selected(old('discount_type', $pricingRule->discount_type) == 'Fixed')
                        >
                            Fixed Amount
                        </option>

                    </select>

                    @error('discount_type')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>
                                <div class="col-md-6">

                    <label class="form-label">

                        Discount Value

                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="discount_value"
                        class="form-control @error('discount_value') is-invalid @enderror"
                        value="{{ old('discount_value',$pricingRule->discount_value) }}"
                        placeholder="Enter Discount Value"
                    >

                    @error('discount_value')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Tax Rate (%)

                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="tax_rate"
                        class="form-control @error('tax_rate') is-invalid @enderror"
                       value="{{ old('tax_rate', $pricingRule->tax_rate) }}"
                        placeholder="Enter Tax Rate"
                    >

                    @error('tax_rate')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Start Date

                    </label>

                    <input
                        type="date"
                        name="start_date"
                        class="form-control @error('start_date') is-invalid @enderror"
                        value="{{ old('start_date', optional($pricingRule->start_date)->format('Y-m-d')) }}"
                    >

                    @error('start_date')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        End Date

                    </label>

                    <input
                        type="date"
                        name="end_date"
                        class="form-control @error('end_date') is-invalid @enderror"
                        value="{{ old('end_date', optional($pricingRule->end_date)->format('Y-m-d')) }}"
                    >

                    @error('end_date')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>
                            

  <div class="col-md-6">

    <label class="form-label">
        Status
    </label>

    <input
        type="text"
        class="form-control"
        value="Automatically determined by Start Date and End Date"
        readonly
    >

    <small class="text-muted">
        The system will automatically set this pricing rule to Active or Inactive based on the selected dates.
    </small>

</div>
                </div>

            </div>

        </div>

        <div class="card-footer bg-white border-top d-flex flex-column flex-sm-row justify-content-end gap-2 p-3">
            <a href="{{ route('pricing-rules.index') }}" class="btn btn-secondary">
                Cancel
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-1"></i>
                Save Pricing Rule
            </button>
        </div>

    </div>
    </form>
</div>

@endsection
