<?php

namespace App\Services;

use App\Models\Expense;
use App\Repositories\ExpenseRepository;
use App\traits\AuthorizationTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExpenseCreated;
use App\Models\Card;

class ExpenseService
{
    use AuthorizationTrait;

    public function __construct(protected ExpenseRepository $expenseRepository)
    {
    }

    public function all()
    {
        $authorized = $this->authorizeUser(auth()->user());

        if ($authorized) {
            return $authorized;
        }

        if (auth()->user()->tokenCan('admin')) {
            $expenses = $this->expenseRepository->all();
        } else {
            $expenses = auth()->user()->expenses;
        }

        return response()->json([
            'data' => [
                'expenses' => $expenses,
            ],
        ]);
    }

    public function show(Expense $expense)
    {
        $authorized = $this->authorizeUser(auth()->user(), $expense);

        if ($authorized) {
            return $authorized;
        }

        return response()->json([
            'data' => [
                'expense' => $expense,
            ],
        ]);
    }

    public function create($request)
    {
        $card = $this->validateCard($request->number, $request->amount);
        if (! $card) {
            return response()->json(['message' => 'Card not found or insufficient balance'], 400);
        }
               
        $authorizedCard = $this->authorizeCardOwner($card);
        if ($authorizedCard) {
            return $$authorizedCard;
        }

        try {
            $expense = $this->createExpense($card, $request->amount, $request->description);
            return response()->json([
                'data' => [
                    'expense' => $expense,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    protected function validateCard($cardNumber, $amount)
    {
        $card = Card::where('number', $cardNumber)->first();
        if (! $card) {
            return null; 
        }
    
        if ($card->balance < $amount) {
            return null; 
        }
    
        return $card;
    }

    protected function createExpense($card, $amount, $description)
    {
        DB::beginTransaction();
        try {
            $expense = $card->expenses()->create([
                'amount' => $amount,
                'description' => $description,
                'user_id' => auth()->user()->id,
            ]);

            $card->balance -= $amount;
            $card->save();

            Mail::to($card->user->email)->send(new ExpenseCreated($expense));                 

            DB::commit();

            return $expense;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    
}