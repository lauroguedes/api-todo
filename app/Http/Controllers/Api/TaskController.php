<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

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
            ->with(['children', 'labels'])
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

        return $task->load(['children', 'labels'])->toResource();
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
