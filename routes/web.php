<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProviderResponseController;
use App\Http\Controllers\DestinationVehicleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::get('/check/{request_id}', [RequestController::class, 'check'])->name('check');
Route::get('/', [RequestController::class, 'create'])->name('requests.create');
Route::post('/store', [RequestController::class, 'store'])->name('requests.store');
Route::get('/destination-vehicle-form/{request_id}', [DestinationVehicleController::class, 'create'])->name('destination-vehicle.create');
Route::post('/destination-vehicle-form', [DestinationVehicleController::class, 'store'])->name('destination-vehicle.store');
Route::get('/customer/create/{request_id}', [CustomerController::class, 'create'])->name('customer.create');
Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');

Route::get('/customer/loading/{request_id}', [CustomerController::class, 'showLoading'])->name('customer.loading');
Route::get('/customer/apology/{request_id}/{reason}', [CustomerController::class, 'showApology'])->name('customer.apology');


Route::get('/provider/response/{provider_id}/{request_id}/{token}', [ProviderResponseController::class, 'handleProviderResponse'])
    ->name('provider.response')
    ->middleware('signed');
    Route::post('/provider/response/{provider_id}/{request_id}', [ProviderResponseController::class, 'submitResponse'])
    ->name('provider.response.submit');
    Route::get('/web', [RequestController::class, 'web'])->name('requests.web');

    Route::get('/provider/loading/{request_id}', [ProviderResponseController::class, 'showLoading'])->name('provider.loading');
    Route::get('/provider/success/{request_id}', [ProviderResponseController::class, 'showSuccess'])->name('provider.success');
Route::get('/provider/apology/{request_id}', [ProviderResponseController::class, 'showApology'])->name('provider.apology');


Route::post('/payment/cancel', [PaymentController::class, 'cancelPayment'])->name('payment.cancel');

    Route::get('/payment/create/{request_id}', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/payment/submit', [PaymentController::class, 'submit'])->name('payment.submit');

    // Route::get('/provider/loading/{request_id}', [ProviderResponseController::class, 'showLoading'])->name('provider.loading');
    // Route::get('/provider/thankyou/{request_id}', [ProviderResponseController::class, 'showThankYou'])->name('provider.thankyou');
    // Route::get('/provider/customer-info/{request_id}', [ProviderResponseController::class, 'showCustomerInfo'])->name('provider.customer-info');

// Route::get('/', function () {
//     return view('welcome');
// });
