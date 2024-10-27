<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/js/app.js', 'resources/css/app.css'])

    <title>Request Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #ffffff;
            margin-top: 50px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        h1 {
            color: #333;
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 30px;
        }

        p {
            font-size: 1.1rem;
            color: #555;
        }

        p strong {
            color: #333;
        }

        label {
            font-weight: bold;
            margin-top: 20px;
            display: block;
            color: #333;
        }

        select, input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1rem;
            color: #333;
        }

        button {
            margin-top: 30px;
            padding: 12px;
            background-color: #5c6bc0;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.2rem;
            cursor: pointer;
        }

        button:hover {
            background-color: #3949ab;
        }

        .hidden {
            display: none;
        }

        #timer {
            font-size: 1.5rem;
            color: red;
            text-align: center;
            margin-top: 20px;
        }

    </style>
</head>

<body>

    <div class="container">
        <h1>Request Details for {{ $provider->provider_name }}</h1>

        <p><strong>Service:</strong> {{ $requestEntry->service->name }}</p>
        <p><strong>Class:</strong> {{ $requestEntry->classmodel->name }}</p>
        <p><strong>Vehicle Model:</strong> {{ $requestEntry->vehicle->vehicle_model }}</p>
        <p><strong>Vehicle Color:</strong> {{ $requestEntry->vehicle->vehicle_color }}</p>
        <p><strong>Country:</strong> {{ $requestEntry->request_ip_country }}</p>
        <p><strong>City:</strong> {{ $requestEntry->request_ip_city }}</p>
        <p><strong>ZipCode:</strong> {{ $requestEntry->request_zipcode }}</p>
        <!-- Timer -->
        <div id="timer">Time remaining: 05:00</div>

        <form method="POST" action="{{ route('provider.response.submit', ['provider_id' => $provider->provider_id, 'request_id' => $requestEntry->request_id]) }}">
            @csrf
            <!-- Accept and Reject Buttons -->
            <div class="d-flex justify-content-between">
                <button type="button" id="acceptBtn" class="btn btn-success">Accept</button>
                <button type="button" id="rejectBtn" class="btn btn-danger">Reject</button>
            </div>

            <!-- Dropdown for Drop Reasons (Initially Hidden) -->
            <div id="dropReasonSection" class="hidden mt-3">
                <label for="drop_reason">Drop Reason:</label>
                <select name="drop_reason" id="drop_reason">
                    @foreach(\App\Models\DropReason::all() as $dropReason)
                    <option value="{{ $dropReason->reason_id }}">{{ $dropReason->reason }}</option>
                    @endforeach
                </select>

                <!-- Submit Button for Reject Action -->
                <button type="submit" name="action" value="reject" class="btn btn-danger mt-3">Submit Rejection</button>
            </div>

            <!-- Hidden Field for Accept Action -->
            <input type="hidden" name="action" id="action" value="">

            <!-- Submit Button for Accept Action -->
            <button type="submit" id="submitAccept" name="action" value="accept" style="display: none;"></button>
        </form>
    </div>

    <script>
        const expirationTime = {{ $expirationTime }} * 1000; // Convert to milliseconds
        let timeRemaining = Math.floor((expirationTime - Date.now()) / 1000); // Remaining time in seconds

        const timerElement = document.getElementById('timer');
        const rejectBtn = document.getElementById('rejectBtn');
        const acceptBtn = document.getElementById('acceptBtn');
        const dropReasonSection = document.getElementById('dropReasonSection');

        // Countdown Timer
        const countdown = setInterval(function () {
            let minutes = Math.floor(timeRemaining / 60);
            let seconds = timeRemaining % 60;

            // Format seconds
            seconds = seconds < 10 ? '0' + seconds : seconds;

            timerElement.innerHTML = `Time remaining: ${minutes}:${seconds}`;

            timeRemaining--;

            if (timeRemaining < 0) {
                clearInterval(countdown);
                timerElement.innerHTML = "This link has expired.";
                acceptBtn.disabled = true;
                rejectBtn.disabled = true;
            }
        }, 1000);


        document.getElementById('rejectBtn').addEventListener('click', function() {
        // Show the drop reason section when 'Reject' is clicked
        document.getElementById('dropReasonSection').style.display = 'block';
        document.getElementById('action').value = 'reject'; // Set action value to 'reject'
    });

    document.getElementById('acceptBtn').addEventListener('click', function() {
        // Hide the drop reason section if 'Accept' is clicked
        document.getElementById('dropReasonSection').style.display = 'none';
        document.getElementById('drop_reason').value = ''; // Clear drop reason

        // Automatically submit the form for the 'Accept' action
        document.getElementById('action').value = 'te'; // Set action value to 'accept'
        document.getElementById('submitAccept').click();
    });
    </script>

</body>

</html>
