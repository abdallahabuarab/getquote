<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Reject;
use App\Models\Service;
use App\Models\Provider;
use App\Models\ClassModel;
use App\Models\DropReason;
use App\Models\LocationType;
use Illuminate\Http\Request;
use App\Models\ZipcodeReference;
use Illuminate\Support\Facades\DB;
use App\Models\Request as RequestModel;

class RequestController extends Controller
{
    public function create()
    {
        $classes = ClassModel::all();
        $services = Service::all();
        $locationTypes = LocationType::all();

        return view('get-quote', compact('classes', 'services', 'locationTypes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'service_id' => 'required|exists:services,service_id',
            'class_id' => 'required|exists:classes,class_id',
            'zipcode' => 'nullable|string', // Used in autocomplete case
            'manual_zipcode' => 'nullable|string', // Used in manual case
            'locationInput' => 'nullable|string',
            'request_longitude' => 'nullable|numeric',
            'request_latitude' => 'nullable|numeric',
            'request_ip_city' => 'nullable|string|max:50',
            'request_ip_region' => 'nullable|string|max:50',
            'request_ip_country' => 'nullable|string|max:50', // Validate both manual and API country
            'manual_country' => 'nullable|string|max:50', // Manual country input validation
            'request_ip_timezone' => 'nullable|string|max:50',
            'request_street_number' => 'nullable|string|max:20',
            'request_route' => 'nullable|string|max:50',
            'request_priority' => 'nullable|in:low,normal,high',
            'request_location_type_id' => 'required|exists:location_types,location_type_id',
        ]);


         // dd($validatedData);

        // Determine which ZIP code is being used (manual or autocomplete)
        if ($request->has('manual_zipcode') && !empty($validatedData['manual_zipcode'])) {
            $zipcode = $validatedData['manual_zipcode'];
            $city = $validatedData['request_ip_city'];
            $country = $validatedData['manual_country']; // Use the manually entered country

            // Validate the manually entered address with Google Geocoding API
            $validLocation = $this->validateManualLocation($zipcode, $city, $country);
            if (!$validLocation) {
                return redirect()->back()->withErrors(['manual_zipcode' => 'Invalid location: Please check the ZIP code, city, or country.'])->withInput();
            }
        } elseif ($request->has('zipcode') && !empty($validatedData['zipcode'])) {
            $zipcode = $validatedData['zipcode'];
            $country = $validatedData['request_ip_country']; // Country from API
        } else {
            return redirect()->back()->withErrors(['zipcode' => 'Zip Code is required.'])->withInput();
        }

        // Check if the ZIP code is covered and providers exist
        $zipReference = ZipcodeReference::where('zipcode', $zipcode)->first();
        if (!$zipReference) {
            $dropReason = DropReason::where('reason', 'Zip Code is not defined')->first();
            $this->handleRejection($validatedData, $dropReason, $zipcode);

            return redirect()->back()->withErrors(['zipcode' => $dropReason ? $dropReason->reason : 'Zip Code not covered.'])->withInput();
        }

        $activeProvider = Provider::where('zipcode', $zipcode)
            ->where('is_active', 'yes')
            ->first();

        if (!$activeProvider) {
            $dropReason = DropReason::where('reason', 'No active provider covering zip code')->first();
            $this->handleRejection($validatedData, $dropReason, $zipcode);

            return redirect()->back()->withErrors(['zipcode' => $dropReason ? $dropReason->reason : 'No active provider covering this ZIP code.'])->withInput();
        }

        // Create the request entry
        $requestEntry = RequestModel::create([
            'request_ip_address' => inet_pton($request->ip()),
            'request_ip_city' => $validatedData['request_ip_city'] ?? 'Default City',
            'request_ip_region' => $validatedData['request_ip_region'] ?? 'Default Region',
            'request_ip_country' => $validatedData['request_ip_country'] ??$validatedData['manual_country'],
            'request_ip_timezone' => $validatedData['request_ip_timezone'] ?? 'UTC',
            'request_street_number' => $validatedData['request_street_number'] ?? 'N/A',
            'request_route' => $validatedData['request_route'] ?? 'N/A',
            'request_locality' => $validatedData['request_locality'] ?? 'N/A',
            'request_administrative_area_level_1' => $validatedData['request_administrative_area_level_1'] ?? 'N',
            'request_longitude' => $validatedData['request_longitude'] ?? 0.0,
            'request_latitude' => $validatedData['request_latitude'] ?? 0.0,
            'request_zipcode' => $zipcode,
            'request_priority' => $validatedData['request_priority'] ?? 'normal',
            'request_location_type_id' => $validatedData['request_location_type_id'],
            'request_service' => $validatedData['service_id'],
            'request_class' => $validatedData['class_id'],
            'request_device' => $validatedData['request_device'] ?? 'Unknown Device',
            'request_os' => $validatedData['request_os'] ?? 'Unknown OS',
            'request_cross_street' => $validatedData['request_cross_street'] ?? 'N/A',
            'request_location_name' => $validatedData['request_location_name'] ?? 'N/A',
            'request_datetime' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $service = Service::find($validatedData['service_id'])->name;

        $request->session()->put('service', $service);
        $request->session()->put('request_id', $requestEntry->request_id);

        return redirect()->route('customer.create', ['request_id' => $requestEntry->request_id]);
    }

    private function validateManualLocation($zipcode, $city, $country)
{
    $googleApiKey = env('GOOGLE_MAPS_API_KEY');
    $address = urlencode(trim($zipcode) . ' ' . trim($city) . ' ' . trim($country));

    $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$googleApiKey}";

    $response = file_get_contents($url);
    $json = json_decode($response, true);

    if ($json['status'] === 'OK' && isset($json['results'][0])) {
        $result = $json['results'][0];
        $foundZipCode = '';
        $foundCity = '';
        $foundCountry = '';

        // Extract relevant address components
        foreach ($result['address_components'] as $component) {
            if (in_array('postal_code', $component['types'])) {
                $foundZipCode = $component['long_name'];
            }
            if (in_array('locality', $component['types'])) { // In most cases, city is found in 'locality'
                $foundCity = $component['long_name'];
            }
            if (in_array('administrative_area_level_1', $component['types']) && !$foundCity) { // Sometimes city may be in 'administrative_area_level_1'
                $foundCity = $component['long_name'];
            }
            if (in_array('country', $component['types'])) {
                $foundCountry = $component['long_name'];
            }
        }

        // Use strict matching for ZIP code, city, and country
        if (strcasecmp($foundZipCode, $zipcode) === 0 && strcasecmp($foundCity, $city) === 0 && strcasecmp($foundCountry, $country) === 0) {
            return true;
        } else {
            return false;
        }
    }

    logger()->error('Invalid location: ', [
        'zipcode' => $zipcode,
        'city' => $city,
        'country' => $country,
        'response' => $json
    ]);

    return false;
}


    private function handleRejection($validatedData, $reason, $zipcode)
    {
        // Fetch the drop reason from the DropReason table
        $rejectReason = DropReason::where('reason', $reason)->first();
        $rejectZipcode = preg_replace('/\D/', '', $validatedData['manual_zipcode'] ?? $validatedData['zipcode']);

        if (strlen($rejectZipcode) > 20) {
            return redirect()->back()->withErrors(['zipcode' => 'The ZIP code is too long.'])->withInput();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Reject::create([
            'reject_ip_address' => inet_pton($validatedData['request_ip_address'] ?? '127.0.0.1'),
            'reject_ip_city' => $validatedData['request_ip_city'] ?? 'Default City',
            'reject_ip_region' => $validatedData['request_ip_region'] ?? 'Default Region',
            'reject_ip_country' => $validatedData['request_ip_country'] ?? 'Default Country',
            'reject_ip_timezone' => $validatedData['request_ip_timezone'] ?? 'UTC',
            'reject_device' => $validatedData['request_device'] ?? 'Unknown Device',
            'reject_os' => $validatedData['request_os'] ?? 'Unknown OS',
            'reject_street_number' => $validatedData['request_street_number'] ?? 'N/A',
            'reject_route' => $validatedData['request_route'] ?? 'N/A',
            'reject_locality' => $validatedData['request_locality'] ?? 'N/A',
            'reject_administrative_area_level_1' => $validatedData['request_administrative_area_level_1'] ?? 'N',
            'reject_zipcode' => $rejectZipcode,
            'reject_longitude' => $validatedData['request_longitude'] ?? 0,
            'reject_latitude' => $validatedData['request_latitude'] ?? 0,
            'reject_class' => $validatedData['class_id'],
            'reject_service' => $validatedData['service_id'],
            'reject_datetime' => Carbon::now(),
            'reject_address_source' => $validatedData['request_address_source'] ?? 'Manual',
            'reject_cross_street' => $validatedData['request_cross_street'] ?? 'N/A',
            'reject_location_type_id' => $validatedData['request_location_type_id'],
            'reject_location_name' => $validatedData['request_location_name'] ?? 'N/A',
            'reject_reason' => $rejectReason->reason_id ?? 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
    public function web(){
return view('websocket');
    }
}
