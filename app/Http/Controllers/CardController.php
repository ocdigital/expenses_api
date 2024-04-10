<?php

namespace App\Http\Controllers;

use App\Http\Requests\CardRequest;
use App\Models\Card;
use App\Services\CardService;

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

    public function store(CardRequest $request)
    {
        $validated = $request->validated();

        return $this->cardService->create($validated);
    }

    public function update(CardRequest $request, Card $card)
    {
        $validated = $request->validated();

        return $this->cardService->update($validated, $card);
    }

    public function destroy(Card $card)
    {
        return $this->cardService->delete($card);
    }
}
