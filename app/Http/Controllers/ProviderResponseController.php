<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Request as RequestModel;

class ProviderResponseController extends Controller
{
    public function handleProviderResponse($provider_id, $request_id, $token, Request $request)
    {
        // Check if the token and provider match
        $provider = Provider::where('provider_id', $provider_id)->first();
        $requestEntry = RequestModel::find($request_id);
        $customer = Customer::where('request_id', $requestEntry->request_id)->first();
        if (!$provider || !$requestEntry || !$customer) {
            return redirect()->route('expired')->withErrors(['error' => 'Link has expired or is invalid.']);
        }
        $expirationTime = session('expiration_time', Carbon::now()->timestamp);
        return view('provider-response', compact('provider', 'requestEntry','customer','expirationTime'));
    }
}
