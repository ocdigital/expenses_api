<?php

namespace App\Services;

use App\Models\Card;
use App\Repositories\CardRepository;
use App\traits\AuthorizationTrait;
use Illuminate\Http\JsonResponse;

class CardService
{
    use AuthorizationTrait;

    public function __construct(protected CardRepository $cardRepository)
    {
    }

    public function all(): JsonResponse
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
        ], 200);
    }

    public function show(Card $card): JsonResponse
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
        ], 200);
    }

    public function create($data): JsonResponse
    {
        $authorized = $this->authorizeUser($data);

        if ($authorized) {
            return $authorized;
        }

        $card = $this->cardRepository->create($data);

        return response()->json([
            'data' => [
                'card' => $card,
            ],
        ], 201);
    }

    public function update($data, Card $card): JsonResponse
    {
        $authorized = $this->authorizeUser($card->user);

        if ($authorized) {
            return $authorized;
        }

        if (count($data) > 1 || ! array_key_exists('balance', $data)) {
            return response()->json(['message' => 'Invalid request. Only the balance field can be updated.'], 400);
        }

        $this->cardRepository->update($card, $data);

        $updatedCard = Card::find($card->id);

        return response()->json([
            'data' => [
                'card' => $card,
            ],
        ], 200);

    }

    public function delete(Card $card): JsonResponse
    {

        $authorized = $this->authorizeUser($card->user);

        if ($authorized) {
            return $authorized;
        }

        $this->cardRepository->delete($card);

        return response()->json([], 204);
    }
}
