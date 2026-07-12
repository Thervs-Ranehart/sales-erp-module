<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Create Invoice</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
          /* Your existing CSS */

        .sidebar .sub-menu {
            padding-left: 50px;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.08);
            border-left: 3px solid #887CFD;
        }

        .sidebar .sub-menu:hover {
            background: #887CFD;
        }

        .sidebar .sub-menu.active {
            background: #16C8C7;
            border-left: 4px solid #fff;
        }
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

        /* SIDEBAR */

        .sidebar{
            position:fixed;
            top:0;
            left:0;
            width:285px;
            height:100vh;
            background:var(--primary);
            color:white;
            overflow-y:auto;
            z-index:1000;
        }

        .logo{
            padding:27px;
            font-size:27px;
            font-weight:700;
        }

        .menu-title{
            padding:18px 27px 8px;
            font-size:11px;
            font-weight:600;
            text-transform:uppercase;
            color:rgba(255,255,255,.65);
        }

        .sidebar a{
            display:flex;
            align-items:center;
            gap:12px;
            margin:3px 14px;
            padding:13px 15px;
            border-radius:8px;
            color:white;
            text-decoration:none;
            font-size:15px;
            transition:.2s;
        }

        .sidebar a i{
            width:22px;
            font-size:18px;
        }

        .sidebar a:hover{
            background:var(--secondary);
            color:white;
        }

        .sidebar a.active{
            background:white;
            color:var(--primary);
            font-weight:600;
        }

        /* MAIN CONTENT */

        .main-content{
            margin-left:285px;
            min-height:100vh;
        }

        /* TOPBAR */

        .topbar{
            height:82px;
            background:white;
            display:flex;
            align-items:center;
            padding:0 30px;
            box-shadow:0 2px 10px rgba(0,0,0,.06);
        }

        .topbar-title{
            font-size:25px;
            font-weight:600;
            margin:0;
        }

        .top-icons{
            margin-left:auto;
            display:flex;
            align-items:center;
            gap:18px;
            color:var(--primary);
            font-size:19px;
        }

        .top-icons i{
            cursor:pointer;
        }

        .profile-icon{
            font-size:32px;
            color:var(--accent);
        }

        /* PAGE CONTENT */

        .page-content{
            padding:30px;
        }

        .page-header{
            display:flex;
            align-items:center;
            gap:15px;
            margin-bottom:25px;
        }

        .page-title{
            margin:0;
            font-size:28px;
            font-weight:700;
        }

        .page-subtitle{
            margin:5px 0 0;
            color:var(--text2);
            font-size:15px;
        }

        /* BACK BUTTON */

        .back-btn{
            width:44px;
            height:44px;
            flex-shrink:0;
            display:flex;
            align-items:center;
            justify-content:center;
            background:var(--light-purple);
            color:var(--primary);
            border-radius:10px;
            text-decoration:none;
            transition:.2s;
        }

        .back-btn:hover{
            background:var(--secondary);
            color:white;
        }

        /* CARDS */

        .custom-card{
            border:none;
            border-radius:16px;
            background:white;
            box-shadow:0 5px 20px rgba(0,0,0,.06);
            padding:25px;
            margin-bottom:24px;
        }

        .card-title-custom{
            color:var(--primary);
            font-size:17px;
            font-weight:700;
            margin-bottom:24px;
        }

        /* FORMS */

        .form-label{
            color:var(--text);
            font-size:14px;
            font-weight:600;
            margin-bottom:8px;
        }

        .form-control,
        .form-select{
            min-height:46px;
            border:1px solid var(--border);
            border-radius:9px;
            font-size:14px;
            padding:10px 13px;
            box-shadow:none !important;
        }

        .form-control:focus,
        .form-select:focus{
            border-color:var(--primary);
            box-shadow:0 0 0 3px rgba(83,71,206,.12) !important;
        }

        .form-control[readonly]{
            background:#F4F3FF;
            color:var(--primary);
            font-weight:600;
        }

        textarea.form-control{
            min-height:110px;
        }

        /* TABLE */

        .invoice-table{
            width:100%;
            margin-bottom:0;
            vertical-align:middle;
        }

        .invoice-table thead th{
            background:var(--light-purple);
            color:var(--primary);
            border:none;
            padding:14px;
            font-size:13px;
        }

        .invoice-table tbody td{
            padding:15px 8px;
            border-bottom:1px solid #EEF0F4;
        }

        /* BUTTONS */

        .add-item-btn{
            background:var(--secondary);
            color:white;
            border:none;
            padding:10px 18px;
            border-radius:8px;
            font-size:14px;
            font-weight:600;
        }

        .add-item-btn:hover{
            background:var(--primary);
            color:white;
        }

        .delete-item-btn{
            width:40px;
            height:40px;
            border:none;
            border-radius:8px;
            background:var(--primary);
            color:white;
        }

        .cancel-btn{
            background:white;
            color:var(--text2);
            border:1px solid var(--border);
            border-radius:8px;
            padding:11px 22px;
            font-weight:600;
            text-decoration:none;
        }

        .draft-btn{
            background:var(--secondary);
            color:white;
            border:none;
            border-radius:8px;
            padding:11px 22px;
            font-weight:600;
        }

        .create-btn{
            background:var(--primary);
            color:white;
            border:none;
            border-radius:8px;
            padding:11px 22px;
            font-weight:600;
        }

        .draft-btn:hover,
        .create-btn:hover{
            color:white;
            opacity:.9;
        }

        /* SUMMARY */

        .summary-row{
            display:flex;
            justify-content:space-between;
            padding:10px 0;
            color:var(--text2);
        }

        .summary-row strong{
            color:var(--text);
        }

        .summary-total{
            display:flex;
            justify-content:space-between;
            align-items:center;
            border-top:1px solid var(--border);
            margin-top:8px;
            padding-top:18px;
        }

        .summary-total h4{
            color:var(--primary);
            font-weight:700;
            margin:0;
        }

        @media(max-width:768px){
            .sidebar{
                position:relative;
                width:100%;
                height:auto;
            }

            .main-content{
                margin-left:0;
            }
        }
    </style>
</head>

<body>

@include('sales.partials.sidebar')


<!-- MAIN CONTENT -->
<div class="main-content">

    <!-- TOPBAR -->
    <div class="topbar">

        <h3 class="topbar-title">
            Sales Order Management
        </h3>

        <div class="top-icons">
            <i class="bi bi-bell"></i>
            <i class="bi bi-envelope"></i>
            <i class="bi bi-question-circle"></i>
            <i class="bi bi-person-circle profile-icon"></i>
        </div>

    </div>

    <!-- PAGE CONTENT -->
    <div class="page-content">

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

                <button
                    type="submit"
                    class="create-btn"
                >
                    <i class="bi bi-check-circle me-1"></i>
                    Create Invoice
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>