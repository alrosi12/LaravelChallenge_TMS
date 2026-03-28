<?php

namespace App\Listeners;

use App\Events\TaskAssigned;
use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendTaskAssignmentNotification implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(TaskAssigned $event): void
    {
        $task = $event->task;
        Log::info("تم تعين مهمه", [
            'task' => $task->title,
            'assignee' => $task->assignee?->name
        ]);
    }
}
