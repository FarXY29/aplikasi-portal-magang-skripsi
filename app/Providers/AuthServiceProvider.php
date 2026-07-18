<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Application;
use App\Models\Attendance;
use App\Models\DailyLog;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Policies\ApplicationPolicy;
use App\Policies\AttendancePolicy;
use App\Policies\DailyLogPolicy;
use App\Policies\InstansiPolicy;
use App\Policies\InternshipPositionPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Application::class => ApplicationPolicy::class,
        Attendance::class => AttendancePolicy::class,
        DailyLog::class => DailyLogPolicy::class,
        Instansi::class => InstansiPolicy::class,
        InternshipPosition::class => InternshipPositionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
