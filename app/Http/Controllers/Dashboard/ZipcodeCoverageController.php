<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Models\ZipcodeCoverage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ZipcodeCoverageController extends Controller
{
    public function index()
    {
        // Get the logged-in provider's ID
        $providerId = Auth::user()->provider->provider_id;

        // Fetch ZIP code coverage for this provider
        $zipcodes = ZipcodeCoverage::with('zipcodeReference')
        ->where('provider_id', $providerId)
        ->get();
        return view('portal.zipcode_coverage.index', compact('zipcodes'));
    }
}
