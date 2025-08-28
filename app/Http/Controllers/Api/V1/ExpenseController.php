<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use Illuminate\Support\Facades\Gate;

class ExpenseController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Expense::class);
        return request()->user()->expenses()->get()->toResourceCollection();
    }

    public function store(StoreExpenseRequest $request)
    {
        Gate::authorize('create', Expense::class);
        $expense = request()->user()->expenses()->create($request->validated());
        return $expense->toResource();
    }

    public function show(Expense $expense)
    {
        Gate::authorize('view', $expense);
        return $expense->toResource();
    }

    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        Gate::authorize('update', $expense);
        $expense->update($request->validated());
        return $expense->toResource();
    }

    public function destroy(Expense $expense)
    {
        Gate::authorize('delete', $expense);
        $expense->delete();
        return response()->noContent();
    }
}
