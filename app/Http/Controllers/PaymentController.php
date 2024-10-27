<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\CustomerFormFilled;
use Illuminate\Support\Facades\Log;
use App\Models\Request as RequestModel;

class PaymentController extends Controller
{
    public function create($request_id)
    {
        // Find the request using the request_id

        return view('payment-form', ['request_id' => $request_id]);
    }
    public function submit(Request $request)
    {
        $requestId = $request->input('request_id');
        Log::info('Broadcasting successful payment event for request ID: ' . $requestId);

        // Broadcast the success event to notify the provider.
        broadcast(new CustomerFormFilled(true, $requestId));

        // Redirect the customer to a confirmation page or thank you page.
        return redirect()->back()->with('message', 'Payment submitted successfully.');
}
public function cancelPayment(Request $request)
    {
        $requestId = $request->input('request_id');

        // Broadcast the cancel event to notify the provider
        broadcast(new CustomerFormFilled(false, $requestId, true));  // success = false, canceled = true

        // Redirect the customer to a cancel confirmation page or wherever you want
        return redirect()->back()->with('message', 'You have successfully canceled the request.');
    }
}
