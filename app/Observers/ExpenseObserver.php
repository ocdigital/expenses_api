<?php

namespace App\Observers;

use App\Jobs\SendExpenseCreatedEmail;
use App\Models\Expense;

class ExpenseObserver
{
    public function created(Expense $expense)
    {
        SendExpenseCreatedEmail::dispatch($expense);
    }
}
