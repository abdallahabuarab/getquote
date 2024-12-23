@extends('admin.admin_master')
@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <h4 class="mb-4">Availabilities Schedule</h4>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-primary">Current Availabilities</h5>
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Class</th>
                            <th>Service</th>
                            <th>Price</th>
                            <th>Free Enroute Miles</th>
                            <th>Enroute Mile Price</th>
                            <th>Free Loaded Miles</th>
                            <th>Loaded Mile Price</th>
                            <th>Availability</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($availabilities as $availability)
                            <tr>
                                <td>{{ $availability->classModel->name }}</td>
                                <td>{{ $availability->service->name }}</td>
                                <td>{{ $availability->service_price ?? 'N/A' }}</td>
                                <td>{{ $availability->free_enroute_miles ?? 'N/A' }}</td>
                                <td>{{ $availability->enroute_mile_price ?? 'N/A' }}</td>
                                <td>{{ $availability->free_loaded_miles ?? 'N/A' }}</td>
                                <td>{{ $availability->loaded_mile_price ?? 'N/A' }}</td>
                                <td>
                                    <form
                                        action="{{ route('availabilities.updateAvailability', [$availability->provider_id, $availability->class_id, $availability->service_id]) }}"
                                        method="POST"
                                        class="availability-form">
                                        @csrf
                                        @method('PATCH')
                                        <select name="availability" class="form-control availability-select" onchange="this.form.submit()">
                                            <option value="yes" {{ $availability->availability == 'yes' ? 'selected' : '' }}>Yes</option>
                                            <option value="no" {{ $availability->availability == 'no' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No availabilities found for this provider.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
