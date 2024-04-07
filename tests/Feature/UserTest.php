<?php

namespace Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

uses(RefreshDatabase::class);

describe('Unit Tests', function () {

    // tenta criar um usuário
    it('create user', function () {
        $user = User::factory()->create();
        expect ($user->id)->toBe(1);

    });

    // tenta criar um usuário sem email especificado
    it('prevent duplicate user', function () {
        $user = User::factory()->create();
        $user2 = User::factory()->make(['email' => $user->email]);
        
        try {
            $user2->save();
        } catch (\Illuminate\Database\QueryException $e) {
            $this->assertStringContainsString('UNIQUE constraint', $e->getMessage());
            return;
        }

        $this->fail('User was created with duplicate email');
    });

    //tentar atualizar um usuario
    it('update user', function () {
        $user = User::factory()->create();
        $user->name = 'Teste';
        $user->save();
        expect($user->name)->toBe('Teste');
    });

    //tentar deletar um usuario
    it('delete user', function () {
        $user = User::factory()->create();
        $user->delete();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    });
});

describe('Integration Tests', function () {


})->todo();