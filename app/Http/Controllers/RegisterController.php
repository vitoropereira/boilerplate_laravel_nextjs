<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function __invoke()
    {
        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed'],
        ]);

        Log::info("TokenController::store() - request: " . request()->token_name);

        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
        ]);


        Auth::guard('web')->login($user);

        $token =  request()->user()->createToken(
            request()->has('device') ?  request()->get('device') : 'access_token',
            request()->has('abilites') ?  request()->get('abilites') : ['*'],
        );

        Log::info("RegisterController::__invoke() - request: " .    $user);

        return response()->json([
            'message' => 'You are now logged in.',
            'token' => $token->plainTextToken
        ]);
    }
}
