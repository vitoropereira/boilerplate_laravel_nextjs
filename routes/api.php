<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    LoginController,
    LogoutController,
    RegisterController,
    TokenController,
};

// Auth ...
Route::post('/login', LoginController::class);
Route::post('/register', RegisterController::class);
Route::post('/logout/{user:id}', [LogoutController::class, 'destroy']);

Route::apiResource('/tokens', TokenController::class)->only(['store', 'destroy']);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    // $token = $request->user()->createToken('user-token');

    return response()->json([
        'user' => $request->user(),
        'token' => '$token->plainTextToken',
    ]);
});


Route::middleware(['auth:sanctum'])->get('/users', [\App\Http\Controllers\UserController::class, 'index']);
Route::middleware(['auth:sanctum'])->post('/users', [\App\Http\Controllers\UserController::class, 'index']);
Route::middleware(['auth:sanctum'])->put('/user/{user:id}', [\App\Http\Controllers\UserController::class, 'update']);
