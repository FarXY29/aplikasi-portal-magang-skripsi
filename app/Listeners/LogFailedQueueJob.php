<?php

namespace App\Listeners;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Log;

class LogFailedQueueJob
{
    /**
     * Handle the event.
     */
    public function handle(JobFailed $event): void
    {
        $jobName = $event->job->resolveName();

        Log::error("Queue job gagal diproses [{$jobName}].", [
            'connection' => $event->connectionName,
            'queue' => $event->job->getQueue(),
            'job' => $jobName,
            'attempts' => $event->job->attempts(),
            'exception_message' => $event->exception->getMessage(),
        ]);
    }
}

