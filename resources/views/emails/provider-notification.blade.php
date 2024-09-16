<!DOCTYPE html>
<html>
<head>
    <title>New Service Request</title>
</head>
<body>
    <h1>Hello, {{ $provider->provider_name }}</h1>

    <p>A new service request has been made by {{ $customer_name }} for {{ $service_name }}.</p>

    <p>Please click the button below to review the details:</p>

    <a href="{{ $secureLink }}" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
        View Request
    </a>

    <p>This link will expire in 5 minutes.</p>
</body>
</html>
