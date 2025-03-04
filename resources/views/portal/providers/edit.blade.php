@extends('admin.admin_master')

@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Edit Provider</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('providerspa.index') }}">Providers</a></li>
                            <li class="breadcrumb-item active">Edit Provider</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <form id="edit-provider" class="form-horizontal" action="{{ route('providerspa.update', $provider->provider_id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Provider Details -->
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Provider Information</h5>

                    <!-- Provider Name -->
                    <div class="row mb-3">
                        <label for="provider_name" class="col-sm-2 col-form-label">Provider Name</label>
                        <div class="col-sm-6">
                            <input type="text" name="provider_name" id="provider_name" class="form-control" value="{{ old('provider_name', $provider->provider_name) }}" readonly>
                        </div>
                    </div>

                    <!-- Provider Email -->
                    <div class="row mb-3">
                        <label for="provider_email" class="col-sm-2 col-form-label">Provider Email</label>
                        <div class="col-sm-6">
                            <input type="email" name="provider_email" id="provider_email" class="form-control" value="{{ old('provider_email', $provider->provider_email) }}" required>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="row mb-3">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-6">
                            <input type="password" name="password" id="password" class="form-control">
                            <small class="text-muted">Leave blank if not changing the password.</small>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="row mb-3">
                        <label for="password_confirmation" class="col-sm-2 col-form-label">Confirm Password</label>
                        <div class="col-sm-6">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                        </div>
                    </div>

                    <!-- Provider Zipcode -->
                    <div class="row mb-3">
                        <label for="provider_zipcode" class="col-sm-2 col-form-label">Provider Zipcode</label>
                        <div class="col-sm-6">
                            <input type="text" name="provider_zipcode" id="provider_zipcode" class="form-control" value="{{ old('provider_zipcode', $provider->zipcode) }}" required>
                        </div>
                    </div>

                    <!-- Contact Name -->
                    <div class="row mb-3">
                        <label for="contact_name" class="col-sm-2 col-form-label">Contact Name</label>
                        <div class="col-sm-6">
                            <input type="text" name="contact_name" id="contact_name" class="form-control" value="{{ old('contact_name', $provider->contact_name) }}">
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="row mb-3">
                        <label for="provider_address" class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-6">
                            <input type="text" name="provider_address" id="provider_address" class="form-control" value="{{ old('provider_address', $provider->provider_address) }}" required>
                        </div>
                    </div>

                    <!-- City -->
                    <div class="row mb-3">
                        <label for="provider_city" class="col-sm-2 col-form-label">City</label>
                        <div class="col-sm-6">
                            <input type="text" name="provider_city" id="provider_city" class="form-control" value="{{ old('provider_city', $provider->provider_city) }}" required>
                        </div>
                    </div>

                    <!-- State -->
                    <div class="row mb-3">
                        <label for="provider_state" class="col-sm-2 col-form-label">State</label>
                        <div class="col-sm-6">
                            <input type="text" name="provider_state" id="provider_state" class="form-control" value="{{ old('provider_state', $provider->provider_state) }}" required>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="row mb-3">
                        <label for="provider_phone" class="col-sm-2 col-form-label">Phone</label>
                        <div class="col-sm-6">
                            <input type="text" name="provider_phone" id="provider_phone" class="form-control" value="{{ old('provider_phone', $provider->provider_phone) }}" required>
                        </div>
                    </div>

                    <!-- Contact Phone -->
                    <div class="row mb-3">
                        <label for="contact_phone" class="col-sm-2 col-form-label">Contact Phone</label>
                        <div class="col-sm-6">
                            <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="{{ old('contact_phone', $provider->contact_phone) }}">
                        </div>
                    </div>

                    <!-- Dispatch Method -->
                    <div class="row mb-3">
                        <label for="dispatch_method" class="col-sm-2 col-form-label">Dispatch Method</label>
                        <div class="col-sm-6">
                            <select name="dispatch_method" id="dispatch_method" class="form-select" required>
                                @foreach ($dispatchMethods as $method)
                                    <option value="{{ $method->dispath_method_id }}" {{ $method->dispath_method_id == $provider->dispatch_method ? 'selected' : '' }}>
                                        {{ $method->dispatch_method }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Payment Distribution -->
                    <div class="row mb-3">
                        <label for="payment_distribution" class="col-sm-2 col-form-label">Payment Distribution</label>
                        <div class="col-sm-6">
                            <select name="payment_distribution" id="payment_distribution" class="form-select" required>
                                @foreach ($paymentDistributions as $distribution)
                                    <option value="{{ $distribution->payment_distribution_id }}" {{ $distribution->payment_distribution_id == $provider->payment_distribution ? 'selected' : '' }}>
                                        {{ $distribution->payment_distribution }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Classes -->
                    {{-- <div class="row mb-3">
                        <label for="class_ids" class="col-sm-2 col-form-label">Classes</label>
                        <div class="col-sm-6">
                            <select name="class_ids[]" id="class_ids" class="form-select" multiple>
                                @foreach ($classNames as $class)
                                    <option value="{{ $class->class_id }}" {{ in_array($class->class_id, $provider->availabilities->pluck('class_id')->toArray()) ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}

                    <!-- Services -->
                    {{-- <div class="row mb-3">
                        <label for="service_ids" class="col-sm-2 col-form-label">Services</label>
                        <div class="col-sm-6">
                            <select name="service_ids[]" id="service_ids" class="form-select" multiple>
                                @foreach ($services as $service)
                                    <option value="{{ $service->service_id }}" {{ in_array($service->service_id, $provider->availabilities->pluck('service_id')->toArray()) ? 'selected' : '' }}>
                                        {{ $service->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}

                    <!-- Zip Codes -->
                    {{-- <div class="row mb-3">
                        <label for="zip_codes" class="col-sm-2 col-form-label">Zip Codes</label>
                        <div class="col-sm-6">
                            <select name="zip_codes[]" id="zip_codes" class="form-select" multiple>
                                @foreach ($zipCodes as $zipCode)
                                    <option value="{{ $zipCode->zipcode }}" {{ in_array($zipCode->zipcode, $provider->zipCodes->pluck('zipcode')->toArray()) ? 'selected' : '' }}>
                                        {{ $zipCode->zipcode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Rank -->
                    <div class="row mb-3">
                        <label for="rank" class="col-sm-2 col-form-label">Rank</label>
                        <div class="col-sm-6">
                            <select name="rank" id="rank" class="form-select">
                                <option value="1" {{ $provider->zipCodes->first()->pivot->rank ?? '' == '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ $provider->zipCodes->first()->pivot->rank ?? '' == '2' ? 'selected' : '' }}>2</option>
                                <option value="3" {{ $provider->zipCodes->first()->pivot->rank ?? '' == '3' ? 'selected' : '' }}>3</option>
                            </select>
                        </div>
                    </div> --}}

                    <!-- Status -->
                    <div class="row mb-3">
                        <label for="status" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-2 mt-4">
                            <select name="status" id="status" class="form-select">
                                <option value="yes" {{ $provider->is_active == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ $provider->is_active == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>


                    <!-- Provider Schedule -->
<div class="row mb-3">
    <label for="schedule" class="col-sm-2 col-form-label">Schedule</label>
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
                    @php
                        $schedule = $provider->schedules->firstWhere('dayofweek', $weekday->dayofweek);
                    @endphp
                    <tr>
                        <td>{{ $weekday->name }}</td>
                        <td>
                            <select name="schedule[{{ $weekday->dayofweek }}][open_day]" class="form-select">
                                <option value="yes" {{ optional($schedule)->open_day == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ optional($schedule)->open_day == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <input type="time" name="schedule[{{ $weekday->dayofweek }}][start_time]" class="form-control"
                                value="{{ $schedule ? gmdate('H:i', $schedule->start_time * 60) : '' }}">
                        </td>
                        <td>
                            <input type="time" name="schedule[{{ $weekday->dayofweek }}][end_time]" class="form-control"
                                value="{{ $schedule ? gmdate('H:i', $schedule->close_time * 60) : '' }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

                    <!-- Submit -->
                    <div class="row mb-3">
                        <div class="col-sm-6 offset-sm-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Provider</button>
                            <a href="{{ route('providerspa.index') }}" class="btn btn-secondary">Back</a>
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
        $('#class_ids, #service_ids, #zip_codes').select2({
            placeholder: "Select options",
            allowClear: true
        });
    });
</script>
@endsection
