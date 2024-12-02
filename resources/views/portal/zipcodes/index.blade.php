@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4>Zipcodes</h4>
                </div>
            </div>
        </div>
        @include('portal.search.search', ['action' => route('zipcodes.index'), 'placeholder' => 'Search for a ZipCode'])

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: white;">
                        <h4 class="card-title mb-0">Zipcodes</h4>
                        {{-- <a href="{{ route('zipcodes.create') }}" class="btn btn-primary btn-sm waves-effect waves-light">Add Zipcodes</a> --}}
                    </div>

                    <div class="card-body">
                        <table class="table table-striped dt-responsive nowrap" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>ZipCode</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Population</th>
                                    <th>Density</th>
                                    <th>Country</th>
                                    <th>Time Zone</th>
                                    {{-- <th class="text-end">Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($zipcodes as $zipcode)
                                    <tr>
                                        <td>{{ $zipcode->zipcode }}</td>
                                        <td>{{ $zipcode->city }}</td>
                                        <td>{{ $zipcode->state }}</td>
                                        <td>{{ $zipcode->population }}</td>
                                        <td>{{ $zipcode->density }}</td>
                                        <td>{{ $zipcode->country }}</td>
                                        <td>{{ $zipcode->timezone }}</td>
                                        {{-- <td class="text-end">
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a href="{{route('zipcodes.edit',['zipcode'=>$zipcode->zip_code])}}" class="dropdown-item">Edit</a></li>
                                                    <li>
                                                        <form method="POST" action="{{route('zipcodes.destroy',['zipcode'=>$zipcode->zip_code])}}" onsubmit="return confirm('Are you sure you want to delete this zipcode?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item">Delete</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $zipcodes->appends(['search' => request()->query('search')])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
