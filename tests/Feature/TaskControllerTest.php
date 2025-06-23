<?php

use App\Enums\TaskPriority;
use App\Models\Label;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('index returns a list of tasks', function () {
    $tasks = Task::factory()
        ->count(3)
        ->for($this->user)
        ->create();

    $response = $this->getJson('/api/tasks');

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'priority',
                    'is_completed',
                    'parent_id',
                    'labels',
                    'children',
                    'created_at',
                ]
            ]
        ]);
});

test('store creates a new task', function () {
    $labels = Label::factory()->count(2)->create();

    $data = [
        'title' => 'Test Task',
        'description' => 'This is a test task',
        'priority' => TaskPriority::MEDIUM->value,
        'is_completed' => false,
        'labels' => $labels->pluck('id')->toArray(),
    ];

    $response = $this->postJson('/api/tasks', $data);

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'title' => 'Test Task',
                'description' => 'This is a test task',
                'priority' => TaskPriority::MEDIUM->label(),
                'is_completed' => false,
            ]
        ])
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'priority',
                'is_completed',
                'parent_id',
                'labels',
                'children',
                'created_at',
            ]
        ]);

    $this->assertDatabaseHas('tasks', [
        'title' => 'Test Task',
        'description' => 'This is a test task',
        'priority' => TaskPriority::MEDIUM->value,
        'is_completed' => false,
        'user_id' => $this->user->id,
    ]);

    $taskId = $response->json('data.id');
    foreach ($labels as $label) {
        $this->assertDatabaseHas('label_task', [
            'task_id' => $taskId,
            'label_id' => $label->id,
        ]);
    }
});

test('show returns a specific task', function () {
    $task = Task::factory()
        ->for($this->user)
        ->create();

    $response = $this->getJson("/api/tasks/{$task->id}");

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'priority' => $task->priority->label(),
                'is_completed' => $task->is_completed,
            ]
        ])
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'priority',
                'is_completed',
                'parent_id',
                'labels',
                'children',
                'created_at',
            ]
        ]);
});

test('update modifies an existing task', function () {
    $task = Task::factory()
        ->for($this->user)
        ->create();

    $labels = Label::factory()->count(2)->create();

    $data = [
        'title' => 'Updated Task',
        'description' => 'This task has been updated',
        'priority' => TaskPriority::HIGH->value,
        'is_completed' => true,
        'labels' => $labels->pluck('id')->toArray(),
    ];

    $response = $this->putJson("/api/tasks/{$task->id}", $data);

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $task->id,
                'title' => 'Updated Task',
                'description' => 'This task has been updated',
                'priority' => TaskPriority::HIGH->label(),
                'is_completed' => true,
            ]
        ])
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'priority',
                'is_completed',
                'parent_id',
                'labels',
                'children',
                'created_at',
            ]
        ]);

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'title' => 'Updated Task',
        'description' => 'This task has been updated',
        'priority' => TaskPriority::HIGH->value,
        'is_completed' => true,
        'user_id' => $this->user->id,
    ]);

    foreach ($labels as $label) {
        $this->assertDatabaseHas('label_task', [
            'task_id' => $task->id,
            'label_id' => $label->id,
        ]);
    }
});

test('destroy removes a task', function () {
    $task = Task::factory()
        ->for($this->user)
        ->create();

    $response = $this->deleteJson("/api/tasks/{$task->id}");

    $response->assertStatus(204);

    $this->assertDatabaseMissing('tasks', [
        'id' => $task->id,
    ]);
});

test('validation fails with invalid data', function () {
    $data = [
        'title' => '',
    ];

    $response = $this->postJson('/api/tasks', $data);

    $response->assertStatus(422);
});

test('can create a task with a parent', function () {
    $parentTask = Task::factory()
        ->for($this->user)
        ->create();

    $data = [
        'title' => 'Child Task',
        'description' => 'This is a child task',
        'priority' => TaskPriority::LOW->value,
        'is_completed' => false,
        'parent_id' => $parentTask->id,
    ];

    $response = $this->postJson('/api/tasks', $data);

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'title' => 'Child Task',
                'description' => 'This is a child task',
                'priority' => TaskPriority::LOW->label(),
                'is_completed' => false,
                'parent_id' => $parentTask->id,
            ]
        ])
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'priority',
                'is_completed',
                'parent_id',
                'labels',
                'children',
                'created_at',
            ]
        ]);

    $this->assertDatabaseHas('tasks', [
        'title' => 'Child Task',
        'description' => 'This is a child task',
        'priority' => TaskPriority::LOW->value,
        'is_completed' => false,
        'parent_id' => $parentTask->id,
        'user_id' => $this->user->id,
    ]);
});
