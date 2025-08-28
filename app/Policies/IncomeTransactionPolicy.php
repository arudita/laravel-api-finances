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
        return $user->id === $incomeTransaction->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, IncomeTransaction $incomeTransaction): bool
    {
        return $user->id === $incomeTransaction->user_id;
    }

    public function delete(User $user, IncomeTransaction $incomeTransaction): bool
    {
        return $user->id === $incomeTransaction->user_id;
    }
}
