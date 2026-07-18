<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Application;
use App\Services\ApplicationLifecycleService;

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
    public function handle(ApplicationLifecycleService $lifecycleService)
    {
        $expiredApplications = Application::where('status', 'diterima')
            ->where('tanggal_selesai', '<', now()->toDateString())
            ->get();

        $count = $expiredApplications->count();

        if ($count > 0) {
            foreach ($expiredApplications as $application) {
                $lifecycleService->markAsFinished($application);
            }
            $this->info("Successfully completed {$count} expired internship(s).");
        } else {
            $this->info("No expired internships found.");
        }
    }
}
