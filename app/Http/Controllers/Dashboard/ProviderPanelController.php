<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Service;
use App\Models\Weekday;
use App\Models\ZipCode;
use App\Models\Provider;
use App\Models\ClassName;
use Illuminate\Http\Request;
use App\Models\DispatchMethod;
use App\Models\ZipcodeReference;
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
        'class_ids' => 'required|array',
        'class_ids.*' => 'exists:classes,class_id',
        'service_ids' => 'required|array',
        'service_ids.*' => 'exists:services,service_id',
        'availability' => 'required|in:yes,no',
        'service_price' => 'nullable|numeric|min:0',
        'provider_zipcode'=>'exists:zipcode_reference,zipcode',
        'zip_codes' => 'required|array',
        'zip_codes.*' => 'exists:zipcode_reference,zipcode',
        'rank' => 'required|integer|in:1,2,3',
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

    // Save provider availabilities (multiple classes and services)
    foreach ($request->class_ids as $classId) {
        foreach ($request->service_ids as $serviceId) {
            $provider->availabilities()->create([
                'class_id' => $classId,
                'service_id' => $serviceId,
                'availability' => $request->availability,
                'service_price' => $request->service_price,
            ]);
        }
    }

    // Save zip codes with rank
    $rank = $request->rank;
    $zipCodes = $request->zip_codes;

    foreach ($zipCodes as $zipcode) {
        $provider->zipcodes()->attach($zipcode, ['rank' => $rank]);
    }
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

    $provider->load('zipCodes', 'user', 'availabilities.service', 'availabilities.classModel','schedules');

    return view('portal.providers.show', compact('provider'));
}

public function edit(Provider $provider)
{
    $provider->load('zipCodes', 'availabilities.service', 'availabilities.classModel', 'schedules.weekday');
    $zipCodes = ZipcodeReference::all();
    $classNames = ClassName::all();
    $services = Service::all();
    $dispatchMethods = DispatchMethod::all();
    $paymentDistributions = PaymentDistribution::all();
    $weekdays = Weekday::all();

    return view('portal.providers.edit', compact(
        'provider',
        'zipCodes',
        'classNames',
        'services',
        'dispatchMethods',
        'paymentDistributions',
        'weekdays'
    ));
}

public function update(Request $request, Provider $provider)
{
    $request->validate([
        'provider_name' => 'required|string|max:255',
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
        'class_ids' => 'required|array',
        'service_ids' => 'required|array',
        'zip_codes' => 'required|array',
        'rank' => 'required|integer|in:1,2,3',
        'schedule' => 'required|array',
        'schedule.*.open_day' => 'required|in:yes,no',
        'schedule.*.start_time' => 'nullable|date_format:H:i',
        'schedule.*.end_time' => 'nullable|date_format:H:i',
    ]);

    // Update user data
    $provider->user->update([
        'name' => $request->provider_name,
        'email' => $request->provider_email,
        'password' => $request->password ? Hash::make($request->password) : $provider->user->password,
    ]);

    // Update provider data
    $provider->update([
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

    $provider->availabilities()->delete();
    foreach ($request->class_ids as $classId) {
        foreach ($request->service_ids as $serviceId) {
            $provider->availabilities()->create([
                'class_id' => $classId,
                'service_id' => $serviceId,
                'availability' => 'yes',
                'service_price' => $request->service_price ?? null,
            ]);
        }
    }

    // Update zip codes
    $provider->zipCodes()->detach();
    foreach ($request->zip_codes as $zipcode) {
        $provider->zipCodes()->attach($zipcode, ['rank' => $request->rank]);
    }


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
