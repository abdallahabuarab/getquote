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
            width: 100%;
            cursor: pointer;
        }

        button:hover {
            background-color: #3949ab;
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

        <form method="POST" action="">
            @csrf
            <label for="action">Action:</label>
            <select name="action" id="action">
                <option value="accept">Accept</option>
                <option value="reject">Reject</option>
            </select>

            <label for="drop_reason">Drop Reason:</label>
            <select name="drop_reason" id="drop_reason">
                @foreach(\App\Models\DropReason::all() as $dropReason)
                <option value="{{ $dropReason->id }}">{{ $dropReason->reason }}</option>
                @endforeach
            </select>

            <button type="submit">Submit</button>
        </form>
    </div>

</body>

</html>
