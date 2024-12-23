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
        // Fetch the logged-in provider's ID
        $providerId = Auth::user()->provider->provider_id;

        // Get availabilities for the logged-in provider
        $availabilities = Availability::with(['classModel', 'service'])
            ->where('provider_id', $providerId)
            ->get();

        // Pass data to the view
        return view('portal.availabilities.index', compact('availabilities'));
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
