<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Rejected</title>
    @vite('resources/js/app.js')
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
            text-align: center;
            max-width: 400px;
        }

        h2 {
            font-size: 22px;
            color: #dc3545;
        }

        p {
            font-size: 16px;
            color: #555;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            margin-top: 15px;
        }

        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Request Rejected</h2>
        <p>You rejected this request due to:</p>
        <p><strong>{{ $reason }}</strong></p>
        <a href="{{ route('requests.create') }}" class="btn">Go Back to Home</a>
    </div>

</body>
</html>
