<?php

namespace App\traits;

use App\Models\Card;

trait AuthorizationTrait
{
    protected function authorizeUser($user)
    {
        $authUser = auth()->user();

        if (! $authUser->tokenCan('admin') && $authUser->id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }

    protected function authorizeAdmin()
    {
        if (! auth()->user()->tokenCan('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }

    protected function authorizeCardOwner($card)
    {           
        if (! auth()->user()->tokenCan('admin') && auth()->user()->id !== $card->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }
}
