<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(5)
            ->has(Task::factory(10))
            ->has(Expense::factory(5))
            ->has(Income::factory(7))
            ->create();
    }
}
