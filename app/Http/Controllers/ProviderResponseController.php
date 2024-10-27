<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Provider;
use App\Models\DropReason;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ProviderResponse;
use Illuminate\Support\Facades\Log;
use App\Models\Request as RequestModel;
use App\Events\ProviderResponse as ProviderResponseEvent;

class ProviderResponseController extends Controller
{
    public function showLoading($requestId)
    {
        return view('provider-loading', ['request_id' => $requestId]);
    }
    public function showSuccess($requestId)
    {
        return view('provider-success', ['request_id' => $requestId]);
    }
    public function showApology($requestId)
    {
        return view('provider-apology', ['request_id' => $requestId]);
    }
    public function handleProviderResponse($provider_id, $request_id, $token, Request $request)
    {
        // Check if the token and provider match
        $provider = Provider::where('provider_id', $provider_id)->first();
        $requestEntry = RequestModel::find($request_id);
        $customer = Customer::where('request_id', $requestEntry->request_id)->first();
        $expirationTime = session('expiration_time', Carbon::now()->timestamp);
        return view('provider-response', compact('provider', 'requestEntry','customer','expirationTime'));
    }
    public function submitResponse(Request $request, $provider_id, $request_id)
    {
        $response = $request->input('action'); // 'accept' or 'reject'
        $reasonId = null;
        if ($response === 'reject') {
            $reasonId = $request->input('drop_reason');

            // Validate that reason_id is provided for rejection
            if (!$reasonId) {
                return redirect()->back()->withErrors(['drop_reason' => 'Please select a drop reason.']);
            }

        }

        // Save provider response in the database
         ProviderResponse::create([
            'request_id' => $request_id,
            'provider_id' => $provider_id,
            'provider_respose' => $response,
            'reason_id' => $reasonId,
            'provider_response_time' => now(),
        ]);

        // Broadcast the response to the customer using Laravel Echo/WebSockets
         broadcast(new ProviderResponseEvent($response, $request_id, $reasonId));
         return redirect()->route('provider.loading', ['request_id' => $request_id]);


    }
}
