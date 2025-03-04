@extends('admin.admin_master')

@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title and Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Providers</h4>
                </div>
            </div>
        </div>

        @include('portal.search.search', ['action' => route('providerspa.index'), 'placeholder' => 'Search Providers'])

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: white;">
                        <h4 class="card-title mb-0">Providers</h4>
                        <a href="{{ route('providerspa.create') }}" class="btn btn-primary btn-sm waves-effect waves-light">Add Provider</a>
                    </div>

                    <div class="card-body">
                        <table class="table table-striped dt-responsive nowrap" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Contact Phone</th>
                                    <th>Tax ID</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($providers as $provider)
                                    <tr>
                                        <td>{{ $provider->provider_id }}</td>
                                       <td> <a href="{{ route('providerspa.show', $provider->provider_id) }}">{{ $provider->user->name }}</a></td>
                                        <td>{{ $provider->user->email }}</td>
                                        <td>{{ $provider->contact_phone }}</td>
                                        <td>{{ $provider->tax_id }}</td>
                                        <td>
                                            <i class="ri-checkbox-blank-circle-fill font-size-10 {{ $provider->is_active === 'yes' ? 'text-success' : 'text-danger' }} align-middle me-2"></i>
                                            {{ $provider->is_active === 'yes' ? 'Active' : 'Inactive' }}
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a href="{{ route('providerspa.edit', $provider->provider_id) }}" class="dropdown-item">Edit</a></li>
                                                    <li>
                                                        <form method="POST" action="{{ route('providerspa.destroy',['provider'=> $provider->provider_id]) }}" onsubmit="return confirm('Are you sure you want to delete this provider?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item">Delete</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $providers->appends(['search' => request()->query('search')])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
