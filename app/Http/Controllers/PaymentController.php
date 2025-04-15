<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Provider;
use Stripe\PaymentIntent;
use App\Models\Destination;
use App\Models\InvoiceItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Events\CustomerFormFilled;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Request as RequestModel;

class PaymentController extends Controller
{

    public function create(Request $request)
    {
        $requestId = $request->query('request_id');
        $providerId = $request->query('provider_id');

        if (!$requestId || !$providerId) {
            return redirect()->back()->withErrors(['error' => 'Invalid payment request.']);
        }

        // Calculate the final price
        $finalPrice = $this->calculateFinalPrice($requestId, $providerId);

        return view('payment-form', compact('requestId', 'providerId', 'finalPrice'));
    }

    /**
     * Calculate the final price.
     */
//     private function calculateFinalPrice($requestId, $providerId)
// {
//     $requestEntry = RequestModel::find($requestId);
//     if (!$requestEntry) {
//         return redirect()->back()->withErrors(['error' => 'Request data not found.']);
//     }

//     // Retrieve provider details (city, state, and ZIP)
//     $provider = DB::table('providers')
//         ->where('provider_id', $providerId)
//         ->select('provider_city', 'provider_state', 'zipcode')
//         ->first();

//     if (!$provider) {
//         return redirect()->back()->withErrors(['error' => 'Provider not found.']);
//     }

//     // Fetch provider latitude and longitude from Google API
//     $providerLocation = $this->getLatLngFromAddress($provider->zipcode, $provider->provider_city, $provider->provider_state);
//     if (!$providerLocation['lat'] || !$providerLocation['lng']) {
//         return redirect()->back()->withErrors(['error' => 'Unable to determine provider location.']);
//     }

//     // Retrieve provider availability
//     $availability = DB::table('availabilities')
//         ->where('provider_id', $providerId)
//         ->where('class_id', $requestEntry->request_class)
//         ->where('service_id', $requestEntry->request_service)
//         ->first();

//     if (!$availability) {
//         return redirect()->back()->withErrors(['error' => 'Availability data not found.']);
//     }

//     // Base price
//     $finalPrice = $availability->service_price;

//     // Calculate enroute miles (distance from provider to customer)
//     $enrouteMiles = $this->calculateDistance(
//         $providerLocation['lat'],
//         $providerLocation['lng'],
//         $requestEntry->request_latitude,
//         $requestEntry->request_longitude
//     );

//     // Add enroute mile charge
//     $enrouteMilesChargeable = max(0, $enrouteMiles - $availability->free_enroute_miles);
//     $finalPrice += $enrouteMilesChargeable * $availability->enroute_mile_price;

//     // If service is towing, calculate loaded miles
//     $service = Service::find($requestEntry->request_service);
//     if (strtolower($service->name) === 'tow') {
//         $destination = Destination::where('request_id', $requestId)->first();
//         if (!$destination) {
//             return redirect()->back()->withErrors(['error' => 'Destination not found.']);
//         }

//         // Calculate loaded miles (distance from customer to destination)
//         $loadedMiles = $this->calculateDistance(
//             $requestEntry->request_latitude,
//             $requestEntry->request_longitude,
//             $destination->destination_latitude,
//             $destination->destination_longitude
//         );

//         // Add loaded mile charge
//         $loadedMilesChargeable = max(0, $loadedMiles - $availability->free_loaded_miles);
//         $finalPrice += $loadedMilesChargeable * $availability->loaded_mile_price;
//     }

//     return round($finalPrice, 2);
// }

public function calculateFinalPrice($requestId, $providerId)
{
    //  Step 1: Retrieve the Request Data
    $requestEntry = RequestModel::find($requestId);
    if (!$requestEntry) {
        return redirect()->back()->withErrors(['error' => 'Request data not found.']);
    }

    //  Step 2: Get the Provider Location (ZIP, City, State)
    $provider = DB::table('providers')
        ->where('provider_id', $providerId)
        ->select('provider_city', 'provider_state', 'zipcode')
        ->first();

    if (!$provider) {
        return redirect()->back()->withErrors(['error' => 'Provider not found.']);
    }

    //  Step 3: Fetch Provider Latitude & Longitude from Google API
    $providerLocation = $this->getLatLngFromAddress($provider->zipcode, $provider->provider_city, $provider->provider_state);
    if (!$providerLocation['lat'] || !$providerLocation['lng']) {
        return redirect()->back()->withErrors(['error' => 'Unable to determine provider location.']);
    }

    //  Step 4: Retrieve Provider Availability (Pricing Information)
    $availability = DB::table('availabilities')
        ->where('provider_id', $providerId)
        ->where('class_id', $requestEntry->request_class)
        ->where('service_id', $requestEntry->request_service)
        ->first();

    if (!$availability) {
        return redirect()->back()->withErrors(['error' => 'Availability data not found.']);
    }

    //  Step 5: Start Calculation with Base Price
    $finalPrice = $availability->service_price; // Service Price

    // ----------------------------------------------
    //  Step 6: Calculate Enroute Miles Charge
    // ----------------------------------------------
    $enrouteMiles = $this->calculateDistance(
        $providerLocation['lat'],
        $providerLocation['lng'],
        $requestEntry->request_latitude,
        $requestEntry->request_longitude
    );

    // Chargeable enroute miles: (Enroute Miles - Free Enroute Miles)
    $enrouteMilesChargeable = max(0, $enrouteMiles - $availability->free_enroute_miles);

    // Enroute Miles Cost = Chargeable Enroute Miles * Enroute Mile Price
    $enrouteCost = $enrouteMilesChargeable * $availability->enroute_mile_price;

    //  Add Enroute Cost to Final Price
    $finalPrice += round($enrouteCost, 2);

    //  Log the Enroute Calculation
    Log::info("Enroute miles: $enrouteMiles | Chargeable: $enrouteMilesChargeable | Enroute Cost: $enrouteCost");

    //  Step 7: Calculate Loaded Miles Charge (Only for Tow Services)
    $service = Service::find($requestEntry->request_service);
    if (strtolower($service->name) === 'tow') { // If service is towing
        $destination = Destination::where('request_id', $requestId)->first();
        if (!$destination) {
            return redirect()->back()->withErrors(['error' => 'Destination not found.']);
        }

        // Calculate Loaded Miles (Request to Destination)
        $loadedMiles = $this->calculateDistance(
            $requestEntry->request_latitude,
            $requestEntry->request_longitude,
            $destination->destination_latitude,
            $destination->destination_longitude
        );

        // Chargeable loaded miles: (Loaded Miles - Free Loaded Miles)
        $loadedMilesChargeable = max(0, $loadedMiles - $availability->free_loaded_miles);

        // Loaded Miles Cost = Chargeable Loaded Miles * Loaded Mile Price
        $loadedCost = $loadedMilesChargeable * $availability->loaded_mile_price;

        //  Add Loaded Cost to Final Price
        $finalPrice += round($loadedCost, 2);

        //  Log the Loaded Calculation
        Log::info("Loaded miles: $loadedMiles | Chargeable: $loadedMilesChargeable | Loaded Cost: $loadedCost");
    }

    // ----------------------------------------------
    //  Step 8: Return the Final Price
    // ----------------------------------------------
    return round($finalPrice, 2);
}

public function getLatLngFromAddress($zipcode, $city, $state)
{
    $googleApiKey = 'AIzaSyDsk5RExl2Xr7w2BayGTYdsePr2v6WBjmo';
    $address = urlencode("{$zipcode} {$city} {$state}");

    $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$googleApiKey}";

    $response = file_get_contents($url);
    $json = json_decode($response, true);

    if ($json['status'] === 'OK' && isset($json['results'][0])) {
        $location = $json['results'][0]['geometry']['location'];
        return ['lat' => $location['lat'], 'lng' => $location['lng']];
    }

    return ['lat' => null, 'lng' => null];
}

    /**
     * Calculate distance using Haversine formula.
     */
    public function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $latDiff = deg2rad($lat2 - $lat1);
        $lonDiff = deg2rad($lon2 - $lon1);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDiff / 2) * sin($lonDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return round($distance * 0.621371, 2); // Convert km to miles
    }

    /**
     * Process the payment.
     */



    public function processPayment(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:requests,request_id',
            'final_price' => 'required|numeric',
            'payment_method_id' => 'required|string',
            'billing_address' => 'nullable|string|max:30',
        ]);

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $returnUrl = route('payment.success', ['request_id' => $request->request_id]);

            $paymentIntent = PaymentIntent::create([
                'amount' => $request->final_price * 100,
                'currency' => 'usd',
                'payment_method' => $request->payment_method_id,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => $returnUrl,
            ]);

            $payment = Payment::create([
                'request_id' => $request->request_id,
                'request_total' => $request->final_price,
                'payment_method' => 'card',
                'brand' => $paymentIntent->charges->data[0]->payment_method_details->card->brand ?? 'Unknown',
                'payment_status' => 'completed',
                'payment_account_last4' => $paymentIntent->charges->data[0]->payment_method_details->card->last4 ?? '0000',
                'stripe_payment_method_id' => $request->payment_method_id,
                'method_exp_month' => $paymentIntent->charges->data[0]->payment_method_details->card->exp_month ?? null,
                'method_exp_year' => $paymentIntent->charges->data[0]->payment_method_details->card->exp_year ?? null,
                'payment_confirmation' => $paymentIntent->id,
                'billing_address' => $request->billing_address,
            ]);

            // Ensure we have a valid charge ID
            $chargeId = isset($paymentIntent->charges->data[0]) ? $paymentIntent->charges->data[0]->id : null;

            //  Verify payment exists before creating a transaction
            if (!Payment::where('payment_id', $payment->payment_id)->exists()) {
                Log::error("Payment not found for Transaction: " . $payment->payment_id);
                return redirect()->back()->withErrors(['error' => 'Payment record missing!']);
            }

            Log::info('Saving Transaction for Payment ID: ' . $payment->payment_id);

            //  Create Transaction Entry
            Transaction::create([
                'payment_id' => $payment->payment_id,
                'payment_amount' => $request->final_price,
                'payment_method' => 'card',
                'payment_status' => 'completed',
                'stripe_charge_id' => $chargeId,
                'currency' => 'USD',
                'transaction_confirmation' => $paymentIntent->id,
            ]);


            Log::info('Transaction Saved Successfully for Payment ID: ' . $payment->payment_id);
            broadcast(new CustomerFormFilled(true, $request->request_id));


            return redirect()->route('payment.success', ['request_id' => $request->request_id]);

        } catch (\Exception $e) {
            Log::error("Stripe Payment Failed", ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Payment failed: ' . $e->getMessage()]);
        }
    }



    /**
     * Show the payment success page.
     */
    public function paymentSuccess(Request $request)
    {
        $requestId = $request->request_id;
        if (!$requestId) {
            return redirect()->back()->withErrors(['error' => 'No request ID found. Please submit the request first.']);
        }

        $requestEntry = RequestModel::find($requestId);

        if (!$requestEntry) {
            return redirect()->back()->withErrors(['error' => 'Request not found.']);
        }

        $providerId = session('provider_id');
        $provider = Provider::find($providerId);

        if (!$provider) {
            return redirect()->back()->withErrors(['error' => 'provider not found.']);
        }

        $service = session('service');
        $class = session('class');
        $zipcode_p = session('zipcode');
        $city_p = session('city');
        $country_p = session('country');
        $zipcode_des = session('destination_zipcode');
        $city_des = session('destination_locality');
        $state_des = session('destination_state');
        $vehicle_year=session('vehicle_year');
        $vehicle_make=session('vehicle_make');
        $vehicle_model=session('vehicle_model');
        $VIN=session('VIN');
        $Plate=session('Plate');



        Mail::send('emails.payment-confirmation', [
            'provider' => $provider,
            'service'=>$service,
            'class'=>$class,
            'zipcode'=>$zipcode_p,
            'city'=>$city_p,
            'country'=>$country_p,
            'destination_zipcode'=>$zipcode_des,
            'destination_locality'=>$city_des,
            'destination_state'=>$state_des,
            'vehicle_year'=>$vehicle_year,
            'vehicle_make'=>$vehicle_make,
            'vehicle_model'=>$vehicle_model,
            'VIN'=>$VIN,
            'Plate'=>$Plate,
        ], function ($message) use ($provider, $requestId) {
            $message->to($provider->provider_email)
                    ->subject('Payment Confirmed / Request #' . $requestId);
        });
        $payment = Payment::where('request_id', $requestId)->firstOrFail();

        $transaction = Transaction::where('payment_id', $payment->payment_id)->firstOrFail();

        return view('payment-success', compact('payment', 'transaction'));
    }
}


//     public function create($request_id,$provider_id)
//     {
//         $requestEntry = RequestModel::find($request_id);
//         $chosenProviderId = $requestEntry->provider_id;
//         $availability = Availability::where('class_id', $requestEntry->class_id)
//         ->where('service_id',$requestEntry->service_id)
//         ->where('provider_id', $chosenProviderId)
//         ->first();
//         return view('payment-form', ['request_id' => $request_id]);
//     }
//     public function submit(Request $request)
//     {
//         $requestId = $request->input('request_id');
//         Log::info('Broadcasting successful payment event for request ID: ' . $requestId);

//         // Broadcast the success event to notify the provider.
//         broadcast(new CustomerFormFilled(true, $requestId));

//         // Redirect the customer to a confirmation page or thank you page.
//         return redirect()->back()->with('message', 'Payment submitted successfully.');
// }
// public function cancelPayment(Request $request)
//     {
//         $requestId = $request->input('request_id');

//         // Broadcast the cancel event to notify the provider
//         broadcast(new CustomerFormFilled(false, $requestId, true));  // success = false, canceled = true

//         // Redirect the customer to a cancel confirmation page or wherever you want
//         return redirect()->back()->with('message', 'You have successfully canceled the request.');
//     }

