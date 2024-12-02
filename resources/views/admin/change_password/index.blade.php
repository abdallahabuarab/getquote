@extends('admin.admin_master')
@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-8">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Change password</h4> <br><br>

                            @if(count($errors))
                                @foreach($errors->all() as $error)
                                    <p class="alert alert-danger alert-dismissable fade show">
                                        {{$error}}
                                    </p>
                                @endforeach
                            @endif


                            <form method="post" action="{{route('update.password')}}">
                                @csrf

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Old password</label>
                                    <div class="col-sm-10">
                                        <input name="old_password" class="form-control" type="password"
                                               id="old_password"
                                               value="">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">New password</label>
                                    <div class="col-sm-10">
                                        <input name="new_password" class="form-control" type="password" id="new_password"
                                               value="">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Confirm
                                        password</label>
                                    <div class="col-sm-10">
                                        <input name="confirm_password" class="form-control" type="password"
                                               id="confirm_password"
                                               value="">
                                    </div>
                                </div>

                                <input type="submit" class="btn btn-success waves-effect waves-light" value="Change password">

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
