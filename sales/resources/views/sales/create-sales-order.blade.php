@extends('layouts.app')

@section('title', isset($salesOrder) ? 'Edit Sales Order' : 'New Sales Order')
@section('page-title','Sales Order Management')

@section('content')

@include('sales.partials.alerts')

@php
$isEdit = isset($salesOrder);
$orderItems = $isEdit ? $salesOrder->items : collect();

$selectedRule = $isEdit ? $salesOrder->pricingRule : null;

if ($isEdit && $selectedRule) {
    $discountValue = $selectedRule->discount_value;
    $discountType = strtolower($selectedRule->discount_type);
} else {
    $discountValue = old('discount', 0);
    $discountType = 'percentage';
}

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

.sales-order-hero{display:flex;align-items:center;justify-content:space-between;gap:22px;padding:27px 29px;margin-bottom:24px;border-radius:18px;background:linear-gradient(120deg,#5347CE,#7469e8 60%,#4896FE);color:#fff;box-shadow:0 14px 30px rgba(83,71,206,.2);}
.sales-order-hero .back-btn{flex:0 0 auto;background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.3);}.sales-order-hero .back-btn:hover{background:#fff;color:var(--primary);}
.sales-order-eyebrow{font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#e5e2ff;}.sales-order-hero h2{margin:5px 0 0;color:#fff;font-weight:750;}.sales-order-hero p{margin:7px 0 0;color:#f0efff;}
.custom-card{border:1px solid #e9e8f0;box-shadow:0 7px 20px rgba(31,41,55,.045);}.custom-card .form-control,.custom-card .form-select{min-height:44px;border-radius:9px;}.card-title{display:flex;align-items:center;gap:8px;color:#312b8d;}.card-title i{display:grid;place-items:center;width:30px;height:30px;border-radius:8px;background:#eeecff;color:#5347ce;font-size:14px;}
.order-reference{display:flex;gap:10px;align-items:center;margin-bottom:20px;padding:13px 15px;border:1px solid #e3e0ff;border-radius:11px;background:#f8f7ff;color:#5347ce;}.order-reference i{font-size:20px;}.order-reference small{display:block;color:#6b7280;}.order-reference strong{font-size:14px;}
.products-toolbar{display:flex;align-items:center;justify-content:space-between;gap:15px;margin-bottom:18px;}.products-toolbar .card-title{margin:0;}.order-items-count{font-size:12px;color:#6b7280;}.order-summary-card{background:linear-gradient(150deg,#fbfaff,#f3f1ff);border-color:#e2dfff;}.order-summary-card hr{border-color:#ddd9fb;}.order-summary-card h4:last-child{color:var(--primary);}.order-actions-bar{position:sticky;bottom:12px;z-index:5;display:flex;align-items:center;justify-content:space-between;gap:14px;padding:14px;border:1px solid #e5e7eb;border-radius:12px;background:rgba(255,255,255,.96);box-shadow:0 8px 24px rgba(31,41,55,.1);}.create-btn{box-shadow:0 7px 15px rgba(83,71,206,.22);}.cancel-btn{background:#fff;}
@media(max-width:767px){.sales-order-hero{align-items:flex-start;flex-direction:column;padding:24px}.sales-order-hero .back-btn{width:100%;}.products-toolbar,.order-actions-bar{align-items:stretch;flex-direction:column}.order-actions-bar .text-end{display:flex;gap:8px;}.order-actions-bar a,.order-actions-bar button{flex:1;text-align:center;}}
</style>

<div class="sales-order-hero">

    <div>
        <span class="sales-order-eyebrow"><i class="bi bi-cart-check me-1"></i> Sales order workspace</span>
        <h2>{{ $isEdit ? 'Edit Sales Order' : 'Create Sales Order' }}</h2>
        <p>{{ $isEdit ? 'Review the customer, items, and pricing before saving changes.' : 'Create a confirmed order from the customer’s selected products.' }}</p>
    </div>

    <a href="{{ route('sales.index') }}" class="back-btn" aria-label="Back to sales orders">
        <i class="bi bi-arrow-left"></i>
    </a>

</div>

<form action="{{ $isEdit ? route('sales.update', $salesOrder) : route('sales.store') }}" method="POST" id="salesOrderForm">
@csrf
@if ($isEdit)
    @method('PUT')
@endif

<div class="custom-card">

<h5 class="card-title">
<i class="bi bi-person"></i>
Customer Information
</h5>

<div class="order-reference">
    <i class="bi bi-hash"></i>
    <div><small>Sales order reference</small><strong>{{ $isEdit ? $salesOrder->order_number : 'Generated automatically when the order is created' }}</strong></div>
</div>

<div class="row">

<div class="col-md-6 mb-3">
<label>Customer</label>

<select class="form-select" name="customer_id" id="customerSelect" required>
<option value="">Select Customer</option>
@foreach ($customers as $customer)
<option
    value="{{ $customer->customer_id }}"
    data-loyalty-points="{{ $customer->loyaltyProgram?->available_points ?? 0 }}"
    @selected(old('customer_id', $isEdit ? $salesOrder->customer_id : null) == $customer->customer_id)
>
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

@if (! $isEdit)
<div class="custom-card">

<h5 class="card-title">
<i class="bi bi-gift"></i>
Loyalty Reward
</h5>

<div class="row align-items-end">
    <div class="col-md-8">
        <label for="rewardSelect">Apply an available reward</label>
        <select class="form-select" name="reward_id" id="rewardSelect">
            <option value="">No loyalty reward</option>
            @foreach ($saleRewards as $reward)
                <option value="{{ $reward->reward_id }}"
                    data-points="{{ $reward->points_required }}"
                    data-discount-type="{{ strtolower($reward->discount_type) }}"
                    data-discount-value="{{ $reward->discount_value }}">
                    {{ $reward->name }} — {{ number_format($reward->points_required) }} points
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <div id="rewardAvailability" class="form-text">Select a customer to view eligible rewards.</div>
    </div>
</div>

</div>
@endif

<div class="custom-card">

<div class="products-toolbar">
    <div>
        <h5 class="card-title"><i class="bi bi-box-seam"></i> Products</h5>
        <span class="order-items-count"><span id="orderItemCount">0</span> item(s) in this order</span>
    </div>

    <div class="d-flex align-items-center gap-2">
        <select class="form-select" id="productCategoryFilter" aria-label="Filter products by category">
            <option value="">All categories</option>
            @foreach ($productCategories as $category)
                <option value="{{ $category }}">{{ $category }}</option>
            @endforeach
        </select>
        <button type="button" class="btn btn-outline-primary" id="addProductBtn">
            <i class="bi bi-plus-circle"></i> Add Product
        </button>
    </div>
</div>

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
    data-category="{{ $product->category }}"
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

</div>

<div class="custom-card">

<h5 class="card-title">
<i class="bi bi-percent"></i>
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
    data-discount-type="{{ $rule->discount_type }}"
    data-tax="{{ $rule->tax_rate ?? 12 }}"
    @selected(old('pricing_rule_id', $isEdit ? $salesOrder->pricing_rule_id : null) == $rule->pricing_rule_id)
>
    {{ $rule->rule_name }}
</option>

    @endforeach

</select>
</div>

<div class="col-md-4">

<label id="discountLabel">Discount (%)</label>

<input
type="number"
step="0.01"
min="0"
class="form-control"
name="discount"
id="discountInput"
value="{{ $discountValue }}">

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
<i class="bi bi-arrow-repeat"></i>
Order and Payment Status
</h5>

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label" for="orderStatus">Order Status</label>
        <select class="form-select" name="status" id="orderStatus" required>
            @foreach (['pending', 'processed', 'shipped', 'delivered', 'cancelled'] as $status)
                <option value="{{ $status }}" @selected(old('status', $isEdit ? $salesOrder->order_status : 'pending') == $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label" for="paymentMethod">Payment Method</label>
        <select class="form-select" name="payment_method" id="paymentMethod" required>
            @foreach (['Cash', 'Card', 'Bank Transfer', 'E-Wallet'] as $method)
                <option value="{{ $method }}" @selected(old('payment_method', $isEdit ? $salesOrder->payment_method : 'Cash') == $method)>
                    {{ $method }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label" for="paymentStatus">Payment Status</label>
        <select class="form-select" name="payment_status" id="paymentStatus" required>
            @foreach (['Pending', 'Paid'] as $paymentStatus)
                <option value="{{ $paymentStatus }}" @selected(old('payment_status', $isEdit ? $salesOrder->payment_status : 'Pending') == $paymentStatus)>
                    {{ $paymentStatus }}
                </option>
            @endforeach
        </select>
        <small class="form-text text-muted">A processed order automatically creates an invoice using this payment status.</small>
    </div>
</div>

</div>

<div class="custom-card order-summary-card">

<h5 class="card-title">
<i class="bi bi-calculator"></i>
Order Summary
</h5>

<div class="d-flex justify-content-between">
<span>Subtotal</span>
<strong id="summarySubtotal">₱0.00</strong>
</div>

<div class="d-flex justify-content-between">
<span>Pricing Discount</span>
<strong id="summaryDiscount" class="text-danger">-₱0.00</strong>
</div>

<div class="d-flex justify-content-between">
<span id="summaryRewardLabel">Loyalty Reward</span>
<strong id="summaryRewardDiscount" class="text-danger">-₱0.00</strong>
</div>

<div class="d-flex justify-content-between">
<span>Reward Points Used</span>
<strong id="summaryRewardPoints">0</strong>
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

<div class="order-actions-bar mb-5">

<span class="small text-muted"><i class="bi bi-shield-check me-1"></i>Totals update automatically as you change the order.</span>

<div class="text-end">

<a href="{{ route('sales.index') }}" class="cancel-btn">
Cancel
</a>

<button type="submit" class="create-btn">
<i class="bi bi-check-circle"></i>
{{ $isEdit ? 'Update Sales Order' : 'Create Sales Order' }}
</button>

</div>

</div>

</form>

<template id="productRowTemplate">
<tr class="product-row">
<td>
<select class="form-select product-select" name="product_id[]" required>
<option value="">Select Product</option>
@foreach ($products as $product)
<option value="{{ $product->product_id }}" data-price="{{ $product->unit_price }}" data-category="{{ $product->category }}">
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
    const discountLabel = document.getElementById('discountLabel');
    const productCategoryFilter = document.getElementById('productCategoryFilter');
    const customerSelect = document.getElementById('customerSelect');
    const rewardSelect = document.getElementById('rewardSelect');
    const rewardAvailability = document.getElementById('rewardAvailability');

let discountType = "{{ strtolower($discountType) }}";

    function formatCurrency(value) {
        return '₱' + Number(value || 0).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function recalculateTotals() {
        let subtotal = 0;
        document.getElementById('orderItemCount').textContent = productRows.querySelectorAll('.product-row').length;

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

        const discountValue = parseFloat(discountInput.value || 0);
const taxPercent = parseFloat(taxInput.value || 0);

let discountAmount = 0;

if (discountType.toLowerCase() === "fixed") {

    discountAmount = discountValue;

} else {

    discountAmount = subtotal * (discountValue / 100);

}

discountAmount = Math.min(discountAmount, subtotal);

let rewardDiscount = 0;
const selectedReward = rewardSelect?.options[rewardSelect.selectedIndex];
if (selectedReward?.value && !selectedReward.disabled) {
    rewardDiscount = selectedReward.dataset.discountType === 'fixed'
        ? parseFloat(selectedReward.dataset.discountValue || 0)
        : (subtotal - discountAmount) * (parseFloat(selectedReward.dataset.discountValue || 0) / 100);
}

discountAmount = Math.min(discountAmount + rewardDiscount, subtotal);
const taxableAmount = subtotal - discountAmount;

const taxAmount = taxableAmount * (taxPercent / 100);

const total = taxableAmount + taxAmount;

        document.getElementById('summarySubtotal').textContent = formatCurrency(subtotal);
        document.getElementById('summaryDiscount').textContent = '-' + formatCurrency(discountAmount - rewardDiscount);
        document.getElementById('summaryRewardDiscount').textContent = '-' + formatCurrency(rewardDiscount);
        document.getElementById('summaryRewardLabel').textContent = selectedReward?.value
            ? `Loyalty Reward — ${selectedReward.text.split(' — ')[0]}`
            : 'Loyalty Reward';
        document.getElementById('summaryRewardPoints').textContent = selectedReward?.value && !selectedReward.disabled
            ? Number(selectedReward.dataset.points || 0).toLocaleString()
            : '0';
        document.getElementById('summaryTax').textContent = formatCurrency(taxAmount);
        document.getElementById('summaryTotal').textContent = formatCurrency(total);
    }

    function filterProductsByCategory() {
        const category = productCategoryFilter.value;

        document.querySelectorAll('.product-select option[data-category]').forEach(function (option) {
            option.hidden = category !== '' && option.dataset.category !== category;
        });
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

    productCategoryFilter.addEventListener('change', filterProductsByCategory);
    filterProductsByCategory();

   pricingRuleSelect.addEventListener('change', function () {

    const selected = this.options[this.selectedIndex];

    if (selected.value) {

        discountInput.value = selected.dataset.discount || 0;
        taxInput.value = selected.dataset.tax || 12;

        discountType = selected.dataset.discountType || "percentage";

    } else {

        discountInput.value = 0;
        taxInput.value = 12;
        discountType = "percentage";

    }

   discountLabel.textContent =
    discountType.toLowerCase() === "fixed"
        ? "Discount (₱)"
        : "Discount (%)";
    recalculateTotals();

});

  discountInput.addEventListener('input', recalculateTotals);
taxInput.addEventListener('input', recalculateTotals);

function refreshRewardOptions() {
    if (!rewardSelect) return;

    const points = parseInt(customerSelect?.options[customerSelect.selectedIndex]?.dataset.loyaltyPoints || '0', 10);
    let eligible = 0;
    [...rewardSelect.options].forEach(function (option) {
        if (!option.value) return;
        const canUse = Boolean(customerSelect?.value) && points >= parseInt(option.dataset.points || '0', 10);
        option.disabled = !canUse;
        option.hidden = !canUse;
        if (canUse) eligible++;
    });
    if (rewardSelect.selectedOptions[0]?.disabled) rewardSelect.value = '';
    rewardAvailability.textContent = customerSelect?.value
        ? `${points.toLocaleString()} loyalty points available. ${eligible} reward(s) eligible.`
        : 'Select a customer to view eligible rewards.';
    recalculateTotals();
}

customerSelect?.addEventListener('change', refreshRewardOptions);
rewardSelect?.addEventListener('change', recalculateTotals);
refreshRewardOptions();


recalculateTotals();
});
</script>

@endsection
