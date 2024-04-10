<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_id',
        'amount',
        'description',
    ];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($expense) {
            if ($expense->amount < 0) {
                return false;
            }
        });
    }
}
