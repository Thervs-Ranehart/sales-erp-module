@extends('layouts.app')

@section('title', 'Pricing Rules')
@section('page-title', 'Sales Order Management')

@section('content')

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

      

        /* PAGE */

        .page-content{
            padding:30px;
        }

        .page-header{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:20px;
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

        /* ADD BUTTON */

        .new-btn{
            display:inline-flex;
            align-items:center;
            gap:8px;
            background:var(--primary);
            color:white;
            border:none;
            border-radius:9px;
            padding:12px 20px;
            font-size:14px;
            font-weight:600;
            text-decoration:none;
            transition:.2s;
        }

        .new-btn:hover{
            background:#4539B8;
            color:white;
        }

        /* ================= KPI CARDS ================= */

.stat-card{
    background:#FFFFFF;
    border:none;
    border-radius:18px;
    padding:22px;
    box-shadow:0 10px 25px rgba(0,0,0,.06);
    transition:.25s;
    height:100%;
}

.stat-card:hover{
    transform:translateY(-4px);
    box-shadow:0 15px 35px rgba(83,71,206,.12);
}

.stat-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
}

.stat-label{
    font-size:15px;
    font-weight:600;
    color:#5B6475;
    margin-bottom:8px;
}

.stat-number{
    font-size:48px;
    font-weight:700;
    color:#1F2937;
    line-height:1;
    margin-bottom:14px;
}

/* ICON */

.stat-icon{
    width:56px;
    height:56px;
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
}

/* Different icon colors */

.icon-purple{
    background:#ECE9FF;
    color:#5347CE;
}

.icon-blue{
    background:#E6F0FF;
    color:#2563EB;
}

.icon-green{
    background:#DDF8F5;
    color:#16C8C7;
}

.icon-yellow{
    background:#FFF3DB;
    color:#F59E0B;
}

.icon-red{
    background:#FDECEC;
    color:#EF4444;
}

/* Small badge */

.stat-badge{
    display:inline-flex;
    align-items:center;
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
    color:#fff;
}

.badge-purple{
    background:#5347CE;
}

.badge-green{
    background:#198754;
}

.badge-blue{
    background:#2563EB;
}

.badge-yellow{
    background:#F4B400;
    color:#111827;
}

.badge-red{
    background:#DC3545;
}
        /* FILTER CARD */

        .filter-card{
            background:white;
            border-radius:16px;
            padding:20px;
            box-shadow:0 5px 20px rgba(0,0,0,.06);
            margin-top:25px;
        }

        .search-box{
            position:relative;
        }

        .search-box i{
            position:absolute;
            left:15px;
            top:50%;
            transform:translateY(-50%);
            color:var(--text2);
        }

        .search-box input{
            padding-left:42px;
        }

        .form-control,
        .form-select{
            min-height:45px;
            border:1px solid var(--border);
            border-radius:9px;
            box-shadow:none !important;
        }

        .form-control:focus,
        .form-select:focus{
            border-color:var(--primary);
            box-shadow:0 0 0 3px rgba(83,71,206,.12) !important;
        }

        /* TABLE */

        .table-card{
            background:white;
            border-radius:16px;
            box-shadow:0 5px 20px rgba(0,0,0,.06);
            margin-top:25px;
            overflow:hidden;
        }

        .table-header{
            padding:22px 25px;
            border-bottom:1px solid var(--border);
        }

        .table-header h5{
            margin:0;
            font-size:18px;
            font-weight:700;
        }

        .pricing-table{
            margin:0;
            vertical-align:middle;
        }

        .pricing-table thead th{
            background:var(--light-purple);
            color:var(--primary);
            border:none;
            padding:15px 20px;
            font-size:13px;
            font-weight:700;
        }

        .pricing-table tbody td{
            padding:17px 20px;
            border-bottom:1px solid #EEF0F4;
            color:var(--text);
        }

        .pricing-table tbody tr:hover{
            background:#FAFAFF;
        }

        .rule-name{
            font-weight:700;
        }

        .rule-code{
            color:var(--text2);
            font-size:12px;
            margin-top:3px;
        }

    /* ================= BADGES ================= */

.custom-badge{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-width:95px;
    padding:7px 14px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
    color:#fff;
}

/* Status */

.status-active{
    background:#198754;
    color:#fff;
}

.status-inactive{
    background:#6C757D;
    color:#fff;
}

.status-scheduled{
    background:#2563EB;
    color:#fff;
}

/* Rule Type */

.type-discount{
    background:#5347CE;
    color:#fff;
}

.type-markup{
    background:#F4B400;
    color:#111827;
}

.type-volume{
    background:#16C8C7;
    color:#fff;
}


/* ================= ACTION BUTTONS ================= */

.action-btn{
    width:38px;
    height:38px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    border-radius:10px;
    border:1.5px solid;
    background:#fff;
    text-decoration:none;
    transition:all .25s ease;
    margin-right:6px;
}

/* View */

.view-btn{
    border-color:#2563EB;
    color:#2563EB;
}

.view-btn:hover{
    background:#2563EB;
    color:#fff;
}

/* Edit */

.edit-btn{
    border-color:#F4B400;
    color:#F4B400;
}

.edit-btn:hover{
    background:#F4B400;
    color:#111827;
}

/* Delete */

.delete-btn{
    border-color:#EF4444;
    color:#EF4444;
}

.delete-btn:hover{
    background:#EF4444;
    color:#fff;
}
    </style>
</head>

<body>


    <!-- PAGE CONTENT -->
    <div class="page-content">

        <!-- PAGE HEADER -->
        <div class="page-header">

            <div>
                <h2 class="page-title">Pricing Rules</h2>
                <p class="page-subtitle">
                    Manage product pricing, discounts, markups, and volume-based pricing rules.
                </p>
            </div>
<a href="{{ route('pricing.create') }}" class="new-btn">
    <i class="bi bi-plus-circle"></i>
    New Pricing Rule
</a>

        </div>

        <!-- KPI CARDS -->
        <div class="row g-4">

            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-top">
                        <div>
                            <div class="stat-label">Total Pricing Rules</div>
                            <div class="stat-number">24</div>
                        </div>

                        <div class="stat-icon">
                            <i class="bi bi-tags"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-top">
                        <div>
                            <div class="stat-label">Active Rules</div>
                            <div class="stat-number">18</div>
                        </div>

                        <div class="stat-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-top">
                        <div>
                            <div class="stat-label">Scheduled Rules</div>
                            <div class="stat-number">4</div>
                        </div>

                        <div class="stat-icon">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-top">
                        <div>
                            <div class="stat-label">Inactive Rules</div>
                            <div class="stat-number">2</div>
                        </div>

                        <div class="stat-icon">
                            <i class="bi bi-pause-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- FILTERS -->
        <div class="filter-card">

            <div class="row g-3">

                <div class="col-lg-6">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Search pricing rules..."
                        >
                    </div>
                </div>

                <div class="col-lg-3">
                    <select class="form-select">
                        <option>All Types</option>
                        <option>Discount</option>
                        <option>Markup</option>
                        <option>Volume Pricing</option>
                    </select>
                </div>

                <div class="col-lg-3">
                    <select class="form-select">
                        <option>All Status</option>
                        <option>Active</option>
                        <option>Scheduled</option>
                        <option>Inactive</option>
                    </select>
                </div>

            </div>

        </div>

        <!-- PRICING RULES TABLE -->
        <div class="table-card">

            <div class="table-header">
                <h5>Pricing Rules List</h5>
            </div>

            <div class="table-responsive">

                <table class="table pricing-table">

                    <thead>
                        <tr>
                            <th>Rule</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Applies To</th>
                            <th>Effective Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>
                                <div class="rule-name">
                                    VIP Customer Discount
                                </div>
                                <div class="rule-code">
                                    PR-001
                                </div>
                            </td>

                            <td>
                                <span class="custom-badge type-discount">
                                    Discount
                                </span>
                            </td>

                            <td>
                                <strong>10%</strong>
                            </td>

                            <td>
                                VIP Customers
                            </td>

                            <td>
                                July 1, 2026
                            </td>

                            <td>
                                <span class="custom-badge status-active">
                                    Active
                                </span>
                            </td>

                            <td>
                                <button class="action-btn view-btn">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <button class="action-btn edit-btn">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button class="action-btn delete-btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="rule-name">
                                    Bulk Order Discount
                                </div>
                                <div class="rule-code">
                                    PR-002
                                </div>
                            </td>

                            <td>
                                <span class="custom-badge type-volume">
                                    Volume Pricing
                                </span>
                            </td>

                            <td>
                                <strong>15%</strong>
                            </td>

                            <td>
                                Orders above ₱50,000
                            </td>

                            <td>
                                July 5, 2026
                            </td>

                            <td>
                                <span class="custom-badge status-active">
                                    Active
                                </span>
                            </td>

                            <td>
                                <button class="action-btn view-btn">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <button class="action-btn edit-btn">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button class="action-btn delete-btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="rule-name">
                                    Premium Product Markup
                                </div>
                                <div class="rule-code">
                                    PR-003
                                </div>
                            </td>

                            <td>
                                <span class="custom-badge type-markup">
                                    Markup
                                </span>
                            </td>

                            <td>
                                <strong>8%</strong>
                            </td>

                            <td>
                                Premium Products
                            </td>

                            <td>
                                July 15, 2026
                            </td>

                            <td>
                                <span class="custom-badge status-scheduled">
                                    Scheduled
                                </span>
                            </td>

                            <td>
                                <button class="action-btn view-btn">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <button class="action-btn edit-btn">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button class="action-btn delete-btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="rule-name">
                                    Old Customer Discount
                                </div>
                                <div class="rule-code">
                                    PR-004
                                </div>
                            </td>

                            <td>
                                <span class="custom-badge type-discount">
                                    Discount
                                </span>
                            </td>

                            <td>
                                <strong>5%</strong>
                            </td>

                            <td>
                                Returning Customers
                            </td>

                            <td>
                                June 1, 2026
                            </td>

                            <td>
                                <span class="custom-badge status-inactive">
                                    Inactive
                                </span>
                            </td>

                            <td>
                                <button class="action-btn view-btn">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <button class="action-btn edit-btn">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button class="action-btn delete-btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>
@endsection