<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\CustomerPricingController;
use App\Http\Controllers\Dashboard\ClassController;
use App\Http\Controllers\ProviderResponseController;
use App\Http\Controllers\Dashboard\RejectsController;
use App\Http\Controllers\Dashboard\ServiceController;
use App\Http\Controllers\Dashboard\ZipcodeController;
use App\Http\Controllers\DestinationVehicleController;
use App\Http\Controllers\Dashboard\UserPanelController;
use App\Http\Controllers\Dashboard\OrderPanelController;
use App\Http\Controllers\Dashboard\AvailabilityController;
use App\Http\Controllers\Dashboard\RequestPanelController;
use App\Http\Controllers\Dashboard\ProviderPanelController;
use App\Http\Controllers\Dashboard\ZipcodeCoverageController;


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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    return view('admin.index');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/', [RequestController::class, 'create'])->name('requests.create');
Route::post('/store', [RequestController::class, 'store'])->name('requests.store');
Route::get('/destination-vehicle-form/{request_id}', [DestinationVehicleController::class, 'create'])->name('destination-vehicle.create');
Route::post('/destination-vehicle-form', [DestinationVehicleController::class, 'store'])->name('destination-vehicle.store');
Route::get('/customer/create/{request_id}', [CustomerController::class, 'create'])->name('customer.create');
Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');

Route::get('/customer/loading/{request_id}', [CustomerController::class, 'showLoading'])->name('customer.loading');
Route::get('/customer/apology/{request_id}/{reason}', [CustomerController::class, 'showApology'])->name('customer.apology');


Route::get('/provider/response/{provider_id}/{request_id}', [ProviderResponseController::class, 'handleProviderResponse'])
    ->name('provider.response')
    ->middleware('signed');
    Route::post('/provider/response/{provider_id}/{request_id}', [ProviderResponseController::class, 'submitResponse'])
    ->name('provider.response.submit');
    Route::get('/web', [RequestController::class, 'web'])->name('requests.web');

    Route::get('/provider/loading/{request_id}', [ProviderResponseController::class, 'showLoading'])->name('provider.loading');
    Route::get('/provider/success/{request_id}', [ProviderResponseController::class, 'showSuccess'])->name('provider.success');
Route::get('/provider/apology/{request_id}', [ProviderResponseController::class, 'showApology'])->name('provider.apology');





Route::get('/payment/create', [PaymentController::class, 'create'])->name('payment.create');
Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');
Route::get('/payment-success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');

Route::get('/payment-authenticate', function (Request $request) {
    return view('payment-authenticate', ['payment_intent' => $request->query('payment_intent')]);
})->name('payment.auth');

Route::get('/customer/pricing', [CustomerPricingController::class, 'showPricing'])->name('customer.pricing');
Route::post('/customer/pricing/accept', [CustomerPricingController::class, 'acceptPricing'])->name('customer.pricing.accept');
Route::post('/customer/pricing/reject', [CustomerPricingController::class, 'rejectPricing'])->name('customer.pricing.reject');

Route::get('/customer/rejection/{request_id}', function (Request $request, $request_id) {
    $reason = $request->query('reason');
    return view('customer-rejection', compact('request_id', 'reason'));
})->name('customer.rejection');


// Route::post('/payment/cancel', [PaymentController::class, 'cancelPayment'])->name('payment.cancel');

//     Route::get('/payment/create/{request_id}', [PaymentController::class, 'create'])->name('payment.create');
//     Route::post('/payment/submit', [PaymentController::class, 'submit'])->name('payment.submit');




Route::controller(AdminController::class)->group(function (){
Route::get('/admin/logout','destroy')->name('admin.logout');
Route::get('/admin/profile', 'profile')->name('admin.profile');
Route::get('/edit/profile', 'editProfile')->name('edit.profile');
Route::post('/edit/profile', 'storeProfile')->name('store.profile');
Route::get('/change/password', 'changePassword')->name('change.password');
Route::post('/update/password', 'updatePassword')->name('update.password');

});

Route::middleware(['auth','role','provider'])->group(function () {

Route::get('/availability', [AvailabilityController::class, 'index'])->name('availabilities.index');
Route::patch('/availabilities-details/{provider_id}/{class_id}/{service_id}', [AvailabilityController::class, 'updateAvailability'])->name('availabilities.updateAvailability');


Route::get('/zipcode-coverage', [ZipcodeCoverageController::class, 'index'])->name('zipcode.coverage.index');

});
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('auth');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy')->middleware('auth');
Route::middleware(['auth','role','user'])->group(function () {
    Route::get('/zipcodes', [ZipcodeController::class, 'index'])
    ->name('zipcodes.index');
    Route::get('/services', [ServiceController::class, 'index'])
    ->name('services.index');
    Route::get('/classes', [ClassController::class, 'index'])
    ->name('class.index');

    Route::get('/providers-panel', [ProviderPanelController::class, 'index'])
    ->name('providerspa.index');
Route::get('/providers-panel/create', [ProviderPanelController::class, 'create'])
    ->name('providerspa.create');
Route::post('/providers-panel', [ProviderPanelController::class, 'store'])
    ->name('providerspa.store');

Route::get('/providers-panel/{provider}/edit', [ProviderPanelController::class, 'edit'])
    ->name('providerspa.edit');
Route::put('/providers-panel/{provider}', [ProviderPanelController::class, 'update'])
    ->name('providerspa.update');
Route::delete('/providers-panel/{provider}', [ProviderPanelController::class, 'destroy'])
    ->name('providerspa.destroy');

    Route::get('/providers-details/{provider}', [ProviderPanelController::class, 'show'])->name('providerspa.show');

    Route::patch('/{provider}/zip-codes', [ProviderPanelController::class, 'updateZipCodes'])->name('providerspa.updateZipCodes');
    Route::patch('/{provider}/availabilities', [ProviderPanelController::class, 'updateAvailability'])->name('providerspa.updateAvailability');
    Route::delete('/providers-panel/{provider}/zip-codes/{zipcode}', [ProviderPanelController::class, 'deleteZipCode'])->name('providerspa.deleteZipCode');
    Route::delete('/providers-panel/{provider}/availabilities', [ProviderPanelController::class, 'deleteAvailability'])
    ->name('providerspa.deleteAvailability');

    Route::patch('/availabilities/{provider_id}/{class_id}/{service_id}', [ProviderPanelController::class, 'editAvailability'])
    ->name('availabilities.editAvailability');




    Route::get('/users-panel', [UserPanelController::class, 'index'])
    ->name('userspa.index');
Route::get('/users-panel/create', [UserPanelController::class, 'create'])
    ->name('userspa.create');
Route::post('/users-panel', [UserPanelController::class, 'store'])
    ->name('userspa.store');

Route::get('/users-panel/{user}/edit', [UserPanelController::class, 'edit'])
    ->name('userspa.edit');
Route::put('/users-panel/{user}', [UserPanelController::class, 'update'])
    ->name('userspa.update');
Route::delete('/users-panel/{user}', [UserPanelController::class, 'destroy'])
    ->name('userspa.destroy');

    Route::get('/customers-requests', [RequestPanelController::class, 'index'])->name('requests.index');

    Route::get('/rejects', [RejectsController::class, 'index'])->name('rejects.index');


    Route::get('/customers-orders', [OrderPanelController::class, 'index'])->name('orders.index');
    Route::get('/orders-details/{order}', [OrderPanelController::class, 'show'])->name('orders.show');
    Route::delete('/customers-orders/{order}', [OrderPanelController::class, 'destroy'])
    ->name('orders.destroy');

});






// Route::get('/zipcodes/create', [ZipcodeController::class, 'create'])
// ->name('zipcodes.create');

// Route::post('/zipcodes', [ZipcodeController::class, 'store'])
// ->name('zipcodes.store');

// Route::get('/zipcodes/{zipcode}/edit', [ZipcodeController::class, 'edit'])
// ->name('zipcodes.edit');

// Route::put('/zipcodes/{zipcode}', [ZipcodeController::class, 'update'])
// ->name('zipcodes.update');

// Route::delete('/zipcodes/{zipcode}', [ZipcodeController::class, 'destroy'])
// ->name('zipcodes.destroy');

require __DIR__.'/auth.php';
