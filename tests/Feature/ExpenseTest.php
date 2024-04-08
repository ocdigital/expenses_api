<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\Expense;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Unit Tests', function () {

    // tenta criar uma despesa
    it('create expense', function () {
        $card = Card::factory()->create();
        $expense = Expense::factory()->create(['card_id' => $card->id]);
        expect($expense->id)->toBe(1);
    });

    // tenta criar uma despesa sem valor especificado
    it('throws exception for creating expense without amount', function () {
        $this->expectException(QueryException::class);
        $card = Card::factory()->create();
        Expense::factory()->create(['card_id' => $card->id, 'amount' => null]);
        $this->assertDatabaseMissing('expenses', ['amount' => null]);
    });

    // tentar atualizar uma despesa
    it('update expense', function () {
        $card = Card::factory()->create();
        $expense = Expense::factory()->create(['card_id' => $card->id]);
        $expense->amount = 100;
        $expense->save();
        expect($expense->amount)->toBe(100);
    });

    // tentar deletar uma despesa
    it('delete expense', function () {
        $card = Card::factory()->create();
        $expense = Expense::factory()->create(['card_id' => $card->id]);
        $expense->delete();
        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    });
});

describe('Integration Tests', function () {

})->todo();
