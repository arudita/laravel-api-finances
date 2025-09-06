<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\IncomeTransaction;
use App\Http\Requests\StoreIncomeTransactionRequest;
use App\Http\Requests\UpdateIncomeTransactionRequest;
use Illuminate\Support\Facades\Gate;

class IncomeTransactionController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', IncomeTransaction::class);
        return request()->user()->incomes()->incomeTransactions()->get()->toResourceCollection();
    }

    public function store(StoreIncomeTransactionRequest $request)
    {
        Gate::authorize('create', IncomeTransaction::class);
        $incomeTransaction = request()->user()->incomes()->expenseTransactions()->create($request->validated());
        return $incomeTransaction->toResource();
    }

    public function show(IncomeTransaction $incomeTransaction)
    {
        Gate::authorize('view', $incomeTransaction);
        return $incomeTransaction->toResource();
    }

    public function update(UpdateIncomeTransactionRequest $request, IncomeTransaction $incomeTransaction)
    {
        Gate::authorize('update', $incomeTransaction);
        $incomeTransaction->update($request->validated());
        return $incomeTransaction->toResource();
    }

    public function destroy(IncomeTransaction $incomeTransaction)
    {
        Gate::authorize('delete', $incomeTransaction);
        $incomeTransaction->delete();
        return response()->noContent();
    }
}
