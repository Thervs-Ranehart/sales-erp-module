@extends('layouts.app')

@section('title', 'Create Quotation')
@section('page-title', 'Quotation Management')

@section('content')

@php
    $isEdit = isset($quotation);

    $quotationItems = $isEdit
        ? $quotation->items
        : collect();

    $discountPercent = $isEdit
        ? $quotation->discountPercent()
        : old('discount', 0);

    $taxPercent = $isEdit
        ? $quotation->taxPercent()
        : old('tax', 12);
@endphp

<style>

:root{
    --primary:#5347CE;
    --secondary:#887CFD;
    --accent:#4896FE;
    --success:#16C8C7;
    --white:#FFFFFF;
    --bg:#F8FAFC;
    --text:#1F2937;
    --text2:#6B7280;
    --border:#E5E7EB;
    --light-purple:#EEECFF;
}

*{
    box-sizing:border-box;
}

body{
    margin:0;
    background:var(--bg);
    font-family:"Segoe UI",sans-serif;
    color:var(--text);
}

.page-content{
    padding:30px;
}

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.page-title{
    font-size:28px;
    font-weight:700;
}

.page-subtitle{
    color:var(--text2);
}

.back-btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:11px 18px;
    border:1px solid #DCD8FF;
    border-radius:8px;
    text-decoration:none;
    background:#fff;
    color:var(--primary);
    font-weight:600;
}

.back-btn:hover{
    background:var(--light-purple);
}

.form-card{
    background:#fff;
    padding:25px;
    border-radius:16px;
    box-shadow:0 5px 20px rgba(0,0,0,.06);
    margin-bottom:24px;
}

.section-title{
    color:var(--primary);
    font-size:18px;
    font-weight:700;
    margin-bottom:22px;
    display:flex;
    align-items:center;
    gap:8px;
}

.form-label{
    font-weight:600;
}

.form-control,
.form-select{
    border-radius:8px;
}

.quotation-add-product-btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:7px;
    min-height:40px;
    padding:9px 16px;
    border:1px solid var(--primary);
    border-radius:8px;
    background:#fff;
    color:var(--primary);
    font-weight:600;
    transition:background-color .18s ease,color .18s ease,box-shadow .18s ease,transform .18s ease;
}

.quotation-add-product-btn:hover,
.quotation-add-product-btn:focus-visible{
    background:var(--primary);
    color:#fff;
    box-shadow:0 6px 14px rgba(83,71,206,.22);
    transform:translateY(-1px);
}

.quotation-remove-product-btn{
    width:36px;
    height:36px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:0;
    border:1px solid #EF4444;
    border-radius:8px;
    background:#fff;
    color:#EF4444;
    transition:background-color .18s ease,color .18s ease,box-shadow .18s ease;
}

.quotation-remove-product-btn:hover,
.quotation-remove-product-btn:focus-visible{
    background:#EF4444;
    color:#fff;
    box-shadow:0 5px 12px rgba(239,68,68,.2);
}

.quotation-remove-product-btn:disabled{
    border-color:#D1D5DB;
    background:#F9FAFB;
    color:#9CA3AF;
    box-shadow:none;
    cursor:not-allowed;
}

.quotation-hero{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:24px;
    padding:28px 30px;
    margin-bottom:24px;
    border-radius:18px;
    background:linear-gradient(120deg,#5347CE 0%,#7469e8 58%,#4896FE 100%);
    color:#fff;
    box-shadow:0 14px 32px rgba(83,71,206,.2);
}

.quotation-eyebrow{
    display:inline-flex;
    align-items:center;
    gap:7px;
    margin-bottom:8px;
    font-size:12px;
    font-weight:700;
    letter-spacing:.08em;
    text-transform:uppercase;
    color:#e9e7ff;
}

.quotation-hero .page-title{ color:#fff; margin:0; }
.quotation-hero .page-subtitle{ color:#f0efff; margin:7px 0 0; }
.quotation-hero .back-btn{ border-color:rgba(255,255,255,.45); background:rgba(255,255,255,.14); color:#fff; }
.quotation-hero .back-btn:hover{ background:#fff; color:var(--primary); }

.quotation-reference{
    display:flex;
    align-items:center;
    gap:10px;
    padding:12px 14px;
    background:#f7f6ff;
    border:1px solid #e2defc;
    border-radius:10px;
    color:var(--primary);
}

.quotation-reference__icon{
    width:34px;
    height:34px;
    display:grid;
    place-items:center;
    border-radius:9px;
    background:#e4e0ff;
}

.quotation-reference small{ display:block; color:var(--text2); }
.quotation-reference strong{ font-size:14px; }
.quotation-help{ margin-top:7px; color:var(--text2); font-size:12px; }
.quotation-items-meta{ color:var(--text2); font-size:13px; }

.quote-summary{
    border:1px solid #e6e3ff;
    border-radius:14px;
    padding:16px 18px;
    background:linear-gradient(150deg,#fbfaff,#f3f1ff);
}

.quote-summary .table{ margin-bottom:0; }
.quote-summary .grand-total-row th,
.quote-summary .grand-total-row td{ border-top:1px solid #dcd8ff; padding-top:16px; }
.quote-validity-note{
    display:flex;
    gap:8px;
    margin-top:14px;
    padding-top:14px;
    border-top:1px solid #e6e3ff;
    color:var(--text2);
    font-size:12px;
}

.quotation-form-actions{
    position:sticky;
    bottom:12px;
    z-index:5;
    padding:14px;
    border:1px solid #e5e7eb;
    border-radius:12px;
    background:rgba(255,255,255,.95);
    box-shadow:0 8px 24px rgba(31,41,55,.1);
}

@media (max-width:767px){
    .page-content{ padding:18px; }
    .quotation-hero{ align-items:flex-start; flex-direction:column; padding:23px; }
    .quotation-hero .back-btn{ width:100%; justify-content:center; }
}

</style>

<div class="page-content">

    <div class="quotation-hero">

        <div>

            <span class="quotation-eyebrow"><i class="bi bi-receipt-cutoff"></i> Sales quotation workspace</span>

            <h2 class="page-title">

                {{ $isEdit ? 'Edit Quotation' : 'Create New Quotation' }}

            </h2>

            <p class="page-subtitle">

                {{ $isEdit ? 'Review pricing, items, and validity before saving changes.' : 'Prepare a customer-ready offer with clear pricing and validity.' }}

            </p>

        </div>

        <a
            href="{{ route('quotations.index') }}"
            class="back-btn"
        >

            <i class="bi bi-arrow-left"></i>

            Back to Quotations

        </a>

    </div>

<form
    action="{{ $isEdit ? route('quotations.update',$quotation) : route('quotations.store') }}"
    method="POST"
>

    @csrf

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Quotation could not be saved.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($isEdit)
        @method('PUT')
    @endif

    <div class="form-card">

        <h5 class="section-title">

            <i class="bi bi-file-earmark-text"></i>

            Quotation Information

        </h5>

        <div class="row g-4">
            <div class="col-12">
                <div class="quotation-reference">
                    <span class="quotation-reference__icon"><i class="bi bi-hash"></i></span>
                    <div>
                        <small>Quotation reference</small>
                        <strong>{{ $isEdit ? $quotation->quotation_number : 'Generated automatically when saved' }}</strong>
                    </div>
                </div>
            </div>

            <div class="col-md-6">

                <label class="form-label">
                    Customer
                </label>

                <select
                    class="form-select"
                    name="customer_id"
                    id="quotationCustomer"
                    required
                >

                    <option value="">
                        Select Customer
                    </option>

                    @foreach($customers as $customer)

                        <option
                            value="{{ $customer->customer_id }}"
                            @selected(old('customer_id', optional($quotation)->customer_id) == $customer->customer_id)
                        >

                            {{ $customer->full_name }}

                        </option>

                    @endforeach

                </select>

                <div class="quotation-help">Choose the customer receiving this quotation.</div>

            </div>

            <div class="col-md-3">

                <label class="form-label">
                    Quotation Date
                </label>

                <input
                    type="date"
                    class="form-control"
                    name="quotation_date"
                    id="quotationDate"
                    value="{{ old('quotation_date', optional($quotation)->quotation_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                    required
                >

                <div class="quotation-help">The price offer stays valid through this date.</div>

            </div>

            <div class="col-md-3">

                <label class="form-label">
                    Valid Until
                </label>

                <input
                    type="date"
                    class="form-control"
                    name="valid_until"
                    id="validUntil"
                    onchange="updateQuotationMeta()"
                    value="{{ old('valid_until', optional($quotation)->valid_until?->format('Y-m-d') ?? now()->addDays(30)->format('Y-m-d')) }}"
                    required
                >

            </div>

            <div class="col-md-6">

                <label class="form-label">
                    Status
                </label>

                <select
                    class="form-select"
                    name="status"
                    required
                >

                    @php
                        $status = old('status', optional($quotation)->quotation_status ?? 'draft');
                    @endphp

                    <option value="draft" @selected($status=='draft')>
                        Draft
                    </option>

                    <option value="sent" @selected($status=='sent')>
                        Sent
                    </option>

                    <option value="accepted" @selected($status=='accepted')>
                        Accepted
                    </option>

                    <option value="rejected" @selected($status=='rejected')>
                        Rejected
                    </option>

                    <option value="expired" @selected($status=='expired')>
                        Expired
                    </option>

                </select>

            </div>

        </div>

    </div>

    <!-- PRODUCTS -->

    <div class="form-card">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h5 class="section-title mb-1">

                <i class="bi bi-box-seam"></i>

                Products / Items

                </h5>
                <div class="quotation-items-meta"><span id="itemCount">0</span> item(s) included in this quotation</div>
            </div>

            <select class="form-select w-auto" id="productCategoryFilter" aria-label="Filter products by category">
                <option value="">All categories</option>
                @foreach($productCategories as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>

            <button
                type="button"
                class="quotation-add-product-btn"
                onclick="addProduct()"
            >

                <i class="bi bi-plus-circle"></i>

                Add Product

            </button>

        </div>

        <div class="table-responsive">

            <table class="table product-table">

                <thead>

                    <tr>

                        <th>Product</th>

                        <th width="120">
                            Quantity
                        </th>

                        <th width="170">
                            Unit Price
                        </th>

                        <th width="170">
                            Total
                        </th>

                        <th width="60"></th>

                    </tr>

                </thead>

                <tbody id="productRows">
                    @if($quotationItems->isEmpty())

<tr>

    <td>

        <select
            class="form-select product-select"
            name="product_id[]"
            onchange="updatePrice(this)"
            required
        >

            <option value="">
                Select Product
            </option>

            @foreach($products as $product)

                <option
                    value="{{ $product->product_id }}"
                    data-price="{{ $product->unit_price }}"
                    data-category="{{ $product->category }}"
                >

                    {{ $product->product_name }}

                </option>

            @endforeach

        </select>

    </td>

    <td>

        <input
            type="number"
            class="form-control quantity"
            name="qty[]"
            value="1"
            min="1"
            oninput="calculateTotals()"
            required
        >

    </td>

    <td>

        <input
            type="number"
            class="form-control price"
            name="price[]"
            value="0"
            min="0"
            step="0.01"
            oninput="calculateTotals()"
            required
        >

    </td>

    <td>

        <input
            type="text"
            class="form-control row-total"
            value="₱0.00"
            readonly
        >

    </td>

    <td>

        <button
            type="button"
            class="quotation-remove-product-btn"
            onclick="removeProduct(this)"
            aria-label="Delete product row"
            title="Delete product"
        >

            <i class="bi bi-trash"></i>

        </button>

    </td>

</tr>

@else

@foreach($quotationItems as $item)

<tr>

    <td>

        <select
            class="form-select product-select"
            name="product_id[]"
            onchange="updatePrice(this)"
            required
        >

            <option value="">
                Select Product
            </option>

            @foreach($products as $product)

                <option
                    value="{{ $product->product_id }}"
                    data-price="{{ $product->unit_price }}"
                    data-category="{{ $product->category }}"
                    @selected($item->product_id == $product->product_id)
                >

                    {{ $product->product_name }}

                </option>

            @endforeach

        </select>

    </td>

    <td>

        <input
            type="number"
            class="form-control quantity"
            name="qty[]"
            value="{{ $item->quantity }}"
            min="1"
            oninput="calculateTotals()"
            required
        >

    </td>

    <td>

        <input
            type="number"
            class="form-control price"
            name="price[]"
            value="{{ $item->unit_price }}"
            min="0"
            step="0.01"
            oninput="calculateTotals()"
            required
        >

    </td>

    <td>

        <input
            type="text"
            class="form-control row-total"
            value="₱{{ number_format($item->subtotal,2) }}"
            readonly
        >

    </td>

    <td>

        <button
            type="button"
            class="quotation-remove-product-btn"
            onclick="removeProduct(this)"
            aria-label="Delete product row"
            title="Delete product"
        >

            <i class="bi bi-trash"></i>

        </button>

    </td>

</tr>

@endforeach

@endif

</tbody>

</table>

</div>

</div>
<div class="form-card">

    <div class="row">

        <div class="col-lg-6">

            <label class="form-label">
                Notes
            </label>

            <textarea
                class="form-control"
                rows="8"
                name="remarks"
                placeholder="Optional notes..."
            >{{ old('remarks') }}</textarea>

        </div>

        <div class="col-lg-6">

            <div class="quote-summary">

            <table class="table table-borderless">

                <tr>

                    <th width="50%">
                        Subtotal
                    </th>

                    <td class="text-end">

                        ₱<span id="subtotal">
                            0.00
                        </span>

                    </td>

                </tr>

                <tr>

                    <th>

                        <span id="discountLabel">Discount %</span>

                    </th>

                    <td>

                        <input
                            type="number"
                            class="form-control"
                            id="discount"
                            name="discount"
                            value="{{ old('discount',$discountPercent) }}"
                            min="0"
                            oninput="calculateTotals()"
                        >

                    </td>

                </tr>

                <tr>

                    <th>
                        Applied Discount
                    </th>

                    <td class="text-end">
                        &#8369;<span id="discountAmount">0.00</span>
                    </td>

                </tr>

                <tr>

                    <th>

                        Tax %

                    </th>

                    <td>

                        <input
                            type="number"
                            class="form-control"
                            id="tax"
                            name="tax"
                            value="{{ old('tax',$taxPercent) }}"
                            min="0"
                            max="100"
                            oninput="calculateTotals()"
                        >

                    </td>

                </tr>

                <tr class="grand-total-row">

                    <th class="fs-5">

                        Grand Total

                    </th>

                    <td class="text-end fs-4 fw-bold text-primary">

                        ₱<span id="grandTotal">
                            0.00
                        </span>

                    </td>

                </tr>

            </table>

            <div class="quote-validity-note">
                <i class="bi bi-clock-history"></i>
                <span id="validityMessage">Set a valid-until date to show the quotation validity period.</span>
            </div>

            </div>

        </div>

    </div>

</div>

<div class="quotation-form-actions d-flex flex-wrap justify-content-between align-items-center gap-2">

    <span class="small text-muted"><i class="bi bi-shield-check me-1"></i>Totals are calculated from the quoted items.</span>

    <div class="d-flex gap-2">

    <a
        href="{{ route('quotations.index') }}"
        class="btn btn-outline-secondary"
    >

        Cancel

    </a>

    <button
        type="submit"
        class="btn btn-primary"
    >

        <i class="bi bi-check-circle me-1"></i>

        {{ $isEdit ? 'Update Quotation' : 'Save Quotation' }}

    </button>

    </div>

</div>

</form>
<script>

function addProduct()
{
    let row = `
<tr>

<td>

<select
class="form-select product-select"
name="product_id[]"
onchange="updatePrice(this)"
required>

<option value="">Select Product</option>

@foreach($products as $product)

<option
value="{{ $product->product_id }}"
data-price="{{ $product->unit_price }}"
data-category="{{ $product->category }}">

{{ $product->product_name }}

</option>

@endforeach

</select>

</td>

<td>

<input
type="number"
name="qty[]"
class="form-control quantity"
value="1"
min="1"
oninput="calculateTotals()"
required>

</td>

<td>

<input
type="number"
name="price[]"
class="form-control price"
value="0"
step="0.01"
min="0"
oninput="calculateTotals()"
required>

</td>

<td>

<input
type="text"
class="form-control row-total"
value="₱0.00"
readonly>

</td>

<td>

<button
type="button"
class="quotation-remove-product-btn"
onclick="removeProduct(this)"
aria-label="Delete product row"
title="Delete product">

<i class="bi bi-trash"></i>

</button>

</td>

</tr>
`;

document
.getElementById('productRows')
.insertAdjacentHTML('beforeend',row);

updateRemoveButtons();
updateQuotationMeta();
}

function removeProduct(button)
{
    let tbody=document.getElementById('productRows');

    if(tbody.rows.length>1)
    {
        button.closest('tr').remove();
        calculateTotals();
        updateQuotationMeta();
    }

    updateRemoveButtons();
}

function updateRemoveButtons()
{
    let tbody=document.getElementById('productRows');
    let isOnlyRow=tbody.rows.length<=1;

    tbody
        .querySelectorAll('.quotation-remove-product-btn')
        .forEach(function(button){
            button.disabled=isOnlyRow;
            button.setAttribute('aria-disabled',isOnlyRow ? 'true' : 'false');
        });
}

function updatePrice(select)
{
    let option=select.options[select.selectedIndex];

    let price=option.dataset.price ?? 0;

    let row=select.closest('tr');

    row.querySelector('.price').value=price;

    calculateTotals();
}

function applyPricingRule(select)
{
    let selectedRule=select.options[select.selectedIndex];
    let discountInput=document.getElementById('discount');
    let discountLabel=document.getElementById('discountLabel');
    let discountType=(selectedRule.dataset.discountType || '').trim().toLowerCase();
    let discountValue=parseFloat(selectedRule.dataset.discountValue)||0;
    let taxRate=parseFloat(selectedRule.dataset.taxRate);
    let helpText=document.getElementById('pricingRuleDiscountHelp');

    discountInput.readOnly=discountType !== '';

    if (discountType === 'percentage') {
        discountInput.value=discountValue;
        discountInput.max=100;
        discountLabel.textContent='Discount %';
        helpText.textContent='This rule applies a '+discountValue+'% discount.';
    } else if (discountType === 'fixed') {
        discountInput.value=discountValue.toFixed(2);
        discountInput.removeAttribute('max');
        discountLabel.textContent='Discount (₱)';
        helpText.textContent='This rule applies a fixed discount of ₱'+discountValue.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})+'.';
    } else {
        discountInput.readOnly=false;
        discountInput.max=100;
        discountLabel.textContent='Discount %';
        helpText.textContent='Select a pricing rule to apply its discount.';
    }

    if (discountType && !Number.isNaN(taxRate)) {
        document.getElementById('tax').value=taxRate;
    }

    calculateTotals();
}

function calculateTotals()
{
    let subtotal=0;

    document.querySelectorAll('#productRows tr').forEach(function(row){

        let qty=parseFloat(
            row.querySelector('.quantity').value
        )||0;

        let price=parseFloat(
            row.querySelector('.price').value
        )||0;

        let total=qty*price;

        subtotal+=total;

        row.querySelector('.row-total').value=
            "₱"+total.toLocaleString(undefined,{
                minimumFractionDigits:2,
                maximumFractionDigits:2
            });

    });

    document.getElementById('subtotal').innerHTML=
        subtotal.toLocaleString(undefined,{
            minimumFractionDigits:2,
            maximumFractionDigits:2
        });

    let discountInput=document.getElementById('discount');
    let discount=parseFloat(discountInput.value)||0;
    let discountAmount=subtotal*(discount/100);
    document.getElementById('discountAmount').innerHTML=
        discountAmount.toLocaleString(undefined,{
            minimumFractionDigits:2,
            maximumFractionDigits:2
        });

    let tax=parseFloat(
        document.getElementById('tax').value
    )||0;

    let taxable=subtotal-discountAmount;

    let taxAmount=taxable*(tax/100);

    let grandTotal=taxable+taxAmount;

    document.getElementById('grandTotal').innerHTML=
        grandTotal.toLocaleString(undefined,{
            minimumFractionDigits:2,
            maximumFractionDigits:2
        });
}

function updateQuotationMeta()
{
    const itemCount=document.getElementById('itemCount');
    const itemRows=document.querySelectorAll('#productRows tr').length;
    itemCount.textContent=itemRows;

    const quotationDate=document.getElementById('quotationDate');
    const validUntil=document.getElementById('validUntil');
    const validityMessage=document.getElementById('validityMessage');

    if (!quotationDate.value || !validUntil.value) {
        validityMessage.textContent='Set a valid-until date to show the quotation validity period.';
        return;
    }

    const start=new Date(quotationDate.value+'T00:00:00');
    const end=new Date(validUntil.value+'T00:00:00');
    const days=Math.round((end-start)/86400000);

    validityMessage.textContent=days >= 0
        ? `This quotation is valid for ${days === 0 ? 'today only' : days+' day'+(days === 1 ? '' : 's')+'.'}`
        : 'The valid-until date must be on or after the quotation date.';
}

document.addEventListener('DOMContentLoaded',function(){

    const productCategoryFilter=document.getElementById('productCategoryFilter');
    const filterProductsByCategory=function(){
        const category=productCategoryFilter.value;
        document.querySelectorAll('.product-select option[data-category]').forEach(function(option){
            option.hidden=category !== '' && option.dataset.category !== category;
        });
    };

    updateRemoveButtons();

    document.querySelectorAll('.product-select').forEach(function(select){

        if(select.value!="")
        {
            updatePrice(select);
        }

    });

    calculateTotals();
    updateQuotationMeta();

    document.getElementById('quotationDate').addEventListener('change',updateQuotationMeta);
    productCategoryFilter.addEventListener('change',filterProductsByCategory);
    filterProductsByCategory();

});

</script>
@endsection
