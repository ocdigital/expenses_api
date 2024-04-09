<?php

namespace App\Repositories;

use App\Models\Expense;


class ExpenseRepository{

    public function all()
    {
        return Expense::all();
    }

    public function create(array $data)
    {
        return Expense::create($data);
    }

    public function find($id)
    {
        return Expense::find($id);
    }

    public function update(Expense $expense, array $data)
    {
        return $expense->update($data);
    }

    public function delete(Expense $expense)
    {
        return $expense->delete();
    }
}