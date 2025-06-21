<?php

namespace App\Http\Controllers\Api;

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
     */
    public function index(Request $request): ResourceCollection
    {
       return $request->user()
            ->tasks()
            ->whereNull('parent_id')
            ->with(['children.children', 'labels'])
            ->orderBy('priority')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toResourceCollection();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $validated = $request->validated();

        $task = $request->user()
            ->tasks()
            ->create($request->safe()->except('labels'));;

        if (!empty($validated['labels'])) {
            $task->labels()->attach($validated['labels']);
        }

        return $task->load(['children.children', 'labels'])->toResource();
    }

    /**
     * Display the specified resource.
     * @throws \Throwable
     */
    public function show(Task $task): JsonResource
    {
        return $task->load(['children.children', 'labels'])->toResource();
    }

    /**
     * Update the specified resource in storage.
     * @throws \Throwable
     */
    public function update(TaskRequest $request, Task $task): JsonResource
    {
        $validated = $request->validated();

        $task->update($request->safe()->except('labels'));

        if (isset($validated['labels'])) {
            $task->labels()->sync($validated['labels']);
        }

        return $task->load(['children.children', 'labels'])->toResource();
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
