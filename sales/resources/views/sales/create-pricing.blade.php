@extends('layouts.app')

@section('title', 'Create Pricing Rule')

@section('page-title', 'Sales Order Management')

@section('content')


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

    <div class="page-header">

        <div>

            <h2 class="page-title">
                {{ $pricingRule->exists ? 'Edit Pricing Rule' : 'Create Pricing Rule' }}
            </h2>

            <p class="page-subtitle">
                Configure a new pricing rule.
            </p>

        </div>

       

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

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

@endsection
