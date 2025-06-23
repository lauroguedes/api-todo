<?php

use App\Enums\TaskPriority;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('withRecursiveExpression returns all root tasks when taskId is null', function () {
    $rootTasks = Task::factory()
        ->count(3)
        ->for($this->user)
        ->create(['parent_id' => null]);

    $childTasks = Task::factory()
        ->count(2)
        ->for($this->user)
        ->create(['parent_id' => $rootTasks[0]->id]);

    $otherUser = User::factory()->create();
    Task::factory()
        ->for($otherUser)
        ->create(['parent_id' => null]);

    $result = Task::withRecursiveExpression();

    expect($result)
        ->toBeInstanceOf(\Illuminate\Support\Collection::class)
        ->toHaveCount(3);

    $firstTask = $result->firstWhere('id', $rootTasks[0]->id);
    expect($firstTask)
        ->not
        ->toBeNull();
});

test('withRecursiveExpression returns a specific task tree when taskId is provided', function () {
    $rootTask = Task::factory()
        ->for($this->user)
        ->create(['parent_id' => null]);

    $childTasks = Task::factory()
        ->count(2)
        ->for($this->user)
        ->create(['parent_id' => $rootTask->id]);

    $grandchildTasks = Task::factory()
        ->count(2)
        ->for($this->user)
        ->create(['parent_id' => $childTasks[0]->id]);

    $result = Task::withRecursiveExpression($rootTask->id);

    expect($result)
        ->toBeInstanceOf(\Illuminate\Support\Collection::class)
        ->toHaveCount(1);

    $returnedRootTask = $result->first();
    expect($returnedRootTask->id)
        ->toBe($rootTask->id);
});

test('withRecursiveExpression returns empty collection for non-existent taskId', function () {
    $result = Task::withRecursiveExpression(999);

    expect($result)
        ->toBeInstanceOf(\Illuminate\Support\Collection::class)
        ->toBeEmpty();
});

test('withRecursiveExpression only returns tasks belonging to authenticated user', function () {
    $userTask = Task::factory()
        ->for($this->user)
        ->create(['parent_id' => null]);

    $otherUser = User::factory()->create();
    $otherUserTask = Task::factory()
        ->for($otherUser)
        ->create(['parent_id' => null]);

    $result = Task::withRecursiveExpression();

    expect($result)
        ->toBeInstanceOf(\Illuminate\Support\Collection::class)
        ->toHaveCount(1)
        ->and($result->first()->id)
        ->toBe($userTask->id);

    $result = Task::withRecursiveExpression($otherUserTask->id);
    expect($result)->toBeEmpty();
});
