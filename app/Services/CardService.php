<?php

namespace App\Services;

use App\Models\Card;
use App\Repositories\CardRepository;
use App\traits\AuthorizationTrait;

class CardService
{
    use AuthorizationTrait;

    public function __construct(protected CardRepository $cardRepository)
    {
    }

    public function all()
    {
        $authorized = $this->authorizeUser(auth()->user());

        if ($authorized) {
            return $authorized;
        }

        if (auth()->user()->tokenCan('admin')) {
            $cards = $this->cardRepository->all();
        } else {
            $cards = auth()->user()->cards;
        }

        return response()->json([
            'data' => [
                'cards' => $cards,
            ],
        ]);
    }

    public function show(Card $card)
    {
        $authorized = $this->authorizeUser($card->user);

        if ($authorized) {
            return $authorized;
        }

        $card = $this->cardRepository->find($card->id);

        return response()->json([
            'data' => [
                'card' => $card,
            ],
        ]);
    }

    public function create($data)
    {
        $authorized = $this->authorizeUser($data);

        if ($authorized) {
            return $authorized;
        }

        $card = $this->cardRepository->create($data->all());

        return response()->json([
            'data' => [
                'card' => $card,
            ],
        ]);
    }

    public function update($data, Card $card)
    {
        $authorized = $this->authorizeUser($card->user);

        if ($authorized) {
            return $authorized;
        }

        if (count($data->all()) > 1 || ! array_key_exists('balance', $data->all())) {
            return response()->json(['message' => 'Invalid request. Only the balance field can be updated.'], 400);
        }

        $this->cardRepository->update($card, $data->all());

        $updatedCard = Card::find($card->id);

        return response()->json(['card' => $updatedCard]);

    }

    public function delete(Card $card)
    {

        $authorized = $this->authorizeUser($card->user);

        if ($authorized) {
            return $authorized;
        }

        $this->cardRepository->delete($card);

        return response()->json([], 204);
    }
}
