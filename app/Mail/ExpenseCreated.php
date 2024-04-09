<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Expense;

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
     * @param  \App\Models\Expense  $expense
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
