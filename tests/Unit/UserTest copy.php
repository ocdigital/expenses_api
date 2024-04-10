<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('create user', function () {
    $user = User::factory()->make([
        'name' => 'Usuario Teste',
        'email' => 'usuario@teste.com',
        'password' => Hash::make('teste'),
    ]);

    expect($user->name)->toBe('Usuario Teste');
    expect($user->email)->toBe('usuario@teste.com');
    expect(Hash::check('teste', $user->password))->toBeTrue();

});

it('update user', function () {
    $user = User::factory()->make([
        'name' => 'Usuario Teste',
        'email' => 'usuario@teste.com',
        'password' => Hash::make('teste'),
    ]);

    $user->name = 'Usuario Teste 2';
    $user->email = 'novoemail@test.com';
    $user->password = Hash::make('teste2');

    expect($user->name)->toBe('Usuario Teste 2');
    expect($user->email)->toBe('novoemail@test.com');
    expect(Hash::check('teste2', $user->password))->toBeTrue();

});

it('delete user', function () {
    $user = User::factory()->make([
        'name' => 'Usuario Teste',
        'email' => 'usuario@teste.com',
        'password' => Hash::make('teste'),
    ]);

    $user->delete();
    expect($user->exists)->toBeFalse();
});
