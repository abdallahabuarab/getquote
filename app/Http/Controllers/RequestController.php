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
            'request_ip_country' => 'nullable|string|max:50',
            'request_ip_timezone' => 'nullable|string|max:50',
            'request_street_number' => 'nullable|string|max:20',
            'request_route' => 'nullable|string|max:50',
            'request_priority' => 'nullable|in:low,normal,high',
            'request_location_type_id' => 'required|exists:location_types,location_type_id',
        ]);

        if ($request->has('manual_zipcode') && !empty($validatedData['manual_zipcode'])) {
            $zipcode = $validatedData['manual_zipcode'];
        } elseif ($request->has('zipcode') && !empty($validatedData['zipcode'])) {
            $zipcode = $validatedData['zipcode'];
        } else {
            return redirect()->back()->withErrors(['zipcode' => 'Zip Code is required.'])->withInput();
        }

        $zipReference = ZipcodeReference::where('zipcode', $zipcode)->first();
        if (!$zipReference) {
            $dropReason = DropReason::where('reason', 'Zip Code is not defined')->first();

            // Insert the rejection into the `Reject` table
            $this->handleRejection($validatedData, $dropReason, $zipcode);

            return redirect()->back()->withErrors([
                'zipcode' => $dropReason ? $dropReason->reason : 'Zip Code not covered.'
            ])->withInput();
        }



        // Check if there is an active provider covering the ZIP code
        $activeProvider = Provider::where('zipcode', $zipcode)
            ->where('is_active', 'yes')
            ->first();

        if (!$activeProvider) {
            // Fetch drop reason for "No active provider covering ZIP code"
            $dropReason = DropReason::where('reason', 'No active provider covering zip code')->first();

            // Insert the rejection into the `Reject` table
            $this->handleRejection($validatedData, $dropReason, $zipcode);

            return redirect()->back()->withErrors([
                'zipcode' => $dropReason ? $dropReason->reason : 'No active provider covering this ZIP code.'
            ])->withInput();
        }


        $requestEntry = RequestModel::create([
            'request_ip_address' => inet_pton($request->ip()),
            'request_ip_city' => $validatedData['request_ip_city'] ?? 'Default City',
            'request_ip_region' => $validatedData['request_ip_region'] ?? 'Default Region',
            'request_ip_country' => $validatedData['request_ip_country'] ?? 'Default Country',
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

        $service = Service::find($validatedData['service_id'])->name; // or whatever key represents 'tow'

        $request->session()->put('service', $service);

        $request->session()->put('request_id', $requestEntry->request_id);

        return redirect()->route('customer.create', ['request_id' => $requestEntry->request_id]);
    }


    private function handleRejection($validatedData, $reason, $zipcode)
    {
        // Fetch the drop reason from the DropReason table based on the provided reason
        $rejectReason = DropReason::where('reason', $reason)->first();

        // Ensure the ZIP code is cleaned properly
        $rejectZipcode = preg_replace('/\D/', '', $validatedData['manual_zipcode'] ?? $validatedData['zipcode']);

        // Validate ZIP code length before saving
        if (strlen($rejectZipcode) > 20) {
            return redirect()->back()->withErrors([
                'zipcode' => 'The ZIP code is too long.'
            ])->withInput();
        }

        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Insert the rejection into the Reject table
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
            'reject_reason' => $rejectReason->reason_id ?? 1, // Assign the reason_id from DropReason, default to 1 if not found
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

  public function web(){
    return view('websocket');
  }

}


