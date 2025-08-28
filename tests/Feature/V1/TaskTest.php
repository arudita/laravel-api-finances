<?php

use App\Models\Task;
use App\Models\User;

test('Tasks: get list of tasks', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = Task::factory()->count(3)->create([
        'user_id' => $user->id
    ]);

    // Act
    $response = $this->getJson('/api/v1/tasks');

    // Assert
    $response->assertOk();
    $response->assertJsonCount(3, 'data');
    $response->assertJsonStructure([
        'data' => [
            '*' => ['id', 'name', 'is_completed']
        ]
    ]);
});

test('Tasks: get single task', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $task = Task::factory()->create([
        'user_id' => $user->id
    ]);

    // Act
    $response = $this->getJson("/api/v1/tasks/{$task->id}");

    // Assert
    $response->assertOk();
    $response->assertJsonStructure([
        'data' => ['id', 'name', 'is_completed']
    ]);
    $response->assertJson([
        'data' => ['id' => $task->id, 'name' => $task->name, 'is_completed' => $task->is_completed]
    ]);
});

test('Tasks: create a task', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $taskData = ['name' => 'New Task', 'is_completed' => false, 'user_id' => $user->id];

    // Act
    $response = $this->postJson('/api/v1/tasks', $taskData);

    // Assert
    $response->assertCreated();
    $response->assertJsonStructure([
        'data' => ['id', 'name', 'is_completed']
    ]);
    $response->assertJson([
        'data' => ['name' => $taskData['name'], 'is_completed' => $taskData['is_completed']]
    ]);
    $this->assertDatabaseHas('tasks', $taskData);
});

test('Tasks: update a task', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $task = Task::factory()->create([
        'user_id' => $user->id
    ]);
    $updateData = ['name' => 'Updated Task'];

    // Act
    $response = $this->putJson("/api/v1/tasks/{$task->id}", $updateData);

    // Assert
    $response->assertOk();
    $response->assertJsonStructure([
        'data' => ['id', 'name', 'is_completed']
    ]);
    $response->assertJson([
        'data' => ['id' => $task->id, 'name' => $updateData['name']]
    ]);
    $this->assertDatabaseHas('tasks', array_merge(['id' => $task->id], $updateData));
});

test('Tasks: update a task with invalid data', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $task = Task::factory()->create([
        'user_id' => $user->id
    ]);
    $invalidData = ['name' => ''];

    // Act
    $response = $this->putJson("/api/v1/tasks/{$task->id}", $invalidData);

    // Assert
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name']);
});

test('Tasks: complete a task', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $task = Task::factory()->create(['is_completed' => false, 'user_id' => $user->id]);
    $completeData = ['is_completed' => true];

    // Act
    $response = $this->patchJson("/api/v1/tasks/{$task->id}/complete", $completeData);

    // Assert
    $response->assertOk();
    $response->assertJson([
        'message' => 'Task completed successfully',
        'task' => ['id' => $task->id, 'name' => $task->name, 'is_completed' => true]
    ]);
    $this->assertDatabaseHas('tasks', ['id' => $task->id, 'is_completed' => true]);
});

test('Tasks: delete a task', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $task = Task::factory()->create([
        'user_id' => $user->id
    ]);

    // Act
    $response = $this->deleteJson("/api/v1/tasks/{$task->id}");

    // Assert
    $response->assertNoContent();
    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});
