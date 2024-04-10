<?php

namespace App\Repositories;

use App\Models\Expense;

class ExpenseRepository
{
    public function all(): mixed
    {
        return Expense::all();
    }

    public function create(array $data): Expense
    {
        return Expense::create($data);
    }

    public function find($id): mixed
    {
        return Expense::find($id);
    }

    public function update(Expense $expense, array $data): bool
    {
        return $expense->update($data);
    }

    public function delete(Expense $expense): bool
    {
        return $expense->delete();
    }
}
