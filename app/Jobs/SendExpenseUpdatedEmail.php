<?php

namespace App\Jobs;

use App\Mail\ExpenseUpdated;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendExpenseUpdatedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Expense $expense)
    {

    }

    public function handle(): void
    {
        Log::info('SendExpenseUpdatedEmail');
        $admins = User::where('is_admin', 1)->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new ExpenseUpdated($this->expense));
        }
        $user = $this->expense->card->user;
        Mail::to($user->email)->send(new ExpenseUpdated($this->expense));
    }
}
