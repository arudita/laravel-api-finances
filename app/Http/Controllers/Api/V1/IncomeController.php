<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIncomeRequest;
use App\Http\Requests\UpdateIncomeRequest;
use App\Models\Income;
use Illuminate\Support\Facades\Gate;

class IncomeController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Income::class);
        return request()->user()->incomes()->get()->toResourceCollection();
    }

    public function store(StoreIncomeRequest $request)
    {
        Gate::authorize('create', Income::class);
        $incomes = request()->user()->incomes()->create($request->validated());
        return $incomes->toResource();
    }

    public function show(Income $income)
    {
        Gate::authorize('view', $income);
        return $income->toResource();
    }

    public function update(UpdateIncomeRequest $request, Income $income)
    {
        Gate::authorize('update', $income);
        $income->update($request->validated());
        return $income->toResource();
    }

    public function destroy(Income $income)
    {
        Gate::authorize('delete', $income);
        $income->delete();
        return response()->noContent();
    }
}
