<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\ApplicationTimeline;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * P1-2002: Application Timeline & History Feature Tests
 */
class ApplicationTimelineTest extends TestCase
{
    use DatabaseTransactions;

    private User $peserta;
    private User $adminInstansi;
    private Instansi $instansi;
    private InternshipPosition $position;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('application_timelines') || !Schema::hasTable('attendance_disputes')) {
            Artisan::call('migrate');
        }

        $this->seed(RoleAndPermissionSeeder::class);

        $this->instansi = Instansi::factory()->create([
            'nama_dinas' => 'Dinas Perhubungan Timeline Test',
            'max_total_quota' => 10,
        ]);

        $this->position = InternshipPosition::factory()->create([
            'instansi_id' => $this->instansi->id,
            'judul_posisi' => 'Software Engineer Intern',
            'kuota' => 5,
        ]);

        $this->adminInstansi = User::factory()->create(['instansi_id' => $this->instansi->id, 'role' => 'admin_instansi']);
        $this->adminInstansi->assignRole('admin_instansi');

        $this->peserta = User::factory()->create(['role' => 'peserta']);
        $this->peserta->assignRole('peserta');
    }

    public function test_submitting_application_creates_submitted_timeline_event(): void
    {
        Storage::fake('private');

        $suratFile = UploadedFile::fake()->create('surat_pengantar.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->peserta)->post(route('peserta.daftar', $this->position->id), [
            'letter_number' => 'SURAT/UNIV/001',
            'surat' => $suratFile,
            'tanggal_mulai' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now()->addDays(35)->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('peserta.dashboard'));

        $app = Application::where('user_id', $this->peserta->id)->firstOrFail();

        $this->assertDatabaseHas('application_timelines', [
            'application_id' => $app->id,
            'event' => ApplicationTimeline::EVENT_SUBMITTED,
            'new_status' => 'pending',
            'actor_id' => $this->peserta->id,
        ]);
    }

    public function test_application_lifecycle_records_timelines_chronologically(): void
    {
        $app = Application::factory()->create([
            'user_id' => $this->peserta->id,
            'internship_position_id' => $this->position->id,
            'status' => 'pending',
            'tanggal_mulai' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now()->addDays(35)->format('Y-m-d'),
        ]);

        $app->recordTimeline(ApplicationTimeline::EVENT_SUBMITTED, null, 'pending', [], $this->peserta->id);

        // Accept
        $this->actingAs($this->adminInstansi)
            ->post(route('dinas.pelamar.terima', $app->id));

        $this->assertDatabaseHas('application_timelines', [
            'application_id' => $app->id,
            'event' => ApplicationTimeline::EVENT_ACCEPTED,
            'old_status' => 'pending',
            'new_status' => 'diterima',
        ]);

        $timelines = $app->fresh()->timelines;
        $this->assertCount(2, $timelines);
        $this->assertEquals(ApplicationTimeline::EVENT_SUBMITTED, $timelines[0]->event);
        $this->assertEquals(ApplicationTimeline::EVENT_ACCEPTED, $timelines[1]->event);
    }

    public function test_public_tracking_exposes_application_timelines(): void
    {
        $app = Application::factory()->create([
            'user_id' => $this->peserta->id,
            'internship_position_id' => $this->position->id,
            'status' => 'diterima',
            'nomor_registrasi' => 'REG-2026-TEST01',
            'token_verifikasi' => 'token-test-12345678901234567890',
            'tanggal_mulai' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now()->addDays(35)->format('Y-m-d'),
        ]);

        $app->recordTimeline(ApplicationTimeline::EVENT_SUBMITTED, null, 'pending', [], $this->peserta->id);
        $app->recordTimeline(ApplicationTimeline::EVENT_ACCEPTED, 'pending', 'diterima', [], $this->adminInstansi->id);

        $response = $this->getJson(route('tracking.search', ['keyword' => $app->nomor_registrasi]));

        $response->assertOk();
        $response->assertJsonPath('success', true);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonCount(2, 'data.0.timelines');
        $this->assertEquals(ApplicationTimeline::EVENT_SUBMITTED, $response->json('data.0.timelines.0.event'));
        $this->assertEquals(ApplicationTimeline::EVENT_ACCEPTED, $response->json('data.0.timelines.1.event'));
    }
}
