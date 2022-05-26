<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth:sanctum'])->get('/users', [\App\Http\Controllers\UserController::class, 'index']);
Route::middleware(['auth:sanctum'])->post('/users', [\App\Http\Controllers\UserController::class, 'index']);
Route::middleware(['auth:sanctum'])->put('/users/{user}', [\App\Http\Controllers\UserController::class, 'update']);

Route::middleware(['auth:sanctum'])->get('/customers', [\App\Http\Controllers\CustomerController::class, 'index']);
Route::middleware(['auth:sanctum', 'verified'])->post('/customers', [\App\Http\Controllers\CustomerController::class, 'create']);
Route::middleware(['auth:sanctum'])->put('/customers/{customer}', [\App\Http\Controllers\CustomerController::class, 'update']);
Route::middleware(['auth:sanctum'])->get('/customers/{customer}', [\App\Http\Controllers\CustomerController::class, 'show']);

Route::middleware(['auth:sanctum'])->get('/customers-addresse/{customer}', [\App\Http\Controllers\CustomerAddresseController::class, 'index']);
Route::middleware(['auth:sanctum'])->post('/customers-addresse/{customer}', [\App\Http\Controllers\CustomerAddresseController::class, 'create']);
Route::middleware(['auth:sanctum'])->put('/customers-addresse/{customer}', [\App\Http\Controllers\CustomerAddresseController::class, 'update']);

Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index']);
Route::get('/product/{product:id}', [\App\Http\Controllers\ProductController::class, 'show']);
Route::middleware(['auth:sanctum', 'verified'])->post('/products', [\App\Http\Controllers\ProductController::class, 'create']);
Route::middleware(['auth:sanctum', 'verified'])->put('/products/{product:id}', [\App\Http\Controllers\ProductController::class, 'update']);
Route::middleware(['auth:sanctum', 'verified'])->delete('/products/{product:id}', [\App\Http\Controllers\ProductController::class, 'delete']);

Route::middleware(['auth:sanctum', 'verified'])->post('/products/{product}/images', [\App\Http\Controllers\ProductImageController::class, 'store']);
Route::middleware(['auth:sanctum', 'verified'])->delete('/products/{product}/images/{image:id}', [\App\Http\Controllers\ProductImageController::class, 'delete']);

Route::get('/tour_destinations', [\App\Http\Controllers\TourDestinationController::class, 'index']);
Route::get('/tour_destinations/{tour_destination:id}', [\App\Http\Controllers\TourDestinationController::class, 'show']);
Route::middleware(['auth:sanctum', 'verified'])->post('/tour_destinations', [\App\Http\Controllers\TourDestinationController::class, 'create']);
Route::middleware(['auth:sanctum', 'verified'])->put('/tour_destinations/{tour_destination:id}', [\App\Http\Controllers\TourDestinationController::class, 'update']);
Route::middleware(['auth:sanctum', 'verified'])->delete('/tour_destinations/{tour_destination:id}', [\App\Http\Controllers\TourDestinationController::class, 'delete']);
Route::middleware(['auth:sanctum', 'verified'])->post('/tour_destinations/{tour_destination}/images', [\App\Http\Controllers\TourDestinationImageController::class, 'store']);
Route::middleware(['auth:sanctum', 'verified'])->delete('/tour_destinations/{tour_destination}/images/{image:id}', [\App\Http\Controllers\TourDestinationImageController::class, 'delete']);


Route::get('/cities', [\App\Http\Controllers\CityController::class, 'index']);
Route::get('/state', [\App\Http\Controllers\StateController::class, 'index']);
Route::get('/country-region', [\App\Http\Controllers\CountryRegionController::class, 'index']);
Route::get('/country', [\App\Http\Controllers\CountryController::class, 'index']);
