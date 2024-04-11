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
        'Authorization' => 'Bearer '.$token,
    ])->postJson('/api/users', $userData);

    $response->assertStatus(201);

    expect($response->json('data.user.name'))->toBe('John Doe1');
});

it('cant create a new user with duplicated email', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;
    $userData = [
        'name' => 'John Doe1',
        'email' => $user->email,
        'password' => 'password123',
    ];

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->postJson('/api/users', $userData);

    $response->assertStatus(422);

    expect($response->json('errors.email'))->toBe(['Email já cadastrado']);
});

it('can list all users', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->getJson('/api/users');

    $response->assertStatus(200);

    expect($response->json('data.users'))->toHaveCount(1);

});

it('can show a user', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->getJson('/api/users/'.$user->id);

    $response->assertStatus(200);

    expect($response->json('data.user.name'))->toBe($user->name);

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
        'Authorization' => 'Bearer '.$token,
    ])->putJson('/api/users/'.$user->id, $userData);

    $response->assertStatus(200);

    expect($response->json('data.user.name'))->toBe('John Doe1');
});

it('can delete a user', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->deleteJson('/api/users/'.$user->id);

    $response->assertStatus(204);

    expect(User::all())->toHaveCount(0);
});

it('can login a user', function () {
    $user = User::factory()->create();
    $userData = [
        'email' => $user->email,
        'password' => 'password',
    ];

    $response = $this->postJson('/api/login', $userData);

    $response->assertStatus(200);

    expect($response->json('token'))->not()->toBeNull();

});

it('can logout a user', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->postJson('/api/logout');

    $response->assertStatus(204);

    expect($user->tokens)->toHaveCount(0);

});
