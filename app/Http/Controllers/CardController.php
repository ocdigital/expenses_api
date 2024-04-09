<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Services\CardService;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function __construct(protected CardService $cardService)
    {
    }

    public function index()
    {
        return $this->cardService->all();
    }

    public function show(Card $card)
    {
        return $this->cardService->show($card);
    }

    public function store(Request $request)
    {
        return $this->cardService->create($request);
    }

    public function update(Request $request, Card $card)
    {
        return $this->cardService->update($request, $card);
    }

    public function destroy(Card $card)
    {
        return $this->cardService->delete($card);
    }
}
