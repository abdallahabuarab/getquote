<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\CustomerAcceptedPricing;
use App\Events\CustomerRejectedPricing;
use App\Models\Request as RequestModel;

class CustomerPricingController extends Controller
{
    public function showPricing(Request $request)
    {
        $requestId = $request->query('request_id');
        $finalPrice = $request->query('final_price');
        $eta = $request->query('eta');

        return view('customer-pricing', compact('requestId', 'finalPrice', 'eta'));
    }

    public function acceptPricing(Request $request)
    {
        $requestId = $request->input('request_id');

        // Broadcast the acceptance event
        broadcast(new CustomerAcceptedPricing($requestId));

        return redirect()->route('customer.create', ['request_id' => $requestId]);
    }

    public function rejectPricing(Request $request)
    {
        $requestId = $request->input('request_id');
        $reason = $request->input('reject_reason');

        if (!$reason) {
            return redirect()->back()->withErrors(['reject_reason' => 'Please select a reason for rejection.']);
        }

        // Broadcast the rejection event to provider
        broadcast(new CustomerRejectedPricing($requestId, $reason));

        Log::info("Customer rejected pricing for request ID: $requestId, Reason: $reason");

        return redirect()->route('requests.create');
    }
}

