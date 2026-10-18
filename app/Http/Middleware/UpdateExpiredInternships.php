<?php

namespace App\Http\Middleware;

use App\Models\Application;
use App\Services\ApplicationLifecycleService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class UpdateExpiredInternships
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only run check if not checked in the last hour
        Cache::remember('expired_internships_checked', 3600, function () {
            $expiredApplications = Application::where('status', 'diterima')
                ->where('tanggal_selesai', '<', now()->toDateString())
                ->get();

            $lifecycleService = app(ApplicationLifecycleService::class);
            foreach ($expiredApplications as $application) {
                $lifecycleService->markAsFinished($application);
            }

            return true;
        });

        return $next($request);
    }
}
