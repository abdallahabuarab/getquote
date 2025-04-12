<?php

namespace App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Availability;

use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index()
{
    $provider = \App\Models\User::with('provider.zipcodeReference')->find(Auth::id())->provider;

    $availabilities = Availability::with([
        'classModel',
        'service',
        'provider.schedules'
    ])->where('provider_id', $provider->provider_id)->get();

    $timezone = $provider->zipcodeReference->timezone ?? 'UTC';

    return view('portal.availabilities.index', compact('availabilities', 'timezone'));

}

    public function updateAvailability(Request $request, $provider_id, $class_id, $service_id)
    {
        $request->validate([
            'availability' => 'required|in:yes,no',
        ]);

        // Find the availability record using composite keys
        Availability::where('provider_id', $provider_id)
            ->where('class_id', $class_id)
            ->where('service_id', $service_id)
            ->update(['availability' => $request->availability]);
        return redirect()->back()->with('success', 'Availability updated successfully!');
    }
}
