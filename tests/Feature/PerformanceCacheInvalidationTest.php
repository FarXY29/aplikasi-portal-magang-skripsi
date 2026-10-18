<?php

namespace Tests\Feature;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\Setting;
use App\Models\User;
use App\Services\ApplicationStateTransitionService;
use App\Services\PdfExportService;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PerformanceCacheInvalidationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_dashboard_whitelists_allowed_periods(): void
    {
        $admin = User::factory()->create(['role' => 'admin_instansi']);
        $admin->assignRole('admin_instansi');
        $instansi = Instansi::factory()->create();
        $admin->update(['instansi_id' => $instansi->id]);

        $this->actingAs($admin);

        // Request with malicious or arbitrary period query param
        $response = $this->get('/dinas/dashboard?period=malicious_cache_key_injection');
        $response->assertStatus(200);

        // Cache must NOT store the arbitrary key, must fallback to 30_hari
        $this->assertFalse(Cache::has("admin_instansi_dashboard:{$instansi->id}:malicious_cache_key_injection"));
        $this->assertTrue(Cache::has("admin_instansi_dashboard:{$instansi->id}:30_hari"));
    }

    public function test_state_transition_invalidates_instansi_dashboard_and_landing_caches(): void
    {
        $user = User::factory()->create(['role' => 'peserta']);
        $user->assignRole('peserta');
        $instansi = Instansi::factory()->create();
        $position = InternshipPosition::factory()->create(['instansi_id' => $instansi->id]);

        $app = Application::factory()->create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'status' => ApplicationStatus::Pending,
        ]);

        $dashKey = "admin_instansi_dashboard:{$instansi->id}:30_hari";
        Cache::put($dashKey, ['cached' => true], 300);
        Cache::put('landing_page_stats_v3', ['cached' => true], 300);

        $this->assertTrue(Cache::has($dashKey));
        $this->assertTrue(Cache::has('landing_page_stats_v3'));

        $transitionService = app(ApplicationStateTransitionService::class);
        $transitionService->accept($app);

        $this->assertFalse(Cache::has($dashKey), 'Dashboard cache should be invalidated on transition');
        $this->assertFalse(Cache::has('landing_page_stats_v3'), 'Landing page stats should be invalidated on transition');
    }

    public function test_pdf_export_service_memoizes_settings(): void
    {
        Setting::updateOrCreate(['key' => 'app_name'], ['value' => 'SIMAGANG TEST']);

        $service = app(PdfExportService::class);

        DB::enableQueryLog();
        $data1 = $service->injectKopData([]);
        $data2 = $service->injectSignatureData([]);
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        $settingQueries = collect($queries)->filter(fn ($q) => str_contains($q['query'], 'settings'));
        $this->assertLessThanOrEqual(1, $settingQueries->count(), 'Settings query should be memoized during PDF generation');
    }
}
