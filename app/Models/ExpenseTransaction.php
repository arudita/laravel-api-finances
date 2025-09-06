<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'amount',
        'payment_date',
        'expense_id',
        'status',
        'archived',
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
}
