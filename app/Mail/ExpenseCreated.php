<?php

namespace App\Mail;

use App\Models\Expense;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExpenseCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * A despesa criada.
     *
     * @var \App\Models\Expense
     */
    public $expense;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Expense $expense)
    {
        $this->expense = $expense;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Despesa Criada')
            ->view('emails.expense.created');
    }
}
