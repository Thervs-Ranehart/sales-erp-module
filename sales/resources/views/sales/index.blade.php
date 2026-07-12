@extends('layouts.app')

@section('content')
    @php($title = 'Sales Order Management')
    @php($subtitle = 'Manage customer orders and quotation workflow')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle, 'actions' => '<button class="btn btn-primary"><i class="bi bi-plus-circle"></i> New Sales Order</button>'])

    <div class="card p-4">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Order No.</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>SO-001</td>
                    <td>ABC Company</td>
                    <td>07/07/2026</td>
                    <td><span class="badge bg-success">Completed</span></td>
                    <td>₱25,000</td>
                    <td>
                        <button class="btn btn-info btn-sm"><i class="bi bi-eye"></i></button>
                        <button class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>SO-002</td>
                    <td>XYZ Trading</td>
                    <td>07/06/2026</td>
                    <td><span class="badge bg-warning">Pending</span></td>
                    <td>₱18,500</td>
                    <td>
                        <button class="btn btn-info btn-sm"><i class="bi bi-eye"></i></button>
                        <button class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>SO-003</td>
                    <td>John Doe</td>
                    <td>07/05/2026</td>
                    <td><span class="badge bg-primary">Processing</span></td>
                    <td>₱31,200</td>
                    <td>
                        <button class="btn btn-info btn-sm"><i class="bi bi-eye"></i></button>
                        <button class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
