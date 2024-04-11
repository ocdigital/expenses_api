<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('cant create a new expense', function () {
    $user = User::factory()->create();

    $token = $user->createToken('test')->plainTextToken;
    $card = Card::factory()->create([
        'user_id' => $user->id,
    ]);

    $expenseData = [
        'number' => $card->number,
        'amount' => 50,
        'description' => 'Compra de um livro',
        'card_id' => $card->id,
    ];

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->postJson('/api/expenses', $expenseData);

    $response->assertStatus(201);

    expect($response->json('data.expense.amount'))->toBe(50);
    expect($response->json('data.expense.description'))->toBe('Compra de um livro');
    expect($response->json('data.expense.card_id'))->toBe($card->id);

});

it('cant create a new expense with invalid data', function () {
    $user = User::factory()->create();

    $token = $user->createToken('test')->plainTextToken;
    $card = Card::factory()->create([
        'user_id' => $user->id,
        'balance' => 0,
    ]);

    $expenseData = [
        'number' => $card->number,
        'amount' => 50,
        'description' => 'Compra de um livro',
    ];

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->postJson('/api/expenses', $expenseData);

    expect($response->json('message'))->toBe('Card not found or insufficient balance');
});

it('cant create a new expense with negative amount', function () {
    $user = User::factory()->create();

    $token = $user->createToken('test')->plainTextToken;
    $card = Card::factory()->create([
        'user_id' => $user->id,
    ]);

    $expenseData = [
        'number' => $card->number,
        'amount' => -50,
        'description' => 'Compra de um livro',
    ];

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->postJson('/api/expenses', $expenseData);

    expect($response->json('message'))->toBe('Valor deve ser maior ou igual a 0');
});

it('can list all expenses', function () {
    $user = User::factory()->create();

    $token = $user->createToken('test')->plainTextToken;
    $card = Card::factory()->create([
        'user_id' => $user->id,
    ]);

    $expense = Expense::factory()->create([
        'card_id' => $card->id,
    ]);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->getJson('/api/expenses');

    $response->assertStatus(200);

    expect($response->json('data.expenses'))->toHaveCount(1);

});

it('can show a expense', function () {
    $user = User::factory()->create();

    $token = $user->createToken('test')->plainTextToken;
    $card = Card::factory()->create([
        'user_id' => $user->id,
    ]);

    $expense = Expense::factory()->create([
        'card_id' => $card->id,
    ]);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->getJson('/api/expenses/'.$expense->id);

    $response->assertStatus(200);

    expect($response->json('data.expense.description'))->toBe($expense->description);
    expect($response->json('data.expense.amount'))->toBe($expense->amount);
    expect($response->json('data.expense.card_id'))->toBe($expense->card_id);

});

it('can update a expense', function () {
    $user = User::factory()->create();

    $token = $user->createToken('test')->plainTextToken;
    $card = Card::factory()->create([
        'user_id' => $user->id,
    ]);

    $expense = Expense::factory()->create([
        'card_id' => $card->id,
    ]);

    $expenseData = [
        'description' => 'Compra de um livro atualizada',
    ];

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->putJson('/api/expenses/'.$expense->id, $expenseData);

    $response->assertStatus(200);

    $response->assertJson([
        'expense' => [
            'description' => 'Compra de um livro atualizada',
        ],
    ]);
});

it('can delete a expense', function () {
    $user = User::factory()->create();

    $token = $user->createToken('test')->plainTextToken;
    $card = Card::factory()->create([
        'user_id' => $user->id,
    ]);

    $expense = Expense::factory()->create([
        'card_id' => $card->id,
    ]);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->deleteJson('/api/expenses/'.$expense->id);

    $response->assertStatus(204);

    expect(Expense::all())->toHaveCount(0);
});
