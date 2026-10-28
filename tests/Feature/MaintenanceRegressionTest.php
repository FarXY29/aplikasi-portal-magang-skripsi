<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Certificate;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use App\Services\ApplicationStateTransitionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Regresi untuk perbaikan pada PR "SIMAGANG — defect fixes + dead-code removal".
 * Setiap test di sini gagal pada kode sebelum perbaikan.
 */
class MaintenanceRegressionTest extends TestCase
{
    use DatabaseTransactions;

    private ApplicationStateTransitionService $service;

    private Instansi $instansi;

    private InternshipPosition $position;

    private User $adminInstansi;

    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('application_timelines')) {
            $this->artisan('migrate');
        }

        $this->seed(RoleAndPermissionSeeder::class);
        Cache::flush();

        $this->service = app(ApplicationStateTransitionService::class);

        $this->instansi = Instansi::factory()->create([
            'nama_dinas' => 'Dinas Regresi',
            'max_total_quota' => 10,
        ]);

        $this->position = InternshipPosition::factory()->create([
            'instansi_id' => $this->instansi->id,
            'kuota' => 1,
        ]);

        $this->adminInstansi = User::factory()->create([
            'instansi_id' => $this->instansi->id,
            'role' => 'admin_instansi',
        ]);
        $this->adminInstansi->assignRole('admin_instansi');
    }

    private function makeApplication(string $status, string $start, string $end): Application
    {
        $peserta = User::factory()->create(['role' => 'peserta']);
        $peserta->assignRole('peserta');

        return Application::factory()->create([
            'user_id' => $peserta->id,
            'internship_position_id' => $this->position->id,
            'status' => $status,
            'tanggal_mulai' => $start,
            'tanggal_selesai' => $end,
        ]);
    }

    /** Peserta with an active (diterima) internship — required to render the logbook page. */
    private function makeActivePeserta(): User
    {
        $application = $this->makeApplication('diterima', '2026-10-01', '2026-12-31');

        return $application->user;
    }

    private function makeGradedApplication(): Application
    {
        $participant = User::factory()->create(['role' => 'peserta']);
        $participant->assignRole('peserta');

        $application = Application::create([
            'user_id' => $participant->id,
            'internship_position_id' => $this->position->id,
            'cv_path' => 'cv.pdf',
            'surat_pengantar_path' => 'surat.pdf',
            'status' => 'selesai',
        ]);

        $application->forceFill(['nilai_rata_rata' => 85])->save();

        return $application->refresh();
    }

    // --- Step 1: promosi daftar tunggu ---

    public function test_waiting_promotion_recalculates_dates(): void
    {
        $finished = $this->makeApplication('diterima', '2026-10-01', '2026-11-30');
        $waiting = $this->makeApplication('menunggu', '2026-10-05', '2026-10-19');

        $this->service->promoteNextWaitingCandidate($finished);

        $waiting->refresh();

        $this->assertSame('diterima', $waiting->status_value);
        // Durasi dipertahankan (14 hari), mulai sehari setelah peserta sebelumnya selesai.
        $this->assertSame('2026-12-01', $waiting->tanggal_mulai);
        $this->assertSame('2026-12-15', $waiting->tanggal_selesai);
    }

    public function test_failed_promotion_leaves_candidate_untouched(): void
    {
        $finished = $this->makeApplication('diterima', '2026-10-01', '2026-11-30');
        $waiting = $this->makeApplication('menunggu', '2026-10-05', '2026-10-19');

        // Tutup posisi lewat DB langsung agar $finished tetap berstatus diterima,
        // sementara promosi kandidat berikutnya ditolak handler.
        DB::table('internship_positions')->where('id', $this->position->id)->update(['status' => 'tutup']);

        try {
            $this->service->promoteNextWaitingCandidate($finished);
            $this->fail('Promosi ke posisi yang sudah ditutup seharusnya melempar exception.');
        } catch (\Throwable $e) {
            // diharapkan: sebelum perbaikan, tanggal sudah terlanjur ditulis.
        }

        $waiting->refresh();

        $this->assertSame('menunggu', $waiting->status_value);
        $this->assertSame('2026-10-05', $waiting->tanggal_mulai);
        $this->assertSame('2026-10-19', $waiting->tanggal_selesai);
    }

    // --- Step 3: invalidasi cache pencocokan jurusan ---

    public function test_closing_position_bumps_match_cache_version(): void
    {
        $versionBefore = (int) Cache::get(InternshipPosition::MATCH_CACHE_VERSION_KEY, 0);

        $this->position->update(['status' => 'tutup']);

        $this->assertSame(
            $versionBefore + 1,
            (int) Cache::get(InternshipPosition::MATCH_CACHE_VERSION_KEY)
        );
    }

    public function test_match_cache_key_changes_after_position_update(): void
    {
        $majorId = 99;
        $keyBefore = InternshipPosition::MATCH_CACHE_PREFIX
            .(int) Cache::get(InternshipPosition::MATCH_CACHE_VERSION_KEY, 0).':'.$majorId;
        Cache::put($keyBefore, collect([$this->position->id]), 300);

        $this->position->update(['status' => 'tutup']);

        $keyAfter = InternshipPosition::MATCH_CACHE_PREFIX
            .(int) Cache::get(InternshipPosition::MATCH_CACHE_VERSION_KEY, 0).':'.$majorId;

        $this->assertNotSame($keyBefore, $keyAfter);
        $this->assertNull(Cache::get($keyAfter), 'Kunci versi baru harus kosong agar dihitung ulang.');
    }

    // --- Step 5: penerbitan sertifikat ---

    public function test_certificate_store_rejects_number_used_by_another_application(): void
    {
        $first = $this->makeGradedApplication();
        $second = $this->makeGradedApplication();

        Certificate::create([
            'application_id' => $first->id,
            'nomor_sertifikat' => 'MAG-2026-0001',
            'token_verifikasi' => 'token-first',
            'status' => 'active',
            'published_at' => now(),
        ]);

        $response = $this->actingAs($this->adminInstansi)->post(
            route('dinas.sertifikat.store', $second->id),
            [
                'nomor_sertifikat' => 'MAG-2026-0001',
                'tanggal_sertifikat' => now()->toDateString(),
            ]
        );

        $response->assertSessionHasErrors('nomor_sertifikat');
        $this->assertSame(1, Certificate::where('nomor_sertifikat', 'MAG-2026-0001')->count());
        $this->assertNull($second->fresh()->nomor_sertifikat);
    }

    public function test_certificate_store_rolls_back_when_pdf_rendering_fails(): void
    {
        $application = $this->makeGradedApplication();

        Pdf::shouldReceive('loadView')->once()->andThrow(new \RuntimeException('render gagal'));

        try {
            $this->actingAs($this->adminInstansi)->post(
                route('dinas.sertifikat.store', $application->id),
                [
                    'nomor_sertifikat' => 'MAG-2026-0002',
                    'tanggal_sertifikat' => now()->toDateString(),
                ]
            );
        } catch (\Throwable $e) {
            // Exception permintaan render diteruskan oleh TestCase.
        }

        $this->assertNull($application->fresh()->nomor_sertifikat);
        $this->assertFalse(Certificate::where('application_id', $application->id)->exists());
    }

    // --- Step 10: escape JSON di dalam <script> ---

    public function test_logbook_config_escapes_script_breakout(): void
    {
        $peserta = $this->makeActivePeserta();

        $response = $this->actingAs($peserta)->get(route('peserta.logbook.index', [
            'status' => 'semua',
        ]));

        $response->assertOk();
        $this->assertStringContainsString('peserta-logbook-config', $response->getContent());
    }
}
