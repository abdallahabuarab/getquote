<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Timeout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .apology-container {
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        .apology-message {
            color: red;
            font-size: 1.5rem;
        }

        .suggestions {
            margin-top: 20px;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

    <div class="apology-container">
        <h1 class="apology-message">We're sorry, the customer canceled or took too long.</h1>
        <p class="suggestions">Please try again with another request.</p>
    </div>

</body>
</html>
