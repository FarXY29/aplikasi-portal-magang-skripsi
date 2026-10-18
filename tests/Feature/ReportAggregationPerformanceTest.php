<?php

namespace Tests\Feature;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use App\Services\ReportService;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ReportAggregationPerformanceTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_instansi_rekap_uses_single_pass_aggregation(): void
    {
        $instansi = Instansi::factory()->create();
        $position = InternshipPosition::factory()->create(['instansi_id' => $instansi->id]);
        $user1 = User::factory()->create(['asal_instansi' => 'Universitas Lambung Mangkurat']);
        $user2 = User::factory()->create(['asal_instansi' => 'Politeknik Negeri Banjarmasin']);

        Application::factory()->create([
            'user_id' => $user1->id,
            'internship_position_id' => $position->id,
            'status' => ApplicationStatus::Diterima,
        ]);
        Application::factory()->create([
            'user_id' => $user2->id,
            'internship_position_id' => $position->id,
            'status' => ApplicationStatus::Selesai,
        ]);

        $service = app(ReportService::class);
        $request = Request::create('/dinas/laporan/rekap', 'GET');

        DB::enableQueryLog();
        $data = $service->getInstansiRekapData($request, $instansi->id, false);
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        $this->assertEquals(2, $data['stats']['total']);
        $this->assertEquals(1, $data['stats']['aktif']);
        $this->assertEquals(1, $data['stats']['selesai']);
        $this->assertEquals(0, $data['stats']['pending']);
        $this->assertEquals(2, $data['stats']['total_kampus']);

        $summaryQueries = collect($queries)->filter(fn ($q) => str_contains($q['query'], 'SUM(CASE'));
        $this->assertNotEmpty($summaryQueries, 'Instansi rekap should use single-pass conditional aggregation');
    }

    public function test_application_tracking_report_uses_single_pass_aggregation(): void
    {
        $instansi = Instansi::factory()->create();
        $position = InternshipPosition::factory()->create(['instansi_id' => $instansi->id]);
        $user = User::factory()->create();

        Application::factory()->create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'status' => ApplicationStatus::Pending,
        ]);
        Application::factory()->create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'status' => ApplicationStatus::Menunggu,
        ]);

        $service = app(ReportService::class);
        $request = Request::create('/admin/laporan/pendaftaran', 'GET');

        DB::enableQueryLog();
        $data = $service->getApplicationTrackingReportData($instansi->id, $request, false);
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        $this->assertEquals(2, $data['stats']['total']);
        $this->assertEquals(1, $data['stats']['pending']);
        $this->assertEquals(1, $data['stats']['menunggu']);

        $summaryQueries = collect($queries)->filter(fn ($q) => str_contains($q['query'], 'SUM(CASE'));
        $this->assertNotEmpty($summaryQueries, 'Tracking report should use single-pass conditional aggregation');
    }

    public function test_admin_instansi_demografi_kampus_includes_menunggu_and_restricts_aktif(): void
    {
        $admin = User::factory()->create(['role' => 'admin_instansi']);
        $admin->assignRole('admin_instansi');
        $instansi = Instansi::factory()->create();
        $admin->update(['instansi_id' => $instansi->id]);

        $position = InternshipPosition::factory()->create(['instansi_id' => $instansi->id]);

        $uActive = User::factory()->create(['asal_instansi' => 'Kampus A']);
        $uSelesai = User::factory()->create(['asal_instansi' => 'Kampus A']);
        $uMenunggu = User::factory()->create(['asal_instansi' => 'Kampus A']);

        Application::factory()->create([
            'user_id' => $uActive->id,
            'internship_position_id' => $position->id,
            'status' => ApplicationStatus::Diterima,
        ]);
        Application::factory()->create([
            'user_id' => $uSelesai->id,
            'internship_position_id' => $position->id,
            'status' => ApplicationStatus::Selesai,
        ]);
        Application::factory()->create([
            'user_id' => $uMenunggu->id,
            'internship_position_id' => $position->id,
            'status' => ApplicationStatus::Menunggu,
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('dinas.laporan.demografi_kampus'));
        $response->assertStatus(200);

        $response->assertViewHas('stats', function ($stats) {
            return $stats['total_pending'] === 1 && $stats['total_diterima'] === 2;
        });

        $response->assertViewHas('demografi', function ($demografi) {
            $kampusA = $demografi['Kampus A'];

            return $kampusA['pending'] === 1
                && count($kampusA['peserta_aktif']) === 1
                && count($kampusA['peserta']) === 2;
        });
    }
}
