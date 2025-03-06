@extends('admin.admin_master')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Add Provider</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('providerspa.index') }}">Providers</a></li>
                            <li class="breadcrumb-item active">Add Provider</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Start -->
        <form id="create-provider" class="form-horizontal" action="{{ route('providerspa.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <!-- Provider Information -->
                            <h5 class="mb-3">Provider Information</h5>

                            <div class="row mb-3">
                                <label for="provider_name" class="col-sm-2 col-form-label">Provider Name</label>
                                <div class="col-sm-4">
                                    <input class="form-control @error('provider_name') is-invalid @enderror" type="text" id="provider_name" name="provider_name" value="{{ old('provider_name') }}" placeholder="Enter provider name" required>
                                    @error('provider_name')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="tax_id" class="col-sm-2 col-form-label">Tax ID</label>
                                <div class="col-sm-4">
                                    <input class="form-control @error('tax_id') is-invalid @enderror"
                                           type="text" id="tax_id" name="tax_id"
                                           value="{{ old('tax_id') }}"
                                           placeholder="Enter 9-digit Tax ID" required>
                                    @error('tax_id')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="provider_name" class="col-sm-2 col-form-label">Contact Name</label>
                                <div class="col-sm-4">
                                    <input class="form-control @error('provider_name') is-invalid @enderror" type="text" id="provider_name" name="contact_name" value="{{ old('provider_name') }}" placeholder="Enter provider name" required>
                                    @error('provider_name')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="provider_email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-4">
                                    <input class="form-control @error('provider_email') is-invalid @enderror" type="email" id="provider_email" name="provider_email" value="{{ old('provider_email') }}" placeholder="Enter email address" required>
                                    @error('provider_email')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-4">
                                    <input class="form-control @error('password') is-invalid @enderror" type="password" id="password" name="password" placeholder="Enter password" required>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password_confirmation" class="col-sm-2 col-form-label">Confirm Password</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="provider_zipcode" class="col-sm-2 col-form-label">Provider ZIP Code</label>
                                <div class="col-sm-4">
                                    <input class="form-control @error('provider_zipcode') is-invalid @enderror" type="text" id="provider_zipcode" name="provider_zipcode" value="{{ old('provider_zipcode') }}" placeholder="Enter provider ZIP code" required>
                                    @error('provider_zipcode')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="provider_phone" class="col-sm-2 col-form-label">Phone</label>
                                <div class="col-sm-4">
                                    <input class="form-control @error('provider_phone') is-invalid @enderror" type="text" id="provider_phone" name="provider_phone" value="{{ old('provider_phone') }}" placeholder="Enter phone number" required>
                                    @error('provider_phone')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="provider_phone" class="col-sm-2 col-form-label">Contact Phone</label>
                                <div class="col-sm-4">
                                    <input class="form-control @error('provider_phone') is-invalid @enderror" type="text" id="contact_phone" name="contact_phone" value="{{ old('provider_phone') }}" placeholder="Enter phone number" required>
                                    @error('provider_phone')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="provider_address" class="col-sm-2 col-form-label">Address</label>
                                <div class="col-sm-4">
                                    <input class="form-control @error('provider_address') is-invalid @enderror" type="text" id="provider_address" name="provider_address" value="{{ old('provider_address') }}" placeholder="Enter address" required>
                                    @error('provider_address')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="provider_city" class="col-sm-2 col-form-label">City</label>
                                <div class="col-sm-4">
                                    <input class="form-control @error('provider_city') is-invalid @enderror" type="text" id="provider_city" name="provider_city" value="{{ old('provider_city') }}" placeholder="Enter city" required>
                                    @error('provider_city')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="provider_state" class="col-sm-2 col-form-label">State</label>
                                <div class="col-sm-4">
                                    <input class="form-control @error('provider_state') is-invalid @enderror" type="text" id="provider_state" name="provider_state" value="{{ old('provider_state') }}" placeholder="Enter state" required>
                                    @error('provider_state')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Classes and Services -->
                            {{-- <div class="row mb-3">
                                <label for="class_ids" class="col-sm-2 col-form-label">Classes</label>
                                <div class="col-sm-6">
                                    <select id="class_ids" name="class_ids[]" class="form-select @error('class_ids') is-invalid @enderror" multiple>
                                        @foreach ($classNames as $class)
                                            <option value="{{ $class->class_id }}" {{ in_array($class->class_id, old('class_ids', [])) ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_ids')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div> --}}


                            {{-- <div class="row mb-3">
                                <label for="service_ids" class="col-sm-2 col-form-label">Services</label>
                                <div class="col-sm-6">
                                    <select id="service_ids" name="service_ids[]" class="form-select @error('service_ids') is-invalid @enderror" multiple>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->service_id }}" {{ in_array($service->service_id, old('service_ids', [])) ? 'selected' : '' }}>
                                                {{ $service->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_ids')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div> --}}


                            {{-- <div class="row mb-3">
                                <label for="availability" class="col-sm-2 col-form-label">Availability</label>
                                <div class="col-sm-4">
                                    <select id="availability" name="availability" class="form-select @error('availability') is-invalid @enderror" required>
                                        <option value="yes" {{ old('availability') == 'yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="no" {{ old('availability') == 'no' ? 'selected' : '' }}>No</option>
                                    </select>
                                    @error('availability')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div> --}}

                            {{-- <div class="row mb-3">
                                <label for="service_price" class="col-sm-2 col-form-label">Service Price</label>
                                <div class="col-sm-4">
                                    <input type="number" id="service_price" name="service_price" class="form-control @error('service_price') is-invalid @enderror" value="{{ old('service_price') }}" placeholder="Enter Price">
                                    @error('service_price')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div> --}}

                            <div class="row mb-3">
                                <label for="dispatch_method" class="col-sm-2 col-form-label">Dispatch Method</label>
                                <div class="col-sm-4">
                                    <select id="dispatch_method" name="dispatch_method" class="form-select @error('dispatch_method') is-invalid @enderror" required>
                                        <option value="">Select Dispatch Method</option>
                                        @foreach ($dispatchMethods as $dispatchMethod)
                                            <option value="{{ $dispatchMethod->dispath_method_id }}" {{ old('dispatch_method') == $dispatchMethod->dispath_method_id ? 'selected' : '' }}>
                                                {{ $dispatchMethod->dispatch_method }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('dispatch_method')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Zip Codes -->
                            {{-- <h5 class="mb-3 mt-4">Coverage</h5>
                            <div class="row mb-3">
                                <label for="zip_codes" class="col-sm-2 col-form-label">Zip Codes</label>
                                <div class="col-sm-6">
                                    <select id="zip_codes" name="zip_codes[]" class="form-select @error('zip_codes') is-invalid @enderror" multiple>
                                        @foreach ($zipCodes as $zipCode)
                                            <option value="{{ $zipCode->zipcode }}" {{ in_array($zipCode->zipcode, old('zip_codes', [])) ? 'selected' : '' }}>
                                                {{ $zipCode->zipcode }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('zip_codes')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div> --}}
                            {{-- <div class="row mb-3">
                                <label for="rank" class="col-sm-2 col-form-label">Rank</label>
                                <div class="col-sm-4">
                                    <select id="rank" name="rank" class="form-select @error('rank') is-invalid @enderror" required>
                                        <option value="">Select Rank</option>
                                        <option value="1" {{ old('rank') == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ old('rank') == '2' ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ old('rank') == '3' ? 'selected' : '' }}>3</option>
                                    </select>
                                    @error('rank')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="row mb-3">
                                <label for="payment_distribution" class="col-sm-2 col-form-label">Payment Distribution</label>
                                <div class="col-sm-4">
                                    <select id="payment_distribution" name="payment_distribution" class="form-select @error('payment_distribution') is-invalid @enderror" required>
                                        <option value="">Select Payment Distribution</option>
                                        @foreach ($paymentDistributions as $paymentDistribution)
                                            <option value="{{ $paymentDistribution->payment_distribution_id }}" {{ old('payment_distribution') == $paymentDistribution->payment_distribution_id ? 'selected' : '' }}>
                                                {{ $paymentDistribution->payment_distribution }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('payment_distribution')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status -->
                            <h5 class="mb-3 mt-4">Provider Status</h5>
                            <div class="row mb-3">
                                <label for="status" class="col-sm-2 col-form-label">Status</label>
                                <div class="col-sm-2">
                                    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="yes" {{ old('status') == 'yes' ? 'selected' : '' }}>Active</option>
                                        <option value="no" {{ old('status') == 'no' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="provider_schedule" class="col-sm-2 col-form-label">Provider Schedule</label>
                                <div class="col-sm-10">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Day</th>
                                                <th>Open</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($weekdays as $weekday)
                                                <tr>
                                                    <td>{{ $weekday->name }}</td>
                                                    <td>
                                                        <select name="schedule[{{ $weekday->dayofweek }}][open_day]" class="form-select">
                                                            <option value="yes" {{ old("schedule.{$weekday->dayofweek}.open_day") == 'yes' ? 'selected' : '' }}>Yes</option>
                                                            <option value="no" {{ old("schedule.{$weekday->dayofweek}.open_day") == 'no' ? 'selected' : '' }}>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="time" name="schedule[{{ $weekday->dayofweek }}][start_time]" class="form-control"
                                                               value="{{ old("schedule.{$weekday->dayofweek}.start_time") }}">
                                                    </td>
                                                    <td>
                                                        <input type="time" name="schedule[{{ $weekday->dayofweek }}][end_time]" class="form-control"
                                                               value="{{ old("schedule.{$weekday->dayofweek}.end_time") }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                            <!-- Submit Button -->
                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-outline-dark waves-effect waves-light">Save</button>
                                    <a href="{{ route('providerspa.index') }}" class="btn btn-primary waves-effect waves-light">Back</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#zip_codes').select2({
            placeholder: "Select ZIP codes",
            allowClear: true
        });
    });
    $(document).ready(function() {
    $('#class_ids, #service_ids').select2({
        placeholder: "Select options",
        allowClear: true
    });
});

</script>
@endsection
