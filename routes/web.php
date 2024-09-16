<?php

use Illuminate\Support\Facades\Route;
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
Route::get('/', [RequestController::class, 'create'])->name('requests.create');
Route::post('/store', [RequestController::class, 'store'])->name('requests.store');
Route::get('/customer/create/{request_id}', [CustomerController::class, 'create'])->name('customer.create');
Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');
Route::get('/destination-vehicle-form/{request_id}', [DestinationVehicleController::class, 'create'])->name('destination-vehicle.create');
Route::post('/destination-vehicle-form', [DestinationVehicleController::class, 'store'])->name('destination-vehicle.store');

Route::get('/provider/response/{provider_id}/{request_id}/{token}', [ProviderResponseController::class, 'handleProviderResponse'])
    ->name('provider.response')
    ->middleware('signed');
    Route::get('/web', [RequestController::class, 'web'])->name('requests.web');


// Route::get('/', function () {
//     return view('welcome');
// });
