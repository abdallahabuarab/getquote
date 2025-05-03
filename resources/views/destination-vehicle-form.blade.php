<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/js/app.js', 'resources/css/app.css'])

    <title>Destination & Vehicle Details</title>
    <style>
        body {
            background-color: #e0f7fa;
            font-family: Arial, sans-serif;
        }

        .form-container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 40px;
            margin-top: 50px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        h2 {
            color: #333;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
        }

        .btn-primary {
            width: 100%;
            padding: 14px;
            background-color: #5c6bc0; /* Soft blue */
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #3949ab; /* Darker blue on hover */
        }

        .form-control {
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
            padding: 12px;
        }

        .alert-danger ul {
            list-style: none;
            padding-left: 0;
        }

        /* Unified sections with consistent styles */
        .section-header {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        .form-section {
            margin-bottom: 30px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="form-container">
            <h2>Enter Destination & Vehicle Details</h2>

            @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form id="destinationVehicleForm" method="POST" action="{{ route('destination-vehicle.store') }}">
                @csrf
                <input type="hidden" name="provider_id" value="{{ $provider->provider_id ?? '' }}">
                @if($service == 'tow')
                <div class="destination-header">
                    <h3>Destination Information</h3>
                    <input type="hidden" name="unique_token" value="{{ Str::random(40) }}">
                    <div class="form-group">
                        <label for="destinationLocationType">Select Destination Location Type:</label>
                        <select name="destination_location_type_id" id="destinationLocationType" class="form-control">
                            @foreach($locationTypes as $locationType)
                            <option value="{{ $locationType->location_type_id }}" {{ old('destination_location_type_id') == $locationType->location_type_id ? 'selected' : '' }}>
                                {{ $locationType->location_type }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="isBusiness" name="is_business">
                        <label class="form-check-label" for="isBusiness">Is this a business?</label>
                    </div>

                    <div class="form-group" id="businessNameGroup" style="display: none;">
                        <label for="business_name">Business Name:</label>
                        <input type="text" id="business_name" name="business_name" class="form-control" placeholder="Enter business name" value="{{ old('business_name') }}">
                    </div>

                    <script>
                        const isBusinessCheckbox = document.getElementById('isBusiness');
                        const businessNameGroup = document.getElementById('businessNameGroup');

                        isBusinessCheckbox.addEventListener('change', function () {
                            businessNameGroup.style.display = this.checked ? 'block' : 'none';
                        });

                        window.addEventListener('DOMContentLoaded', function () {
                            if (isBusinessCheckbox.checked) {
                                businessNameGroup.style.display = 'block';
                            }
                        });
                    </script>

                    <div class="form-group">
                        <label for="destination_autocomplete">Destination Address:</label>
                        <input type="text" id="destination_autocomplete" name="destination_autocomplete" class="form-control"
                            placeholder="Start typing address..." value="{{ old('destination_autocomplete') }}">
                    </div>

                    <!-- Hidden inputs to store parsed values -->
                    <input type="hidden" name="destination_zipcode" id="destination_zipcode" value="{{ old('destination_zipcode') }}">
                    <input type="hidden" name="destination_locality" id="destination_locality" value="{{ old('destination_locality') }}">
                    <input type="hidden" name="destination_administrative_area_level_1" id="destination_administrative_area_level_1" value="{{ old('destination_administrative_area_level_1') }}">

                @endif

                <!-- Vehicle Section -->
                <div class="form-section">
                    <div class="section-header">Vehicle Information</div>

                    <div class="form-group">
                        <label for="vehicle_year">Vehicle Year:</label>
                        <input type="number" id="vehicle_year" name="vehicle_year" class="form-control" placeholder="Enter vehicle year" value="{{ old('vehicle_year') }}">
                    </div>

                    <div class="form-group">
                        <label for="vehicle_make">Vehicle Make:</label>
                        <input type="text" id="vehicle_make" name="vehicle_make" class="form-control" placeholder="Enter vehicle make" value="{{ old('vehicle_make') }}">
                    </div>

                    <div class="form-group">
                        <label for="vehicle_model">Vehicle Model:</label>
                        <input type="text" id="vehicle_model" name="vehicle_model" class="form-control" placeholder="Enter vehicle model" value="{{ old('vehicle_model') }}">
                    </div>

                    <div class="form-group">
                        <label for="vehicle_color">Vehicle Color:</label>
                        <input type="text" id="vehicle_color" name="vehicle_color" class="form-control" placeholder="Enter vehicle color" value="{{ old('vehicle_color') }}">
                    </div>

                    <div class="form-group">
                        <label for="vehicle_style">Vehicle Style:</label>
                        <input type="text" id="vehicle_style" name="vehicle_style" class="form-control" placeholder="Enter vehicle style (e.g., sedan, SUV)" value="{{ old('vehicle_style') }}">
                    </div>

                    <div class="form-group">
                        <label for="vin">Vehicle Identification Number (VIN):</label>
                        <input type="text" id="vin" name="vin" class="form-control" placeholder="Enter VIN" value="{{ old('vin') }}">
                    </div>

                    <div class="form-group">
                        <label for="plate">License Plate Number:</label>
                        <input type="text" id="plate" name="plate" class="form-control" placeholder="Enter plate number" value="{{ old('plate') }}">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="p-4">
                    <button type="submit" class="btn btn-primary">Submit Vehicle Details</button>
                </div>

            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector('form').addEventListener('submit', function () {
            this.querySelector('button[type="submit"]').disabled = true;
        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsk5RExl2Xr7w2BayGTYdsePr2v6WBjmo&libraries=places&callback=initAutocomplete" async defer></script>
    <script>
        function initAutocomplete() {
            const autocompleteInput = document.getElementById('destination_autocomplete');
            const autocomplete = new google.maps.places.Autocomplete(autocompleteInput, {
                types: ['geocode'],
                componentRestrictions: { country: ['us'] }, // restrict to US if needed
            });

            autocomplete.addListener('place_changed', function () {
                const place = autocomplete.getPlace();
                let zip = '', city = '', state = '';

                if (!place.address_components) return;

                for (const component of place.address_components) {
                    const types = component.types;
                    if (types.includes('postal_code')) {
                        zip = component.long_name;
                    } else if (types.includes('locality')) {
                        city = component.long_name;
                    } else if (types.includes('administrative_area_level_1')) {
                        state = component.short_name;
                    }
                }

                // Fill hidden fields
                document.getElementById('destination_zipcode').value = zip;
                document.getElementById('destination_locality').value = city;
                document.getElementById('destination_administrative_area_level_1').value = state;
            });
        }

        window.addEventListener('load', initAutocomplete);
    </script>

</body>

</html>
