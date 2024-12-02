@extends('admin.admin_master')

@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- Page Title and Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Order Details</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Orders</a></li>
                            <li class="breadcrumb-item active">Order Details</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Order Details</h4>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 20%;">Customer Name:</th>
                                    <td>{{ $order->customer->customer_name }}</td>
                                </tr>
                                <tr>
                                    <th>Customer Email:</th>
                                    <td>{{ $order->customer->email }}</td>
                                </tr>
                                <tr>
                                    <th>Customer Contact:</th>
                                    <td>{{ $order->customer->customer_contact }}</td>
                                </tr>
                                <tr>
                                    <th>Provider Name:</th>
                                    <td>{{ $order->provider->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Provider Email:</th>
                                    <td>{{ $order->provider->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Provider Contact:</th>
                                    <td>{{ $order->provider->contact_phone }}</td>
                                </tr>
                                <tr>
                                    <th>Address Point 1:</th>
                                    <td>{{ $order->request->address_point1 }}</td>
                                </tr>
                                <tr>
                                    <th>Address Point 2:</th>
                                    <td>{{ $order->request->address_point2 }}</td>
                                </tr>
                                <tr>
                                    <th>Class:</th>
                                    <td>{{ $order->request->category->class->type }}</td>
                                </tr>
                                <tr>
                                    <th>Service:</th>
                                    <td>{{ $order->request->category->service->name }}</td>
                                </tr>
                                <tr>
                                    <th>Price:</th>
                                    <td>{{ $order->request->category->base_price }}</td>
                                </tr>
                                <tr>
                                    <th>Order Created At:</th>
                                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-4">
                            <a href="{{ route('orders.index') }}" class="btn btn-primary">Back to Orders</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
