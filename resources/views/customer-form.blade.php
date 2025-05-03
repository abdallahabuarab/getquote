<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Customer Information</title>
    <style>
        /* Main Page Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa; /* Light cyan background */
            color: #333;
        }

        /* Form Section */
        .form-container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 40px;
            margin-top: 50px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            max-width: 650px;
            margin-left: auto;
            margin-right: auto;
            border: 1px solid #e0f0f1;
        }

        .form-container h2 {
            font-weight: 700;
            color: #004d40;
            margin-bottom: 30px;
        }

        .form-group label {
            font-weight: bold;
            color: #333;
        }

        .form-control {
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #cccccc;
            padding: 12px;
        }

        .btn-primary {
            background-color: #00838f; /* Dark cyan */
            width: 100%;
            border-radius: 30px;
            font-size: 1.2rem;
            padding: 15px 0;
            border: none;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #006064; /* Darker shade on hover */
        }

        .alert-danger ul {
            list-style: none;
            padding-left: 0;
        }

        /* Additional Styling for Text */
        .info-text {
            text-align: center;
            font-size: 1.2rem;
            color: #00796b;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>



    <div class="container mt-4">
        <p class="info-text">
            Please provide your information below so that we can contact you regarding your request. Our team is committed to offering you the best service possible.
        </p>
    </div>

    <div class="container">
        <div class="form-container">
            <h2>Customer Information</h2>
            <form id="customerForm" method="POST" action="{{ route('customer.store') }}">
                @csrf
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <input type="hidden" name="request_id" value="{{ $requestId }}">

                <!-- Given Name -->
                <div class="form-group">
                    <label for="given_name">First Name:</label>
                    <input type="text" name="given_name" id="given_name" class="form-control" placeholder="Enter your first name" value="{{ old('given_name') }}">
                </div>

                <!-- Surname -->
                <div class="form-group">
                    <label for="surname">Last Name:</label>
                    <input type="text" name="surname" id="surname" class="form-control" placeholder="Enter your last name" value="{{ old('surname') }}">
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label for="phone_number">Phone Number:</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="Enter your phone number" value="{{ old('phone_number') }}">
                </div>

                <div class="form-group">
                    <label for="other_phone_number">Other Phone Number (Optional):</label>
                    <input type="text" name="other_phone_number" id="other_phone_number" class="form-control" placeholder="Enter another phone number" value="{{ old('other_phone_number') }}">
                </div>

                <div class="form-group">
                    <label for="communication_preference">Preferred Method of Communication:</label>
                    <select name="communication_preference[]" id="communication_preference" class="form-control" multiple>
                        <option value="text" {{ old('communication_preference') && in_array('text', old('communication_preference')) ? 'selected' : '' }}>Text</option>
                        <option value="call" {{ old('communication_preference') && in_array('call', old('communication_preference')) ? 'selected' : '' }}>Call</option>
                        <option value="email" {{ old('communication_preference') && in_array('email', old('communication_preference')) ? 'selected' : '' }}>Email</option>
                        <option value="app" {{ old('communication_preference') && in_array('app', old('communication_preference')) ? 'selected' : '' }}>App</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="p-4">
                    <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                </div>
            </form>
        </div>
    </div>



<style>
    .loading-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        height: 100vh;
        background-color: rgba(255, 255, 255, 0.8);
    }

    .spinner {
        border: 8px solid #f3f3f3;
        border-top: 8px solid #3498db;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    #loading-page p {
        font-size: 18px;
        color: #555;
        margin-top: 20px;
    }
</style>
</body>

</html>
