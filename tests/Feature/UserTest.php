<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a new user', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;
    $userData = [
        'name' => 'John Doe1',
        'email' => 'john1@example.com',
        'password' => 'password123', 
    ];
 
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/users', $userData);

    $response->assertStatus(201);
        
    $response->assertJsonStructure([
        'data' => [
            'user' => [
                'name',
                'email',
                'id',
                'updated_at',
                'created_at',
            ],
        ],
    ]);
});

it('can list all users', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->getJson('/api/users');

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'data' => [
            'users',
        ],
    ]);
});

it('can show a user', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->getJson('/api/users/' . $user->id);

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'data' => [
            'user' => [
                'name',
                'email',
                'id',
                'updated_at',
                'created_at',
            ],
        ],
    ]);
});

it('can update a user', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;
    $userData = [
        'name' => 'John Doe1',
        'email' => 'usuario@teste.com',
        'password' => 'password123',
    ];

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->putJson('/api/users/' . $user->id, $userData);

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'data' => [
            'user' => [
                'name',
                'email',
                'id',
                'updated_at',
                'created_at',
            ],
        ],
    ]);
});

it('can delete a user', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->deleteJson('/api/users/' . $user->id);

    $response->assertStatus(204);
});




 