<?php

namespace App\Policies;

use App\Models\ExpenseTransaction;
use App\Models\User;

class ExpenseTransactionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ExpenseTransaction $expenseTransaction): bool
    {
        return $user->id === $expenseTransaction->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ExpenseTransaction $expenseTransaction): bool
    {
        return $user->id === $expenseTransaction->user_id;
    }

    public function delete(User $user, ExpenseTransaction $expenseTransaction): bool
    {
        return $user->id === $expenseTransaction->user_id;
    }
}
