<?php

namespace App\Repositories;

use App\Models\Card;

class CardRepository
{
    public function all(): mixed
    {
        return Card::all();
    }

    public function create(array $data): Card
    {
        return Card::create($data);
    }

    public function find($id): mixed
    {
        return Card::find($id);
    }

    public function update(Card $card, array $data): bool
    {
        return $card->update($data);
    }

    public function delete(Card $card): bool
    {
        return $card->delete();
    }
}
