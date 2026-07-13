@extends('layouts.app')

@section('title', 'Create Invoice')
@section('page-title', 'Sales Order Management')

@section('content')


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

/* REMOVE THESE
.sidebar
.main-content
.topbar
body
.logo
.menu-title
*/

/* ONLY KEEP PAGE STYLES */

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



        <!-- PAGE HEADER -->
        <div class="page-header">

            <a href="{{ route('invoices.index') }}" class="back-btn">
                <i class="bi bi-arrow-left"></i>
            </a>

            <div>
                <h2 class="page-title">Create New Invoice</h2>
                <p class="page-subtitle">
                    Create an invoice for a customer sales order
                </p>
            </div>

        </div>

        <form>

            <!-- INVOICE INFORMATION -->
            <div class="custom-card">

                <h5 class="card-title-custom">
                    <i class="bi bi-receipt me-2"></i>
                    Invoice Information
                </h5>

                <div class="row g-4">

                    <div class="col-md-4">
                        <label class="form-label">Invoice ID</label>
                        <input
                            type="text"
                            class="form-control"
                            value="INV-006"
                            readonly
                        >
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Invoice Date</label>
                        <input
                            type="date"
                            class="form-control"
                            value="2026-07-12"
                        >
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Due Date</label>
                        <input
                            type="date"
                            class="form-control"
                        >
                    </div>

                </div>

            </div>

            <!-- CUSTOMER & ORDER -->
            <div class="custom-card">

                <h5 class="card-title-custom">
                    <i class="bi bi-person me-2"></i>
                    Customer & Order Details
                </h5>

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="form-label">
                            Customer Name
                        </label>

                        <select class="form-select">
                            <option selected disabled>
                                Select Customer
                            </option>
                            <option>Adelaide Ful</option>
                            <option>Maria Santos</option>
                            <option>Jose Reyes</option>
                            <option>Juan Dela Cruz</option>
                            <option>ABC Corporation</option>
                        </select>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Sales Order
                        </label>

                        <select class="form-select">
                            <option selected disabled>
                                Select Sales Order
                            </option>
                            <option>SO-001</option>
                            <option>SO-002</option>
                            <option>SO-003</option>
                            <option>SO-004</option>
                            <option>SO-005</option>
                        </select>

                    </div>

                </div>

            </div>

            <!-- INVOICE ITEMS -->
            <div class="custom-card">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <h5 class="card-title-custom mb-0">
                        <i class="bi bi-box-seam me-2"></i>
                        Invoice Items
                    </h5>

                    <button type="button" class="add-item-btn">
                        <i class="bi bi-plus-circle me-1"></i>
                        Add Item
                    </button>

                </div>

                <div class="table-responsive">

                    <table class="table invoice-table">

                        <thead>
                            <tr>
                                <th>Product / Service</th>
                                <th width="130">Quantity</th>
                                <th width="180">Unit Price</th>
                                <th width="180">Amount</th>
                                <th width="70">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr>

                                <td>
                                    <select class="form-select">
                                        <option selected disabled>
                                            Select Product
                                        </option>
                                        <option>Desktop Computer</option>
                                        <option>Laptop</option>
                                        <option>Monitor</option>
                                        <option>Keyboard</option>
                                        <option>Mouse</option>
                                    </select>
                                </td>

                                <td>
                                    <input
                                        type="number"
                                        class="form-control"
                                        value="1"
                                        min="1"
                                    >
                                </td>

                                <td>
                                    <input
                                        type="number"
                                        class="form-control"
                                        placeholder="0.00"
                                    >
                                </td>

                                <td>
                                    <input
                                        type="text"
                                        class="form-control"
                                        value="₱0.00"
                                        readonly
                                    >
                                </td>

                                <td>
                                    <button
                                        type="button"
                                        class="delete-item-btn"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

            <!-- PAYMENT AND SUMMARY -->
            <div class="row g-4">

                <!-- PAYMENT DETAILS -->
                <div class="col-lg-7">

                    <div class="custom-card h-100">

                        <h5 class="card-title-custom">
                            <i class="bi bi-credit-card me-2"></i>
                            Payment Details
                        </h5>

                        <div class="mb-3">

                            <label class="form-label">
                                Payment Status
                            </label>

                            <select class="form-select">
                                <option selected>Pending</option>
                                <option>Paid</option>
                                <option>Draft</option>
                            </select>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Payment Terms
                            </label>

                            <select class="form-select">
                                <option>Due on Receipt</option>
                                <option>Net 7 Days</option>
                                <option selected>Net 14 Days</option>
                                <option>Net 30 Days</option>
                            </select>

                        </div>

                        <div>

                            <label class="form-label">
                                Notes
                            </label>

                            <textarea
                                class="form-control"
                                rows="4"
                                placeholder="Enter invoice notes..."
                            ></textarea>

                        </div>

                    </div>

                </div>

                <!-- INVOICE SUMMARY -->
                <div class="col-lg-5">

                    <div class="custom-card">

                        <h5 class="card-title-custom">
                            <i class="bi bi-calculator me-2"></i>
                            Invoice Summary
                        </h5>

                        <div class="summary-row">
                            <span>Subtotal</span>
                            <strong>₱0.00</strong>
                        </div>

                        <div class="summary-row">
                            <span>Discount</span>
                            <strong>₱0.00</strong>
                        </div>

                        <div class="summary-row">
                            <span>Tax (12%)</span>
                            <strong>₱0.00</strong>
                        </div>

                        <div class="summary-total">

                            <h5 class="mb-0 fw-bold">
                                Total
                            </h5>

                            <h4>
                                ₱0.00
                            </h4>

                        </div>

                    </div>

                </div>

            </div>

            <!-- ACTION BUTTONS -->
            <div class="d-flex justify-content-end gap-3 mt-4 mb-5">

                <a
                    href="{{ route('invoices.index') }}"
                    class="cancel-btn"
                >
                    Cancel
                </a>

                <button
                    type="button"
                    class="draft-btn"
                >
                    <i class="bi bi-file-earmark me-1"></i>
                    Save as Draft
                </button>

            <a href="{{ route('invoices.generate') }}" class="create-btn text-decoration-none">
    <i class="bi bi-check-circle me-1"></i>
    Generate Invoice
</a>
            </div>

        </form>

    </div>

</div>
@endsection