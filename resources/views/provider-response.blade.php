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

        .btn-custom {
            margin-top: 20px;
            padding: 12px;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
        }

        .btn-accept {
            background-color: #5c6bc0;
            color: white;
            border: none;
        }

        .btn-accept:hover {
            background-color: #3949ab;
        }

        .btn-reject {
            background-color: #e57373;
            color: white;
            border: none;
        }

        .btn-reject:hover {
            background-color: #d32f2f;
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

        <div id="timer">Time remaining: 05:00</div>

        <form method="POST" action="{{ route('provider.response.submit', ['provider_id' => $provider->provider_id, 'request_id' => $requestEntry->request_id]) }}">
            @csrf

            <div class="d-flex justify-content-between mt-3">
                <button type="button" id="acceptBtn" class="btn btn-custom btn-accept">Accept</button>
                <button type="button" id="rejectBtn" class="btn btn-custom btn-reject">Reject</button>
            </div>

            <div id="etaSection" class="hidden mt-3">
                <label for="eta">ETA (Estimated Time of Arrival):</label>
                <select name="eta" id="eta">
                    @for ($i = 5; $i <= 60; $i += 5)
                        <option value="{{ $i }}">{{ $i }} minutes</option>
                    @endfor
                    @for ($i = 75; $i <= 120; $i += 15)
                        <option value="{{ $i }}">{{ $i }} minutes</option>
                    @endfor
                </select>
                <button type="submit" name="action" value="accept" class="btn btn-custom btn-accept mt-3">Confirm Accept</button>
            </div>

            <div id="dropReasonSection" class="hidden mt-3">
                <label for="drop_reason">Drop Reason:</label>
                <select name="drop_reason" id="drop_reason">
                    @foreach(\App\Models\DropReason::all() as $dropReason)
                        <option value="{{ $dropReason->reason_id }}">{{ $dropReason->reason }}</option>
                    @endforeach
                </select>

                <button type="submit" name="action" value="reject" class="btn btn-reject mt-3">Submit Rejection</button>
            </div>

            <input type="hidden" name="action" id="action" value="">
        </form>
    </div>

    <script>
        const expirationTime = {{ $expirationTime }} * 1000;
        let timeRemaining = Math.floor((expirationTime - Date.now()) / 1000);

        const timerElement = document.getElementById('timer');
        const rejectBtn = document.getElementById('rejectBtn');
        const acceptBtn = document.getElementById('acceptBtn');
        const etaSection = document.getElementById('etaSection');
        const dropReasonSection = document.getElementById('dropReasonSection');

        const countdown = setInterval(function () {
            let minutes = Math.floor(timeRemaining / 60);
            let seconds = timeRemaining % 60;
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

        rejectBtn.addEventListener('click', function() {
            dropReasonSection.style.display = 'block';
            etaSection.style.display = 'none';
            document.getElementById('action').value = 'reject';
        });

        acceptBtn.addEventListener('click', function() {
            etaSection.style.display = 'block';
            dropReasonSection.style.display = 'none';
            document.getElementById('action').value = 'accept';
        });
    </script>

</body>

</html>
