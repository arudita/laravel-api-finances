<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompleteTaskRequest;
use App\Models\Task;

class CompleteTaskController extends Controller
{
    public function __invoke(CompleteTaskRequest $request, Task $task)
    {
        $task->is_completed = $request->is_completed;
        $task->save();

        return response()->json([
            'message' => 'Task completed successfully',
            'task' => $task->toResource(),
        ]);
    }
}
