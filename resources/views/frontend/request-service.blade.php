@extends('frontend.main_master')

@section('content')
@if (Route::has('login'))
    <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
        @auth
            <a href="{{ url('/dashboard') }}" class="font-semibold text-white hover:text-gray-900 dark:text-white dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="font-semibold text-white hover:text-gray-900 dark:text-white dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="ml-4 font-semibold text-white hover:text-gray-900 dark:text-white dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
            @endif
        @endauth
    </div>
@endif
<div class="container mt-5">
    <h2 class="mb-4">Get a Quotee</h2>
    <form action="{{ route('request.service') }}" method="POST">
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
                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                @endforeach
            </select>
        </div>
        <input type="hidden" id="zipCode" name="zipcode">

        <div class="form-group">
            <label for="class">Select Class:</label>
            <select name="class_id" id="class" class="form-control">
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->type }}</option>
                @endforeach
            </select>
        </div>

        <div class="relative">
            <input id="locationInput" type="text" name="address_point1" class="pl-3 pr-5 py-2 border rounded-full text-sm mt-3 bg-gray-300" placeholder="Search for a location or use your location">
            <button type="button" onclick="getLocation()" class="btn btn-info">Use My Location</button>
        </div>

        <div class="p-4">
            <button type="submit" class="btn btn-primary btn-lg">Confirm Location</button>
        </div>
    </form>
</div>

<script
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4mO0ojwx3yLd6tf68E62eNRyZ8DzhDWc&libraries=places&callback=initAutocomplete"
async defer></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const confirmBtn = document.getElementById('confirmBtn');
        const modal = document.getElementById('pickupBranchModal');
        const closeModal = document.getElementById('closeModal1');
        const hasErrors = @json($errors->any());

        if (hasErrors) {
            modal.classList.remove('hidden');
            initAutocomplete();  // Initialize autocomplete after showing the modal if there are errors
        }

        confirmBtn.addEventListener('click', function(event) {
            event.preventDefault();
            modal.classList.remove('hidden');
            initAutocomplete();  // Reinitialize autocomplete every time the modal is shown
        });

        closeModal.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
    });

    let autocomplete;

    function initAutocomplete() {
        const locationInput = document.getElementById('locationInput');
        if (locationInput) {
            autocomplete = new google.maps.places.Autocomplete(locationInput, {types: ['geocode']});
            autocomplete.addListener('place_changed', fillInAddress);
        } else {
            console.error('Location input not found');
        }
    }

    function fillInAddress() {
        const place = autocomplete.getPlace();
        for (const component of place.address_components) {
            if (component.types[0] === "postal_code") {
                document.getElementById('zipCode').value = component.long_name;
            }
        }
    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const geocoder = new google.maps.Geocoder();
                const latlng = {lat: parseFloat(position.coords.latitude), lng: parseFloat(position.coords.longitude)};
                geocoder.geocode({'location': latlng}, function(results, status) {
                    if (status === 'OK') {
                        if (results[0]) {
                            document.getElementById('locationInput').value = results[0].formatted_address;
                            for (const component of results[0].address_components) {
                                if (component.types[0] === "postal_code") {
                                    document.getElementById('zipCode').value = component.long_name;
                                }
                            }
                        } else {
                            window.alert('No results found');
                        }
                    } else {
                        window.alert('Geocoder failed due to: ' + status);
                    }
                });
            }, function() {
                handleLocationError(true);
            });
        } else {
            handleLocationError(false);
        }
    }

    function handleLocationError(browserHasGeolocation) {
        window.alert(browserHasGeolocation ?
            'Error: The Geolocation service failed.' :
            'Error: Your browser doesnt support geolocation.');
    }
    </script>
@endsection
