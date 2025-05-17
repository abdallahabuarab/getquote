<!DOCTYPE html>
<html>
<head>
    <title>New Service Request</title>
</head>
<body>
    <h1>Hello, {{ $provider->provider_name }}</h1>

    <p>A new service request has been made for {{ $service_name }}.</p>

    <p>Please click the button below to review the details:</p>

    <a href="{{ $secureLink }}" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
        View Request
    </a>

    <p>This link will expire at {{ \Carbon\Carbon::parse($expiration)->format('h:i A') }} ({{ \Carbon\Carbon::parse($expiration)->diffForHumans() }}).</p>
</body>
</html>
