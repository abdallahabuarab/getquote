@extends('admin.admin_master')

@section('admin')
    <div class="page-content">
        <div class="container-fluid">

            <!-- Start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Provider Details</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('providerspa.index') }}">Providers</a></li>
                                <li class="breadcrumb-item active">Provider Details</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Provider Details -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Provider: {{ $provider->user->name }}</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th scope="row">Email</th>
                                            <td>{{ $provider->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Contact Phone</th>
                                            <td>{{ $provider->provider_phone }}</td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Address</th>
                                            <td>{{ $provider->provider_address }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">City</th>
                                            <td>{{ $provider->provider_city }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">State</th>
                                            <td>{{ $provider->provider_state }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Services</th>
                                            <td>
                                                <ul class="list-group">
                                                    @foreach ($provider->availabilities->pluck('service.name')->unique() as $serviceName)
                                                    <li class="list-group-item">{{ $serviceName }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Classes</th>
                                            <td>
                                                <ul class="list-group">
                                                    @foreach ($provider->availabilities->pluck('classModel.name')->unique() as $className)
                                                        <li class="list-group-item">{{ $className }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Zip Codes</th>
                                            <td>
                                                <ul class="list-group">
                                                    @foreach ($provider->zipCodes as $zipCode)
                                                        <li class="list-group-item">{{ $zipCode->zipcode }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Schedule</th>
                                            <td>
                                                <ul class="list-group">
                                                    @foreach ($provider->schedules as $schedule)
                                                        <li class="list-group-item">
                                                            <strong>{{ $schedule->weekday->name }}</strong>                                                            @if ($schedule->open_day === 'yes')
                                                                {{ gmdate('H:i', $schedule->start_time * 60) }} - {{ gmdate('H:i', $schedule->close_time * 60) }}
                                                            @else
                                                                Closed
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <a href="{{ route('providerspa.index') }}" class="btn btn-primary mt-3">Back to Providers</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
