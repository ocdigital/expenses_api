<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function create(Request $request)
    {
        $card = new Card();
        $card->number = $request->number;
        $card->balance = $request->balance;
        $card->save();

        return response()->json($card);
    }
}
