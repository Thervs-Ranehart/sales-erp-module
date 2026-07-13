@extends('layouts.app')

@section('title', 'Quotations')
@section('page-title', 'Sales Order Management')

@section('content')

    <style>

        <head>
    <!-- Bootstrap links -->

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

        

        /* ================= PAGE ================= */

        .page-content{
            padding:28px;
        }

        .page-header{
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:22px;
        }

        .page-title{
            margin:0;
            font-size:28px;
            font-weight:700;
        }

        .new-btn{
            background:var(--primary);
            color:white;
            border:none;
            padding:11px 20px;
            border-radius:8px;
            font-weight:600;
        }

        .new-btn:hover{
            background:var(--secondary);
            color:white;
        }

        /* ================= SEARCH ================= */

        .toolbar{
            display:flex;
            align-items:center;
            gap:15px;
            margin-bottom:20px;
        }

        .search-box{
            width:350px;
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

        .search-box input:focus{
            border-color:var(--primary);
        }

        /* ================= FILTERS ================= */

        .filter-buttons{
            display:flex;
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
            color:var(--primary);
            font-weight:600;
        }

        /* ================= TABLE CARD ================= */

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
           background:#EEECFF;
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
        }

 .status{
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

.status-draft{
    background:#5347CE;
}

.status-pending{
    background:#F4B400;
    color:#111827;
}

.status-approved{
    background:#16A34A;
}

.status-rejected{
    background:#EF4444;
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

/* Responsive */

@media(max-width:900px){

    .sidebar{
        width:220px;
    }

    .main-content{
        margin-left:220px;
    }

}
    </style>
</head>

<body>



    <div class="page-content">

        <div class="page-header">

            <h2 class="page-title">
                Quotations
            </h2>
<a href="{{ route('quotations.create') }}" class="new-btn text-decoration-none">
    <i class="bi bi-plus-circle me-1"></i>
    New Quotation
</a>

        </div>


        <!-- SEARCH -->

        <div class="toolbar">

            <div class="search-box">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    id="quotationSearch"
                    placeholder="Search Quotation ID or Customer Name"
                    onkeyup="searchQuotation()"
                >

            </div>

        </div>

<div class="filter-buttons">

    <button class="filter-btn active" onclick="filterQuotation('all', this)">
        All ({{ $statusCounts['all'] }})
    </button>

    <button class="filter-btn" onclick="filterQuotation('draft', this)">
        Draft ({{ $statusCounts['draft'] }})
    </button>

    <button class="filter-btn" onclick="filterQuotation('sent', this)">
        Sent ({{ $statusCounts['sent'] }})
    </button>

    <button class="filter-btn" onclick="filterQuotation('accepted', this)">
        Accepted ({{ $statusCounts['accepted'] }})
    </button>

    <button class="filter-btn" onclick="filterQuotation('rejected', this)">
        Rejected ({{ $statusCounts['rejected'] }})
    </button>

</div>

        <!-- TABLE -->

        <div class="table-card">

            <div class="table-responsive">

                <table class="table" id="quotationTable">

                    <thead>

                        <tr>
                            <th>Quotation ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Valid Until</th>
                            <th>Subtotal</th>
                            <th>Discount</th>
                            <th>Tax</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

@forelse($quotations as $quotation)

<tr data-status="{{ strtolower($quotation->quotation_status) }}">

    <td>{{ $quotation->quotation_number }}</td>

    <td>
        {{ $quotation->customer?->full_name ?? 'N/A' }}
    </td>

    <td>
        {{ $quotation->quotation_date?->format('M d, Y') }}
    </td>

    <td>
        {{ $quotation->valid_until?->format('M d, Y') }}
    </td>

    <td>
        ₱{{ number_format((float)$quotation->subtotal,2) }}
    </td>

    <td>
        ₱{{ number_format((float)$quotation->discount,2) }}
    </td>

    <td>
        ₱{{ number_format((float)$quotation->tax,2) }}
    </td>

    <td>
        ₱{{ number_format((float)$quotation->total_amount,2) }}
    </td>

    <td>

        @php
            $status = strtolower($quotation->quotation_status);

            $class = match($status){
                'draft' => 'status-draft',
                'pending' => 'status-pending',
                'approved' => 'status-approved',
                'accepted' => 'status-approved',
                'rejected' => 'status-rejected',
                default => 'status-draft'
            };
        @endphp

        <span class="status {{ $class }}">
            {{ ucfirst($quotation->quotation_status) }}
        </span>

    </td>

    <td>

        <a href="{{ route('quotations.show',$quotation) }}" class="action-btn view-btn">
            <i class="bi bi-eye"></i>
        </a>

        <a href="{{ route('quotations.edit',$quotation) }}" class="action-btn edit-btn">
            <i class="bi bi-pencil"></i>
        </a>

        <form action="{{ route('quotations.destroy',$quotation) }}"
              method="POST"
              style="display:inline;">

            @csrf
            @method('DELETE')

            <button class="action-btn delete-btn"
                    onclick="return confirm('Delete this quotation?')">

                <i class="bi bi-trash"></i>

            </button>

        </form>

    </td>

</tr>

@empty

<tr>

    <td colspan="10" class="text-center py-5">

        No quotations found.

    </td>

</tr>

@endforelse

</tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<script>

    function filterQuotation(status, button){

        const rows = document.querySelectorAll(
            '#quotationTable tbody tr'
        );

        const buttons = document.querySelectorAll(
            '.filter-btn'
        );

        buttons.forEach(btn => {
            btn.classList.remove('active');
        });

        button.classList.add('active');

        rows.forEach(row => {

            if(
                status === 'all' ||
                row.dataset.status === status
            ){
                row.style.display = '';
            }
            else{
                row.style.display = 'none';
            }

        });
    }


    function searchQuotation(){

        const input = document
            .getElementById('quotationSearch')
            .value
            .toLowerCase();

        const rows = document.querySelectorAll(
            '#quotationTable tbody tr'
        );

        rows.forEach(row => {

            const text = row
                .innerText
                .toLowerCase();

            row.style.display =
                text.includes(input)
                ? ''
                : 'none';

        });
    }

</script>

</body>
</html>

@endsection