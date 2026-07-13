@extends('layouts.app')

@section('title', 'Customer Relationship Management')
@section('page-title', 'Customer Relationship Management')

@section('content')

<div class="container-fluid">

    <div class="row g-4">

        <div class="col-md-3">
            <div class="card p-4">
                <h6>Total Customers</h6>
                <h2>1,245</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-4">
                <h6>Active Customers</h6>
                <h2>987</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-4">
                <h6>Loyalty Members</h6>
                <h2>654</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-4">
                <h6>Pending Follow-Ups</h6>
                <h2>18</h2>
            </div>
        </div>

    </div>

    <div class="row mt-4">

        <div class="col-lg-8">

            <div class="card p-4">

                <h5 class="fw-bold mb-3">
                    Recent Customer Activities
                </h5>

                <table class="table">

                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Activity</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>ABC Corporation</td>
                            <td>Placed New Order</td>
                            <td>July 7, 2026</td>
                        </tr>

                        <tr>
                            <td>XYZ Trading</td>
                            <td>Updated Profile</td>
                            <td>July 6, 2026</td>
                        </tr>

                        <tr>
                            <td>John Smith</td>
                            <td>Redeemed Loyalty Points</td>
                            <td>July 5, 2026</td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="card p-4">

                <h5 class="fw-bold mb-3">
                    CRM Summary
                </h5>

                <ul class="list-group list-group-flush">

                    <li class="list-group-item d-flex justify-content-between">
                        New Customers
                        <strong>15</strong>
                    </li>

                    <li class="list-group-item d-flex justify-content-between">
                        Today's Follow-Ups
                        <strong>7</strong>
                    </li>

                    <li class="list-group-item d-flex justify-content-between">
                        Support Requests
                        <strong>4</strong>
                    </li>

                    <li class="list-group-item d-flex justify-content-between">
                        VIP Customers
                        <strong>82</strong>
                    </li>

                </ul>

            </div>

        </div>

    </div>

</div>

@endsection