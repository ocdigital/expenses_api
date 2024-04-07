<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;

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
