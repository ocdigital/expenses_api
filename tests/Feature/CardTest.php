<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('cant create a new card', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;
    $cardData = [
        'number' => '1234567890123456',
        'balance' => 1000,
        'user_id' => $user->id,
    ];

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->postJson('/api/cards', $cardData);

    $response->assertStatus(201);

    expect($response->json('data.card.number'))->toBe('1234567890123456');
    expect($response->json('data.card.balance'))->toBe(1000);
    expect($response->json('data.card.user_id'))->toBe($user->id);

});

it('cant create a new card with invalid data', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;
    $cardData = [
        'number' => '1234567890123456',
    ];

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->postJson('/api/cards', $cardData);

    $response->assertStatus(422);

    expect($response->json('errors.balance'))->toBe(['Saldo é obrigatório']);
    expect($response->json('errors.user_id'))->toBe(['Usuário é obrigatório']);

});

it('cant create a new card with negative balance', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;
    $cardData = [
        'number' => '1234567890123456',
        'balance' => -1000,
        'user_id' => $user->id,
    ];

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->postJson('/api/cards', $cardData);

    expect($response->json('message'))->toBe('Saldo deve ser maior ou igual a 0');
});

it('can list all cards', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $card = Card::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->getJson('/api/cards');

    $response->assertStatus(200);

    expect($response->json('data.cards'))->toHaveCount(1);
});

it('can show a card', function () {
    $user = User::factory()->create();

    $token = $user->createToken('test-token')->plainTextToken;

    $card = Card::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->getJson('/api/cards/'.$card->id);

    $response->assertStatus(200);

    expect($response->json('data.card.number'))->toBe($card->number);

});

it('can update a card', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $card = Card::factory()->create([
        'user_id' => $user->id,
    ]);

    $cardData = [
        'balance' => 1000,
    ];

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->putJson('/api/cards/'.$card->id, $cardData);

    $response->assertStatus(200);

    expect($response->json('data.card.balance'))->toBe(1000);
});

it('cant update a card with invalid data', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $card = Card::factory()->create([
        'user_id' => $user->id,
    ]);

    $cardData = [
        'balance' => 'invalid',
    ];

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->putJson('/api/cards/'.$card->id, $cardData);

    $response->assertStatus(422);

    expect($response->json('errors.balance'))->toBe(['Saldo deve ser um número']);

});

it('can delete a card', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $card = Card::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->deleteJson('/api/cards/'.$card->id);

    $response->assertStatus(204);

    expect(Card::find($card->id))->toBeNull();
});
