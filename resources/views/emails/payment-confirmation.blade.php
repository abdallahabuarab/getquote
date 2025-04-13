<p>Dear {{ $provider->provider_name }},</p>

<p>We are pleased to inform you that the customer has successfully completed the payment for the requested service: <strong>{{ $service }}</strong>.</p>

<p>You may now proceed with fulfilling the service as scheduled.</p>

<p><strong>Pickup Location:</strong><br>
City: {{ $city }}<br>
Country: {{ $country }}<br>
ZIP Code: {{ $zipcode }}</p>

<p><strong>Destination Location:</strong><br>
City: {{ $destination_locality ?? 'N/A' }}<br>
State: {{ $destination_state ?? 'N/A' }}<br>
ZIP Code: {{ $destination_zipcode ?? 'N/A' }}</p>

<p>Thank you</p>


