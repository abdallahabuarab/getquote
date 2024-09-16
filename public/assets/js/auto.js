

    let autocomplete;

    function initAutocomplete() {
        const locationInput = document.getElementById('locationInput');
        if (locationInput) {
            autocomplete = new google.maps.places.Autocomplete(locationInput, { types: ['geocode'] });
            autocomplete.addListener('place_changed', fillInAddress);
        } else {
++               console.error('Location input not found');
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
                const latlng = { lat: parseFloat(position.coords.latitude), lng: parseFloat(position.coords.longitude) };
                geocoder.geocode({ 'location': latlng }, function(results, status) {
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
            'Error: Your browser doesn\'t support geolocation.');
    }
