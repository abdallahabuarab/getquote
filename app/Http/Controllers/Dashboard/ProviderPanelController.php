<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Service;
use App\Models\Weekday;
use App\Models\ZipCode;
use App\Models\Provider;
use App\Models\ClassName;
use App\Models\Availability;
use Illuminate\Http\Request;
use App\Models\DispatchMethod;
use App\Models\ZipcodeReference;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentDistribution;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ProviderPanelController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search', '');
        $providers = Provider::with('user')
            ->search($searchTerm)
            ->orderByDesc('provider_id')
            ->paginate(20);

        return view('portal.providers.index', compact('providers'));
    }

    public function create()
    {
        $zipCodes = ZipcodeReference::all();
        $classNames = ClassName::all();
        $services = Service::all();
        $dispatchMethods = DispatchMethod::all();
        $paymentDistributions = PaymentDistribution::all();
        $weekdays = Weekday::all();
        return view('portal.providers.create', compact('zipCodes', 'classNames', 'services','dispatchMethods','paymentDistributions','weekdays'));
    }

    public function store(Request $request)
{
    // Validate the request
    $request->validate([
        'provider_name' => 'required|string|max:255',
        'provider_email' => 'required|string|email|max:255|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'provider_address' => 'required|string|max:255',
        'provider_city' => 'required|string|max:255',
        'provider_state' => 'required|string|max:2',
        'provider_zipcode' => 'required|numeric',
        'provider_phone' => 'required|string|max:20',
        'contact_phone' => 'nullable|string|max:20',
        'status' => 'required|in:yes,no',
        'dispatch_method' => 'required|exists:dispatch_methods,dispath_method_id',
        'payment_distribution' => 'required|exists:payment_distributions,payment_distribution_id',
        // 'class_ids' => 'required|array',
        // 'class_ids.*' => 'exists:classes,class_id',
        // 'service_ids' => 'required|array',
        // 'service_ids.*' => 'exists:services,service_id',
        // 'availability' => 'required|in:yes,no',
        // 'service_price' => 'nullable|numeric|min:0',
        'provider_zipcode'=>'exists:zipcode_reference,zipcode',
        // 'zip_codes' => 'required|array',
        // 'zip_codes.*' => 'exists:zipcode_reference,zipcode',
        // 'rank' => 'required|integer|in:1,2,3',
        'schedule' => 'required|array',
        'schedule.*.open_day' => 'required|in:yes,no',
        'schedule.*.start_time' => 'nullable|date_format:H:i',
        'schedule.*.end_time' => 'nullable|date_format:H:i',
    ]);

    // Create the user
    $user = User::create([
        'name' => $request->provider_name,
        'email' => $request->provider_email,
        'password' => Hash::make($request->password),
    ]);

    // Create the provider
    $provider = Provider::create([
        'user_id' => $user->id,
        'provider_name' => $request->provider_name,
        'contact_name' => $request->contact_name,
        'provider_address' => $request->provider_address,
        'provider_city' => $request->provider_city,
        'provider_state' => $request->provider_state,
        'zipcode' => $request->provider_zipcode,
        'provider_phone' => $request->provider_phone,
        'contact_phone' => $request->contact_phone,
        'provider_email' => $request->provider_email,
        'is_active' => $request->status,
        'dispatch_method' => $request->dispatch_method,
        'payment_distribution' => $request->payment_distribution,
    ]);


    foreach ($request->schedule as $dayOfWeek => $schedule) {
        $startTime = $schedule['start_time'] ? (intval(explode(':', $schedule['start_time'])[0]) * 60 + intval(explode(':', $schedule['start_time'])[1])) : null;
        $endTime = $schedule['end_time'] ? (intval(explode(':', $schedule['end_time'])[0]) * 60 + intval(explode(':', $schedule['end_time'])[1])) : null;

        $provider->schedules()->create([
            'dayofweek' => $dayOfWeek,
            'open_day' => $schedule['open_day'],
            'start_time' => $startTime,
            'close_time' => $endTime,
        ]);
    }

    return redirect()->route('providerspa.index')->with('success', 'Provider created successfully.');
}





    public function show(Provider $provider)
{

   $provider->load('zipCodes', 'availabilities.service', 'availabilities.classModel');

   $classNames = ClassName::all(); // List of all classes
   $services = Service::all(); // List of all services

   return view('portal.providers.show', compact('provider',  'classNames', 'services'));
}
public function updateZipCodes(Request $request, Provider $provider)
{
    $request->validate([
        'zip_codes' => 'required|array',
        'zip_codes.*.zipcode' => [
            'required',
            'exists:zipcode_reference,zipcode',
            function ($attribute, $value, $fail) use ($request, $provider) {
                $rank = $request->zip_codes[array_search($value, array_column($request->zip_codes, 'zipcode'))]['rank']?? 1;
                $existing = DB::table('zipcode_coverage')
                    ->where('zipcode', $value)
                    ->where('rank', $rank)
                    ->where('provider_id', '!=', $provider->provider_id)
                    ->exists();
                if ($existing) {
                    $fail("The ZIP code $value is already assigned with rank $rank to another provider.");
                }
            },
        ],
        'zip_codes.*.rank' => 'required|integer|in:1,2,3',
    ]);

    foreach ($request->zip_codes as $zipCode) {

        $provider->zipCodes()->syncWithoutDetaching([
            $zipCode['zipcode'] => ['rank' => $zipCode['rank']],
        ]);
    }

    return back()->with('success', 'ZIP codes added successfully.');
}


public function deleteZipCode(Provider $provider, $zipcode)
{
    $provider->zipCodes()->detach($zipcode);

    return back()->with('success', 'ZIP code deleted successfully.');
}



public function updateAvailability(Request $request, Provider $provider)
{
    $request->validate([
        'availabilities' => 'required|array',
        'availabilities.*.class_id' => 'required|exists:classes,class_id',
        'availabilities.*.service_id' => 'required|exists:services,service_id',
        'availabilities.*.service_price' => 'nullable|numeric|min:0',
        'availabilities.*.free_enroute_miles' => 'nullable|integer|min:0',
        'availabilities.*.free_loaded_miles' => 'nullable|integer|min:0',
        'availabilities.*.enroute_mile_price' => 'nullable|numeric|min:0',
        'availabilities.*.loaded_mile_price' => 'nullable|numeric|min:0',
        'availabilities.*.availability' => 'required|in:yes,no',
    ]);

    $existingCombinations = $provider->availabilities->map(function ($availability) {
        return "{$availability->class_id}-{$availability->service_id}";
    });

    foreach ($request->availabilities as $availability) {
        $newCombination = "{$availability['class_id']}-{$availability['service_id']}";

        if ($existingCombinations->contains($newCombination)) {
            return back()->withErrors("The combination of Class ID {$availability['class_id']} and Service ID {$availability['service_id']} already exists.");
        }

        $provider->availabilities()->updateOrCreate(
            [
                'class_id' => $availability['class_id'],
                'service_id' => $availability['service_id'],
            ],
            [
                'availability' => $availability['availability'],
                'service_price' => $availability['service_price'] ?? null,
                'free_enroute_miles' => $availability['free_enroute_miles'] ?? null,
                'free_loaded_miles' => $availability['free_loaded_miles'] ?? null,
                'enroute_mile_price' => $availability['enroute_mile_price'] ?? null,
                'loaded_mile_price' => $availability['loaded_mile_price'] ?? null,
            ]
        );
    }

    return back()->with('success', 'Availabilities updated successfully.');
}

public function editAvailability(Request $request, $provider_id, $class_id, $service_id)
{
    $request->validate([
        'availability' => 'required|in:yes,no',
        'service_price' => 'nullable|numeric|min:0',
        'enroute_mile_price' => 'nullable|numeric|min:0',
        'free_enroute_miles' => 'nullable|integer|min:0',
        'loaded_mile_price' => 'nullable|numeric|min:0',
        'free_loaded_miles' => 'nullable|integer|min:0',
    ]);

    Availability::where('provider_id', $provider_id)
        ->where('class_id', $class_id)
        ->where('service_id', $service_id)
        ->update([
            'availability' => $request->availability,
            'service_price' => $request->service_price,
            'enroute_mile_price' => $request->enroute_mile_price,
            'free_enroute_miles' => $request->free_enroute_miles,
            'loaded_mile_price' => $request->loaded_mile_price,
            'free_loaded_miles' => $request->free_loaded_miles,
        ]);

    return redirect()->back()->with('success', 'Availability updated successfully!');
}


// public function updateAvailability(Request $request, Provider $provider)
// {
//     $request->validate([
//         'availabilities' => 'required|array',
//         'availabilities.*.class_id' => 'required|exists:classes,class_id',
//         'availabilities.*.service_id' => 'required|exists:services,service_id',
//         'availabilities.*.service_price' => 'nullable|numeric|min:0',
//     ]);

//     $existingCombinations = $provider->availabilities->map(function ($availability) {
//         return "{$availability->class_id}-{$availability->service_id}";
//     });

//     foreach ($request->availabilities as $availability) {
//         $newCombination = "{$availability['class_id']}-{$availability['service_id']}";

//         if ($existingCombinations->contains($newCombination)) {
//             return back()->withErrors("The combination of Class ID {$availability['class_id']} and Service ID {$availability['service_id']} already exists for this provider.");
//         }

//         $provider->availabilities()->create([
//             'class_id' => $availability['class_id'],
//             'service_id' => $availability['service_id'],
//             'availability' => 'yes',
//             'service_price' => $availability['service_price'] ?? null,
//         ]);

//     }

//     return back()->with('success', 'Availabilities updated successfully.');
// }
public function deleteAvailability(Request $request, Provider $provider)
{
    $provider->availabilities()
        ->where('class_id', $request->class_id)
        ->where('service_id', $request->service_id)
        ->delete();

    return back()->with('success', 'Availability deleted successfully.');
}




public function edit(Provider $provider)
{
    $provider->load('zipCodes', 'availabilities.service', 'availabilities.classModel', 'schedules.weekday');
    // $zipCodes = ZipcodeReference::all();
    // $classNames = ClassName::all();
    // $services = Service::all();
    $dispatchMethods = DispatchMethod::all();
    $paymentDistributions = PaymentDistribution::all();
    $weekdays = Weekday::all();

    return view('portal.providers.edit', compact(
        'provider',
        'dispatchMethods',
        'paymentDistributions',
        'weekdays'
    ));
}

public function update(Request $request, Provider $provider)
{
    $request->validate([
        'provider_email' => 'required|string|email|max:255|unique:users,email,' . $provider->user_id,
        'password' => 'nullable|string|min:8|confirmed',
        'provider_address' => 'required|string|max:255',
        'provider_city' => 'required|string|max:255',
        'provider_state' => 'required|string|max:2',
        'provider_zipcode' => 'required|numeric',
        'provider_phone' => 'required|string|max:20',
        'contact_phone' => 'nullable|string|max:20',
        'status' => 'required|in:yes,no',
        'dispatch_method' => 'required|exists:dispatch_methods,dispath_method_id',
        'payment_distribution' => 'required|exists:payment_distributions,payment_distribution_id',
        'schedule' => 'required|array',
        'schedule.*.open_day' => 'required|in:yes,no',
        'schedule.*.start_time' => 'nullable|date_format:H:i',
        'schedule.*.end_time' => 'nullable|date_format:H:i',
    ]);

    // Ensure provider name remains unchanged
    $request->merge(['provider_name' => $provider->provider_name]);

    // Update user data (excluding provider_name)
    $provider->user->update([
        'email' => $request->provider_email,
        'password' => $request->password ? Hash::make($request->password) : $provider->user->password,
    ]);

    // Update provider data (excluding provider_name)
    $provider->update([
        'contact_name' => $request->contact_name,
        'provider_address' => $request->provider_address,
        'provider_city' => $request->provider_city,
        'provider_state' => $request->provider_state,
        'zipcode' => $request->provider_zipcode,
        'provider_phone' => $request->provider_phone,
        'contact_phone' => $request->contact_phone,
        'provider_email' => $request->provider_email,
        'is_active' => $request->status,
        'dispatch_method' => $request->dispatch_method,
        'payment_distribution' => $request->payment_distribution,
    ]);

    $provider->schedules()->delete(); // Clear existing schedules
    foreach ($request->schedule as $dayOfWeek => $schedule) {
        $startTime = $schedule['start_time']
            ? (intval(explode(':', $schedule['start_time'])[0]) * 60 + intval(explode(':', $schedule['start_time'])[1]))
            : null;

        $endTime = $schedule['end_time']
            ? (intval(explode(':', $schedule['end_time'])[0]) * 60 + intval(explode(':', $schedule['end_time'])[1]))
            : null;

        $provider->schedules()->create([
            'dayofweek' => $dayOfWeek,
            'open_day' => $schedule['open_day'],
            'start_time' => $startTime,
            'close_time' => $endTime,
        ]);
    }

    return redirect()->route('providerspa.index')->with('success', 'Provider updated successfully.');
}



    private function providerData(Request $request)
    {
        return $request->only([
            'contact_phone',
            'payment_method',
            'address',
            'city',
            'state',
            'class_name_id',
        'service_id',
        ]);
    }
}
