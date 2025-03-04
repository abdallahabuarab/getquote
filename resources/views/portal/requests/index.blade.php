@extends('admin.admin_master')

@section('admin')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Requests</h4>


        </div>
    </div>
</div>
<div class="page-content">
    <div class="container-fluid">
        @include('portal.search.search', ['action' => route('requests.index'), 'placeholder' => 'Search for a Request'])


        <!-- Requests Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Requests</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Request Zipcode</th>
                                        <th>Request Country</th>
                                        <th>Request City</th>
                                        <th>Request Class</th>
                                        <th>Request Service</th>
                                        <th>Request Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                        <tr>
                                            <td>{{ $request->request_zipcode }}</td>
                                            <td>{{ $request->request_ip_country }}</td>
                                            <td>{{ $request->request_ip_city }}</td>
                                            <td>{{ $request->classModel->name }}</td>
                                            <td>{{ $request->service->name }}</td>
                                            <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $requests->appends(['search' => request()->query('search')])->links() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
