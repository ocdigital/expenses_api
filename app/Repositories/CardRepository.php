<?php

namespace App\Repositories;

use App\Models\Card;

class CardRepository
{
    public function all()
    {
        return Card::all();
    }

    public function create(array $data)
    {   
        return Card::create($data);
    }

    public function find($id)
    {
        return Card::find($id);
    }

    public function update(Card $card, array $data)
    {
        return $card->update($data);
    }

    public function delete(Card $card)
    {
        return $card->delete();
    }
}