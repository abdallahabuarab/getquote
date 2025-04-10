<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DropReason;
use Illuminate\Http\Request;
use App\Events\CustomerFormFilled;
use App\Models\Request as RequestModel;

class CustomerController extends Controller
{
    public function create($requestId)
    {
        return view('customer-form', compact('requestId'));
    }
    public function showLoading($requestId)
    {
        return view('customer-loading', ['request_id' => $requestId]);
    }
    public function showApology($requestId)
    {
        $dropReason = \App\Models\ProviderResponse::where('request_id', $requestId)->value('reason');

        return view('customer-apology', [
            'request_id' => $requestId,
            'dropReason' => $dropReason
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'given_name' => 'required|string|max:30',
            'surname' => 'required|string|max:30',
            'email' => 'required|email|max:50',
            'phone_number' => 'required|string|max:20',
            'other_phone_number' => 'nullable|string|max:20',
            'communication_preference' => 'required|array',
            'communication_preference.*' => 'in:text,call,email,app',
        ]);

        // Get the request_id,provider_id from the session, so users cannot tamper with it
        $requestId = session('request_id');
        $providerId = session('provider_id');
        if (!$requestId) {
            return redirect()->route('requests.create')->withErrors(['error' => 'Request session expired. Please try again.']);
        }

        $customer = Customer::create([
            'request_id' => $requestId,
            'given_name' => $validatedData['given_name'],
            'surname' => $validatedData['surname'],
            'email' => $validatedData['email'],
            'phone_number' => $validatedData['phone_number'],
            'other_phone_number' => $validatedData['other_phone_number'],
            'communication_preference' => implode(',', $validatedData['communication_preference']),
        ]);
        // $provider = RequestModel::where('request_id', $requestId)->first();
        // if (!$provider) {
        //     return redirect()->back()->withErrors(['error' => 'Provider not found for this request.']);
        // }

        // $providerId = $provider->provider_id;

        return redirect()->route('payment.create', ['request_id' => $requestId, 'provider_id' => $providerId]);


    }



}
