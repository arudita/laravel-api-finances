<?php

use App\Http\Controllers\Api\V1\CompleteTaskController;
use App\Http\Controllers\Api\V1\ExpenseController;
use App\Http\Controllers\Api\V1\ExpenseTransactionController;
use App\Http\Controllers\Api\V1\IncomeController;
use App\Http\Controllers\Api\V1\IncomeTransactionController;
use App\Http\Controllers\Api\V1\TaskController;
use Illuminate\Support\Facades\Route;

Route::apiResource('tasks', TaskController::class);
Route::patch('tasks/{task}/complete', CompleteTaskController::class);

Route::apiResource('expenses', ExpenseController::class);
Route::apiResource('expense_transaction', ExpenseTransactionController::class);
Route::apiResource('incomes', IncomeController::class);
Route::apiResource('income_transaction', IncomeTransactionController::class);
