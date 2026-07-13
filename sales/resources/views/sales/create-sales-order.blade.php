@extends('layouts.app')

@section('title','New Sales Order')
@section('page-title','Sales Order Management')

@section('content')

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
        <h2>Create Sales Order</h2>
        <p>Create a new customer sales order.</p>
    </div>

</div>

<form action="{{ route('sales.store') }}" method="POST">
@csrf

<div class="custom-card">

<h5 class="card-title">
Customer Information
</h5>

<div class="row">

<div class="col-md-6 mb-3">
<label>Customer</label>

<select class="form-select" name="customer_id">

<option>Select Customer</option>

<option>ABC Corporation</option>
<option>XYZ Trading</option>
<option>Juan Dela Cruz</option>

</select>
</div>

<div class="col-md-6 mb-3">

<label>Order Date</label>

<input
type="date"
class="form-control"
name="order_date">

</div>

</div>

</div>

<div class="custom-card">

<h5 class="card-title">
Products
</h5>

<table class="table">

<thead>

<tr>

<th>Product</th>
<th>Qty</th>
<th>Price</th>
<th>Total</th>

</tr>

</thead>

<tbody>

<tr>

<td>

<select class="form-select" name="product[]">

<option>Laptop</option>
<option>Desktop Computer</option>
<option>Monitor</option>

</select>

</td>

<td>

<input
type="number"
class="form-control"
name="qty[]"
value="1">

</td>

<td>

<input
type="number"
class="form-control"
name="price[]">

</td>

<td>

<input
type="text"
class="form-control"
value="₱0.00"
readonly>

</td>

</tr>

</tbody>

</table>

<button type="button" class="btn btn-outline-primary">
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

<select class="form-select">

<option>Regular Price</option>
<option>VIP Discount</option>
<option>Bulk Discount</option>

</select>

</div>

<div class="col-md-4">

<label>Discount (%)</label>

<input
type="number"
class="form-control"
name="discount">

</div>

<div class="col-md-4">

<label>Tax (%)</label>

<input
type="number"
class="form-control"
value="12">

</div>

</div>

</div>

<div class="custom-card">

<h5 class="card-title">
Order Status
</h5>

<select class="form-select" name="status">

<option>Pending</option>
<option>Processed</option>
<option>Shipped</option>
<option>Delivered</option>

</select>

</div>

<div class="custom-card">

<h5 class="card-title">
Order Summary
</h5>

<div class="d-flex justify-content-between">
<span>Subtotal</span>
<strong>₱0.00</strong>
</div>

<div class="d-flex justify-content-between">
<span>Discount</span>
<strong>₱0.00</strong>
</div>

<div class="d-flex justify-content-between">
<span>Tax</span>
<strong>₱0.00</strong>
</div>

<hr>

<div class="d-flex justify-content-between">

<h4>Total</h4>

<h4>₱0.00</h4>

</div>

</div>

<div class="text-end mb-5">

<a href="{{ route('sales.index') }}" class="cancel-btn">
Cancel
</a>

<button class="create-btn">
<i class="bi bi-check-circle"></i>
Create Sales Order
</button>

</div>

</form>

@endsection