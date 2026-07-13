@extends('layouts.app')

@section('title','Invoice Generated')
@section('page-title','Sales Order Management')

@section('content')

<style>

:root{
    --primary:#5347CE;
    --secondary:#887CFD;
    --accent:#4896FE;
    --success:#16C8C7;
    --warning:#F59E0B;
    --danger:#EF4444;
    --border:#E5E7EB;
    --light:#F8FAFC;
}

.page-header{
    margin-bottom:30px;
}

.page-title{
    font-size:30px;
    font-weight:700;
}

.page-subtitle{
    color:#6B7280;
}

.custom-card{
    background:#fff;
    border:none;
    border-radius:16px;
    box-shadow:0 5px 20px rgba(0,0,0,.06);
    padding:25px;
    margin-bottom:25px;
}

.info-title{
    color:var(--primary);
    font-size:18px;
    font-weight:700;
    margin-bottom:20px;
}

.status{
    display:inline-block;
    padding:7px 18px;
    border-radius:20px;
    background:#DDF8F3;
    color:#0A7B71;
    font-weight:600;
}

.summary-item{
    display:flex;
    justify-content:space-between;
    padding:12px 0;
    border-bottom:1px solid #eee;
}

.integration-card{
    border:2px solid #ECEBFF;
    border-radius:14px;
    padding:20px;
    height:100%;
}

.integration-card h5{
    color:var(--primary);
    margin-bottom:15px;
}

.integration-card ul{
    padding-left:20px;
}

.integration-card li{
    margin-bottom:8px;
}

.success-box{
    background:#ECFFF8;
    border-left:5px solid var(--success);
    padding:18px;
    border-radius:10px;
}

.success-box h5{
    color:#128B74;
}

.action-buttons{
    display:flex;
    justify-content:flex-end;
    gap:15px;
    margin-top:30px;
}

.btn-back{
    border:1px solid #ddd;
    color:#6B7280;
    padding:10px 22px;
    border-radius:8px;
    text-decoration:none;
}

.btn-primary-custom{
    background:var(--primary);
    color:white;
    border:none;
    padding:11px 24px;
    border-radius:8px;
}

.btn-primary-custom:hover{
    background:var(--secondary);
    color:white;
}

</style>

<div class="page-header">

    <h2 class="page-title">
        Invoice Generated Successfully
    </h2>

    <p class="page-subtitle">
        The invoice has been generated and synchronized with the ERP shared modules.
    </p>

</div>


<div class="success-box mb-4">

    <h5>
        <i class="bi bi-check-circle-fill me-2"></i>
        Invoice Generation Complete
    </h5>

    <p class="mb-0">
        The invoice has been successfully created. Shared transaction records
        for Inventory and Finance have also been generated.
    </p>

</div>


<div class="custom-card">

    <div class="info-title">
        Invoice Information
    </div>

    <div class="row">

        <div class="col-md-3">
            <strong>Invoice No.</strong>
            <p>INV-006</p>
        </div>

        <div class="col-md-3">
            <strong>Sales Order</strong>
            <p>SO-006</p>
        </div>

        <div class="col-md-3">
            <strong>Customer</strong>
            <p>ABC Corporation</p>
        </div>

        <div class="col-md-3">
            <strong>Status</strong><br>
            <span class="status">
                Generated
            </span>
        </div>

    </div>

</div>


<div class="row">

<div class="col-lg-6">

<div class="integration-card">

<h5>
<i class="bi bi-box-seam me-2"></i>
Inventory Transaction
</h5>

<div class="summary-item">
<span>Transaction ID</span>
<strong>INVT-0014</strong>
</div>

<div class="summary-item">
<span>Status</span>
<strong class="text-success">
Ready
</strong>
</div>

<ul class="mt-3">

<li>Inventory transaction record created</li>

<li>Reserved product stock</li>

<li>Ready for Inventory Module processing</li>

</ul>

</div>

</div>


<div class="col-lg-6">

<div class="integration-card">

<h5>
<i class="bi bi-cash-stack me-2"></i>
Finance Transaction
</h5>

<div class="summary-item">
<span>Transaction ID</span>
<strong>FIN-0014</strong>
</div>

<div class="summary-item">
<span>Status</span>
<strong class="text-success">
Ready
</strong>
</div>

<ul class="mt-3">

<li>Accounts Receivable created</li>

<li>Sales revenue recorded</li>

<li>Ready for Finance Module processing</li>

</ul>

</div>

</div>

</div>


<div class="custom-card mt-4">

<div class="info-title">
ERP Synchronization Summary
</div>

<div class="summary-item">
<span>Invoice Record</span>

<strong class="text-success">
<i class="bi bi-check-circle-fill"></i>
Completed
</strong>
</div>

<div class="summary-item">
<span>Invoice Items</span>

<strong class="text-success">
<i class="bi bi-check-circle-fill"></i>
Completed
</strong>
</div>

<div class="summary-item">
<span>Inventory Transaction</span>

<strong class="text-success">
<i class="bi bi-check-circle-fill"></i>
Created
</strong>
</div>

<div class="summary-item">
<span>Finance Transaction</span>

<strong class="text-success">
<i class="bi bi-check-circle-fill"></i>
Created
</strong>
</div>

<div class="summary-item">
<span>ERP Status</span>

<strong style="color:#16C8C7;">
Ready for Processing
</strong>
</div>

</div>


<div class="action-buttons">

<a href="{{ route('invoices.index') }}" class="btn-back">
Back to Invoices
</a>

<a href="{{ route('invoices.create') }}" class="btn-primary-custom text-decoration-none">
Generate Another Invoice
</a>

</div>

@endsection