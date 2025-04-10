<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Vehicle;
use App\Models\Provider;
use App\Models\Destination;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ZipcodeReference;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Models\Request as RequestModel;
use App\Models\Service;
use App\Models\Customer;

class DestinationVehicleController extends Controller
{
    public function create($request_id, Request $request)
    {
        $providerId = $request->query('provider_id');
        $provider = Provider::find($providerId);
        $service = session()->get('service', 'other');
        $locationTypes = \App\Models\LocationType::all();

        return view('destination-vehicle-form', compact('request_id', 'locationTypes', 'service', 'provider'));
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
        'vin' => '',
        'plate' => '',
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
    $providerId = $request->input('provider_id');
    $provider = Provider::find($providerId);
    if (!$provider) {
        return redirect()->back()->withErrors(['error' => 'Provider not found.']);
    }


    $service = Service::find($requestEntry->request_service);
    if ($service && strtolower($service->name) === 'tow') {
        $validatedDestinationData = $request->validate([
            'destination_zipcode' => [
        'required',
        'numeric',
        'exists:zipcode_reference,zipcode'  // Check if exists in the table
    ],
            'business_name' => 'nullable|string|max:50',
            'destination_street_number' => 'nullable|string|max:20',
            'destination_route' => 'nullable|string|max:32',
            'destination_locality' => 'nullable|string|max:32',
            'destination_administrative_area_level_1' => 'nullable|string|size:2',
            'destination_location_type_id' => 'required|exists:location_types,location_type_id',
            'destination_name' => 'nullable|string|max:50',
        ]);

        // Clean the ZIP code
        $validatedDestinationData['destination_zipcode'] = preg_replace('/\D/', '', $validatedDestinationData['destination_zipcode']);
if($validatedDestinationData)
        // Validate the destination address with Google Geocoding API
        $locationValidation  = $this->validateDestinationLocation(
            $validatedDestinationData['destination_zipcode'],
            $validatedDestinationData['destination_locality'],
            $validatedDestinationData['destination_administrative_area_level_1']
        );
        if (!$locationValidation || !$locationValidation['longitude'] ) {
            return redirect()->back()->withErrors(['destination_zipcode' => 'Invalid destination location: Please check the ZIP code, city, or state.'])->withInput();
        }


        // Insert destination information
        Destination::create([
            'request_id' => $requestId,
            'business_name' => $validatedDestinationData['business_name'] ?? 'N/A',
            'destination_street_number' => $validatedDestinationData['destination_street_number'] ?? 'N/A',
            'destination_route' => $validatedDestinationData['destination_route'] ?? 'N/A',
            'destination_locality' => $validatedDestinationData['destination_locality'] ?? 'N/A',
            'destination_administrative_area_level_1' => strtoupper($validatedDestinationData['destination_administrative_area_level_1'] ?? 'N'),
            'destination_zipcode' => $validatedDestinationData['destination_zipcode'],
            'destination_longitude' => $locationValidation['longitude'], // Save extracted longitude
            'destination_latitude' => $locationValidation['latitude'], // Save extracted latitude
            'destination_address_source' => 'Manual',
            'destination_location_type_id' => $validatedDestinationData['destination_location_type_id'],
            'destination_name' => $validatedDestinationData['destination_name'] ?? 'N/A',
        ]);
        session([
            'destination_zipcode' => $validatedDestinationData['destination_zipcode'],
            'destination_locality' => $validatedDestinationData['destination_locality'],
            'destination_state' => strtoupper($validatedDestinationData['destination_administrative_area_level_1']),
        ]);

    }

    // Vehicle section remains unchanged
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


    $expires = Carbon::now()->addMinutes(50);
    $token = Str::random(40);

    $secureLink = URL::temporarySignedRoute('provider.response', $expires, [
        'provider_id' => $provider->provider_id,
        'request_id' => $requestEntry->request_id,
        'token' => $token
    ]);
    session(['expiration_time' => $expires->timestamp]);
    // Send email notification to the provider
    Mail::send('emails.provider-notification', [
        'service_name' => $service->name,
        'secureLink' => $secureLink,
        'provider' => $provider,
    ], function ($message) use ($provider,$requestId) {
        $message->to($provider->provider_email)
                ->subject('New Service Request/'.$requestId);
    });


    return redirect()->route('customer.loading', ['request_id' => $requestEntry->request_id,'provider_id'=>$provider]);
}
/**
 * Validate the destination location using the Google Geocoding API.
 *
 * @param string $zipcode
 * @param string $city
 * @param string $state
 * @return bool
 */


 private function validateDestinationLocation($zipcode, $city, $state)
{
    $googleApiKey = 'AIzaSyDsk5RExl2Xr7w2BayGTYdsePr2v6WBjmo'; // Ensure this is set in your .env file
    $address = urlencode(trim($zipcode) . ' ' . trim($city) . ' ' . trim($state));

    $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$googleApiKey}";

    // Fetch the API response
    $response = file_get_contents($url);
    $json = json_decode($response, true);

    // Log the response for debugging
    Log::info('Google API Response:', $json);

    if ($json['status'] === 'OK' && isset($json['results'][0])) {
        $result = $json['results'][0];

        // Extract latitude & longitude
        $latitude = $result['geometry']['location']['lat'];
        $longitude = $result['geometry']['location']['lng'];

        $foundZipCode = '';
        $foundCity = '';
        $foundState = '';

        // Extract address components
        foreach ($result['address_components'] as $component) {
            if (in_array('postal_code', $component['types'])) {
                $foundZipCode = $component['long_name'];
            }
            if (in_array('locality', $component['types'])) {
                $foundCity = $component['long_name'];
            } elseif (in_array('sublocality', $component['types'])) {
                $foundCity = $component['long_name'];
            }
            if (in_array('administrative_area_level_1', $component['types'])) {
                $foundState = $component['short_name'];
            }
        }

        // Log extracted components for debugging
        Log::info('Extracted Zip Code:', [$foundZipCode]);
        Log::info('Extracted City:', [$foundCity]);
        Log::info('Extracted State:', [$foundState]);
        Log::info('Extracted Latitude:', [$latitude]);
        Log::info('Extracted Longitude:', [$longitude]);

        // Perform validation
        $zipMatch = strcasecmp($foundZipCode, $zipcode) === 0;
        $cityMatch = stripos($foundCity, $city) !== false;
        $stateMatch = strcasecmp($foundState, $state) === 0;

        if ($zipMatch && $cityMatch && $stateMatch) {
            return [
                'valid' => true,
                'latitude' => $latitude,
                'longitude' => $longitude
            ];
        }
    } else {
        // Log error if response is not OK
        Log::error('Invalid response from Google API:', [
            'status' => $json['status'] ?? 'UNKNOWN',
            'error_message' => $json['error_message'] ?? 'No error message provided',
        ]);
    }

    return ['valid' => false, 'latitude' => null, 'longitude' => null];
}
}

// private function validateDestinationLocation($zipcode, $city, $state)
// {
//     $googleApiKey ='AIzaSyDsk5RExl2Xr7w2BayGTYdsePr2v6WBjmo';
//     $address = urlencode(trim($zipcode) . ' ' . trim($city) . ' ' . trim($state));

//     $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$googleApiKey}";

//     // Fetch the API response
//     $response = file_get_contents($url);
//     $json = json_decode($response, true);

//     // Log the response for debugging
//     Log::info('Google API Response:', $json);

//     if ($json['status'] === 'OK' && isset($json['results'][0])) {
//         $result = $json['results'][0];
//         $foundZipCode = '';
//         $foundCity = '';
//         $foundState = '';

//         // Extract address components
//         foreach ($result['address_components'] as $component) {
//             if (in_array('postal_code', $component['types'])) {
//                 $foundZipCode = $component['long_name'];
//             }
//             if (in_array('locality', $component['types'])) {
//                 $foundCity = $component['long_name'];
//             } elseif (in_array('sublocality', $component['types'])) {
//                 $foundCity = $component['long_name'];
//             }
//             if (in_array('administrative_area_level_1', $component['types'])) {
//                 $foundState = $component['short_name'];
//             }
//         }

//         // Log extracted components for debugging
//         Log::info('Extracted Zip Code:', [$foundZipCode]);
//         Log::info('Extracted City:', [$foundCity]);
//         Log::info('Extracted State:', [$foundState]);

//         // Perform the validation with detailed logging
//         $zipMatch = strcasecmp($foundZipCode, $zipcode) === 0;
//         $cityMatch = stripos($foundCity, $city) !== false; // Partial match for city
//         $stateMatch = strcasecmp($foundState, $state) === 0;

//         Log::info('ZIP Match:', [$zipMatch]);
//         Log::info('City Match:', [$cityMatch]);
//         Log::info('State Match:', [$stateMatch]);

//         if ($zipMatch && $cityMatch && $stateMatch) {
//             return true;
//         }
//     } else {
//         // Log the error if the response status is not OK
//         Log::error('Invalid response from Google API:', [
//             'status' => $json['status'] ?? 'UNKNOWN',
//             'error_message' => $json['error_message'] ?? 'No error message provided',
//         ]);
//     }

//     return false;
// }











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
