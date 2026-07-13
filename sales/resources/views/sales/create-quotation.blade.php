<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Create New Quotation</title>

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

        .profile-icon{
            font-size:32px;
            color:var(--accent);
        }

        /* PAGE */

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
            margin:0 0 5px;
        }

        .page-subtitle{
            color:var(--text2);
            margin:0;
        }

        .back-btn{
            display:inline-flex;
            align-items:center;
            gap:8px;
            background:white;
            color:var(--primary);
            padding:11px 18px;
            border:1px solid #DCD8FF;
            border-radius:8px;
            text-decoration:none;
            font-weight:600;
        }

        .back-btn:hover{
            background:var(--light-purple);
            color:var(--primary);
        }

        /* CARDS */

        .form-card{
            background:white;
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
            gap:9px;
        }

        /* FORM */

        .form-label{
            color:var(--text);
            font-size:14px;
            font-weight:600;
            margin-bottom:8px;
        }

        .form-control,
        .form-select{
            border:1px solid var(--border);
            border-radius:8px;
            padding:11px 13px;
        }

        .form-control:focus,
        .form-select:focus{
            border-color:var(--secondary);
            box-shadow:0 0 0 3px rgba(136,124,253,.15);
        }

        textarea{
            resize:none;
        }

        /* PRODUCTS */

        .add-product-btn{
            background:var(--secondary);
            color:white;
            border:none;
            padding:10px 17px;
            border-radius:8px;
            font-weight:600;
        }

        .add-product-btn:hover{
            background:var(--primary);
        }

        .product-table{
            vertical-align:middle;
            margin-bottom:0;
        }

        .product-table thead th{
            background:var(--light-purple);
            color:var(--primary);
            padding:14px;
            border-bottom:2px solid var(--primary);
            font-size:13px;
        }

        .product-table tbody td{
            padding:12px;
        }

        .remove-btn{
            width:38px;
            height:38px;
            border:none;
            border-radius:8px;
            background:var(--primary);
            color:white;
        }

        .remove-btn:hover{
            background:var(--secondary);
        }

        /* SUMMARY */

        .summary-row{
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:14px 0;
            border-bottom:1px solid var(--border);
        }

        .summary-input{
            padding:14px 0;
            border-bottom:1px solid var(--border);
        }

        .summary-input label{
            font-weight:600;
            margin-bottom:8px;
        }

        .grand-total{
            border-bottom:none;
            color:var(--primary);
            font-size:20px;
            font-weight:700;
        }

        /* ACTIONS */

        .form-actions{
            display:flex;
            justify-content:flex-end;
            gap:12px;
            margin-bottom:30px;
        }

        .cancel-btn,
        .draft-btn,
        .create-btn{
            display:inline-flex;
            align-items:center;
            gap:7px;
            padding:11px 20px;
            border-radius:8px;
            border:none;
            text-decoration:none;
            font-weight:600;
        }

        .cancel-btn{
            background:white;
            color:var(--text2);
            border:1px solid var(--border);
        }

        .draft-btn{
            background:var(--secondary);
            color:white;
        }

        .create-btn{
            background:var(--primary);
            color:white;
        }

        .draft-btn:hover,
        .create-btn:hover{
            opacity:.9;
        }

        @media(max-width:900px){
            .sidebar{
                width:220px;
            }

            .main-content{
                margin-left:220px;
            }

            .page-header{
                align-items:flex-start;
                gap:15px;
                flex-direction:column;
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

        <div class="page-header">

            <div>
                <h2 class="page-title">Create New Quotation</h2>
                <p class="page-subtitle">
                    Create a quotation for your customer
                </p>
            </div>

            <a href="{{ route('quotations.index') }}" class="back-btn">
                <i class="bi bi-arrow-left"></i>
                Back to Quotations
            </a>

        </div>

        <form>

            <!-- QUOTATION INFORMATION -->

            <div class="form-card">

                <h5 class="section-title">
                    <i class="bi bi-file-earmark-text"></i>
                    Quotation Information
                </h5>

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="form-label">
                            Customer
                        </label>

                        <select class="form-select">
                            <option selected disabled>
                                Select Customer
                            </option>
                            <option>Adelaide Ful</option>
                            <option>Maria Santos</option>
                            <option>Jose Reyes</option>
                            <option>Juan Dela Cruz</option>
                        </select>

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">
                            Quotation Date
                        </label>

                        <input type="date" class="form-control">

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">
                            Valid Until
                        </label>

                        <input type="date" class="form-control">

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Reference Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            placeholder="e.g. REF-2026-001"
                        >

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Status
                        </label>

                        <select class="form-select">
                            <option>Draft</option>
                            <option>Pending</option>
                            <option>Approved</option>
                        </select>

                    </div>

                </div>

            </div>

            <!-- PRODUCTS -->

            <div class="form-card">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <h5 class="section-title mb-0">
                        <i class="bi bi-box-seam"></i>
                        Products / Items
                    </h5>

                    <button
                        type="button"
                        class="add-product-btn"
                        onclick="addProduct()"
                    >
                        <i class="bi bi-plus-circle me-1"></i>
                        Add Product
                    </button>

                </div>

                <div class="table-responsive">

                    <table class="table product-table">

                        <thead>
                            <tr>
                                <th>Product / Item</th>
                                <th width="120">Quantity</th>
                                <th width="170">Unit Price</th>
                                <th width="170">Total</th>
                                <th width="60"></th>
                            </tr>
                        </thead>

                        <tbody id="productRows">

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
                                        class="form-control quantity"
                                        value="1"
                                        min="1"
                                        oninput="calculateTotals()"
                                    >
                                </td>

                                <td>
                                    <input
                                        type="number"
                                        class="form-control price"
                                        value="0"
                                        min="0"
                                        oninput="calculateTotals()"
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
                                        class="remove-btn"
                                        onclick="removeProduct(this)"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

            <!-- NOTES + SUMMARY -->

            <div class="row g-4">

                <div class="col-lg-7">

                    <div class="form-card h-100">

                        <h5 class="section-title">
                            <i class="bi bi-card-text"></i>
                            Notes and Terms
                        </h5>

                        <label class="form-label">
                            Notes
                        </label>

                        <textarea
                            class="form-control mb-4"
                            rows="4"
                            placeholder="Enter additional notes..."
                        ></textarea>

                        <label class="form-label">
                            Terms and Conditions
                        </label>

                        <textarea
                            class="form-control"
                            rows="4"
                            placeholder="Enter terms and conditions..."
                        ></textarea>

                    </div>

                </div>

                <div class="col-lg-5">

                    <div class="form-card">

                        <h5 class="section-title">
                            <i class="bi bi-calculator"></i>
                            Quotation Summary
                        </h5>

                        <div class="summary-row">
                            <span>Subtotal</span>
                            <strong id="subtotal">₱0.00</strong>
                        </div>

                        <div class="summary-input">

                            <label>
                                Discount
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₱
                                </span>

                                <input
                                    type="number"
                                    id="discount"
                                    class="form-control"
                                    value="0"
                                    min="0"
                                    oninput="calculateTotals()"
                                >

                            </div>

                        </div>

                        <div class="summary-row">
                            <span>VAT (12%)</span>
                            <strong id="tax">₱0.00</strong>
                        </div>

                        <div class="summary-row grand-total">
                            <span>Grand Total</span>
                            <strong id="grandTotal">₱0.00</strong>
                        </div>

                    </div>

                </div>

            </div>

            <!-- ACTION BUTTONS -->

            <div class="form-actions">

                <a
                    href="{{ route('quotations.index') }}"
                    class="cancel-btn"
                >
                    Cancel
                </a>

                <button
                    type="button"
                    class="draft-btn"
                >
                    <i class="bi bi-file-earmark"></i>
                    Save as Draft
                </button>

                <button
                    type="button"
                    class="create-btn"
                >
                    <i class="bi bi-check-circle"></i>
                    Create Quotation
                </button>

            </div>

        </form>

    </div>

</div>

<script>

    function addProduct(){

        const tbody =
            document.getElementById('productRows');

        const firstRow =
            tbody.querySelector('tr');

        const newRow =
            firstRow.cloneNode(true);

        newRow.querySelector('select').selectedIndex = 0;
        newRow.querySelector('.quantity').value = 1;
        newRow.querySelector('.price').value = 0;
        newRow.querySelector('.row-total').value = '₱0.00';

        tbody.appendChild(newRow);

        calculateTotals();
    }


    function removeProduct(button){

        const tbody =
            document.getElementById('productRows');

        if(tbody.rows.length > 1){

            button.closest('tr').remove();

            calculateTotals();
        }
    }


    function calculateTotals(){

        let subtotal = 0;

        document
            .querySelectorAll('#productRows tr')
            .forEach(row => {

                const quantity =
                    parseFloat(
                        row.querySelector('.quantity').value
                    ) || 0;

                const price =
                    parseFloat(
                        row.querySelector('.price').value
                    ) || 0;

                const total =
                    quantity * price;

                row.querySelector('.row-total').value =
                    formatMoney(total);

                subtotal += total;

            });


        const discount =
            parseFloat(
                document.getElementById('discount').value
            ) || 0;


        const discountedAmount =
            Math.max(subtotal - discount, 0);


        const tax =
            discountedAmount * 0.12;


        const grandTotal =
            discountedAmount + tax;


        document.getElementById('subtotal').textContent =
            formatMoney(subtotal);

        document.getElementById('tax').textContent =
            formatMoney(tax);

        document.getElementById('grandTotal').textContent =
            formatMoney(grandTotal);
    }


    function formatMoney(value){

        return '₱' +
            value.toLocaleString(
                'en-PH',
                {
                    minimumFractionDigits:2,
                    maximumFractionDigits:2
                }
            );
    }

</script>

</body>
</html>