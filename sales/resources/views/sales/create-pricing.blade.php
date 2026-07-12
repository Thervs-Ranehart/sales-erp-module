<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Create Pricing Rule</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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
            background:white;
            border:none;
            border-radius:16px;
            padding:25px;
            margin-bottom:24px;
            box-shadow:0 5px 20px rgba(0,0,0,.06);
        }

        .card-title-custom{
            color:var(--primary);
            font-size:17px;
            font-weight:700;
            margin-bottom:24px;
        }

        /* FORM */

        .form-label{
            color:var(--text);
            font-size:14px;
            font-weight:600;
            margin-bottom:8px;
        }

        .required{
            color:#DC3545;
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
            resize:vertical;
        }

        /* VALUE INPUT */

        .input-group-text{
            background:var(--light-purple);
            color:var(--primary);
            border:1px solid var(--border);
            font-weight:700;
        }

        /* SWITCH */

        .form-check-input{
            width:45px !important;
            height:23px;
            cursor:pointer;
        }

        .form-check-input:checked{
            background-color:var(--primary);
            border-color:var(--primary);
        }

        /* INFO BOX */

        .info-box{
            background:#F4F3FF;
            border-left:4px solid var(--primary);
            border-radius:8px;
            padding:16px 18px;
            color:var(--text2);
            font-size:14px;
        }

        .info-box i{
            color:var(--primary);
            margin-right:8px;
        }

        /* BUTTONS */

        .action-buttons{
            display:flex;
            justify-content:flex-end;
            gap:12px;
            margin-bottom:40px;
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

        .cancel-btn:hover{
            background:#F3F4F6;
            color:var(--text);
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

        @media(max-width:768px){
            .sidebar{
                position:relative;
                width:100%;
                height:auto;
            }

            .main-content{
                margin-left:0;
            }

            .page-content{
                padding:20px;
            }

            .action-buttons{
                flex-direction:column;
            }

            .action-buttons a,
            .action-buttons button{
                width:100%;
                text-align:center;
            }
        }
    </style>
</head>

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

        <!-- HEADER -->
        <div class="page-header">

            <a href="{{ route('pricing.index') }}" class="back-btn">
                <i class="bi bi-arrow-left"></i>
            </a>

            <div>
                <h2 class="page-title">Create New Pricing Rule</h2>
                <p class="page-subtitle">
                    Create a pricing rule for products, customers, or order values.
                </p>
            </div>

        </div>

        <form>

            <!-- BASIC INFORMATION -->
            <div class="custom-card">

                <h5 class="card-title-custom">
                    <i class="bi bi-info-circle me-2"></i>
                    Basic Information
                </h5>

                <div class="row g-4">

                    <div class="col-md-4">
                        <label class="form-label">Rule ID</label>

                        <input
                            type="text"
                            class="form-control"
                            value="PR-005"
                            readonly
                        >
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">
                            Rule Name <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            placeholder="Enter pricing rule name"
                            required
                        >
                    </div>

                    <div class="col-12">
                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            class="form-control"
                            placeholder="Enter a short description of the pricing rule..."
                        ></textarea>
                    </div>

                </div>

            </div>

            <!-- RULE CONFIGURATION -->
            <div class="custom-card">

                <h5 class="card-title-custom">
                    <i class="bi bi-sliders me-2"></i>
                    Rule Configuration
                </h5>

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="form-label">
                            Rule Type <span class="required">*</span>
                        </label>

                        <select class="form-select" required>
                            <option value="" selected disabled>
                                Select Rule Type
                            </option>
                            <option>Discount</option>
                            <option>Markup</option>
                            <option>Volume Pricing</option>
                            <option>Fixed Price</option>
                        </select>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Value Type <span class="required">*</span>
                        </label>

                        <select class="form-select" required>
                            <option value="" selected disabled>
                                Select Value Type
                            </option>
                            <option>Percentage</option>
                            <option>Fixed Amount</option>
                        </select>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Rule Value <span class="required">*</span>
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">%</span>

                            <input
                                type="number"
                                class="form-control"
                                placeholder="0.00"
                                min="0"
                                step="0.01"
                                required
                            >
                        </div>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Applies To <span class="required">*</span>
                        </label>

                        <select class="form-select" required>
                            <option value="" selected disabled>
                                Select Application
                            </option>
                            <option>All Products</option>
                            <option>Specific Product</option>
                            <option>Product Category</option>
                            <option>All Customers</option>
                            <option>VIP Customers</option>
                            <option>Returning Customers</option>
                            <option>Order Amount</option>
                        </select>

                    </div>

                </div>

            </div>

            <!-- CONDITIONS -->
            <div class="custom-card">

                <h5 class="card-title-custom">
                    <i class="bi bi-funnel me-2"></i>
                    Conditions
                </h5>

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="form-label">
                            Minimum Order Amount
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">₱</span>

                            <input
                                type="number"
                                class="form-control"
                                placeholder="0.00"
                                min="0"
                            >
                        </div>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Minimum Quantity
                        </label>

                        <input
                            type="number"
                            class="form-control"
                            placeholder="Enter minimum quantity"
                            min="1"
                        >

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Customer Group
                        </label>

                        <select class="form-select">
                            <option selected>All Customers</option>
                            <option>VIP Customers</option>
                            <option>Regular Customers</option>
                            <option>New Customers</option>
                            <option>Returning Customers</option>
                        </select>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Product Category
                        </label>

                        <select class="form-select">
                            <option selected>All Categories</option>
                            <option>Computers</option>
                            <option>Laptops</option>
                            <option>Accessories</option>
                            <option>Office Equipment</option>
                        </select>

                    </div>

                </div>

            </div>

            <!-- EFFECTIVE PERIOD -->
            <div class="custom-card">

                <h5 class="card-title-custom">
                    <i class="bi bi-calendar-event me-2"></i>
                    Effective Period
                </h5>

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="form-label">
                            Start Date <span class="required">*</span>
                        </label>

                        <input
                            type="date"
                            class="form-control"
                            required
                        >

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            End Date
                        </label>

                        <input
                            type="date"
                            class="form-control"
                        >

                    </div>

                    <div class="col-12">

                        <div class="form-check form-switch">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="noEndDate"
                            >

                            <label
                                class="form-check-label ms-2"
                                for="noEndDate"
                            >
                                This pricing rule has no end date
                            </label>

                        </div>

                    </div>

                </div>

            </div>

            <!-- STATUS -->
            <div class="custom-card">

                <h5 class="card-title-custom">
                    <i class="bi bi-toggle-on me-2"></i>
                    Rule Status
                </h5>

                <div class="row">

                    <div class="col-md-6">

                        <label class="form-label">
                            Status
                        </label>

                        <select class="form-select">
                            <option selected>Active</option>
                            <option>Scheduled</option>
                            <option>Inactive</option>
                        </select>

                    </div>

                </div>

                <div class="info-box mt-4">
                    <i class="bi bi-info-circle-fill"></i>
                    Active pricing rules will automatically be applied when
                    the selected conditions are met.
                </div>

            </div>

            <!-- BUTTONS -->
            <div class="action-buttons">

                <a
                    href="{{ route('pricing.index') }}"
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
                    Create Pricing Rule
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>