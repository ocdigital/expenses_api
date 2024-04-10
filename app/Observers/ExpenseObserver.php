<?php

namespace App\Observers;

use App\Jobs\SendExpenseCreatedEmail;
use App\Jobs\SendExpenseUpdatedEmail;
use App\Models\Expense;
use Illuminate\Support\Facades\Log;

class ExpenseObserver
{
    public function created(Expense $expense)
    {
        SendExpenseCreatedEmail::dispatch($expense);
    }

    public function updated(Expense $expense)
    {
        Log::info('Expense updated');
        SendExpenseUpdatedEmail::dispatch($expense);
    }
}
