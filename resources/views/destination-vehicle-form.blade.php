<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Destination & Vehicle Details</title>
    <style>
        body {
            background-color: #e0f7fa; /* Light cyan background */
            font-family: Arial, sans-serif;
        }

        .form-container {
            background-color: #ffffff; /* White background for the form */
            border-radius: 12px;
            padding: 40px;
            margin-top: 50px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        h2 {
            color: #333;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
        }

        .btn-primary {
            width: 100%;
            padding: 14px;
            background-color: #5c6bc0; /* Soft blue */
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #3949ab; /* Darker blue on hover */
        }

        .form-control {
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
            padding: 12px;
        }

        .alert-danger ul {
            list-style: none;
            padding-left: 0;
        }

        /* Unified sections with consistent styles */
        .section-header {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        .form-section {
            margin-bottom: 30px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="form-container">
            <h2>Enter Destination & Vehicle Details</h2>

            @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form id="destinationVehicleForm" method="POST" action="{{ route('destination-vehicle.store') }}">
                @csrf

                @if($service == 'tow')
                <div class="destination-header">
                    <h3>Destination Information</h3>
                    <input type="hidden" name="unique_token" value="{{ Str::random(40) }}">
                    <div class="form-group">
                        <label for="destinationLocationType">Select Destination Location Type:</label>
                        <select name="destination_location_type_id" id="destinationLocationType" class="form-control">
                            @foreach($locationTypes as $locationType)
                            <option value="{{ $locationType->location_type_id }}" {{ old('destination_location_type_id') == $locationType->location_type_id ? 'selected' : '' }}>
                                {{ $locationType->location_type }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="destination_zipcode">Destination Zip Code:</label>
                        <input type="text" id="destination_zipcode" name="destination_zipcode" class="form-control" placeholder="Enter destination ZIP code" value="{{ old('destination_zipcode') }}">
                    </div>

                    <div class="form-group">
                        <label for="destination_locality">City:</label>
                        <input type="text" id="destination_locality" name="destination_locality" class="form-control" placeholder="Enter destination city" value="{{ old('destination_locality') }}">
                    </div>

                    <div class="form-group">
                        <label for="destination_administrative_area_level_1">State:</label>
                        <input type="text" id="destination_administrative_area_level_1" name="destination_administrative_area_level_1" class="form-control" placeholder="Enter destination state" value="{{ old('destination_administrative_area_level_1') }}">
                    </div>

                @endif

                <!-- Vehicle Section -->
                <div class="form-section">
                    <div class="section-header">Vehicle Information</div>

                    <div class="form-group">
                        <label for="vehicle_year">Vehicle Year:</label>
                        <input type="number" id="vehicle_year" name="vehicle_year" class="form-control" placeholder="Enter vehicle year" value="{{ old('vehicle_year') }}">
                    </div>

                    <div class="form-group">
                        <label for="vehicle_make">Vehicle Make:</label>
                        <input type="text" id="vehicle_make" name="vehicle_make" class="form-control" placeholder="Enter vehicle make" value="{{ old('vehicle_make') }}">
                    </div>

                    <div class="form-group">
                        <label for="vehicle_model">Vehicle Model:</label>
                        <input type="text" id="vehicle_model" name="vehicle_model" class="form-control" placeholder="Enter vehicle model" value="{{ old('vehicle_model') }}">
                    </div>

                    <div class="form-group">
                        <label for="vehicle_color">Vehicle Color:</label>
                        <input type="text" id="vehicle_color" name="vehicle_color" class="form-control" placeholder="Enter vehicle color" value="{{ old('vehicle_color') }}">
                    </div>

                    <div class="form-group">
                        <label for="vehicle_style">Vehicle Style:</label>
                        <input type="text" id="vehicle_style" name="vehicle_style" class="form-control" placeholder="Enter vehicle style (e.g., sedan, SUV)" value="{{ old('vehicle_style') }}">
                    </div>

                    <div class="form-group">
                        <label for="vin">Vehicle Identification Number (VIN):</label>
                        <input type="text" id="vin" name="vin" class="form-control" placeholder="Enter VIN" value="{{ old('vin') }}">
                    </div>

                    <div class="form-group">
                        <label for="plate">License Plate Number:</label>
                        <input type="text" id="plate" name="plate" class="form-control" placeholder="Enter plate number" value="{{ old('plate') }}">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="p-4">
                    <button type="submit" class="btn btn-primary">Submit Vehicle Details</button>
                </div>

            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector('form').addEventListener('submit', function () {
            this.querySelector('button[type="submit"]').disabled = true;
        });
    </script>
</body>

</html>
