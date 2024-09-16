<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function create($request_id)
    {
        return view('customer-form', compact('request_id'));
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

        // Get the request_id from the session, so users cannot tamper with it
        $requestId = session('request_id');

        if (!$requestId) {
            return redirect()->route('requests.create')->withErrors(['error' => 'Request session expired. Please try again.']);
        }

        Customer::create([
            'request_id' => $requestId,
            'given_name' => $validatedData['given_name'],
            'surname' => $validatedData['surname'],
            'email' => $validatedData['email'],
            'phone_number' => $validatedData['phone_number'],
            'other_phone_number' => $validatedData['other_phone_number'],
            'communication_preference' => implode(',', $validatedData['communication_preference']),
        ]);


        return redirect()->route('destination-vehicle.create', ['request_id' => $requestId]);
    }

}
