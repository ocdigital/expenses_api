<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login($request): JsonResponse
    {
        $credentials = $request;

        if (! Auth::attempt($credentials)) {
            abort(401, 'Invalid Credentials');
        }
        $user = Auth::user();
        $permissions = $user->is_admin ? ['admin'] : ['user'];
        $token = $user->createToken('auth_token', $permissions);

        return response()->json([
            'token' => $token->plainTextToken,
        ]);
    }

    public function logout(): JsonResponse
    {
        if (! Auth::user()) {
            abort(401, 'Unauthenticated');
        }
        Auth::user()->currentAccessToken()->delete();

        return response()->json([], 204);
    }
}
