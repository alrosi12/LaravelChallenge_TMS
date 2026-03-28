<?php

use App\Jobs\ProcessOverdueTasksJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(ProcessOverdueTasksJob::class)->daily();
// Schedule::job(ProcessOverdueTasksJob::class)->everyMinute();
