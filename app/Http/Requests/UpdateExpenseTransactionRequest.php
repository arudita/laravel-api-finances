<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1024',
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
            'expense_id' => 'required|integer',
            'status' => 'integer',
            'archived' => 'integer',
        ];
    }
}
