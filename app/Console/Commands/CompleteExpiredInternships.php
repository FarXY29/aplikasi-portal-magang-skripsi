<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Services\ApplicationLifecycleService;
use Illuminate\Console\Command;

class CompleteExpiredInternships extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'internship:complete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically complete internship applications that have expired';

    /**
     * Execute the console command.
     */
    public function handle(ApplicationLifecycleService $lifecycleService): int
    {
        $count = 0;

        Application::where('status', 'diterima')
            ->where('tanggal_selesai', '<', now()->toDateString())
            ->chunkById(100, function ($expiredApplications) use ($lifecycleService, &$count) {
                foreach ($expiredApplications as $application) {
                    $lifecycleService->markAsFinished($application);
                    $count++;
                }
            });

        if ($count > 0) {
            $this->info("Successfully completed {$count} expired internship(s).");
        } else {
            $this->info('No expired internships found.');
        }

        return 0;
    }
}
