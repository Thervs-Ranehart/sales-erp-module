@extends('layouts.app')

@section('title', isset($salesOrder) ? 'Edit Sales Order' : 'New Sales Order')
@section('page-title','Sales Order Management')

@section('content')

@include('sales.partials.alerts')

@php
    $isEdit = isset($salesOrder);
    $orderItems = $isEdit ? $salesOrder->items : collect();
    $discountPercent = $isEdit ? $salesOrder->discountPercent() : old('discount', 0);
    $taxPercent = $isEdit ? $salesOrder->taxPercent() : old('tax', 12);
@endphp

<style>
:root{
    --primary:#5347CE;
    --secondary:#887CFD;
    --accent:#4896FE;
    --success:#16C8C7;
    --border:#E5E7EB;
    --light:#EEECFF;
}

.page-header{
    display:flex;
    align-items:center;
    gap:15px;
    margin-bottom:25px;
}

.back-btn{
    width:45px;
    height:45px;
    border-radius:10px;
    background:var(--light);
    color:var(--primary);
    display:flex;
    justify-content:center;
    align-items:center;
    text-decoration:none;
}

.custom-card{
    background:#fff;
    border-radius:15px;
    padding:25px;
    margin-bottom:25px;
    box-shadow:0 5px 20px rgba(0,0,0,.05);
}

.card-title{
    color:var(--primary);
    font-weight:700;
    margin-bottom:20px;
}

.create-btn{
    background:var(--primary);
    color:#fff;
    border:none;
    padding:12px 25px;
    border-radius:8px;
}

.cancel-btn{
    border:1px solid #ddd;
    padding:12px 25px;
    border-radius:8px;
    text-decoration:none;
    color:#666;
}
</style>

<div class="page-header">

    <a href="{{ route('sales.index') }}" class="back-btn">
        <i class="bi bi-arrow-left"></i>
    </a>

    <div>
        <h2>{{ $isEdit ? 'Edit Sales Order' : 'Create Sales Order' }}</h2>
        <p>{{ $isEdit ? 'Update an existing customer sales order.' : 'Create a new customer sales order.' }}</p>
    </div>

</div>

<form action="{{ $isEdit ? route('sales.update', $salesOrder) : route('sales.store') }}" method="POST" id="salesOrderForm">
@csrf
@if ($isEdit)
    @method('PUT')
@endif

<div class="custom-card">

<h5 class="card-title">
Customer Information
</h5>

<div class="row">

<div class="col-md-6 mb-3">
<label>Customer</label>

<select class="form-select" name="customer_id" required>
<option value="">Select Customer</option>
@foreach ($customers as $customer)
<option value="{{ $customer->customer_id }}">
    {{ $customer->first_name }} {{ $customer->last_name }}
</option>
@endforeach
</select>
</div>

<div class="col-md-6 mb-3">

<label>Order Date</label>

<input
type="date"
class="form-control"
name="order_date"
value="{{ old('order_date', isset($salesOrder) ? $salesOrder->order_date?->format('Y-m-d') : now()->format('Y-m-d')) }}"
required>

</div>

</div>

</div>

<div class="custom-card">

<h5 class="card-title">
Products
</h5>

<table class="table" id="productsTable">

<thead>

<tr>

<th>Product</th>
<th>Qty</th>
<th>Price</th>
<th>Total</th>
<th></th>

</tr>

</thead>

<tbody id="productRows">

@php
    $rows = old('product_id')
        ? collect(old('product_id'))->map(fn ($productId, $index) => [
            'product_id' => $productId,
            'qty' => old('qty.'.$index, 1),
            'price' => old('price.'.$index, 0),
        ])
        : ($orderItems->isNotEmpty()
            ? $orderItems->map(fn ($item) => [
                'product_id' => $item->product_id,
                'qty' => $item->quantity,
                'price' => $item->unit_price,
            ])
            : collect([['product_id' => '', 'qty' => 1, 'price' => '']]));
@endphp

@foreach ($rows as $index => $row)
<tr class="product-row">

<td>

<select class="form-select product-select" name="product_id[]" required>
<option value="">Select Product</option>
@foreach ($products as $product)
<option value="{{ $product->product_id }}"
    data-price="{{ $product->unit_price }}"
    @selected($row['product_id'] == $product->product_id)>
    {{ $product->product_name }}
</option>
@endforeach
</select>

</td>

<td>

<input
type="number"
class="form-control qty-input"
name="qty[]"
min="1"
value="{{ $row['qty'] }}"
required>

</td>

<td>

<input
type="number"
step="0.01"
min="0"
class="form-control price-input"
name="price[]"
value="{{ $row['price'] }}"
required>

</td>

<td>

<input
type="text"
class="form-control line-total"
value="₱0.00"
readonly>

</td>

<td>
@if ($loop->count > 1)
<button type="button" class="btn btn-sm btn-outline-danger remove-row-btn">
    <i class="bi bi-trash"></i>
</button>
@endif
</td>

</tr>
@endforeach

</tbody>

</table>

<button type="button" class="btn btn-outline-primary" id="addProductBtn">
<i class="bi bi-plus-circle"></i>
Add Product
</button>

</div>

<div class="custom-card">

<h5 class="card-title">
Pricing
</h5>

<div class="row">

<div class="col-md-4">

<label>Pricing Rule</label>

<select class="form-select" name="pricing_rule_id" id="pricingRuleSelect">

    <option value="">Regular Price</option>

    @foreach ($pricingRules as $rule)

        <option
            value="{{ $rule->pricing_rule_id }}"
            data-discount="{{ $rule->discount_value ?? 0 }}"
            data-tax="{{ $rule->tax_rate ?? 12 }}"
            @selected(old('pricing_rule_id', $isEdit ? $salesOrder->pricing_rule_id : null) == $rule->pricing_rule_id)
        >
            {{ $rule->rule_name }}
        </option>

    @endforeach

</select>
</div>

<div class="col-md-4">

<label>Discount (%)</label>

<input
type="number"
step="0.01"
min="0"
max="100"
class="form-control"
name="discount"
id="discountInput"
value="{{ $discountPercent }}">

</div>

<div class="col-md-4">

<label>Tax (%)</label>

<input
type="number"
step="0.01"
min="0"
max="100"
class="form-control"
name="tax"
id="taxInput"
value="{{ $taxPercent }}">

</div>

</div>

</div>

<div class="custom-card">

<h5 class="card-title">
Order Status
</h5>
<select class="form-select" name="status" required>

    @foreach (['pending', 'processed', 'shipped', 'delivered', 'cancelled'] as $status)

        <option
            value="{{ $status }}"
            @selected(old('status', $isEdit ? $salesOrder->order_status : null) == $status)
        >
            {{ ucfirst($status) }}
        </option>

    @endforeach

</select>

</div>

<div class="custom-card">

<h5 class="card-title">
Order Summary
</h5>

<div class="d-flex justify-content-between">
<span>Subtotal</span>
<strong id="summarySubtotal">₱0.00</strong>
</div>

<div class="d-flex justify-content-between">
<span>Discount</span>
<strong id="summaryDiscount">₱0.00</strong>
</div>

<div class="d-flex justify-content-between">
<span>Tax</span>
<strong id="summaryTax">₱0.00</strong>
</div>

<hr>

<div class="d-flex justify-content-between">

<h4>Total</h4>

<h4 id="summaryTotal">₱0.00</h4>

</div>

</div>

<div class="text-end mb-5">

<a href="{{ route('sales.index') }}" class="cancel-btn">
Cancel
</a>

<button type="submit" class="create-btn">
<i class="bi bi-check-circle"></i>
{{ $isEdit ? 'Update Sales Order' : 'Create Sales Order' }}
</button>

</div>

</form>

<template id="productRowTemplate">
<tr class="product-row">
<td>
<select class="form-select product-select" name="product_id[]" required>
<option value="">Select Product</option>
@foreach ($products as $product)
<option value="{{ $product->product_id }}" data-price="{{ $product->unit_price }}">
    {{ $product->product_name }}
</option>
@endforeach
</select>
</td>
<td>
<input type="number" class="form-control qty-input" name="qty[]" min="1" value="1" required>
</td>
<td>
<input type="number" step="0.01" min="0" class="form-control price-input" name="price[]" required>
</td>
<td>
<input type="text" class="form-control line-total" value="₱0.00" readonly>
</td>
<td>
<button type="button" class="btn btn-sm btn-outline-danger remove-row-btn">
    <i class="bi bi-trash"></i>
</button>
</td>
</tr>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const productRows = document.getElementById('productRows');
    const addProductBtn = document.getElementById('addProductBtn');
    const template = document.getElementById('productRowTemplate');
    const discountInput = document.getElementById('discountInput');
    const taxInput = document.getElementById('taxInput');
    const pricingRuleSelect = document.getElementById('pricingRuleSelect');

    function formatCurrency(value) {
        return '₱' + Number(value || 0).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function recalculateTotals() {
        let subtotal = 0;

        productRows.querySelectorAll('.product-row').forEach(function (row) {
            const qty = parseFloat(row.querySelector('.qty-input')?.value || 0);
            const price = parseFloat(row.querySelector('.price-input')?.value || 0);
            const lineTotal = qty * price;

            subtotal += lineTotal;

            const lineTotalInput = row.querySelector('.line-total');
            if (lineTotalInput) {
                lineTotalInput.value = formatCurrency(lineTotal);
            }
        });

        const discountPercent = parseFloat(discountInput.value || 0);
        const taxPercent = parseFloat(taxInput.value || 0);
        const discountAmount = subtotal * (discountPercent / 100);
        const taxableAmount = Math.max(subtotal - discountAmount, 0);
        const taxAmount = taxableAmount * (taxPercent / 100);
        const total = subtotal - discountAmount + taxAmount;

        document.getElementById('summarySubtotal').textContent = formatCurrency(subtotal);
        document.getElementById('summaryDiscount').textContent = formatCurrency(discountAmount);
        document.getElementById('summaryTax').textContent = formatCurrency(taxAmount);
        document.getElementById('summaryTotal').textContent = formatCurrency(total);
    }

    function bindRowEvents(row) {
        row.querySelector('.product-select')?.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            const priceInput = row.querySelector('.price-input');

            if (selected && selected.dataset.price && priceInput && !priceInput.value) {
                priceInput.value = selected.dataset.price;
            }

            recalculateTotals();
        });

        row.querySelector('.qty-input')?.addEventListener('input', recalculateTotals);
        row.querySelector('.price-input')?.addEventListener('input', recalculateTotals);

        row.querySelector('.remove-row-btn')?.addEventListener('click', function () {
            if (productRows.querySelectorAll('.product-row').length > 1) {
                row.remove();
                recalculateTotals();
            }
        });
    }

    productRows.querySelectorAll('.product-row').forEach(bindRowEvents);

    addProductBtn.addEventListener('click', function () {
        const clone = template.content.cloneNode(true);
        const row = clone.querySelector('.product-row');
        productRows.appendChild(clone);
        bindRowEvents(productRows.lastElementChild);
        recalculateTotals();
    });

    pricingRuleSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];

        if (selected && selected.value) {
            if (selected.dataset.discount) {
                discountInput.value = selected.dataset.discount;
            }

            if (selected.dataset.tax) {
                taxInput.value = selected.dataset.tax;
            }
        }

        recalculateTotals();
    });

    discountInput.addEventListener('input', recalculateTotals);
    taxInput.addEventListener('input', recalculateTotals);

    recalculateTotals();
});
</script>

@endsection