@extends('admin.admin_master')

@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title and Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Orders</h4>
                </div>
            </div>
        </div>

        @include('portal.search.search', ['action' => route('orders.index'), 'placeholder' => 'Search Orders'])

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: white;">
                        <h4 class="card-title mb-0">Orders</h4>
                    </div>

                    <div class="card-body">
                        <table class="table table-striped dt-responsive nowrap" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Provider Name</th>
                                    <th>Customer Name</th>
                                    <th>Service Price</th>
                                    <th>Created At</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->provider->user->name }}</td>
                                        <td>{{ $order->customer->customer_name }}</td>
                                        <td>{{ $order->request->category->base_price }}</td>
                                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="text-end">
                                            <a href="{{route('orders.show',$order->id)}}" class="btn btn-sm btn-primary">Show details</a>
                                            <form method="POST" action="{{ route('orders.destroy', $order->id) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this order?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $orders->appends(['search' => request()->query('search')])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
