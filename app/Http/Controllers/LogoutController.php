<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class LogoutController extends Controller
{

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {

        if (EnsureFrontendRequestsAreStateful::fromFrontend(request())) {
            Auth::guard('web')->logout();

            Log::info("LogoutController::destroy():IF " . $user->id);
            request()->session()->invalidate();
            $user->tokens()->delete();

            request()->session()->regenerateToken();
        } else {
            Log::info("LogoutController::destroy():ELSE " .  $user->id);
            $user->tokens()->delete();

            return response()->json(['message' => 'Logout com sucesso'], 204);
        }


        return response()->noContent();
    }
}
