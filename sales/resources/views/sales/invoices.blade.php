@extends('layouts.app')

@section('title', 'Invoice')
@section('page-title', 'Sales Order Management')

@section('content')

    <style>
         .action-buttons{

    display:flex;
    gap:8px;
}

.action-btn{

    width:36px;
    height:36px;

    border-radius:8px;

    display:flex;

    align-items:center;

    justify-content:center;

    text-decoration:none;

    border:1px solid #dcdcdc;

    background:white;

    transition:.2s;
}

.view-btn:hover{

    background:#4896FE;
    color:white;

}

.edit-btn:hover{

    background:#16C8C7;
    color:white;

}

.delete-btn:hover{

    background:#dc3545;
    color:white;

}

.filter-btn.active{

    background:#5347CE;

    color:white;

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

      
        /* PAGE */

        .page-content{
            padding:28px;
        }

        .page-header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:22px;
        }

        .page-title{
            margin:0;
            font-size:28px;
            font-weight:700;
        }

        .page-subtitle{
            margin:5px 0 0;
            color:var(--text2);
        }

        .new-btn{
            display:inline-flex;
            align-items:center;
            gap:7px;
            background:var(--primary);
            color:white;
            border:none;
            padding:11px 20px;
            border-radius:8px;
            font-weight:600;
            text-decoration:none;
        }

        .new-btn:hover{
            background:var(--secondary);
            color:white;
        }

        /* SEARCH */

        .toolbar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:15px;
            margin-bottom:20px;
        }

        .search-box{
            width:380px;
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
            width:100%;
            border:1px solid var(--border);
            border-radius:25px;
            padding:11px 15px 11px 42px;
            outline:none;
        }

        /* FILTERS */

        .filter-buttons{
            display:flex;
            flex-wrap:wrap;
            gap:12px;
            margin-bottom:22px;
        }

        .filter-btn{
            border:none;
            background:white;
            color:var(--primary);
            padding:10px 22px;
            border-radius:25px;
            font-size:14px;
            box-shadow:0 2px 8px rgba(0,0,0,.04);
        }

        .filter-btn.active{
            background:#DCD8FF;
            color:#4035A8;
            font-weight:600;
        }

        /* TABLE */

        .table-card{
            background:white;
            border-radius:16px;
            padding:20px;
            box-shadow:0 5px 20px rgba(0,0,0,.06);
        }

        .table{
            margin-bottom:0;
            vertical-align:middle;
        }

        .table thead th{
            background:var(--light-purple);
            color:var(--primary);
            border-bottom:2px solid var(--primary);
            padding:15px 12px;
            font-size:13px;
            white-space:nowrap;
        }

        .table tbody td{
            padding:16px 12px;
            border-color:#E8EAF0;
            font-size:14px;
            white-space:nowrap;
        }
/* ================= STATUS ================= */

.status{
    display:inline-block;
    min-width:95px;
    padding:6px 14px;
    border-radius:50px;
    text-align:center;
    font-size:12px;
    font-weight:600;
    color:#fff;
}

/* Bright colors */

.status-paid,
.status-approved{
    background:#198754;   /* Green */
}

.status-pending{
    background:#FFC107;   /* Yellow */
    color:#212529;
}

.status-overdue,
.status-rejected{
    background:#DC3545;   /* Red */
}

.status-draft{
    background:#6C757D;   /* Gray */
}

.status-shipped{
    background:#0DCAF0;   /* Cyan */
}

.status-processed{
    background:#0D6EFD;   /* Blue */
}


.action-btn{
    width:36px;
    height:36px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    border:1px solid transparent;
    border-radius:8px;
    background:#fff;
    text-decoration:none;
    transition:all .2s ease;
    margin-right:4px;
}

/* View */
.view-btn{
    border-color:#0D6EFD;
    color:#0D6EFD;
}

.view-btn:hover{
    background:#0D6EFD;
    color:#fff;
}

/* Edit */
.edit-btn{
    border-color:#FFC107;
    color:#FFC107;
}

.edit-btn:hover{
    background:#FFC107;
    color:#212529;
}

/* Download */
.download-btn{
    border-color:#198754;
    color:#198754;
}

.download-btn:hover{
    background:#198754;
    color:#fff;
}

/* Delete */
.delete-btn{
    border-color:#DC3545;
    color:#DC3545;
}

.delete-btn:hover{
    background:#DC3545;
    color:#fff;
}
    </style>
</head>

<body>

    <!-- PAGE CONTENT -->
<div class="page-content">

    <div class="page-header">

        <div>

            <h2 class="page-title">
                Invoices
            </h2>

            <p class="page-subtitle">
                Manage all customer invoices.
            </p>

        </div>

        <a
            href="{{ route('invoices.create') }}"
            class="new-btn"
        >
            <i class="bi bi-plus-circle"></i>
            New Invoice
        </a>

    </div>

    <!-- KPI -->

    <div class="row g-4">

        <div class="col-lg-3">

            <div class="stat-card">

                <div class="stat-top">

                    <div>

                        <div class="stat-label">
                            Total Invoices
                        </div>

                        <div class="stat-number">
                            {{ $statusCounts['all'] }}
                        </div>

                    </div>

                    <div class="stat-icon icon-purple">

                        <i class="bi bi-receipt"></i>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="stat-card">

                <div class="stat-top">

                    <div>

                        <div class="stat-label">
                            Paid
                        </div>

                        <div class="stat-number">
                            {{ $statusCounts['paid'] }}
                        </div>

                    </div>

                    <div class="stat-icon icon-green">

                        <i class="bi bi-check-circle"></i>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="stat-card">

                <div class="stat-top">

                    <div>

                        <div class="stat-label">
                            Pending
                        </div>

                        <div class="stat-number">
                            {{ $statusCounts['pending'] }}
                        </div>

                    </div>

                    <div class="stat-icon icon-yellow">

                        <i class="bi bi-hourglass"></i>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="stat-card">

                <div class="stat-top">

                    <div>

                        <div class="stat-label">
                            Cancelled
                        </div>

                        <div class="stat-number">
                            {{ $statusCounts['cancelled'] }}
                        </div>

                    </div>

                    <div class="stat-icon icon-red">

                        <i class="bi bi-x-circle"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Search -->

    <div class="filter-card mt-4">

        <div class="search-box">

            <i class="bi bi-search"></i>

            <input
                type="text"
                class="form-control"
                id="invoiceSearch"
                placeholder="Search Invoice..."
                onkeyup="searchInvoice()"
            >

        </div>

    </div>

    <!-- Status Buttons -->

    <div class="filter-buttons mt-4">

        <button
            class="filter-btn active"
            onclick="filterInvoice('all',this)"
        >
            All ({{ $statusCounts['all'] }})
        </button>

        <button
            class="filter-btn"
            onclick="filterInvoice('paid',this)"
        >
            Paid ({{ $statusCounts['paid'] }})
        </button>

        <button
            class="filter-btn"
            onclick="filterInvoice('pending',this)"
        >
            Pending ({{ $statusCounts['pending'] }})
        </button>

        <button
            class="filter-btn"
            onclick="filterInvoice('cancelled',this)"
        >
            Cancelled ({{ $statusCounts['cancelled'] }})
        </button>

    </div>

    <!-- Table -->

    <div class="table-card mt-4">

        <div class="table-header">

            <h5>
                Invoice List
            </h5>

        </div>

        <div class="table-responsive">

            <table class="table pricing-table">

                <thead>

                    <tr>

                        <th>Invoice No.</th>

                        <th>Customer</th>

                        <th>Date</th>

                        <th>Total</th>

                        <th>Status</th>

                        <th>Actions</th>

                    </tr>

                </thead>

                <tbody>
@forelse($invoices as $invoice)

<tr
    data-status="{{ strtolower($invoice->payment_status) }}"
>

    <td>

        <strong>
            {{ $invoice->invoice_number }}
        </strong>

    </td>

    <td>

        {{ optional($invoice->salesOrder->customer)->full_name }}

    </td>

    <td>

        {{ optional($invoice->invoice_date)->format('M d, Y') }}

    </td>

    <td>

        ₱{{ number_format($invoice->total_amount,2) }}

    </td>

    <td>

        @php

            $status = strtolower($invoice->payment_status);

            $badge = match($status){

                'paid' => 'success',

                'pending' => 'warning',

                'cancelled' => 'danger',

                default => 'secondary'

            };

        @endphp

        <span class="badge bg-{{ $badge }}">

            {{ ucfirst($invoice->payment_status) }}

        </span>

    </td>

    <td>

        <div class="action-buttons">

            <a
                href="{{ route('invoices.show',$invoice) }}"
                class="action-btn view-btn"
                title="View"
            >

                <i class="bi bi-eye"></i>

            </a>

            <a
                href="{{ route('invoices.edit',$invoice) }}"
                class="action-btn edit-btn"
                title="Edit"
            >

                <i class="bi bi-pencil"></i>

            </a>

            <form
                action="{{ route('invoices.destroy',$invoice) }}"
                method="POST"
                style="display:inline;"
            >

                @csrf
                @method('DELETE')

                <button
                    class="action-btn delete-btn"
                    onclick="return confirm('Delete this invoice?')"
                >

                    <i class="bi bi-trash"></i>

                </button>

            </form>

        </div>

    </td>

</tr>

@empty

<tr>

    <td colspan="6" class="text-center py-5">

        No invoices found.

    </td>

</tr>

@endforelse

</tbody>

</table>

</div>

</div>


<script>

function searchInvoice() {

    let input = document
        .getElementById("invoiceSearch")
        .value
        .toLowerCase();

    let rows = document.querySelectorAll("tbody tr");

    rows.forEach(function(row){

        let text = row.innerText.toLowerCase();

        row.style.display =
            text.includes(input)
                ? ""
                : "none";

    });

}

function filterInvoice(status, button){

    document.querySelectorAll(".filter-btn")
        .forEach(btn => btn.classList.remove("active"));

    button.classList.add("active");

    let rows = document.querySelectorAll("tbody tr");

    rows.forEach(function(row){

        if(status === "all"){

            row.style.display = "";

            return;

        }

        if(row.dataset.status === status){

            row.style.display = "";

        }

        else{

            row.style.display = "none";

        }

    });

}

</script>
@endsection