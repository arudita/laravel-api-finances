<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'archived',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expenseTransactions()
    {
        return $this->hasMany(ExpenseTransaction::class);
    }
}
