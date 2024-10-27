<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    >
    <title>Address Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #E0F7FA;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Is this the right address?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Your address doesn’t match postal service records. Check that the info is correct before continuing.</p>
                    <div class="address-details">
                        <p>{{ $request_street_number }}, {{ $manual_route }}</p>
                        <p>{{ $manual_city }}, {{ $manual_state }}, {{ $manual_zipcode }}</p>
                        <p>{{ $manual_country }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="">
                        @csrf
                        <!-- Hidden field to send the request_id -->
                        <input type="hidden" name="request_id" value="">

                        <button type="submit" class="btn btn-primary">Use address</button>
                    </form>
                    <a href="" class="btn btn-secondary">Edit address</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
