<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;

class TokenController extends Controller
{
    public function store(Request $request)
    {
        Log::info("TokenController::store() - request: ");

        $token = $request->user()->createToken(
            $request->has('device') ? $request->get('device') : 'access_token',
            $request->has('abilites') ? $request->get('abilites') : ['*'],
        );
        return response()->json(['token' => $token->plainTextToken]);
    }

    public function destroy(PersonalAccessToken $token)
    {
        request()->user()->tokens()->where('id', $token->id)->delete();

        return response()->json(['message' => 'Logout com sucesso'], 204);
    }
}
