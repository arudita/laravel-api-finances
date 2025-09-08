<?php

use App\Models\Income;
use App\Models\IncomeTransaction;
use App\Models\User;

test('Income Transaction: get list of income transaction', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $income = Income::factory()->create([
        'user_id' => $user->id
    ]);
    IncomeTransaction::factory()->count(6)->create([
        'income_id' => $income->id
    ]);

    // Act
    $response = $this->getJson('/api/v1/income_transaction');

    // Assert
    $response->assertOk();
    $response->assertJsonCount(6, 'data');
    $response->assertJsonStructure([
        'data' => [
            '*' => ['id', 'name', 'description', 'amount', 'payment_date', 'status', 'archived']
        ]
    ]);
});

test('Income Transaction: get single income transaction', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $income = Income::factory()->create([
        'user_id' => $user->id
    ]);
    $incomeTransaction = IncomeTransaction::factory()->create([
        'income_id' => $income->id
    ]);

    // Act
    $response = $this->getJson("/api/v1/income_transaction/" . $incomeTransaction->id);

    // Assert
    $response->assertOk();
    $response->assertJsonStructure([
        'data' => ['id', 'name', 'description', 'amount', 'payment_date', 'income_id', 'status', 'archived']
    ]);
    $response->assertJson([
        'data' => [
            'id' => $incomeTransaction->id,
            'name' => $incomeTransaction->name,
            'description' => $incomeTransaction->description,
            'amount' => $incomeTransaction->amount,
            'payment_date' => $incomeTransaction->payment_date,
            'income_id' => $incomeTransaction->income_id,
            'status' => $incomeTransaction->status,
            'archived' => $incomeTransaction->archived,
        ]
    ]);
});

test('Income Transaction: create a income transaction', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $income = Income::factory()->create([
        'user_id' => $user->id
    ]);
    $incomeTransaction = [
        'name' => 'New Exepense Transaction',
        'description' => 'Transaction Description',
        'amount' => 100000,
        'payment_date' => '2025-09-08',
        'status' => 0,
        'archived' => 0,
        'income_id' => $income->id,
    ];

    // Act
    $response = $this->postJson('/api/v1/income_transaction', $incomeTransaction);

    // Assert
    $response->assertCreated();
    $response->assertJsonStructure([
        'data' => ['id', 'name', 'description', 'amount', 'payment_date', 'income_id', 'status', 'archived']
    ]);
    $response->assertJson([
        'data' => [
            'name' => $incomeTransaction['name'],
            'description' => $incomeTransaction['description'],
            'amount' => $incomeTransaction['amount'],
            'payment_date' => $incomeTransaction['payment_date'],
            'status' => $incomeTransaction['status'],
            'archived' => $incomeTransaction['archived'],
            'income_id' => $incomeTransaction['income_id']
        ]
    ]);
    $this->assertDatabaseHas('income_transactions', $incomeTransaction);
});

test('Income Transaction: update a income transaction', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $income = Income::factory()->create([
        'user_id' => $user->id
    ]);
    $incomeTransaction = IncomeTransaction::factory()->create([
        'income_id' => $income->id
    ]);
    $updateData = [
        'name' => 'Updated Income Transaction',
        'description' => 'Transaction Description #1',
        'amount' => 120000,
        'payment_date' => '2025-09-01',
        'status' => 1,
        'archived' => 1,
        'income_id' => $income->id
    ];

    // Act
    $response = $this->putJson("/api/v1/income_transaction/{$incomeTransaction->id}", $updateData);

    // Assert
    $response->assertOk();
    $response->assertJsonStructure([
        'data' => ['id', 'name', 'description', 'amount', 'payment_date', 'income_id', 'status', 'archived']
    ]);
    $response->assertJson([
        'data' => [
            'name' => $updateData['name'],
            'description' => $updateData['description'],
            'amount' => $updateData['amount'],
            'payment_date' => $updateData['payment_date'],
            'status' => $updateData['status'],
            'archived' => $updateData['archived'],
            'income_id' => $updateData['income_id'],
        ]
    ]);
    $this->assertDatabaseHas('income_transactions', array_merge(['id' => $incomeTransaction->id, 'income_id' => $income->id], $updateData));
});

test('Income Transaction: delete a income transaction', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $income = Income::factory()->create([
        'user_id' => $user->id
    ]);
    $incomeTransaction = IncomeTransaction::factory()->create([
        'income_id' => $income->id
    ]);

    // Act
    $response = $this->deleteJson("/api/v1/income_transaction/{$incomeTransaction->id}");

    // Assert
    $response->assertNoContent();
    $this->assertDatabaseMissing('income_transactions', ['id' => $income->id]);
});
