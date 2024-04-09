<?php

namespace App\Jobs;

use App\Mail\ExpenseCreated;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendExpenseCreatedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Expense $expense)
    {

    }

    public function handle(): void
    {
        //enviar para o dono do cartão e como copia para os admins

        $admins = User::where('is_admin', 1)->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new ExpenseCreated($this->expense));
        }
        $user = $this->expense->card->user;
        Mail::to($user->email)->send(new ExpenseCreated($this->expense));
    }
}
