<?php

use App\Http\Controllers\Car\CarController;
use App\Http\Controllers\Car\CarAssociationController;
use App\Http\Controllers\User\UserController;
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

Route::get('/me', [UserController::class, 'show'])
    ->middleware('auth')
    ->name('me');

Route::put('/me', [UserController::class, 'update'])
    ->middleware('auth')
    ->name('me');

Route::get('/users', [UserController::class, 'index'])
    ->middleware('auth')
    ->name('users');

Route::get('/users/{userId}', [UserController::class, 'show'])
    ->middleware('auth')
    ->name('users.show');

/** @todo add user roles */
Route::put('/users/{userId}', [UserController::class, 'update'])
    ->middleware('auth')
    ->name('users.update');

/** @todo add user roles */
Route::delete('/users/{userId}', [UserController::class, 'destroy'])
    ->middleware('auth')
    ->name('users.destroy');

/** @todo add user roles */
Route::post('/users/{userId}/restore', [UserController::class, 'restore'])
    ->middleware('auth')
    ->name('users.restore');

Route::get('/cars', [CarController::class, 'index'])
    ->middleware('auth')
    ->name('cars');

Route::post('/cars', [CarController::class, 'store'])
    ->middleware('auth')
    ->name('cars.store');

Route::get('/cars/{carId}', [CarController::class, 'show'])
    ->middleware('auth')
    ->name('cars.update');

Route::put('/cars/{carId}', [CarController::class, 'update'])
    ->middleware('auth')
    ->name('cars.update');

Route::delete('/cars/{carId}', [CarController::class, 'destroy'])
    ->middleware('auth')
    ->name('cars.destroy');

Route::post('/cars/{carId}/restore', [CarController::class, 'restore'])
    ->middleware('auth')
    ->name('cars.restore');

Route::delete('/cars/{carId}/force-delete', [CarController::class, 'forceDelete'])
    ->middleware('auth')
    ->name('cars.forceDelete');

Route::get('/cars/{carId}/associate', [CarAssociationController::class, 'index'])
    ->middleware('auth')
    ->name('cars.associate');

Route::post('/cars/{carId}/associate', [CarAssociationController::class, 'store'])
    ->middleware('auth')
    ->name('cars.associate');

Route::delete('/cars/{carId}/associate', [CarAssociationController::class, 'destroy'])
    ->middleware('auth')
    ->name('cars.associate');