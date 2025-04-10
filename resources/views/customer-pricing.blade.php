<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Pricing</title>
    @vite('resources/js/app.js')

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom right, #f8f9fa, #e3e6eb);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .pricing-container {
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.15);
            text-align: center;
            transition: transform 0.3s ease-in-out;
        }

        .pricing-container:hover {
            transform: scale(1.03);
        }

        h2 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }

        p {
            font-size: 16px;
            margin: 5px 0;
            color: #555;
        }

        /* Buttons */
        .btn {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            font-size: 16px;
            font-weight: 500;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        .accept-btn {
            background: #28a745;
            color: white;
            box-shadow: 0px 4px 10px rgba(40, 167, 69, 0.3);
        }

        .accept-btn:hover {
            background: #218838;
            box-shadow: 0px 6px 15px rgba(40, 167, 69, 0.4);
        }

        .reject-btn {
            background: #dc3545;
            color: white;
            box-shadow: 0px 4px 10px rgba(220, 53, 69, 0.3);
        }

        .reject-btn:hover {
            background: #c82333;
            box-shadow: 0px 6px 15px rgba(220, 53, 69, 0.4);
        }

        /* Dropdown Styling */
        select {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: #fff;
            cursor: pointer;
            appearance: none;
            transition: border 0.3s ease-in-out;
        }

        select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0px 0px 6px rgba(0, 123, 255, 0.3);
        }

        /* Form Layout */
        form {
            margin-top: 15px;
        }

        label {
            font-size: 14px;
            color: #555;
            font-weight: 500;
            display: block;
            text-align: left;
            margin-top: 15px;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .pricing-container {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="pricing-container">
        <h2>Confirm Service Pricing</h2>
        <p><strong>Estimated Time of Arrival (ETA):</strong> {{ $eta }} minutes</p>
        <p><strong>Service Cost:</strong> ${{ number_format($finalPrice, 2) }}</p>

        <!-- Accept Button -->
        <form method="POST" action="{{ route('customer.pricing.accept') }}">
            @csrf
            <input type="hidden" name="request_id" value="{{ $requestId }}">
            <button type="submit" class="btn accept-btn">Accept and Proceed</button>
        </form>

        <!-- Reject Button with Reason -->
        <form method="POST" action="{{ route('customer.pricing.reject') }}" id="rejectForm">
            @csrf
            <input type="hidden" name="request_id" value="{{ $requestId }}">

            <label for="reject_reason">Reason for Rejection:</label>
            <select name="reject_reason" id="reject_reason" required>
                <option value="">Select Reason</option>
                <option value="Too expensive">Too expensive</option>
                <option value="Wait time is too long">Wait time is too long</option>
                <option value="Don't need the service anymore">Don't need the service anymore</option>
            </select>

            <button type="submit" class="btn reject-btn">Reject Request</button>
        </form>
    </div>

</body>

</html>
