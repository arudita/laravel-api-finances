<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'amount',
        'payment_date',
        'income_id',
        'status',
        'archived',
    ];

    public function income()
    {
        return $this->belongsTo(Income::class);
    }
}
