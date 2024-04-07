<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;
use App\Models\Card;

uses(RefreshDatabase::class);

describe('Unit Tests', function () {
   
    //Cards tests
    it('create card', function () {
        $card = Card::factory()->create();
        expect ($card->id)->toBe(1);
    });

    // tenta criar um cartão sem saldo especificado
    it('throws exception for creating card without balance', function () {
        $this->expectException(QueryException::class);
        Card::factory()->create(['balance' => null]);
        $this->assertDatabaseMissing('cards', ['balance' => null]);
    });

    // tenta criar um cartão sem numero especificado
    it('throws exception for creating card without number', function () {
        $this->expectException(QueryException::class);
        Card::factory()->create(['number' => null]);
        $this->assertDatabaseMissing('cards', ['number' => null]);
    });

    //tentar criar um cartão com saldo negativo
    it('prevent negative balance card', function () {
        $card = Card::factory()->make(['balance' => -100]);
        expect($card->save())->toBeFalse();
    });   

    //tentar atualizar um cartão
    it('update card', function () {
        $card = Card::factory()->create();
        $card->balance = 100;
        $card->save();
        expect($card->balance)->toBe(100);
    });

    //tentar deletar um cartão
    it('delete card', function () {
        $card = Card::factory()->create();
        $card->delete();
        $this->assertDatabaseMissing('cards', ['id' => $card->id]);
    });
});


describe('Integration Tests', function () {


})->todo();