<?php

namespace App\Policies;

use App\Models\IncomeTransaction;
use App\Models\User;

class IncomeTransactionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, IncomeTransaction $incomeTransaction): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, IncomeTransaction $incomeTransaction): bool
    {
        return true;
    }

    public function delete(User $user, IncomeTransaction $incomeTransaction): bool
    {
        return true;
    }
}
