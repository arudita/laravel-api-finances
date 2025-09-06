<?php

use App\Models\Expense;
use App\Models\ExpenseTransaction;
use App\Models\User;

test('Expense Transaction: get list of expense transaction', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $expense = Expense::factory()->create([
        'user_id' => $user->id
    ]);
    ExpenseTransaction::factory()->count(6)->create([
        'expense_id' => $expense->id
    ]);

    // Act
    $response = $this->getJson('/api/v1/expense_transaction');

    // Assert
    $response->assertOk();
    $response->assertJsonCount(6, 'data');
    $response->assertJsonStructure([
        'data' => [
            '*' => ['id', 'name', 'description', 'amount', 'payment_date', 'status', 'archived']
        ]
    ]);
});

test('Expense Transaction: get single expense transaction', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $expense = Expense::factory()->create([
        'user_id' => $user->id
    ]);
    $expenseTransaction = ExpenseTransaction::factory()->create([
        'expense_id' => $expense->id
    ]);

    // Act
    $response = $this->getJson("/api/v1/expense_transaction/" . $expenseTransaction->id);

    // Assert
    $response->assertOk();
    $response->assertJsonStructure([
        'data' => ['id', 'name', 'description', 'amount', 'payment_date', 'status', 'archived']
    ]);
    $response->assertJson([
        'data' => [
            'id' => $expenseTransaction->id,
            'name' => $expenseTransaction->name,
            'description' => $expenseTransaction->description,
            'amount' => $expenseTransaction->amount,
            'payment_date' => $expenseTransaction->payment_date,
            'status' => $expenseTransaction->status,
            'archived' => $expenseTransaction->archived,
        ]
    ]);
});
