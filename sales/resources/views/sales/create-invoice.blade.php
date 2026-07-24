@extends('layouts.app')

@section('title', $isEdit ? 'Edit Invoice' : 'Create Invoice')
@section('page-title', 'Invoice')

@section('content')

@php
$formAction = $isEdit
    ? route('invoices.update', $invoice)
    : route('invoices.store');
@endphp

<form action="{{ $formAction }}" method="POST">

    @csrf

    @if($isEdit)
        @method('PUT')
    @endif

<style>

:root{
    --primary:#5347CE;
    --secondary:#887CFD;
    --accent:#4896FE;
    --success:#16C8C7;
    --border:#E5E7EB;
    --light-purple:#EEECFF;
    --text:#1F2937;
    --text2:#6B7280;
}

.page-header{
    display:flex;
    align-items:center;
    gap:15px;
    margin-bottom:25px;
}

.page-title{
    font-size:28px;
    font-weight:700;
    margin:0;
}

.page-subtitle{
    color:#6B7280;
}

.back-btn{
    width:44px;
    height:44px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:var(--light-purple);
    border-radius:10px;
    color:var(--primary);
    text-decoration:none;
}

.custom-card{
    border:none;
    border-radius:16px;
    padding:25px;
    box-shadow:0 5px 20px rgba(0,0,0,.06);
    background:#fff;
    margin-bottom:24px;
}

.card-title-custom{
    color:var(--primary);
    font-weight:700;
    margin-bottom:20px;
}

.form-control,
.form-select{
    min-height:46px;
    border-radius:10px;
}

.invoice-table thead th{
    background:#EEECFF;
    color:var(--primary);
}

.add-item-btn{
    background:var(--secondary);
    color:#fff;
    border:none;
    padding:10px 18px;
    border-radius:8px;
}

.delete-item-btn{
    width:40px;
    height:40px;
    border:none;
    border-radius:8px;
    background:var(--primary);
    color:#fff;
}

.summary-row{
    display:flex;
    justify-content:space-between;
    margin-bottom:12px;
}

.summary-total{
    display:flex;
    justify-content:space-between;
    border-top:1px solid #ddd;
    padding-top:15px;
}

.cancel-btn{
    border:1px solid #ddd;
    padding:11px 22px;
    border-radius:8px;
    text-decoration:none;
    color:#6B7280;
}

.draft-btn{
    background:var(--secondary);
    color:#fff;
    border:none;
    padding:11px 22px;
    border-radius:8px;
}

.create-btn{
    background:var(--primary);
    color:#fff;
    border:none;
    padding:11px 22px;
    border-radius:8px;
}

</style>

<div class="page-header">

    <a href="{{ route('invoices.index') }}" class="back-btn">
        <i class="bi bi-arrow-left"></i>
    </a>

    <div>

        <h2 class="page-title">
            {{ $isEdit ? 'Edit Invoice' : 'Create New Invoice' }}
        </h2>

        <p class="page-subtitle">
            Generate an invoice from an existing Sales Order.
        </p>

    </div>

</div>

@if($errors->any())

<div class="alert alert-danger">

<ul class="mb-0">

@foreach($errors->all() as $error)

<li>{{ $error }}</li>

@endforeach

</ul>

</div>

@endif
<!-- ================= INVOICE INFORMATION ================= -->

<div class="custom-card">

    <h5 class="card-title-custom">
        Invoice Information
    </h5>

    <div class="row g-4">

        <div class="col-md-4">

            <label class="form-label">
                Invoice Number
            </label>

            <input
                type="text"
                class="form-control"
                value="{{ $isEdit ? $invoice->invoice_number : 'Auto Generated' }}"
                readonly
            >

        </div>

        <div class="col-md-4">

            <label class="form-label">
                Sales Order
            </label>

            <select
                class="form-select"
                name="order_id"
                id="salesOrderSelect"
                @if(! $isEdit) onchange="window.location='{{ route('invoices.create') }}?order_id='+this.value" @endif
                required
            >

                <option value="">
                    Select Sales Order
                </option>

         @foreach($salesOrders as $order)

    <option
        value="{{ $order->order_id }}"
        data-customer="{{ $order->customer->full_name }}"
        data-subtotal="{{ $order->subtotal }}"
        data-discount="{{ $order->discount }}"
        data-tax="{{ $order->tax }}"
        data-shipping="{{ $order->shipping_fee }}"
        data-total="{{ $order->total_amount }}"
        @selected(
            old(
                'order_id',
                $isEdit ? $invoice->order_id : request('order_id')
            ) == $order->order_id
        )
    >

        {{ $order->order_number }} — {{ $order->customer->full_name }}

    </option>

@endforeach
            </select>

        </div>

        <div class="col-md-4">

            <label class="form-label">
                Customer
            </label>

            <input
                type="text"
                class="form-control"
                id="customer_name"
                value="{{ old('customer_name', optional(optional($invoice->order)->customer)->full_name) }}"
                readonly
            >

        </div>

        <div class="col-md-4">

            <label class="form-label">
                Invoice Date
            </label>

            <input
                type="date"
                class="form-control"
                name="invoice_date"

                value="{{ old(
                    'invoice_date',
                    $isEdit
                        ? optional($invoice->invoice_date)->format('Y-m-d')
                        : now()->format('Y-m-d')
                ) }}"

                required
            >

        </div>

        <div class="col-md-4">

            <label class="form-label">
                Payment Method
            </label>

            <select
                class="form-select"
                name="payment_method"
                required
            >

                @foreach(['Cash','GCash','Bank Transfer','Credit Card'] as $method)

                    <option
                        value="{{ $method }}"
                        @selected(
                            old(
                                'payment_method',
                                $isEdit ? $invoice->payment_method : null
                            ) == $method
                        )
                    >

                        {{ $method }}

                    </option>

                @endforeach

            </select>

        </div>

        <div class="col-md-4">

            <label class="form-label">
                Payment Status
            </label>

            <select
                class="form-select"
                name="payment_status"
                required
            >

                @foreach(['Pending','Paid','Cancelled'] as $status)

                    <option
                        value="{{ $status }}"
                        @selected(
                            old(
                                'payment_status',
                                $isEdit ? $invoice->payment_status : 'Pending'
                            ) == $status
                        )
                    >

                        {{ $status }}

                    </option>

                @endforeach

            </select>

        </div>

    </div>

</div>

@if(! $isEdit && $selectedOrder)
<div class="custom-card">
    <h5 class="card-title-custom">Invoice Items</h5>
    <p class="text-muted small">Enter the quantities to invoice now. Remaining quantities stay available for a later invoice.</p>
    <div class="table-responsive">
        <table class="table invoice-table align-middle">
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="text-center">Ordered</th>
                    <th class="text-center">Previously invoiced</th>
                    <th style="width:160px">Invoice now</th>
                </tr>
            </thead>
            <tbody>
                @foreach($selectedOrder->items as $item)
                    @php
                        $previouslyInvoiced = $selectedOrder->invoices
                            ->where('payment_status', '!=', 'Cancelled')
                            ->flatMap->items
                            ->where('product_id', $item->product_id)
                            ->sum('quantity');
                        $remaining = max(0, $item->quantity - $previouslyInvoiced);
                    @endphp
                    @if($remaining > 0)
                        <tr>
                            <td>
                                <strong>{{ $item->product?->product_name ?? 'Product #'.$item->product_id }}</strong>
                                <div class="small text-muted">₱{{ number_format((float) $item->unit_price, 2) }} each</div>
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-center">{{ $previouslyInvoiced }}</td>
                            <td>
                                <input type="number" class="form-control"
                                    name="quantities[{{ $item->order_item_id }}]"
                                    value="{{ old('quantities.'.$item->order_item_id, $remaining) }}"
                                    min="0" max="{{ $remaining }}">
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
<!-- ================= INVOICE SUMMARY ================= -->

<div class="custom-card">

    <h5 class="card-title-custom">
        Invoice Summary
    </h5>

    <div class="row g-4">

        <div class="col-md-6">

            <label class="form-label">
                Subtotal
            </label>

            <input
                type="number"
                step="0.01"
                min="0"
                class="form-control"
                id="subtotal"
                name="subtotal"
                value="{{ old('subtotal', $isEdit ? $invoice->subtotal : 0) }}"
                readonly
            >

        </div>

        <div class="col-md-6">

            <label class="form-label">
                Discount
            </label>

            <input
                type="number"
                step="0.01"
                min="0"
                class="form-control"
                id="discount"
                name="discount"
                value="{{ old('discount', $isEdit ? $invoice->discount : 0) }}"
                readonly
            >

        </div>

        <div class="col-md-6">

            <label class="form-label">
                Tax
            </label>

            <input
                type="number"
                step="0.01"
                min="0"
                class="form-control"
                id="tax"
                name="tax"
                value="{{ old('tax', $isEdit ? $invoice->tax : 0) }}"
                readonly
            >

        </div>

        <div class="col-md-6">

            <label class="form-label">
                Shipping Fee
            </label>

            <input
                type="number"
                step="0.01"
                min="0"
                class="form-control"
                id="shipping_fee"
                name="shipping_fee"
                value="{{ old('shipping_fee', $isEdit ? $invoice->shipping_fee : 0) }}"
                readonly
            >

        </div>

        <div class="col-12">

            <label class="form-label">
                Total Amount
            </label>

            <input
                type="number"
                step="0.01"
                min="0"
                class="form-control fw-bold fs-5"
                id="total_amount"
                name="total_amount"
                value="{{ old('total_amount', $isEdit ? $invoice->total_amount : 0) }}"
                readonly
            >

        </div>

    </div>

</div>
<div class="d-flex justify-content-end gap-2 mt-4">

    <a
        href="{{ route('invoices.index') }}"
        class="btn btn-outline-secondary"
    >
        Cancel
    </a>

    <button
        type="submit"
        class="btn btn-primary"
    >
        <i class="bi bi-check-circle"></i>

        {{ $isEdit ? 'Update Invoice' : 'Create Invoice' }}

    </button>

    @if($isEdit)

        <a
            href="{{ route('crm.purchase.show', $invoice) }}"
            class="btn btn-success"
        >
            <i class="bi bi-file-earmark-text"></i>

            Generate Invoice
        </a>

    @endif

</div>
<script>

document.addEventListener('DOMContentLoaded', function () {

    const orderSelect = document.getElementById('salesOrderSelect');

    const subtotal = document.getElementById('subtotal');
    const discount = document.getElementById('discount');
    const tax = document.getElementById('tax');
    const shipping = document.getElementById('shipping_fee');
    const total = document.getElementById('total_amount');
    const customer = document.getElementById('customer_name');

    function loadOrderData(){

        const option = orderSelect.options[orderSelect.selectedIndex];

        if(!option.value){

            subtotal.value = 0;
            discount.value = 0;
            tax.value = 0;
            shipping.value = 0;
            total.value = 0;
            customer.value = '';

            return;
        }

        subtotal.value = option.dataset.subtotal ?? 0;
        discount.value = option.dataset.discount ?? 0;
        tax.value = option.dataset.tax ?? 0;
        shipping.value = option.dataset.shipping ?? 0;
        total.value = option.dataset.total ?? 0;
        customer.value = option.dataset.customer ?? '';
    }

    orderSelect.addEventListener('change', loadOrderData);

    loadOrderData();

});

</script>

</form>

@endsection
