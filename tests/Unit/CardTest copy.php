<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Unit Tests', function () {

    //Cards tests
    it('create card', function () {
        $user = User::factory()->create();
        $card = Card::factory()->create(['user_id' => $user->id]);
        $this->assertNotNull($card->id);
    })->skip();

    // tenta criar um cartão sem saldo especificado
    it('throws exception for creating card without balance', function () {
        $this->expectException(QueryException::class);
        Card::factory()->create(['balance' => null]);
        $this->assertDatabaseMissing('cards', ['balance' => null]);
    })->skip();

    // tenta criar um cartão sem numero especificado
    it('throws exception for creating card without number', function () {
        $this->expectException(QueryException::class);
        Card::factory()->create(['number' => null]);
        $this->assertDatabaseMissing('cards', ['number' => null]);
    })->skip();

    //tentar criar um cartão com saldo negativo
    it('prevent negative balance card', function () {
        $card = Card::factory()->make(['balance' => -100]);
        expect($card->save())->toBeFalse();
    })->skip();

    //tentar atualizar um cartão
    it('update card', function () {
        $user = User::factory()->create();
        $card = Card::factory()->create(['user_id' => $user->id]);        
        $card->balance = 100;
        $card->save();
        expect($card->balance)->toBe(100);
    })->skip();

    //tentar deletar um cartão
    it('delete card', function () {
        $user = User::factory()->create();
        $card = Card::factory()->create(['user_id' => $user->id]);
        $card->delete();
        $this->assertDatabaseMissing('cards', ['id' => $card->id]);
    })->skip();
})->todo();

describe('Integration Tests', function () {

})->todo();
