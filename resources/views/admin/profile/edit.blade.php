@extends('admin.admin_master')
@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Edit profile</h4>
                            <form method="post" action="{{route('store.profile')}}" enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Name</label>
                                    <div class="col-sm-10">
                                        <input name="name" class="form-control" type="text" id="name"
                                               value="{{$editData->name}}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input name="email" class="form-control" type="email" id="email"
                                               value="{{$editData->email}}">
                                    </div>
                                </div>



                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Profile image</label>
                                    <div class="col-sm-10">
                                        <input name="image" class="form-control" type="file" id="profile_image"
                                               value="">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-10">
                                        <img id="show_profile_image" class="rounded avatar-lg"
                                             src="{{(!empty($editData->image)) ? url('upload/admin_images/'.$editData->image) : url('upload/default_image.png')}}"
                                             alt="Card image cap">
                                    </div>
                                </div>

                                <input type="submit" class="btn btn-success waves-effect waves-light" value="Update profile">

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script type="text/javascript">

        $(document).ready(function(){
            $('#profile_image').change(function(e) {
              var reader = new FileReader();
              reader.onload = function(e) {
                  $('#show_profile_image').attr('src', e.target.result);
              }
              reader.readAsDataURL(e.target.files['0']);
            });
        });

    </script>

@endsection
