@extends('admin.admin_master')
@section('admin')

@php
    $dayNames = $dayNames ?? ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    if (!function_exists('convertMinutesToLocalTime')) {
        function convertMinutesToLocalTime($minutes, $timezone) {
            if ($minutes === null) {
                return '-';
            }

            return \Carbon\Carbon::createFromTime(0, 0, 0, 'UTC')
                ->addMinutes($minutes)
                ->setTimezone($timezone)
                ->format('g:i A');
        }
    }
@endphp

<div class="page-content">
    <div class="container-fluid">
        <h4 class="mb-4">Availabilities Schedule</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-primary">Current Availabilities</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark text-center">
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
                                            <select name="availability" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="yes" {{ $availability->availability == 'yes' ? 'selected' : '' }}>Yes</option>
                                                <option value="no" {{ $availability->availability == 'no' ? 'selected' : '' }}>No</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>

                                @if ($availability->provider && $availability->provider->schedules)
                                    <tr>
                                        <td colspan="8">
                                            <div class="card bg-light border border-secondary-subtle shadow-sm mt-3">
                                                <div class="card-header bg-secondary text-white fw-bold">
                                                    <i class="bi bi-calendar-week"></i> Weekly Work Schedule
                                                </div>
                                                <div class="card-body p-0">
                                                    <table class="table table-sm table-bordered table-hover mb-0 text-center">
                                                        <thead class="table-secondary">
                                                            <tr>
                                                                <th class="text-uppercase">Day</th>
                                                                <th class="text-uppercase">Start Time</th>
                                                                <th class="text-uppercase">End Time</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($availability->provider->schedules as $schedule)
                                                                <tr>
                                                                    <td class="fw-semibold">{{ $dayNames[$schedule->dayofweek] ?? '-' }}</td>
                                                                    <td>{{ convertMinutesToLocalTime($schedule->start_time, $timezone) }}</td>
                                                                    <td>{{ convertMinutesToLocalTime($schedule->close_time, $timezone) }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No availabilities found for this provider.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
