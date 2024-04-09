<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExpenseCreated;


class ExpenseController extends Controller
{
    public function index()
    {
        if (Auth::user()->tokenCan('admin')) {
            $expenses = Expense::all();

        } else {            
            $expenses = Auth::user()->cards;
        }

        return response()->json(['expenses' => $expenses]);
    }

    public function show(Expense $expense)
    {
        if (! Auth::user()->tokenCan('admin') && Auth::user()->id !== $expense->card->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['expense' => $expense]);
    }

    public function store(Request $request)
    {   
        $exists = Card::where('number', $request->number)->exists();
        if (!$exists) 
            return response()->json(['message' => 'Card not found'], 404);

        if(Auth::user()->tokenCan('admin')) {
            $card = Card::where('number', $request->number)->first();
        }else{   
            $card = Card::where('number', $request->number)->where('user_id', Auth::user()->id)->first();
        }
   
        if (!$card) 
            return response()->json(['message' => 'This card is not yours'], 403);

        if ($card->balance < $request->amount) {
            return response()->json(['error' => 'Saldo insuficiente no cartão'], 400);
        }

         // Iniciar a transação
        DB::beginTransaction();

        try {
            // Criar a despesa através do relacionamento do cartão
            $expense = $card->expenses()->create([
                'amount' => $request->amount,
                'description' => $request->description,
            ]);

            // Atualizar o saldo do cartão
            $card->balance -= $request->amount;
            $card->save();

            // Commit da transação se tudo ocorrer bem
            DB::commit();

            // Enviar e-mails para administradores
            $admins = User::where('is_admin', true)->get();
            Mail::to($admins)->send(new ExpenseCreated($expense));

            // Enviar e-mail para o usuário do cartão
            Mail::to($card->user)->send(new ExpenseCreated($expense));

            return response()->json(['message' => 'Despesa criada com sucesso'], 201);
        } catch (\Exception $e) {
            // Em caso de erro, rollback da transação e retorno de erro
            DB::rollBack();
            return response()->json(['error' => 'Erro ao criar a despesa: ' . $e->getMessage()], 500);
        }



        $expense = $card->expenses()->create($request->only('card_id','amount', 'description'));

        if (! $expense->id)
            return response()->json(['message' => 'Expense Creation Failed'], 500);
        

        return response()->json(['expense' => $expense]);
    }

    public function update(Request $request, Expense $expense)
    {   
        if (! Auth::user()->tokenCan('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }       

        $expense->update($request->only('amount', 'description'));

        return response()->json(['expense' => $expense]);
    }

    public function destroy(Expense $expense)
    {
        if (! Auth::user()->tokenCan('admin') && Auth::user()->id !== $expense->card->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }       

        $expense->delete();

        return response()->json([], 204);
    }
}
