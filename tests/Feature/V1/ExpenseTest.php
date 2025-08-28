<?php

use App\Models\Expense;
use App\Models\User;

test('Expense: get list of expense', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = Expense::factory()->count(3)->create([
        'user_id' => $user->id
    ]);

    // Act
    $response = $this->getJson('/api/v1/expenses');

    // Assert
    $response->assertOk();
    $response->assertJsonCount(3, 'data');
    $response->assertJsonStructure([
        'data' => [
            '*' => ['id', 'name', 'description', 'status', 'archived', 'user_id']
        ]
    ]);
});

test('Expense: get single expense', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $expense = Expense::factory()->create([
        'user_id' => $user->id
    ]);

    // Act
    $response = $this->getJson("/api/v1/expenses/{$expense->id}");

    // Assert
    $response->assertOk();
    $response->assertJsonStructure([
        'data' => ['id', 'name', 'description', 'status', 'archived', 'user_id']
    ]);
    $response->assertJson([
        'data' => [
            'id' => $expense->id,
            'name' => $expense->name,
            'description' => $expense->description,
            'status' => $expense->status,
            'archived' => $expense->archived,
            'user_id' => $expense->user_id,
        ]
    ]);
});

test('Expense: create a expense', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $expense = [
        'name' => 'New Exepense',
        'description' => 'Expense Description',
        'status' => 0,
        'archived' => 0,
        'user_id' => $user->id
    ];

    // Act
    $response = $this->postJson('/api/v1/expenses', $expense);

    // Assert
    $response->assertCreated();
    $response->assertJsonStructure([
        'data' => ['id', 'name', 'description', 'status', 'archived', 'user_id']
    ]);
    $response->assertJson([
        'data' => [
            'name' => $expense['name'],
            'description' => $expense['description'],
            'status' => $expense['status'],
            'archived' => $expense['archived'],
            'user_id' => $user->id,
        ]
    ]);
    $this->assertDatabaseHas('expenses', $expense);
});

test('Expense: update a expense', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $expense = Expense::factory()->create([
        'user_id' => $user->id
    ]);
    $updateData = [
        'name' => 'Updated Expense',
        'description' => 'Expense Description #1',
        'status' => 1,
        'archived' => 1,
    ];

    // Act
    $response = $this->putJson("/api/v1/expenses/{$expense->id}", $updateData);

    // Assert
    $response->assertOk();
    $response->assertJsonStructure([
        'data' => ['id', 'name', 'description', 'status', 'archived', 'user_id']
    ]);
    $response->assertJson([
        'data' => [
            'name' => $updateData['name'],
            'description' => $updateData['description'],
            'status' => $updateData['status'],
            'archived' => $updateData['archived'],
            'user_id' => $user->id,
        ]
    ]);
    $this->assertDatabaseHas('expenses', array_merge(['id' => $expense->id, 'user_id' => $expense->user_id], $updateData));
});

test('Expense: delete a expense', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $expense = Expense::factory()->create([
        'user_id' => $user->id
    ]);

    // Act
    $response = $this->deleteJson("/api/v1/expenses/{$expense->id}");

    // Assert
    $response->assertNoContent();
    $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
});
