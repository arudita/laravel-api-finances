<?php

use App\Models\Income;
use App\Models\User;

test('Income: get list of income', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = Income::factory()->count(3)->create([
        'user_id' => $user->id
    ]);

    // Act
    $response = $this->getJson('/api/v1/incomes');

    // Assert
    $response->assertOk();
    $response->assertJsonCount(3, 'data');
    $response->assertJsonStructure([
        'data' => [
            '*' => ['id', 'name', 'description', 'status', 'archived', 'user_id']
        ]
    ]);
});

test('Income: get single income', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $income = Income::factory()->create([
        'user_id' => $user->id
    ]);

    // Act
    $response = $this->getJson("/api/v1/incomes/{$income->id}");

    // Assert
    $response->assertOk();
    $response->assertJsonStructure([
        'data' => ['id', 'name', 'description', 'status', 'archived', 'user_id']
    ]);
    $response->assertJson([
        'data' => [
            'id' => $income->id,
            'name' => $income->name,
            'description' => $income->description,
            'status' => $income->status,
            'archived' => $income->archived,
            'user_id' => $income->user_id,
        ]
    ]);
});

test('Income: create a income', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $income = [
        'name' => 'New Income',
        'description' => 'Income Description',
        'status' => 0,
        'archived' => 0,
        'user_id' => $user->id
    ];

    // Act
    $response = $this->postJson('/api/v1/incomes', $income);

    // Assert
    $response->assertCreated();
    $response->assertJsonStructure([
        'data' => ['id', 'name', 'description', 'status', 'archived', 'user_id']
    ]);
    $response->assertJson([
        'data' => [
            'name' => $income['name'],
            'description' => $income['description'],
            'status' => $income['status'],
            'archived' => $income['archived'],
            'user_id' => $user->id,
        ]
    ]);
    $this->assertDatabaseHas('incomes', $income);
});

test('Income: update a income', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $income = Income::factory()->create([
        'user_id' => $user->id
    ]);
    $updateData = [
        'name' => 'Updated Income',
        'description' => 'Income Description #1',
        'status' => 1,
        'archived' => 1,
    ];

    // Act
    $response = $this->putJson("/api/v1/incomes/{$income->id}", $updateData);

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
    $this->assertDatabaseHas('incomes', array_merge(['id' => $income->id, 'user_id' => $income->user_id], $updateData));
});

test('Income: delete a income', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $income = Income::factory()->create([
        'user_id' => $user->id
    ]);

    // Act
    $response = $this->deleteJson("/api/v1/incomes/{$income->id}");

    // Assert
    $response->assertNoContent();
    $this->assertDatabaseMissing('incomes', ['id' => $income->id]);
});
