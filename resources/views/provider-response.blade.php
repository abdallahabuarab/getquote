<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <h1>Request Details for {{ $provider->name }}</h1>

        <p><strong>Customer Name:</strong> {{ $customer->given_name }}</p>
        <p><strong>Service:</strong> {{ $requestEntry->service->name }}</p>
        <p><strong>Class:</strong> {{ $requestEntry->classmodel->name }}</p>
        <p><strong>Vehicle Model:</strong> {{ $requestEntry->vehicle->vehicle_model }}</p>
        <p><strong>Vehicle Color:</strong> {{ $requestEntry->vehicle->vehicle_color }}</p>

        <!-- Timer -->
        <div id="timer">Time remaining: 05:00</div>

        <form method="POST" action="">
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
                    <option value="{{ $dropReason->id }}">{{ $dropReason->reason }}</option>
                    @endforeach
                </select>

                <!-- Submit Button for Reject Action -->
                <button type="submit" name="action" value="reject" class="btn btn-danger mt-3">Submit Rejection</button>
            </div>
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

        // Handle Reject Button Click
        rejectBtn.addEventListener('click', function () {
            dropReasonSection.classList.remove('hidden');
            acceptBtn.disabled = false; // Allow switching to accept after clicking reject
        });

        // Handle Accept Button Click
        acceptBtn.addEventListener('click', function () {
            dropReasonSection.classList.add('hidden'); // Hide the reject section if they change to accept
            window.location.href = "";
        });
    </script>

</body>

</html>
{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Your existing CSS */
    </style>
</head>

<body>

<div class="container">
    <h1>Request Details for {{ $provider->name }}</h1>

    <p><strong>Customer Name:</strong> {{ $customer->given_name }}</p>
    <p><strong>Service:</strong> {{ $requestEntry->service->name }}</p>
    <p><strong>Class:</strong> {{ $requestEntry->classmodel->name }}</p>
    <p><strong>Vehicle Model:</strong> {{ $requestEntry->vehicle->vehicle_model }}</p>
    <p><strong>Vehicle Color:</strong> {{ $requestEntry->vehicle->vehicle_color }}</p>

    <div id="timer"></div>

    <form method="POST" action="">
        @csrf
        <div id="action-buttons">
            <button type="button" class="btn btn-success" id="acceptBtn">Accept</button>
            <button type="button" class="btn btn-danger" id="rejectBtn">Reject</button>
        </div>

        <div id="dropReasonSection" class="hidden">
            <label for="drop_reason">Drop Reason:</label>
            <select name="drop_reason" id="drop_reason">
                @foreach(\App\Models\DropReason::all() as $dropReason)
                <option value="{{ $dropReason->id }}">{{ $dropReason->reason }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-danger">Submit Rejection</button>
        </div>
    </form>
</div>

<script>
    const expirationTimestamp = {{ $expirationTime }} * 1000; // Convert to milliseconds
    let timeRemaining = Math.floor((expirationTimestamp - Date.now()) / 1000); // Remaining time in seconds

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

    // Handle Reject Button Click
    rejectBtn.addEventListener('click', function () {
        dropReasonSection.classList.remove('hidden');
        acceptBtn.disabled = false; // Allow switching to accept after clicking reject
    });

    // Handle Accept Button Click
    acceptBtn.addEventListener('click', function () {
        dropReasonSection.classList.add('hidden'); // Hide the reject section if they change to accept
        window.location.href = "";
    });
</script>

</body>
</html> --}}
