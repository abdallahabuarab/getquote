<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Vehicle;
use App\Models\Provider;
use App\Models\Destination;
use App\Models\Customer;  // Import the Customer model to get the customer's name
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ZipcodeReference;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Models\Service;  // Ensure the Service model is imported
use App\Models\Request as RequestModel;

class DestinationVehicleController extends Controller
{
    public function create($request_id)
    {
        $service = session()->get('service', 'other');
        $locationTypes = \App\Models\LocationType::all();

        return view('destination-vehicle-form', compact('request_id', 'locationTypes', 'service'));
    }

    public function store(Request $request)
    {
        // Validate vehicle inputs
        $validatedData = $request->validate([
            'vehicle_year' => 'required|numeric|digits:4',
            'vehicle_make' => 'required|string|max:25',
            'vehicle_model' => 'required|string|max:25',
            'vehicle_color' => 'required|string|max:20',
            'vehicle_style' => 'required|string|max:20',
            'vin' => 'required|string|max:17',
            'plate' => 'required|string|max:12',
        ]);

        // Get the request_id from session
        $requestId = $request->session()->get('request_id');

        if (!$requestId) {
            return redirect()->back()->withErrors(['error' => 'No request ID found. Please submit the request first.']);
        }

        $requestEntry = RequestModel::find($requestId);

        if (!$requestEntry) {
            return redirect()->back()->withErrors(['error' => 'Request not found.']);
        }

        $customer = Customer::where('request_id', $requestId)->first();

        if (!$customer) {
            return redirect()->back()->withErrors(['error' => 'Customer not found.']);
        }

        $service = Service::find($requestEntry->request_service);
        if ($service && strtolower($service->name) === 'tow') {
            $validatedDestinationData = $request->validate([
                'destination_zipcode' => 'required|numeric',
                'destination_business_name' => 'nullable|string|max:50',
                'destination_street_number' => 'nullable|string|max:20',
                'destination_route' => 'nullable|string|max:32',
                'destination_locality' => 'nullable|string|max:32',
                'destination_administrative_area_level_1' => 'nullable|string|size:2',
                'destination_location_type_id' => 'required|exists:location_types,location_type_id',
                'destination_name' => 'nullable|string|max:50',
            ]);

            // Clean the ZIP code
            $validatedDestinationData['destination_zipcode'] = preg_replace('/\D/', '', $validatedDestinationData['destination_zipcode']);

            $zipcodeExists = ZipcodeReference::where('zipcode', $validatedDestinationData['destination_zipcode'])->first();
            if (!$zipcodeExists) {
                return redirect()->back()->withErrors(['destination_zipcode' => 'Zip code not covered.'])->withInput();
            }

            $destinationLongitude = $zipcodeExists->longitude ?? 0.0;
            $destinationLatitude = $zipcodeExists->latitude ?? 0.0;
            if ($request->session()->has('unique_token') && $request->session()->get('unique_token') == $request->input('unique_token')) {
                return redirect()->back()->withErrors(['error' => 'This form has already been submitted.']);
            }
            $request->session()->put('unique_token', $request->input('unique_token'));

            Destination::create([
                'request_id' => $requestId,
                'business_name' => $validatedDestinationData['destination_business_name'] ?? 'N/A',
                'destination_street_number' => $validatedDestinationData['destination_street_number'] ?? 'N/A',
                'destination_route' => $validatedDestinationData['destination_route'] ?? 'N/A',
                'destination_locality' => $validatedDestinationData['destination_locality'] ?? 'N/A',
                'destination_administrative_area_level_1' => strtoupper($validatedDestinationData['destination_administrative_area_level_1'] ?? 'N'),
                'destination_zipcode' => $validatedDestinationData['destination_zipcode'],
                'destination_longitude' => $destinationLongitude,
                'destination_latitude' => $destinationLatitude,
                'destination_address_source' => 'Manual',
                'destination_location_type_id' => $validatedDestinationData['destination_location_type_id'],
                'destination_name' => $validatedDestinationData['destination_name'] ?? 'N/A', // Provide a default value here
            ]);
        }

        Vehicle::create([
            'request_id' => $requestId,
            'vehicle_year' => $validatedData['vehicle_year'],
            'vehicle_make' => $validatedData['vehicle_make'],
            'vehicle_model' => $validatedData['vehicle_model'],
            'vehicle_color' => $validatedData['vehicle_color'],
            'vehicle_style' => $validatedData['vehicle_style'],
            'VIN' => $validatedData['vin'],
            'Plate' => $validatedData['plate'],
        ]);

        $provider = Provider::where('zipcode', $requestEntry->request_zipcode)
            ->where('is_active', 'yes')
            ->first();

        if ($provider) {
            $expires = Carbon::now()->addMinutes(5);
            $token = Str::random(40);

            $secureLink = URL::temporarySignedRoute('provider.response', $expires, [
                'provider_id' => $provider->provider_id,
                'request_id' => $requestEntry->request_id,
                'token' => $token
            ]);

            Mail::send('emails.provider-notification', [
                'customer_name' => $customer->given_name . ' ' . $customer->surname,
                'service_name' => Service::find($requestEntry->request_service)->name,
                'secureLink' => $secureLink,
                'provider' => $provider,
            ], function ($message) use ($provider) {
                $message->to($provider->provider_email)
                        ->subject('New Service Request');
            });
        }

        return redirect()->route('requests.web')->with('success', 'Details saved successfully, and the provider has been notified.');
    }
}








// namespace App\Http\Controllers;

// use Carbon\Carbon;
// use App\Models\Vehicle;
// use App\Models\Destination;
// use App\Models\LocationType;
// use Illuminate\Http\Request;
// use App\Models\ZipcodeReference;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Log;

// class DestinationVehicleController extends Controller
// {
//     public function create($request_id)
//     {
//         $locationTypes = LocationType::all();

//         // Display the form for destination and vehicle information
//         return view('destination-vehicle-form', compact('request_id','locationTypes'));
//     }

//     public function store(Request $request)
//     {
//         // Validate input for both destination and vehicle
//         $validatedData = $request->validate([
//             // Destination inputs
//             'destination_zipcode' => 'required|numeric',
//             'destination_business_name' => 'nullable|string|max:50',
//             'destination_street_number' => 'nullable|string|max:20',
//             'destination_route' => 'nullable|string|max:32',
//             'destination_locality' => 'nullable|string|max:32',
//             'destination_administrative_area_level_1' => 'nullable|string|size:2',
//             'destination_name' => 'nullable|string|max:50', // Add this field for destination name
//             'destination_location_type_id' => 'required|exists:location_types,location_type_id', // Add validation for location type

//             // Vehicle inputs
//             'vehicle_year' => 'required|numeric|digits:4',
//             'vehicle_make' => 'required|string|max:25',
//             'vehicle_model' => 'required|string|max:25',
//             'vehicle_color' => 'required|string|max:20',
//             'vehicle_style' => 'required|string|max:20',
//             'vin' => 'required|string|max:17',
//             'plate' => 'required|string|max:12',
//         ]);

//         // Clean the ZIP code
//         $validatedData['destination_zipcode'] = preg_replace('/\D/', '', $validatedData['destination_zipcode']);

//         // Get the request_id from session
//         $requestId = $request->session()->get('request_id');

//         if (!$requestId) {
//             return redirect()->back()->withErrors(['error' => 'No request ID found. Please submit the request first.']);
//         }

//         // Check if ZIP code exists in the `zipcode_reference` table
//         $zipcodeExists = ZipcodeReference::where('zipcode', $validatedData['destination_zipcode'])->first();
//         if (!$zipcodeExists) {
//             return redirect()->back()->withErrors(['destination_zipcode' => 'Zip code not covered.'])->withInput();
//         }

//         // Extract latitude and longitude from the ZIP code
//         $destinationLongitude = $zipcodeExists->longitude ?? 0.0;
//         $destinationLatitude = $zipcodeExists->latitude ?? 0.0;

//         DB::beginTransaction(); // Begin transaction for data consistency

//         try {
//             // Step 1: Save the destination details, including destination_name
//             Destination::create([
//                 'request_id' => $requestId,
//                 'business_name' => $validatedData['destination_business_name'] ?? 'N/A',
//                 'destination_street_number' => $validatedData['destination_street_number'] ?? 'N/A',
//                 'destination_route' => $validatedData['destination_route'] ?? 'N/A',
//                 'destination_locality' => $validatedData['destination_locality'] ?? 'N/A',
//                 'destination_administrative_area_level_1' => strtoupper($validatedData['destination_administrative_area_level_1']) ?? 'N',
//                 'destination_zipcode' => $validatedData['destination_zipcode'],
//                 'destination_longitude' => $destinationLongitude,
//                 'destination_latitude' => $destinationLatitude,
//                 'destination_location_type_id' => $validatedData['destination_location_type_id'],
//                 'destination_address_source' => 'Manual',
//                 'destination_name' => $validatedData['destination_name'] ?? 'N/A', // Add destination_name with default value
//                 'created_at' => Carbon::now(),
//                 'updated_at' => Carbon::now(),
//             ]);

//             // Step 2: Save the vehicle details
//             Vehicle::create([
//                 'request_id' => $requestId,
//                 'vehicle_year' => $validatedData['vehicle_year'],
//                 'vehicle_make' => $validatedData['vehicle_make'],
//                 'vehicle_model' => $validatedData['vehicle_model'],
//                 'vehicle_color' => $validatedData['vehicle_color'],
//                 'vehicle_style' => $validatedData['vehicle_style'],
//                 'vin' => $validatedData['vin'],
//                 'plate' => $validatedData['plate'],
//                 'created_at' => Carbon::now(),
//                 'updated_at' => Carbon::now(),
//             ]);

//             DB::commit(); // Commit transaction after successful saving of both destination and vehicle

//             // Redirect to a success page or next step
//             return redirect()->route('thankyou')->with('success', 'Destination and Vehicle details saved successfully.');

//         } catch (\Exception $e) {
//             DB::rollBack(); // Rollback transaction if something goes wrong

//             // Log the error for debugging
//             Log::error('Error while saving destination and vehicle: ', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

//             // Redirect back with error message and input
//             return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()])->withInput();
//         }
//     }
// }
