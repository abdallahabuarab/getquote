@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-6">
                <div class="card"> <br>
                    <center>
                        <img class="rounded-circle avatar-xl"
                             src="{{(!empty($adminUser->image)) ? url('upload/admin_images/'.$adminUser->image) : url('upload/default_image.png')}}" alt="Card image cap">
                    </center>

                    <div class="card-body">
                        <h4 class="card-title">Name: {{ $adminUser->name }}</h4>
                        <hr>
                        <h4 class="card-title">Email: {{ $adminUser->email }}</h4>
                        <hr>
                        <a href="{{ route('edit.profile') }}" class="btn btn-info btn-rounded waves-effect waves-light">Edit Profile</a>
                    </div>
                </div>
            </div>

            @if($adminUser->provider)
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Assigned Zip Codes</h4>
                        <hr>
                            <ul class="list-group">
                                @foreach ($adminUser->provider->zipCodes as $zipCode)
                                    <li class="list-group-item">{{ $zipCode->zipcode }}</li>
                                @endforeach
                            </ul>

                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection