@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4>services</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: white;">
                        <h4 class="card-title mb-0">services</h4>
                        {{-- <a href="{{ route('services.create') }}" class="btn btn-primary btn-sm waves-effect waves-light">Add services</a> --}}
                    </div>

                    <div class="card-body">
                        <table class="table table-striped dt-responsive nowrap" style="width: 100%">
                            <thead>
                                <tr>

                                    <th>No</th>
                                    <th>service Name</th>
                                    {{-- <th class="text-end">Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($services as $service)
                                    <tr>
                                        <td>{{ $service->service_id }}</td>
                                        <td>{{ $service->name }}</td>

                                        {{-- <td class="text-end">
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a href="{{route('services.edit',['service'=>$service->zip_code])}}" class="dropdown-item">Edit</a></li>
                                                    <li>
                                                        <form method="POST" action="{{route('services.destroy',['service'=>$service->zip_code])}}" onsubmit="return confirm('Are you sure you want to delete this service?')">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
