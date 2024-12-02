@extends('admin.admin_master')

@section('admin')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Rejects</h4>
        </div>
    </div>
</div>
<div class="page-content">
    <div class="container-fluid">
        @include('portal.search.search', ['action' => route('rejects.index'), 'placeholder' => 'Search for a Reject'])

        <!-- Rejects Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Rejects</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Zipcode</th>
                                        <th>Country</th>
                                        <th>City</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rejects as $reject)
                                        <tr>
                                            <td>{{ $reject->reject_zipcode }}</td>
                                            <td>{{ $reject->reject_ip_country }}</td>
                                            <td>{{ $reject->reject_ip_city }}</td>
                                            <td>{{ $reject->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $rejects->appends(['search' => request()->query('search')])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
