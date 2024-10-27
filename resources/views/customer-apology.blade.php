<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We're Sorry</title>
    <style>
        /* Styling for the apology page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .apology-container {
            background-color: white;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        .apology-container h1 {
            color: #d9534f; /* Red color */
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .apology-container p {
            font-size: 1.2rem;
            color: #555;
        }

        .reason-text {
            font-weight: bold;
            color: #d9534f; /* Red for emphasis */
        }

        .back-link {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #5bc0de; /* Blue color */
            color: white;
            border-radius: 25px;
            text-decoration: none;
        }

        .back-link:hover {
            background-color: #31b0d5;
        }
    </style>
</head>
<body>
    <div class="apology-container">
        <h1>We're Sorry!</h1>
        <p>Unfortunately, the provider could not accept your request.</p>

        <!-- Safely display the reason if it's available -->
            <p class="reason-text">Reason: {{ $dropReason }}</p>


        <!-- Add a link to go back to the home page or another relevant page -->
        <a href="{{ route('requests.create') }}" class="back-link">Go Back to Home</a>
    </div>
</body>
</html>
