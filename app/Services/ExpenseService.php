<?php

namespace App\Services;

use App\Models\Card;
use App\Models\Expense;
use App\Repositories\ExpenseRepository;
use App\traits\AuthorizationTrait;
use Illuminate\Support\Facades\DB;

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

    public function create($data)
    {   
        $authorized = $this->authorizeUser($data);

        if ($authorized) {
            return $authorized;
        }

   
        $card = $this->validateCard($data['number'], $data['amount']);
        if (! $card) {
            return response()->json(['message' => 'Card not found or insufficient balance'], 400);
        }

        $authorizedCard = $this->authorizeCardOwner($card);
        if ($authorizedCard) {
            return $$authorizedCard;
        }

        try {
            $expense = $this->createExpense($card, $data['amount'], $data['description']);

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

            DB::commit();

            return $expense;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
