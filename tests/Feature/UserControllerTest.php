<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;


uses(RefreshDatabase::class);

describe('Unit Tests', function () {

    //Users tests
    it('create user', function () {
        $user = User::factory()->create();
        expect ($user->id)->toBe(1);

    });

    it('prevent duplicate user', function () {
        $user = User::factory()->create();
        $duplicateUser = User::factory()->make(['email' => $user->email]);
        expect($duplicateUser->save())->toBeFalse();
    });

    //Cards tests
    it('create card with positive balance', function () {
        $card = Card::factory()->create();
        expect ($card->id)->toBe(1);
    });

    it('create card with negative balance', function () {
        $card = Card::factory()->make(['balance' => -100]);
        expect($card->save())->toBeFalse();
    });

    //Transactions tests
    it('allows creation of expenses with sufficient balance', function () {
        $card = Card::factory()->create();
        $transaction = Transaction::factory()->make(['amount' => 100]);
        expect($transaction->save())->toBeTrue();
    });

    it('prevents creation of expenses with insufficient balance', function () {
        $card = Card::factory()->create();
        $transaction = Transaction::factory()->make(['amount' => 1000]);
        expect($transaction->save())->toBeFalse();
    });

    it('prevents creation of negative expenses', function (){
        $card = Card::factory()->create();
        $transaction = Transaction::factory()->make(['amount' => -100]);
        expect($transaction->save())->toBeFalse();
    });
    
    it('ensures expense transaction and reverts balance on failure', function () {
        $card = Card::factory()->create(['balance' => 200]);
    
        // Inicia uma transação
        DB::beginTransaction();
    
        try {
            // Cria uma despesa que deve falhar devido ao saldo insuficiente
            Expense::create(['card_id' => $card->id, 'amount' => 300]);
        } catch (\Exception $e) {
            // Se a exceção for lançada, reverta a transação
            DB::rollBack();
        }
    
        // Verifica se o saldo do cartão foi revertido para o valor inicial
        $this->assertEquals(200, $card->fresh()->balance);
    });
    

});


describe('Integration Tests', function () {


})->todo();