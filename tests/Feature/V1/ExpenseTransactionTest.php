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
        'data' => ['id', 'name', 'description', 'amount', 'payment_date', 'expense_id', 'status', 'archived']
    ]);
    $response->assertJson([
        'data' => [
            'id' => $expenseTransaction->id,
            'name' => $expenseTransaction->name,
            'description' => $expenseTransaction->description,
            'amount' => $expenseTransaction->amount,
            'payment_date' => $expenseTransaction->payment_date,
            'expense_id' => $expenseTransaction->expense_id,
            'status' => $expenseTransaction->status,
            'archived' => $expenseTransaction->archived,
        ]
    ]);
});

test('Expense Transaction: create a expense transaction', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $expense = Expense::factory()->create([
        'user_id' => $user->id
    ]);
    $expenseTransaction = [
        'name' => 'New Exepense Transaction',
        'description' => 'Transaction Description',
        'amount' => 100000,
        'payment_date' => '2025-09-08',
        'status' => 0,
        'archived' => 0,
        'expense_id' => $expense->id,
    ];

    // Act
    $response = $this->postJson('/api/v1/expense_transaction', $expenseTransaction);

    // Assert
    $response->assertCreated();
    $response->assertJsonStructure([
        'data' => ['id', 'name', 'description', 'amount', 'payment_date', 'expense_id', 'status', 'archived']
    ]);
    $response->assertJson([
        'data' => [
            'name' => $expenseTransaction['name'],
            'description' => $expenseTransaction['description'],
            'amount' => $expenseTransaction['amount'],
            'payment_date' => $expenseTransaction['payment_date'],
            'status' => $expenseTransaction['status'],
            'archived' => $expenseTransaction['archived'],
            'expense_id' => $expenseTransaction['expense_id']
        ]
    ]);
    $this->assertDatabaseHas('expense_transactions', $expenseTransaction);
});

test('Expense Transaction: update a expense transaction', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $expense = Expense::factory()->create([
        'user_id' => $user->id
    ]);
    $expenseTransaction = ExpenseTransaction::factory()->create([
        'expense_id' => $expense->id
    ]);
    $updateData = [
        'name' => 'Updated Expense Transaction',
        'description' => 'Transaction Description #1',
        'amount' => 120000,
        'payment_date' => '2025-09-01',
        'status' => 1,
        'archived' => 1,
        'expense_id' => $expense->id
    ];

    // Act
    $response = $this->putJson("/api/v1/expense_transaction/{$expenseTransaction->id}", $updateData);

    // Assert
    $response->assertOk();
    $response->assertJsonStructure([
        'data' => ['id', 'name', 'description', 'amount', 'payment_date', 'expense_id', 'status', 'archived']
    ]);
    $response->assertJson([
        'data' => [
            'name' => $updateData['name'],
            'description' => $updateData['description'],
            'amount' => $updateData['amount'],
            'payment_date' => $updateData['payment_date'],
            'status' => $updateData['status'],
            'archived' => $updateData['archived'],
            'expense_id' => $updateData['expense_id'],
        ]
    ]);
    $this->assertDatabaseHas('expense_transactions', array_merge(['id' => $expenseTransaction->id, 'expense_id' => $expense->id], $updateData));
});

test('Expense Transaction: delete a expense transaction', function () {
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
    $response = $this->deleteJson("/api/v1/expense_transaction/{$expenseTransaction->id}");

    // Assert
    $response->assertNoContent();
    $this->assertDatabaseMissing('expense_transactions', ['id' => $expense->id]);
});
