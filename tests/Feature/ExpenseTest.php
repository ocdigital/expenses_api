<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Card;
use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;


uses(RefreshDatabase::class);

it('cant create a new expense', function(){
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
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/expenses', $expenseData);
        
        $response->assertStatus(201);

        $response->assertJson([
            'data' => [
                'expense' => [

                    'amount' => 50,
                    'description' => 'Compra de um livro',
                    'card_id' => $card->id,
                ],
            ],
        ]);

});

it('can list all expenses', function(){
        $user = User::factory()->create();

        $token = $user->createToken('test')->plainTextToken;
        $card = Card::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/expenses');
        
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'expenses',
            ],
        ]);

});

it('can show a expense', function(){
        $user = User::factory()->create();

        $token = $user->createToken('test')->plainTextToken;
        $card = Card::factory()->create([
            'user_id' => $user->id,
        ]);

        $expense = Expense::factory()->create([
            'card_id' => $card->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/expenses/' . $expense->id);
        
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'expense' => [
                    'amount',
                    'description',
                    'card_id',
                    'id',
                    'updated_at',
                    'created_at',
                ],
            ],
        ]);

});

it('can update a expense', function(){
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
        'Authorization' => 'Bearer ' . $token,
    ])->putJson('/api/expenses/' . $expense->id, $expenseData);

    $response->assertStatus(200);

    $response->assertJson([
        'expense' => [
            'description' => 'Compra de um livro atualizada',
        ],
    ]);
});

it('can delete a expense', function(){
    $user = User::factory()->create();

    $token = $user->createToken('test')->plainTextToken;
    $card = Card::factory()->create([
        'user_id' => $user->id,
    ]);

    $expense = Expense::factory()->create([
        'card_id' => $card->id,
    ]);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->deleteJson('/api/expenses/' . $expense->id);

    $response->assertStatus(204);
});



