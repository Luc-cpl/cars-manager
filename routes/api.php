<?php

use App\Http\Controllers\CarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/cars', CarController::class . '@index')
    ->middleware('auth')
    ->name('cars');

Route::post('/cars', CarController::class . '@store')
    ->middleware('auth')
    ->name('cars.store');

Route::get('/cars/{carId}', CarController::class . '@show')
    ->middleware('auth')
    ->name('cars.show');

Route::delete('/cars/{carId}', CarController::class . '@destroy')
    ->middleware('auth')
    ->name('cars.destroy');

Route::post('/cars/{carId}/restore', CarController::class . '@restore')
    ->middleware('auth')
    ->name('cars.restore');

Route::post('/cars/{carId}/force-delete', CarController::class . '@forceDelete')
    ->middleware('auth')
    ->name('cars.forceDelete');
