<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    private const USER_PERMISSIONS = ['view-card'];

    private const ADMIN_PERMISSIONS = ['view-all-cards', 'view-card'];

    public function register(Request $request, User $user)
    {
        $userData = $request->only('name', 'email', 'password', 'is_admin');
        $userData['password'] = Hash::make($userData['password']);

        if (! $user = $user->create($userData)) {
            abort(500, 'User Creation Failed');
        }

        return response()->json([
            'data' => [
                'user' => $user,
            ],
        ]);
    }

    public function login(Request $request)
    {
        $user = $this->authenticate($request);
        $permissions = $user->is_admin ? self::ADMIN_PERMISSIONS : self::USER_PERMISSIONS;
        $token = $user->createToken('auth_token', $permissions);

        return response()->json([
            'token' => $token->plainTextToken,
        ]);
    }

    private function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (! Auth::attempt($credentials)) {
            abort(401, 'Invalid Credentials');
        }

        return Auth::user();
    }

    public function me()
    {
        if (! auth()->user()) {
            abort(401, 'Unauthenticated');
        }

        return response()->json([
            'data' => auth()->user(),
        ]);
    }

    public function logout()
    {
        if (! auth()->user()) {
            abort(401, 'Unauthenticated');
        }
        auth()->user()->currentAccessToken()->delete();

        return response()->json([], 204);
    }
}
