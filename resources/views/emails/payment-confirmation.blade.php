<p>Dear {{ $provider->provider_name }},</p>

<p>We are pleased to inform you that the customer has successfully completed the payment for the requested service:</p>
<p><strong>Service:</strong> {{ $service }}<br>
<strong>Class:</strong> {{ $class }}</p>

<p>You may now proceed with fulfilling the service as scheduled.</p>

<hr>

<p><strong>Vehicle Information:</strong></p>
<ul style="margin: 0; padding-left: 20px;">
    <li><strong>Year:</strong> {{ $vehicle_year ?? 'N/A' }}</li>
    <li><strong>Make:</strong> {{ $vehicle_make ?? 'N/A' }}</li>
    <li><strong>Model:</strong> {{ $vehicle_model ?? 'N/A' }}</li>
    <li><strong>VIN:</strong> {{ $VIN ?? 'N/A' }}</li>
    <li><strong>Plate:</strong> {{ $Plate ?? 'N/A' }}</li>
</ul>

<hr>

<p><strong>Pickup Location:</strong></p>
<ul style="margin: 0; padding-left: 20px;">
    <li><strong>City:</strong> {{ $city }}</li>
    <li><strong>Country:</strong> {{ $country }}</li>
    <li><strong>ZIP Code:</strong> {{ $zipcode }}</li>
</ul>

<p><strong>Destination Location:</strong></p>
<ul style="margin: 0; padding-left: 20px;">
    <li><strong>City:</strong> {{ $destination_locality ?? 'N/A' }}</li>
    <li><strong>State:</strong> {{ $destination_state ?? 'N/A' }}</li>
    <li><strong>ZIP Code:</strong> {{ $destination_zipcode ?? 'N/A' }}</li>
</ul>

<hr>

<p>Thank you,<br>
TowNow Team</p>
