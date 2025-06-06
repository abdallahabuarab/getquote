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

            <!-- Provider Information -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h4 class="card-title text-primary">Provider: {{ $provider->provider_name }}</h4>
                            <table class="table table-striped table-bordered">
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
                    <div class="card mt-4 shadow-sm">
                        <div class="card-body">
                            <h4 class="card-title text-primary">Coverage area</h4>

                            <!-- Search ZIP Codes -->
                            <div class="mb-3">
                                <input type="text" id="zipCodeSearch" class="form-control"
                                    placeholder="Search ZIP Codes...">
                            </div>

                            <table class="table table-striped table-bordered" id="zipCodeTable">
                                <thead>
                                    <tr>
                                        <th>ZIP Code</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Rank</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($provider->zipCodes as $zipCode)
                                        <tr>
                                            <td>{{ str_pad($zipCode->zipcode, 5, '0', STR_PAD_LEFT) }}</td>
                                            <td>{{ $zipCode->city }}</td>
                                            <td>{{ $zipCode->state }}</td>
                                            <td>{{ $zipCode->pivot->rank }}</td>
                                            <td>
                                                <form
                                                    action="{{ route('providerspa.deleteZipCode', [$provider->provider_id, $zipCode->zipcode]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <h5 class="mt-4">Add or Edit ZIP Codes and Ranks</h5>
                            <form action="{{ route('providerspa.updateZipCodes', $provider->provider_id) }}"
                                method="POST">
                                @csrf
                                @method('PATCH')

                                <div id="zipcode-fields">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <input type="text" name="zip_codes[0][zipcode]" class="form-control"
                                                placeholder="Enter ZIP code">
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
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">Save ZIP Codes</button>
                                <a href="{{ route('providerspa.index') }}" class="btn btn-secondary">Back</a>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>

                </div>
            </div>

            <!-- Availability Management -->
            <div class="row">
                <div class="col-12">
                    <div class="card mt-4 shadow-sm">
                        <div class="card-body">
                            <h4 class="card-title text-primary">Manage Availability</h4>
                            <h5>Current Availabilities</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Class</th>
                                        <th>Service</th>
                                        <th>Availability</th>
                                        <th>Service Price</th>
                                        <th>Enroute Mile Price</th>
                                        <th>Free Enroute Mile</th>
                                        <th>Loaded Mile Price</th>
                                        <th>Free Loaded Mile</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($provider->availabilities as $availability)
                                        <tr>
                                            <td>{{ $availability->classModel->name }}</td>
                                            <td>{{ $availability->service->name }}</td>
                                            <td>{{ $availability->availability }}</td>
                                            <td>{{ $availability->service_price ?? 'N/A' }}</td>
                                            <td>{{ $availability->enroute_mile_price ?? 'N/A' }}</td>
                                            <td>{{ $availability->free_enroute_miles ?? 'N/A' }}</td>
                                            <td>{{ $availability->loaded_mile_price ?? 'N/A' }}</td>
                                            <td>{{ $availability->free_loaded_miles	?? 'N/A' }}</td>
                                            <td>
                                                <!-- Edit Button -->
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editAvailabilityModal{{ $availability->provider_id }}_{{ $availability->class_id }}_{{ $availability->service_id }}">
                                                    Edit
                                                </button>
                                            </td>
                                            <div class="modal fade" id="editAvailabilityModal{{ $availability->provider_id }}_{{ $availability->class_id }}_{{ $availability->service_id }}" tabindex="-1" aria-labelledby="editAvailabilityModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editAvailabilityModalLabel">Edit Availability</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('availabilities.editAvailability', [$availability->provider_id, $availability->class_id, $availability->service_id]) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="availability" class="form-label">Availability</label>
                                                                    <select name="availability" class="form-control">
                                                                        <option value="yes" {{ $availability->availability == 'yes' ? 'selected' : '' }}>Yes</option>
                                                                        <option value="no" {{ $availability->availability == 'no' ? 'selected' : '' }}>No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="service_price" class="form-label">Service Price</label>
                                                                    <input   name="service_price" class="form-control" value="{{ $availability->service_price }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="enroute_mile_price" class="form-label">Enroute Mile Price</label>
                                                                    <input  name="enroute_mile_price" class="form-control" value="{{ $availability->enroute_mile_price }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="free_enroute_miles" class="form-label">Free Enroute Miles</label>
                                                                    <input  name="free_enroute_miles" class="form-control" value="{{ $availability->free_enroute_miles }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="loaded_mile_price" class="form-label">Loaded Mile Price</label>
                                                                    <input   name="loaded_mile_price" class="form-control" value="{{ $availability->loaded_mile_price }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="free_loaded_miles" class="form-label">Free Loaded Miles</label>
                                                                    <input  name="free_loaded_miles" class="form-control" value="{{ $availability->free_loaded_miles }}">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <td>
                                                <form
                                                    action="{{ route('providerspa.deleteAvailability', $provider->provider_id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="class_id"
                                                        value="{{ $availability->class_id }}">
                                                    <input type="hidden" name="service_id"
                                                        value="{{ $availability->service_id }}">
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <form action="{{ route('providerspa.updateAvailability', $provider->provider_id) }}"
                                method="POST">
                                @csrf
                                @method('PATCH')
                                <div id="availability-fields">
                                    <div class="row mb-3 availability-row">
                                        <div class="col-md-3">
                                            <label>Class</label>
                                            <select name="availabilities[0][class_id]" class="form-control">
                                                <option value="" disabled selected>Select Class</option>
                                                @foreach ($classNames as $className)
                                                    <option value="{{ $className->class_id }}">{{ $className->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Service</label>
                                            <select name="availabilities[0][service_id]"
                                                class="form-control service-select">
                                                <option value="" disabled selected>Select Service</option>
                                                @foreach ($services as $service)
                                                    <option value="{{ $service->service_id }}">{{ $service->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Service Price</label>
                                            <input type=""  name="availabilities[0][service_price]"
                                                class="form-control" placeholder="Enter Price">
                                        </div>
                                        <div class="col-md-2 conditional-input d-none">
                                            <label>Free Loaded Miles</label>
                                            <input type="" name="availabilities[0][free_loaded_miles]"
                                                class="form-control" placeholder="Enter Miles">
                                        </div>
                                        <div class="col-md-2 conditional-input d-none">
                                            <label>Loaded Mile Price</label>
                                            <input type=""  name="availabilities[0][loaded_mile_price]"
                                                class="form-control" placeholder="Enter Price">
                                        </div>
                                        <div class="col-md-1">
                                            <label>Availability</label>
                                            <select name="availabilities[0][availability]" class="form-control">
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 ">
                                            <label>Free Enroute Miles</label>
                                            <input type="" name="availabilities[0][free_enroute_miles]"
                                                class="form-control" placeholder="Enter Miles">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Enroute Mile Price</label>
                                            <input type=""
                                                name="availabilities[0][enroute_mile_price]" class="form-control"
                                                placeholder="Enter Price">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-6">

                                <button type="submit" class="btn btn-primary">Save Availabilities</button>
                                <a href="{{ route('providerspa.index') }}" class="btn btn-secondary">Back</a>
                                </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // ZIP Code Search Functionality
        document.getElementById('zipCodeSearch').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            document.querySelectorAll('#zipCodeTable tbody tr').forEach(row => {
                let zipCode = row.cells[0].textContent.toLowerCase();
                row.style.display = zipCode.includes(filter) ? '' : 'none';
            });
        });

        // Show/Hide Conditional Inputs for Towing
     document.querySelectorAll('.service-select').forEach(select => {
    select.addEventListener('change', function () {
        let selectedService = this.options[this.selectedIndex].text.toLowerCase();
        let conditionalInputs = this.closest('.availability-row').querySelectorAll('.conditional-input');

        if (selectedService === 'towing service') {
            conditionalInputs.forEach(input => input.classList.remove('d-none'));
        } else {
            conditionalInputs.forEach(input => input.classList.add('d-none'));
        }
    });
});

    </script>
@endsection



