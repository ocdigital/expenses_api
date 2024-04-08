<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {   
        if (! auth()->user())
            abort(401, 'Unauthenticated');
        
        if (! auth()->user()->tokenCan('view-all-cards'))
            return response()->json(['message' => 'Unauthorized'], 403);
    
        $users = User::all();
        
        return response()->json([
            'data' => [
                'users' => $users
            ],
        ]);
    }

    private function authorizeUser(User $user)
    {
        $authUser = auth()->user();

        if (!$authUser) {
            abort(401, 'Unauthenticated');
        }

        if (!$authUser->tokenCan('view-all-cards') && $authUser->id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }

    public function show(User $user)
    {
        $authorized = $this->authorizeUser($user);

        if ($authorized) 
            return $authorized;
        
        
        return response()->json([
            'data' => [
                'user' => $user
            ],
        ]);
    }

    public function update(Request $request, User $user)
    {   
        $authorized = $this->authorizeUser($user);
        
        if ($authorized) 
            return $authorized;    

        $user = auth()->user();
        $userData = $request->only('name', 'email', 'password');
        
        if($request->has('password'))
            $userData['password'] = Hash::make($userData['password']);

        if (! $user->update($userData)) {
            abort(500, 'User Update Failed');
        }

        return response()->json([
            'data' => [
                'user' => $user,
            ],
        ]);
    }

    public function destroy(User $user)
    {   
        $authorized = $this->authorizeUser($user);
        
        if ($authorized) 
            return $authorized;       
        
        if (! $user->delete()) {
            abort(500, 'User Deletion Failed');
        }

        return response()->json([], 204);
    }
}
