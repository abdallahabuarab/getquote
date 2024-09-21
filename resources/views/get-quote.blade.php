<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Tow Now - Get a Quote</title>
    <style>
        /* Main Page Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa;
            color: #333;
        }

        /* Form Section */
        .form-container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 40px;
            margin-top: 50px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            max-width: 650px;
            margin-left: auto;
            margin-right: auto;
            border: 1px solid #e0f0f1;
        }

        .form-group label {
            font-weight: bold;
            color: #333;
        }

        .form-control {
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #cccccc;
            padding: 12px;
        }

        .btn-primary {
            background-color: #00838f;
            width: 100%;
            border-radius: 30px;
            font-size: 1.2rem;
            padding: 15px 0;
            border: none;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #006064;
        }

        .btn-info {
            background-color: #00acc1;
            border: none;
            margin-top: 10px;
            padding: 10px 20px;
            color: #fff;
        }

        .alert-danger ul {
            list-style: none;
            padding-left: 0;
        }

        .info-text {
            text-align: center;
            font-size: 1.2rem;
            color: #00796b;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>

    <div class="container mt-4">
        <p class="info-text">
            We provide roadside assistance and towing services for vehicles of all types. Whether you're stuck on the side of the road or need help with your vehicle at home, we're here to help!
        </p>
    </div>

    <div class="container">
        <div class="form-container">
            <h2>Get a Quote</h2>
            <form id="quoteForm" method="POST" action="{{ route('requests.store') }}">
                @csrf
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="form-group">
                    <label for="service">Select Service:</label>
                    <select name="service_id" id="service" class="form-control">
                        @foreach($services as $service)
                        <option value="{{ $service->service_id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="class">Select Class:</label>
                    <select name="class_id" id="class" class="form-control">
                        @foreach($classes as $class)
                        <option value="{{ $class->class_id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="locationType">Select Location Type:</label>
                    <select name="request_location_type_id" id="locationType" class="form-control">
                        @foreach($locationTypes as $locationType)
                        <option value="{{ $locationType->location_type_id }}">{{ $locationType->location_type }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Priority Selection -->
                <div class="form-group">
                    <label for="priority">Select Priority:</label>
                    <select name="request_priority" id="priority" class="form-control">
                        <option value="low">Low</option>
                        <option value="normal" selected>Normal</option>
                        <option value="high">High</option>
                    </select>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="manualInputToggle" onclick="toggleManualInput()">
                    <label class="form-check-label" for="manualInputToggle">Manually Enter Location</label>
                </div>

                <!-- Autocomplete Section -->
                <div id="autocompleteLocationSection" class="form-group">
                    <label for="locationInput">Location:</label>
                    <input id="locationInput" type="text" name="locationInput" class="form-control" placeholder="Search for a location or use your location">
                    <button type="button" onclick="getLocation()" class="btn btn-info">Use My Location</button>
                </div>

                <!-- Manual Input Section -->
                <div id="manualLocationSection" class="d-none">
                    <div class="form-group">
                        <label for="manualZipCode">Zip Code:</label>
                        <input id="manualZipCode" type="text" name="manual_zipcode" class="form-control" placeholder="Enter Zip Code">
                    </div>

                    <div class="form-group">
                        <label for="manualCity">City:</label>
                        <input id="manualCity" type="text" name="request_ip_city" class="form-control" placeholder="Enter City">
                    </div>

                    {{-- <div class="form-group">
                        <label for="manualRegion">Region/State:</label>
                        <input id="manualRegion" type="text" name="request_ip_region" class="form-control" placeholder="Enter Region/State">
                    </div> --}}

                    <div class="form-group">
                        <label for="manualCountry">Country:</label>
                        <select id="manualCountry" name="manual_country" class="form-control">
                            <option value="">Select a country</option>
                            <option value="United States">United States</option>
                            <option value="Canada">Canada</option>
                            <option value="United Kingdom">United Kingdom</option>
                            <option value="Australia">Australia</option>
                            <!-- Add more countries as needed -->
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="manualStreetNumber">Street Number:</label>
                        <input id="manualStreetNumber" type="text" name="request_street_number" class="form-control" placeholder="Enter Street Number">
                    </div>

                    {{-- <div class="form-group">
                        <label for="manualRoute">Route:</label>
                        <input id="manualRoute" type="text" name="request_route" class="form-control" placeholder="Enter Route">
                    </div> --}}
                </div>

                <!-- Hidden Fields for Extracted Data -->
                <input type="hidden" id="zipCode" name="zipcode">
                <input type="hidden" id="request_longitude" name="request_longitude">
                <input type="hidden" id="request_latitude" name="request_latitude">
                <input type="hidden" id="request_ip_country" name="request_ip_country">

                <!-- Submit Button -->
                <div class="p-4">
                    <button type="submit" class="btn btn-primary btn-lg">Confirm Location</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4mO0ojwx3yLd6tf68E62eNRyZ8DzhDWc&libraries=places&callback=initAutocomplete" async defer></script>

    <script>
        let autocomplete;

        function initAutocomplete() {
            const locationInput = document.getElementById('locationInput');
            if (locationInput) {
                autocomplete = new google.maps.places.Autocomplete(locationInput, { types: ['geocode'] });
                autocomplete.addListener('place_changed', fillInAddress);
            } else {
                console.error('Location input not found');
            }
        }

        function fillInAddress() {
            const place = autocomplete.getPlace();
            let zipCode, latitude, longitude, country;

            // Extract zip code, country, latitude, longitude
            for (const component of place.address_components) {
                if (component.types[0] === "postal_code") {
                    zipCode = component.long_name;
                    document.getElementById('zipCode').value = zipCode;
                }
                if (component.types.includes("locality")) { // City
                    city = component.long_name;
                    document.getElementById('manualCity').value = city;
                }
                if (component.types[0] === "country") {
                    country = component.long_name;
                    document.getElementById('request_ip_country').value = country;
                }
            }
            latitude = place.geometry.location.lat();
            longitude = place.geometry.location.lng();

            document.getElementById('request_latitude').value = latitude;
            document.getElementById('request_longitude').value = longitude;
        }

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    const geocoder = new google.maps.Geocoder();
                    const latlng = { lat: parseFloat(position.coords.latitude), lng: parseFloat(position.coords.longitude) };
                    geocoder.geocode({ 'location': latlng }, function (results, status) {
                        if (status === 'OK') {
                            if (results[0]) {
                                document.getElementById('locationInput').value = results[0].formatted_address;
                                for (const component of results[0].address_components) {
                                    if (component.types[0] === "postal_code") {
                                        document.getElementById('zipCode').value = component.long_name;
                                    }
                                    if (component.types[0] === "country") {
                                        document.getElementById('request_ip_country').value = component.long_name;
                                    }
                                }
                                document.getElementById('request_latitude').value = latlng.lat;
                                document.getElementById('request_longitude').value = latlng.lng;
                            } else {
                                window.alert('No results found');
                            }
                        } else {
                            window.alert('Geocoder failed due to: ' + status);
                        }
                    });
                }, function () {
                    handleLocationError(true);
                });
            } else {
                handleLocationError(false);
            }
        }

        function toggleManualInput() {
            const manualSection = document.getElementById('manualLocationSection');
            const autocompleteSection = document.getElementById('autocompleteLocationSection');
            const isChecked = document.getElementById('manualInputToggle').checked;

            if (isChecked) {
                manualSection.classList.remove('d-none');
                autocompleteSection.classList.add('d-none');
            } else {
                manualSection.classList.add('d-none');
                autocompleteSection.classList.remove('d-none');
            }
        }

        function handleLocationError(browserHasGeolocation) {
            window.alert(browserHasGeolocation ?
                'Error: The Geolocation service failed.' :
                'Error: Your browser doesn\'t support geolocation.');
        }

        document.addEventListener('DOMContentLoaded', function () {
            initAutocomplete();
        });
    </script>
</body>

</html>
