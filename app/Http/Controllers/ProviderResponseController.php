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
        $existingResponse = ProviderResponse::where('provider_id', $provider_id)
        ->where('request_id', $request_id)
        ->first();

    if ($existingResponse) {
        // Optionally, you can add a flash message or handle this case in another way
        return redirect()->back()->withErrors(['message' => 'Response already submitted.']);
    }
        $response = $request->input('action'); // 'accept' or 'reject'
        $reasonId = null;
        $eta = null;

        if ($response === 'reject') {
            $reasonId = $request->input('drop_reason');

            if (!$reasonId) {
                return redirect()->back()->withErrors(['drop_reason' => 'Please select a drop reason.']);
            }

            // Save rejection response in the database
            ProviderResponse::create([
                'request_id' => $request_id,
                'provider_id' => $provider_id,
                'provider_respose' => 'reject',
                'reason_id' => $reasonId,
                'eta' => null,
                'provider_response_time' => now(),
            ]);

            // Broadcast rejection event to customer
            broadcast(new ProviderResponseEvent('reject', $request_id, $reasonId, null));

            Log::info('Provider rejected request', [
                'request_id' => $request_id,
                'reason_id' => $reasonId
            ]);

            return redirect()->route('provider.loading', ['request_id' => $request_id]);
        }

        if ($response === 'accept') {
            $eta = $request->input('eta');

            if (!$eta) {
                return redirect()->back()->withErrors(['eta' => 'Please provide an ETA.']);
            }
        // Save provider response and ETA in the database


            $finalPrice = app(PaymentController::class)->calculateFinalPrice($request_id, $provider_id);

            ProviderResponse::create([
                'request_id' => $request_id,
                'provider_id' => $provider_id,
                'provider_respose' => 'accept',
                'reason_id' => null,
                'eta' => $eta,
                'provider_response_time' => now(),
            ]);

            // Broadcast acceptance event to customer
            broadcast(new ProviderResponseEvent('accept', $request_id, null, $eta, $finalPrice));
            Log::info('Provider accepted request', [
                'request_id' => $request_id,
                'eta' => $eta,
                'final_price' => $finalPrice
            ]);
            return redirect()->route('provider.loading', ['request_id' => $request_id]);
        }

    }
}
