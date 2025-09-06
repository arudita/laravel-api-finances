<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\ExpenseTransaction;
use App\Models\Income;
use App\Models\IncomeTransaction;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(5)
            ->has(Task::factory(10))
            ->has(Expense::factory(7)->has(ExpenseTransaction::factory(15)))
            ->has(Income::factory(5)->has(IncomeTransaction::factory(5)))
            ->create();
    }
}
