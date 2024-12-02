@extends('admin.admin_master')
@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Edit Zipcode</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Zipcodes</a></li>
                            <li class="breadcrumb-item active">Edit Zipcode</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <form id="edit-zipcode" class="form-horizontal" action="{{ route('zipcodes.update', ['zipcode'=>$zipcode->zip_code]) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <label for="zip_code" class="col-sm-2 col-form-label">Zip Code</label>
                                <div class="col-sm-4">
                                    <input class="form-control @error('zip_code') is-invalid @enderror" type="text" placeholder="Zip Code" name="zip_code" value="{{  $zipcode->zip_code }}">
                                    @error('zip_code')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="country" class="col-sm-2 col-form-label">Country</label>
                                <div class="col-sm-4">
                                    <input class="form-control @error('country') is-invalid @enderror" type="text" placeholder="Country" name="country" value="{{  $zipcode->country }}">
                                    @error('country')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="state" class="col-sm-2 col-form-label">State</label>
                                <div class="col-sm-3">
                                    <input class="form-control @error('state') is-invalid @enderror" type="text" placeholder="State" name="state" value="{{  $zipcode->state }}">
                                    @error('state')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="city" class="col-sm-2 col-form-label">City</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" placeholder="City" name="city" value="{{  $zipcode->city }}">
                                    @error('city')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="population" class="col-sm-2 col-form-label">Population</label>
                                <div class="col-sm-4">
                                    <input class="form-control @error('population') is-invalid @enderror" type="text" placeholder="Population" name="population" value=" {{$zipcode->population }}">
                                    @error('population')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="density" class="col-sm-2 col-form-label">Density</label>
                                <div class="col-sm-4">
                                    <input class="form-control @error('density') is-invalid @enderror" type="text" name="density" placeholder="e.g., 13254.00" value="{{  $zipcode->density }}">
                                    @error('density')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="timezone" class="col-sm-2 col-form-label">Timezone</label>
                                <div class="col-sm-4">
                                    <select class="form-select @error('timezone') is-invalid @enderror" name="timezone" id="timezone">
                                        <option>Select timezone</option>
                                        @foreach ($timezones as $timezone)
                                            <option value="{{ $timezone->timezone }}" {{ $timezone->timezone == $zipcode->timezone ? 'selected' : '' }}>{{ $timezone->timezone }}</option>
                                        @endforeach
                                    </select>
                                    @error('timezone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10 pull-right">
                                    <button type="submit" class="btn btn-outline-dark w-lg waves-effect waves-light pull-right">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
