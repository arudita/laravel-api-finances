<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Task::class);
        return request()->user()->tasks()->get()->toResourceCollection();
    }

    public function store(StoreTaskRequest $request)
    {
        Gate::authorize('create', Task::class);
        $task = request()->user()->tasks()->create($request->validated());
        return $task->toResource();
    }

    public function show(Task $task)
    {
        Gate::authorize('view', $task);
        return $task->toResource();
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        Gate::authorize('update', $task);
        $task->update($request->validated());
        return $task->toResource();
    }

    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);
        $task->delete();
        return response()->noContent();
    }
}
