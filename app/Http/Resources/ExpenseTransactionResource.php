<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseTransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'amount' => $this->amount,
            'payment_date' => $this->payment_date,
            'expense_id' => $this->expense_id,
            'status' => $this->status,
            'archived' => $this->archived,
        ];
    }
}
