<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\TaskOperationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws \Throwable
     */
    public function index(Request $request): ResourceCollection
    {
        return Task::withRecursiveExpression()
            ->toResourceCollection();
    }

    /**
     * Store a newly created resource in storage.
     * @throws TaskOperationException
     */
    public function store(TaskRequest $request)
    {
        try {
            $validated = $request->validated();

            $task = $request->user()
                ->tasks()
                ->create($request->safe()->except('labels'));;

            if (!empty($validated['labels'])) {
                $task->labels()->attach($validated['labels']);
            }

            return Task::withRecursiveExpression($task->id)
                ->firstOrFail()
                ->toResource();
        } catch (\Throwable $th) {
            throw new TaskOperationException(previous: $th);
        }
    }

    /**
     * Display the specified resource.
     * @throws \Throwable
     */
    public function show(Task $task): JsonResource
    {
        return Task::withRecursiveExpression($task->id)
            ->firstOrFail()
            ->toResource();
    }

    /**
     * Update the specified resource in storage.
     * @throws \Throwable
     */
    public function update(TaskRequest $request, Task $task): JsonResource
    {
        try {
            $validated = $request->validated();

            $task->update($request->safe()->except('labels'));

            if (isset($validated['labels'])) {
                $task->labels()->sync($validated['labels']);
            }

            return Task::withRecursiveExpression($task->id)
                ->firstOrFail()
                ->toResource();
        } catch (\Throwable $th) {
            throw new TaskOperationException(previous: $th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): Response
    {
        $task->delete();

        return response()->noContent();
    }
}
