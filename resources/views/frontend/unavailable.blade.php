@extends('frontend.main_master')

@section('content')

<div class="container d-flex vh-100 align-items-center justify-content-center">
    <div class="card p-4">
        <h1 class="card-title text-center mb-3">We're Sorry!</h1>
        <p class="text-muted text-center mb-2">We apologize for the inconvenience caused due to:</p>
        <p class="text-danger text-center mb-4">{{ request('reason', '') }}</p>
        <div class="text-center">
          <a href="{{route('index')}}"> <button class="btn btn-primary" >Go Back</button></a>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



@endsection
