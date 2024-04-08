<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index()
    {
        if (Auth::user()->tokenCan('view-all-cards')) {
            dd('view-all-cards');
            // $cards = Card::all();
            
        } else {
            dd('view-own-cards');
            // $cards = Auth::user()->cards;
        }

        return response()->json(['cards' => 'cards']);
    }
}
