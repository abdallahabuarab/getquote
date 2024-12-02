@extends('admin.admin_master')

@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Users and Providers</h4>
                </div>
            </div>
        </div>

        @include('portal.search.search', ['action' => route('userspa.index'), 'placeholder' => 'Search Users and Providers'])

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: white;">
                        <h4 class="card-title mb-0">Users and Providers</h4>
                        <a href="{{ route('userspa.create') }}" class="btn btn-primary btn-sm waves-effect waves-light">Add User</a>
                    </div>

                    <div class="card-body">
                        <table class="table table-striped dt-responsive nowrap" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Admins Name</th>
                                    <th>Admins Email</th>
                                    <th>Provider Name</th>
                                    <th>Provider Email</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a href="{{ route('userspa.edit',['user'=> $user->id]) }}" class="dropdown-item">Edit</a></li>
                                                    <li>
                                                        <form method="POST" action="{{ route('userspa.destroy', $user->id) }}" onsubmit="return confirm('Are you sure you want to delete this user?')">
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

                                @foreach($providers as $provider)
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $provider->name }}</td>
                                        <td>{{ $provider->email }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a href="{{ route('userspa.edit',['user'=> $provider->id]) }}" class="dropdown-item">Edit</a></li>
                                                    <li>
                                                        <form method="POST" action="{{ route('userspa.destroy', $provider->id) }}" onsubmit="return confirm('Are you sure you want to delete this provider?')">
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
                        {{ $users->appends(['search' => request()->query('search')])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
