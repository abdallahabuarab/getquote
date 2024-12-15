@extends('admin.admin_master')

@section('admin')
<div class="page-content">
    <div class="container-fluid">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        <!-- Provider Basic Information -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Provider: {{ $provider->provider_name }}</h4>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $provider->provider_email }}</td>
                                </tr>
                                <tr>
                                    <th>Contact Phone</th>
                                    <td>{{ $provider->provider_phone }}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{ $provider->provider_address }}</td>
                                </tr>
                                <tr>
                                    <th>City</th>
                                    <td>{{ $provider->provider_city }}</td>
                                </tr>
                                <tr>
                                    <th>State</th>
                                    <td>{{ $provider->provider_state }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ZIP Code Coverage -->
        <div class="row">
            <div class="col-12">
                <div class="card mt-4">
                    <div class="card-body">
                        <h4 class="card-title">ZIP Code Coverage</h4>

                        <!-- Current ZIP Codes -->
                        <h5>Current ZIP Codes</h5>

                        <!-- Search Input -->
                        {{-- <div class="mb-3">
                            <input type="text" id="zipCodeSearch" class="form-control" placeholder="Search ZIP Codes...">
                        </div> --}}

                        <table class="table table-bordered" id="zipCodeTable">
                            <thead>
                                <tr>
                                    <th>ZIP Code</th>
                                    <th>Rank</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($provider->zipCodes as $zipCode)
                                    <tr>
                                        <td>{{ $zipCode->zipcode }}</td>
                                        <td>{{ $zipCode->pivot->rank }}</td>
                                        <td>
                                            <form action="{{ route('providerspa.deleteZipCode', [$provider->provider_id, $zipCode->zipcode]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Add ZIP Codes -->
                        <h5 class="mt-4">Add or Edit ZIP Codes and Ranks</h5>
                        <form action="{{ route('providerspa.updateZipCodes', $provider->provider_id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div id="zipcode-fields">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <input type="text" name="zip_codes[0][zipcode]" class="form-control" placeholder="Enter ZIP code">
                                    </div>
                                    <div class="col-md-4">
                                        <select name="zip_codes[0][rank]" class="form-control">
                                            <option value="" disabled selected>Select Rank</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Save ZIP Codes</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <!-- Availability Management -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Manage Availability</h4>

                        <!-- Current Availabilities -->
                        <h5>Current Availabilities</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Class</th>
                                    <th>Service</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($provider->availabilities as $availability)
                                    <tr>
                                        <td>{{ $availability->classModel->name }}</td>
                                        <td>{{ $availability->service->name }}</td>
                                        <td>{{ $availability->service_price ?? 'N/A' }}</td>
                                        <td>
                                            <form action="{{ route('providerspa.deleteAvailability', $provider->provider_id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="class_id" value="{{ $availability->class_id }}">
                                                <input type="hidden" name="service_id" value="{{ $availability->service_id }}">
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Add Availability -->
                        <h5 class="mt-4">Add Availability</h5>
                        <form action="{{ route('providerspa.updateAvailability', $provider->provider_id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div id="availability-fields">
                                <div class="row mb-3 availability-row">
                                    <div class="col-md-2">
                                        <label>Class</label>
                                        <select name="availabilities[0][class_id]" class="form-control">
                                            <option value="" disabled selected>Select Class</option>
                                            @foreach ($classNames as $className)
                                                <option value="{{ $className->class_id }}">{{ $className->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Service</label>
                                        <select name="availabilities[0][service_id]" class="form-control service-select">
                                            <option value="" disabled selected>Select Service</option>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->service_id }}">{{ $service->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Service Price</label>
                                        <input type="number" step="0.01" name="availabilities[0][service_price]" class="form-control" placeholder="Enter Price">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Free Enroute Miles</label>
                                        <input type="number" name="availabilities[0][free_enroute_miles]" class="form-control" placeholder="Enter Miles">
                                    </div>
                                    <div class="col-md-2 tow-only d-none">
                                        <label>Free Loaded Miles</label>
                                        <input type="number" name="availabilities[0][free_loaded_miles]" class="form-control" placeholder="Enter Miles">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Enroute Mile Price</label>
                                        <input type="number" step="0.01" name="availabilities[0][enroute_mile_price]" class="form-control" placeholder="Enter Price">
                                    </div>
                                    <div class="col-md-2 tow-only d-none">
                                        <label>Loaded Mile Price</label>
                                        <input type="number" step="0.01" name="availabilities[0][loaded_mile_price]" class="form-control" placeholder="Enter Price">
                                    </div>
                                    <div class="col-md-1">
                                        <label>Availability</label>
                                        <select name="availabilities[0][availability]" class="form-control">
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>


                            <button type="submit" class="btn btn-primary mt-3">Save Availabilities</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('zipCodeSearch'); // Search input element
        const tableRows = document.querySelectorAll('#zipCodeTable tbody tr'); // Table rows

        searchInput.addEventListener('input', function () {
            const searchTerm = searchInput.value.trim(); // Input value
            const isNumeric = /^\d*$/.test(searchTerm); // Check if input is numbers only

            if (!isNumeric) {
                alert('Please enter numbers only for ZIP Code search.');
                searchInput.value = ''; // Reset input if invalid
                return;
            }

            tableRows.forEach(row => {
                const zipCode = row.querySelector('td:first-child').textContent.trim();

                // Check if the ZIP Code matches the search term
                if (zipCode.includes(searchTerm) || searchTerm === '') {
                    row.style.display = ''; // Show row
                } else {
                    row.style.display = 'none'; // Hide row
                }
            });
        });
    });
</script>

<script>





    document.addEventListener('DOMContentLoaded', () => {
        const availabilityFieldsContainer = document.getElementById('availability-fields');
        const addAvailabilityButton = document.querySelector('.add-availability');

        // Add new Availability row
        addAvailabilityButton.addEventListener('click', () => {
            const index = availabilityFieldsContainer.childElementCount;
            const newField = `
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="availabilities[${index}][class_id]">Class</label>
                        <select name="availabilities[${index}][class_id]" class="form-control">
                            <option value="" disabled selected>Select Class</option>
                            @foreach ($classNames as $className)
                                <option value="{{ $className->id }}">{{ $className->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="availabilities[${index}][service_id]">Service</label>
                        <select name="availabilities[${index}][service_id]" class="form-control">
                            <option value="" disabled selected>Select Service</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="availabilities[${index}][service_price]">Price</label>
                        <input type="number" name="availabilities[${index}][service_price]" class="form-control" placeholder="Enter Price">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger mt-4 remove-availability">Remove</button>
                    </div>
                </div>
            `;
            availabilityFieldsContainer.insertAdjacentHTML('beforeend', newField);
        });

        // Remove Availability row
        availabilityFieldsContainer.addEventListener('click', (event) => {
            if (event.target.classList.contains('remove-availability')) {
                event.target.closest('.row').remove();
            }
        });
    });






//     document.addEventListener('DOMContentLoaded', () => {
//     document.addEventListener('change', (event) => {
//         if (event.target.classList.contains('service-select')) {
//             const row = event.target.closest('.availability-row');
//             const towOnlyFields = row.querySelectorAll('.tow-only');

//             // Check the selected option's text (case-insensitive) for "tow"
//             const selectedText = event.target.options[event.target.selectedIndex].text.toLowerCase();

//             if (selectedText.includes('tow')) {
//                 // Show the tow-only fields
//                 towOnlyFields.forEach(field => field.classList.remove('d-none'));
//             } else {
//                 // Hide fields and reset values if service is not "tow"
//                 towOnlyFields.forEach(field => {
//                     field.classList.add('d-none');
//                     const input = field.querySelector('input');
//                     if (input) input.value = '';
//                 });
//             }
//         }
//     });
// });


</script>
@endsection




{{-- @extends('admin.admin_master')

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
@endsection --}}
