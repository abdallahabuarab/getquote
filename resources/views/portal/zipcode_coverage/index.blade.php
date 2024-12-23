@extends('admin.admin_master')

@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <h4 class="mb-4">ZIP Code Coverage</h4>

        <!-- Display ZIP Code Coverage -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-primary">Current ZIP Code Coverage</h5>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ZIP Code</th>
                            <th>City</th>
                            <th>State</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($zipcodes as $zipcode)
                            <tr>

                                <td>{{ str_pad($zipcode->zipcode, 5,'0',STR_PAD_LEFT) }}</td>
                                <td>{{ $zipcode->zipcodeReference->city }}</td>
                                <td>{{ $zipcode->zipcodeReference->state }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">No ZIP Codes available for this provider.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
