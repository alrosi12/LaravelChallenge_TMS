<?php

namespace App\Http\Controllers\Api;

use App\Events\TaskAssigned;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->filter || $request->assignee_id) {

            $tasks = Task::with('comments.user', 'assignee')->where('user_id', auth()->id());

            if ($request->filter === 'overdue') {
                $tasks =   $tasks->overdue();
            }
            if ($request->filter === 'pending') {
                $tasks =  $tasks->pending();
            }
            if ($request->assignee_id) {
                $tasks =  $tasks->assignedTo($request->assignee_id);
            }
            return TaskResource::collection($tasks->paginate());
        }

        $tasks = Cache::remember('tasks_' . auth()->id(), 300, function () {
            return Task::with('comments.user', 'assignee')->where('user_id', auth()->id());
        });
        return TaskResource::collection($tasks->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        Cache::forget('tasks_' . auth()->id());
        $task = Task::create([
            ...$request->validated(),
            'user_id' => auth()->id()
        ]);
        if ($task->assignee_id) {
            TaskAssigned::dispatch($task);
        }
        return response()->json([
            'status' => 'success',
            'task' => new TaskResource($task)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);

        $task->load('comments.user', 'assignee');
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage. 
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        Cache::forget('tasks_' . auth()->id());

        $this->authorize('update', $task);

        $task->update($request->validated());
        if ($task->assignee_id) {
            TaskAssigned::dispatch($task);
        }
        return response()->json([
            'status' => 'Updated Successfully',
            'task' => new TaskResource($task)

        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        Cache::forget('tasks');

        $this->authorize('delete', $task);
        $task->delete();
        return response()->json([
            'status' => 'Deleted Successfully',
        ]);
    }
}
