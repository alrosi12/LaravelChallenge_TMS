<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Date;


class ProcessOverdueTasksJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // sleep(10);
        $overdueTasks = Task::where('due_date', '<', now())
            ->where('status', '!=', 'done')
            ->get();
        foreach ($overdueTasks as $task) {
            Log::info('مهمة متاخرة', [
                'task' => $task->title,
                'due_date' => $task->due_date
            ]);
        }
    }
}
