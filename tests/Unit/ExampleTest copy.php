<?php

use App\Models\User;

it('creates a user', function () {
    $user = User::factory()->make([
        'name' => 'Usuario Teste',
        'email' => 'usuario@teste.com',
        'password' => bcrypt('teste'),
    ]);

    $this->assertInstanceOf(User::class, $user);
    $this->assertNotNull($user->name);
    $this->assertNotNull($user->email);
    $this->assertNotNull($user->password);
});
