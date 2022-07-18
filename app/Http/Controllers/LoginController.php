<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    public function __invoke()
    {
        request()->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required'],
        ]);

        /**
         * We are authenticating a request from our frontend.
         */
        if (EnsureFrontendRequestsAreStateful::fromFrontend(request())) {
            $this->authenticateFrontend();

            $token = request()->user()->createToken(
                request()->has('device') ?  request()->get('device') : 'access_token',
                request()->has('abilites') ?  request()->get('abilites') : ['*'],
            );

            return response()->json([
                'message' => 'You are now logged in.',
                'token' => $token->plainTextToken
            ]);
        }
        /**
         * We are authenticating a request from a 3rd party.
         */
        else {
            if (!Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
                throw ValidationException::withMessages([
                    'email' => ['E-mail ou senha incorretos.'],
                ]);
            }

            $user = request()->user();
            $token = request()->user()->createToken(
                request()->has('device') ?  request()->get('device') : 'access_token',
                request()->has('abilites') ?  request()->get('abilites') : ['*'],
            );

            return response()->json([
                'user' =>    $user,
                'token' => $token->plainTextToken
            ]);
        }
    }

    private function authenticateFrontend()
    {

        if (!Auth::guard('web')
            ->attempt(
                request()->only('email', 'password'),
                request()->boolean('remember')
            )) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate($email, $password)
    {
        $this->ensureIsNotRateLimited();

        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }
    // 
}
