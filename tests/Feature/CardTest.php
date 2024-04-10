<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Card;
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
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/cards', $cardData);

    $response->assertStatus(201);

    $response->assertJson([
        'data' => [
            'card' => [
                'number' => '1234567890123456',
                'balance' => 1000,
                'user_id' => $user->id,
            ],
        ],
    ]);
});

it('can list all cards', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->getJson('/api/cards');

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'data' => [
            'cards',
        ],
    ]);
});

it('can show a card', function () {
    $user = User::factory()->create();

    $token = $user->createToken('test-token')->plainTextToken;

    $card = Card::factory()->create([
        'user_id' => $user->id, 
    ]);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->getJson('/api/cards/' . $card->id);


    $response->assertStatus(200);

    $response->assertJsonStructure([
        'data' => [
            'card',
        ],
    ]);
});

it('can update a card', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $card = Card::factory()->create([
        'user_id' => $user->id,
    ]);

    $cardData = [        
        'balance' => 1000   
    ];

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->putJson('/api/cards/' . $card->id, $cardData);

    $response->assertStatus(200);

    $response->assertJson([
        'data' => [
            'card' => [
                'number' => $card->number,
                'balance' => 1000,
                'user_id' => $user->id,
            ],
        ],
    ]);
});

it('can delete a card', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $card = Card::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->deleteJson('/api/cards/' . $card->id);

    $response->assertStatus(204);
});

   



