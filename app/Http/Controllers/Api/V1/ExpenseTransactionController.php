<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ExpenseTransaction;
use App\Http\Requests\StoreExpenseTransactionRequest;
use App\Http\Requests\UpdateExpenseTransactionRequest;
use App\Http\Resources\ExpenseTransactionResource;
use App\Models\Expense;
use Illuminate\Support\Facades\Gate;

class ExpenseTransactionController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', ExpenseTransaction::class);
        $expenseIds = request()->user()->expenses()->pluck('id');

        $transactions = ExpenseTransaction::whereIn('expense_id', $expenseIds)->get();
        return $transactions->toResourceCollection();
    }

    public function store(StoreExpenseTransactionRequest $request)
    {
        Gate::authorize('create', ExpenseTransaction::class);

        $expenseTransaction = ExpenseTransaction::create($request->validated());
        return new ExpenseTransactionResource($expenseTransaction);
    }

    public function show(ExpenseTransaction $expenseTransaction)
    {
        Gate::authorize('view', $expenseTransaction);
        return $expenseTransaction->toResource();
    }

    public function update(UpdateExpenseTransactionRequest $request, ExpenseTransaction $expenseTransaction)
    {
        Gate::authorize('update', $expenseTransaction);
        $expenseTransaction->update($request->validated());
        return $expenseTransaction->toResource();
    }

    public function destroy(ExpenseTransaction $expenseTransaction)
    {
        Gate::authorize('delete', $expenseTransaction);
        $expenseTransaction->delete();
        return response()->noContent();
    }
}
