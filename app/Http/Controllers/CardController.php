<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\User;
use App\traits\AuthorizationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    use AuthorizationTrait;

    public function index()
    {
        $authorized = $this->authorizeUser(Auth::user());

        if ($authorized) {
            return $authorized;
        }

        $cards = Auth::user()->tokenCan('admin') ? Card::all() : Auth::user()->cards;

        return response()->json(['cards' => $cards]);
    }

    public function show(Card $card)
    {
        if (! Auth::user()->tokenCan('admin') && Auth::user()->id !== $card->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['card' => $card]);
    }

    public function store(Request $request)
    {

        $user = Auth::user()->tokenCan('admin') ? User::find($request->user_id) : Auth::user();

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $card = $user->cards()->create($request->only('number', 'balance'));

        if (! $card->id) {
            return response()->json(['message' => 'Card Creation Failed'], 500);
        }

        return response()->json(['card' => $card]);
    }

    public function update(Request $request, Card $card)
    {
        if (! Auth::user()->tokenCan('admin') && Auth::user()->id !== $card->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (count($request->all()) > 1 || ! array_key_exists('balance', $request->all())) {
            return response()->json(['message' => 'Invalid request. Only the balance field can be updated.'], 400);
        }

        $card->update(['balance' => $request->balance]);

        return response()->json(['card' => $card]);
    }

    public function destroy(Card $card)
    {
        if (! Auth::user()->tokenCan('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $card->delete();

        return response()->json([], 204);
    }
}
