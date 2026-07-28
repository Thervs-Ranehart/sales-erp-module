@extends('layouts.app')

@section('title', 'Invoice')
@section('page-title', 'Sales Order Management')

@section('content')

    <style>
.action-buttons{

    display:flex;
    flex-wrap:wrap;
    align-items:center;
    gap:8px;
}

.invoice-status-actions{
    display:flex;
    flex-wrap:wrap;
    gap:7px;
}

.invoice-status-actions form{ margin:0; }

.invoice-status-btn{
    border:1px solid #5347CE;
    border-radius:7px;
    background:#fff;
    color:#5347CE;
    font-size:11px;
    font-weight:700;
    line-height:1;
    padding:7px 10px;
    transition:background .2s ease, color .2s ease, transform .2s ease;
}

.invoice-status-btn:hover{ background:#5347CE; color:#fff; transform:translateY(-1px); }
.invoice-status-btn--paid{ border-color:#16A34A; color:#15803D; }
.invoice-status-btn--paid:hover{ background:#16A34A; }
.invoice-status-btn--expired{ border-color:#6B7280; color:#4B5563; }
.invoice-status-btn--expired:hover{ background:#4B5563; }

.invoice-payment-modal .modal-content{ border:0; border-radius:18px; overflow:hidden; box-shadow:0 18px 45px rgba(31,41,55,.18); }
.invoice-payment-modal .modal-header{ padding:22px 24px 18px; border:0; background:#f7f6ff; }
.invoice-payment-modal .modal-title{ color:#312a84; font-weight:700; }
.invoice-payment-modal .modal-body{ padding:22px 24px; }
.invoice-payment-details{ padding:15px; border:1px solid #e7e5ff; border-radius:12px; background:#fafaff; }
.invoice-payment-details small{ display:block; color:#6b7280; margin-bottom:4px; }
.invoice-payment-details strong{ color:#1f2937; }
.invoice-payment-total{ color:#5347ce !important; font-size:18px; }
.invoice-payment-modal .modal-footer{ padding:16px 24px 22px; border:0; }

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

        /* KPI STAT CARDS */

        .stat-card{
            background:white;
            border-radius:16px;
            padding:20px;
            box-shadow:0 5px 20px rgba(0,0,0,.06);
            height:100%;
        }

        .stat-top{
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
        }

        .stat-label{
            color:var(--text2);
            font-size:14px;
            margin-bottom:6px;
        }

        .stat-number{
            font-size:28px;
            font-weight:700;
            color:var(--text);
        }

        .stat-icon{
            width:46px;
            height:46px;
            border-radius:12px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:20px;
            flex-shrink:0;
        }

        .icon-purple{
            background:var(--light-purple);
            color:var(--primary);
        }

        .icon-green{
            background:#D1F7E7;
            color:#198754;
        }

        .icon-yellow{
            background:#FFF3CD;
            color:#B8860B;
        }

        .icon-red{
            background:#F8D7DA;
            color:#DC3545;
        }

        /* FILTER / SEARCH CARD */

        .filter-card{
            background:white;
            border-radius:16px;
            padding:20px;
            box-shadow:0 5px 20px rgba(0,0,0,.06);
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
.status-rejected,
.status-expired,
.status-cancelled{
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
            onclick="filterInvoice('expired',this)"
        >
            Expired ({{ $statusCounts['expired'] }})
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

        @endphp

        <span class="status status-{{ $status }}">

            {{ ucfirst($invoice->payment_status) }}

        </span>

    </td>

    <td>

        <div class="action-buttons">

            @if($invoice->payment_status === 'Paid')
                <a href="{{ route('invoices.show', $invoice) }}" class="invoice-status-btn">
                    View Receipt
                </a>
            @else
                <a
                    href="{{ route('invoices.show',$invoice) }}"
                    class="action-btn view-btn"
                    title="View invoice"
                >

                    <i class="bi bi-eye"></i>

                </a>
            @endif

            @if($invoice->payment_status === 'Pending')
                <div class="invoice-status-actions">
                    <form action="{{ route('invoices.update-payment-status', $invoice) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="payment_status" value="Paid">
                        <button type="button" class="invoice-status-btn invoice-status-btn--paid js-invoice-payment-action"
                            data-bs-toggle="modal" data-bs-target="#invoicePaymentModal"
                            data-status="Paid" data-action="{{ route('invoices.update-payment-status', $invoice) }}"
                            data-number="{{ $invoice->invoice_number }}" data-customer="{{ optional($invoice->salesOrder->customer)->full_name }}"
                            data-total="₱{{ number_format($invoice->total_amount, 2) }}">Mark as Paid</button>
                    </form>

                    <form action="{{ route('invoices.update-payment-status', $invoice) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="payment_status" value="Expired">
                        <button type="button" class="invoice-status-btn invoice-status-btn--expired js-invoice-payment-action"
                            data-bs-toggle="modal" data-bs-target="#invoicePaymentModal"
                            data-status="Expired" data-action="{{ route('invoices.update-payment-status', $invoice) }}"
                            data-number="{{ $invoice->invoice_number }}" data-customer="{{ optional($invoice->salesOrder->customer)->full_name }}"
                            data-total="₱{{ number_format($invoice->total_amount, 2) }}">Expire</button>
                    </form>
                </div>
            @endif

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
                    title="Cancel invoice or request manager approval"
                >

                    <i class="bi bi-x-circle"></i>

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


<div class="modal fade invoice-payment-modal" id="invoicePaymentModal" tabindex="-1" aria-labelledby="invoicePaymentModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" id="invoicePaymentForm">
            @csrf
            @method('PATCH')
            <input type="hidden" name="payment_status" id="invoicePaymentStatus">
            <div class="modal-header">
                <div>
                    <div class="small text-uppercase fw-bold text-muted mb-1">Invoice payment update</div>
                    <h5 class="modal-title" id="invoicePaymentModalTitle">Update invoice</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3" id="invoicePaymentMessage"></p>
                <div class="invoice-payment-details">
                    <div class="d-flex justify-content-between gap-3 mb-3"><small>Invoice</small><strong id="invoicePaymentNumber"></strong></div>
                    <div class="d-flex justify-content-between gap-3 mb-3"><small>Customer</small><strong class="text-end" id="invoicePaymentCustomer"></strong></div>
                    <div class="d-flex justify-content-between gap-3"><small>Total amount</small><strong class="invoice-payment-total" id="invoicePaymentTotal"></strong></div>
                </div>
                <p class="small text-danger mt-3 mb-0 d-none" id="invoiceExpiryNotice">Expiring this invoice reverses its inventory and finance entries.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="invoicePaymentSubmit">Confirm</button>
            </div>
        </form>
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

document.querySelectorAll('.js-invoice-payment-action').forEach(function(button){
    button.addEventListener('click', function(){
        const isPaid=this.dataset.status === 'Paid';
        document.getElementById('invoicePaymentForm').action=this.dataset.action;
        document.getElementById('invoicePaymentStatus').value=this.dataset.status;
        document.getElementById('invoicePaymentNumber').textContent=this.dataset.number;
        document.getElementById('invoicePaymentCustomer').textContent=this.dataset.customer;
        document.getElementById('invoicePaymentTotal').textContent=this.dataset.total;
        document.getElementById('invoicePaymentModalTitle').textContent=isPaid ? 'Mark invoice as paid' : 'Expire invoice';
        document.getElementById('invoicePaymentMessage').textContent=isPaid
            ? 'Confirm that payment has been received for this invoice.'
            : 'Confirm that this invoice is no longer valid.';
        document.getElementById('invoicePaymentSubmit').textContent=isPaid ? 'Mark as Paid' : 'Expire Invoice';
        document.getElementById('invoicePaymentSubmit').className=isPaid ? 'btn btn-success' : 'btn btn-outline-danger';
        document.getElementById('invoiceExpiryNotice').classList.toggle('d-none', isPaid);
    });
});

</script>
@endsection
