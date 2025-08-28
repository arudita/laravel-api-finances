<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ExpenseTransaction;
use App\Http\Requests\StoreExpenseTransactionRequest;
use App\Http\Requests\UpdateExpenseTransactionRequest;
use Illuminate\Support\Facades\Gate;

class ExpenseTransactionController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', ExpenseTransaction::class);
    }

    public function store(StoreExpenseTransactionRequest $request)
    {
        Gate::authorize('create', ExpenseTransaction::class);
    }

    public function show(ExpenseTransaction $expenseTransaction)
    {
        Gate::authorize('view', $expenseTransaction);
    }

    public function update(UpdateExpenseTransactionRequest $request, ExpenseTransaction $expenseTransaction)
    {
        Gate::authorize('update', $expenseTransaction);
    }

    public function destroy(ExpenseTransaction $expenseTransaction)
    {
        Gate::authorize('delete', $expenseTransaction);
    }
}
