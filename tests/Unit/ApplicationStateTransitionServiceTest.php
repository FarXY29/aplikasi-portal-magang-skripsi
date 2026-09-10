<?php

namespace Tests\Unit;

use App\Enums\ApplicationStatus;
use App\Exceptions\CancellationPolicyException;
use App\Exceptions\InvalidApplicationStateTransitionException;
use App\Models\Application;
use App\Models\ApplicationTimeline;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use App\Services\ApplicationStateTransitionService;
use Carbon\Carbon;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * P1-2001: Formal Application State Machine & Cancellation/Withdrawal Policies Unit Tests
 */
class ApplicationStateTransitionServiceTest extends TestCase
{
    use DatabaseTransactions;

    private ApplicationStateTransitionService $service;
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

        $this->service = app(ApplicationStateTransitionService::class);

        $this->instansi = Instansi::factory()->create([
            'nama_dinas' => 'Dinas Kominfo Unit Test',
            'max_total_quota' => 10,
        ]);

        $this->position = InternshipPosition::factory()->create([
            'instansi_id' => $this->instansi->id,
            'kuota' => 5,
        ]);

        $this->adminInstansi = User::factory()->create(['instansi_id' => $this->instansi->id, 'role' => 'admin_instansi']);
        $this->adminInstansi->assignRole('admin_instansi');

        $this->peserta = User::factory()->create(['role' => 'peserta']);
        $this->peserta->assignRole('peserta');
    }

    public function test_can_transition_allows_valid_pending_transitions(): void
    {
        $this->assertTrue($this->service->canTransition('pending', 'diterima'));
        $this->assertTrue($this->service->canTransition('pending', 'ditolak'));
        $this->assertTrue($this->service->canTransition('pending', 'menunggu'));
        $this->assertTrue($this->service->canTransition('pending', 'dibatalkan'));
    }

    public function test_can_transition_allows_valid_diterima_transitions(): void
    {
        $this->assertTrue($this->service->canTransition('diterima', 'selesai'));
        $this->assertTrue($this->service->canTransition('diterima', 'dibatalkan'));
        $this->assertTrue($this->service->canTransition('diterima', 'dikeluarkan'));
    }

    public function test_can_transition_rejects_illegal_transitions(): void
    {
        // Terminal states cannot transition
        $this->assertFalse($this->service->canTransition('ditolak', 'selesai'));
        $this->assertFalse($this->service->canTransition('ditolak', 'diterima'));
        $this->assertFalse($this->service->canTransition('selesai', 'pending'));
        $this->assertFalse($this->service->canTransition('selesai', 'diterima'));
        $this->assertFalse($this->service->canTransition('dikeluarkan', 'diterima'));
        $this->assertFalse($this->service->canTransition('dibatalkan', 'diterima'));

        // Illegal jumps
        $this->assertFalse($this->service->canTransition('pending', 'selesai'));
        $this->assertFalse($this->service->canTransition('menunggu', 'selesai'));
    }

    public function test_ditolak_to_selesai_throws_invalid_transition_exception(): void
    {
        $app = Application::factory()->create([
            'user_id' => $this->peserta->id,
            'internship_position_id' => $this->position->id,
            'status' => 'ditolak',
            'tanggal_mulai' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now()->addDays(35)->format('Y-m-d'),
        ]);

        $this->expectException(InvalidApplicationStateTransitionException::class);
        $this->service->transition($app, ApplicationStatus::Selesai);
    }

    public function test_selesai_to_pending_throws_invalid_transition_exception(): void
    {
        $app = Application::factory()->create([
            'user_id' => $this->peserta->id,
            'internship_position_id' => $this->position->id,
            'status' => 'selesai',
        ]);

        $this->expectException(InvalidApplicationStateTransitionException::class);
        $this->service->transition($app, ApplicationStatus::Pending);
    }

    public function test_pending_to_diterima_transition_succeeds_and_creates_timeline(): void
    {
        $app = Application::factory()->create([
            'user_id' => $this->peserta->id,
            'internship_position_id' => $this->position->id,
            'status' => 'pending',
            'tanggal_mulai' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now()->addDays(35)->format('Y-m-d'),
        ]);

        $result = $this->service->accept($app, $this->adminInstansi->id);

        $this->assertEquals('diterima', $result->fresh()->status_value);
        $this->assertEquals($this->adminInstansi->id, $result->fresh()->verified_by);

        $this->assertDatabaseHas('application_timelines', [
            'application_id' => $app->id,
            'event' => ApplicationTimeline::EVENT_ACCEPTED,
            'old_status' => 'pending',
            'new_status' => 'diterima',
            'actor_id' => $this->adminInstansi->id,
        ]);
    }

    public function test_pending_to_ditolak_transition_succeeds_and_records_reason(): void
    {
        $app = Application::factory()->create([
            'user_id' => $this->peserta->id,
            'internship_position_id' => $this->position->id,
            'status' => 'pending',
            'tanggal_mulai' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now()->addDays(35)->format('Y-m-d'),
        ]);

        $reason = 'Kualifikasi teknis belum sesuai kriteria posisi.';
        $result = $this->service->reject($app, $reason, $this->adminInstansi->id);

        $this->assertEquals('ditolak', $result->fresh()->status_value);
        $this->assertEquals($reason, $result->fresh()->rejected_reason);

        $this->assertDatabaseHas('application_timelines', [
            'application_id' => $app->id,
            'event' => ApplicationTimeline::EVENT_REJECTED,
            'old_status' => 'pending',
            'new_status' => 'ditolak',
        ]);
    }

    public function test_cancellation_policy_allows_participant_to_cancel_pending_application(): void
    {
        $app = Application::factory()->create([
            'user_id' => $this->peserta->id,
            'internship_position_id' => $this->position->id,
            'status' => 'pending',
            'tanggal_mulai' => Carbon::now()->addDays(10)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now()->addDays(40)->format('Y-m-d'),
        ]);

        $result = $this->service->cancel($app, 'Ingin mendaftar di dinas lain', $this->peserta->id, isParticipant: true);

        $this->assertEquals('dibatalkan', $result->fresh()->status_value);
        $this->assertNotNull($result->fresh()->canceled_at);

        $this->assertDatabaseHas('application_timelines', [
            'application_id' => $app->id,
            'event' => ApplicationTimeline::EVENT_CANCELLED,
            'new_status' => 'dibatalkan',
        ]);
    }

    public function test_cancellation_policy_allows_participant_to_cancel_accepted_if_before_start_date(): void
    {
        $app = Application::factory()->create([
            'user_id' => $this->peserta->id,
            'internship_position_id' => $this->position->id,
            'status' => 'diterima',
            'tanggal_mulai' => Carbon::now()->addDays(7)->format('Y-m-d'), // future
            'tanggal_selesai' => Carbon::now()->addDays(37)->format('Y-m-d'),
        ]);

        $result = $this->service->cancel($app, 'Mengundurkan diri sebelum magang mulai', $this->peserta->id, isParticipant: true);

        $this->assertEquals('dibatalkan', $result->fresh()->status_value);
    }

    public function test_cancellation_policy_blocks_participant_from_cancelling_once_internship_starts(): void
    {
        $app = Application::factory()->create([
            'user_id' => $this->peserta->id,
            'internship_position_id' => $this->position->id,
            'status' => 'diterima',
            'tanggal_mulai' => Carbon::now()->subDays(2)->format('Y-m-d'), // already started
            'tanggal_selesai' => Carbon::now()->addDays(28)->format('Y-m-d'),
        ]);

        $this->expectException(CancellationPolicyException::class);
        $this->service->cancel($app, 'Membatalkan di tengah jalan', $this->peserta->id, isParticipant: true);
    }

    public function test_cancellation_policy_blocks_cancelling_already_final_status(): void
    {
        $app = Application::factory()->create([
            'user_id' => $this->peserta->id,
            'internship_position_id' => $this->position->id,
            'status' => 'selesai',
        ]);

        $this->expectException(CancellationPolicyException::class);
        $this->service->cancel($app, 'Batal', $this->peserta->id, isParticipant: true);
    }

    public function test_diterima_to_selesai_generates_certificate_and_completes_internship(): void
    {
        $app = Application::factory()->create([
            'user_id' => $this->peserta->id,
            'internship_position_id' => $this->position->id,
            'status' => 'diterima',
            'tanggal_mulai' => Carbon::now()->subDays(30)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now()->subDays(1)->format('Y-m-d'),
        ]);

        $result = $this->service->finish($app, $this->adminInstansi->id);

        $this->assertEquals('selesai', $result->fresh()->status_value);
        $this->assertNotEmpty($result->fresh()->nomor_sertifikat);

        $this->assertDatabaseHas('application_timelines', [
            'application_id' => $app->id,
            'event' => ApplicationTimeline::EVENT_COMPLETED,
            'new_status' => 'selesai',
        ]);
    }

    public function test_diterima_to_dikeluarkan_expels_intern(): void
    {
        $app = Application::factory()->create([
            'user_id' => $this->peserta->id,
            'internship_position_id' => $this->position->id,
            'status' => 'diterima',
            'tanggal_mulai' => Carbon::now()->subDays(10)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now()->addDays(20)->format('Y-m-d'),
        ]);

        $result = $this->service->expel($app, 'Melanggar kode etik dan tata tertib instansi.', $this->adminInstansi->id);

        $this->assertEquals('dikeluarkan', $result->fresh()->status_value);
        $this->assertEquals('Melanggar kode etik dan tata tertib instansi.', $result->fresh()->rejected_reason);

        $this->assertDatabaseHas('application_timelines', [
            'application_id' => $app->id,
            'event' => ApplicationTimeline::EVENT_EXPELLED,
            'new_status' => 'dikeluarkan',
        ]);
    }

    public function test_accept_succeeds_when_position_kuota_is_null(): void
    {
        $positionNoKuota = InternshipPosition::factory()->create([
            'instansi_id' => $this->instansi->id,
            'kuota' => null,
        ]);

        $app = Application::factory()->create([
            'user_id' => $this->peserta->id,
            'internship_position_id' => $positionNoKuota->id,
            'status' => 'pending',
            'tanggal_mulai' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now()->addDays(35)->format('Y-m-d'),
        ]);

        $result = $this->service->accept($app, $this->adminInstansi->id);
        $this->assertEquals('diterima', $result->fresh()->status_value);
    }

    public function test_cancellation_policy_allows_cancel_when_tanggal_mulai_is_null(): void
    {
        $app = Application::factory()->create([
            'user_id' => $this->peserta->id,
            'internship_position_id' => $this->position->id,
            'status' => 'pending',
            'tanggal_mulai' => null,
            'tanggal_selesai' => null,
        ]);

        $result = $this->service->cancel($app, 'Batal sebelum tanggal ditentukan', $this->peserta->id, isParticipant: true);
        $this->assertEquals('dibatalkan', $result->fresh()->status_value);
    }

    public function test_cannot_accept_into_closed_position(): void
    {
        $closedPosition = InternshipPosition::factory()->closed()->create([
            'instansi_id' => $this->instansi->id,
        ]);

        $app = Application::factory()->create([
            'user_id' => $this->peserta->id,
            'internship_position_id' => $closedPosition->id,
            'status' => 'pending',
            'tanggal_mulai' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now()->addDays(35)->format('Y-m-d'),
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Posisi magang ini sudah ditutup');
        $this->service->accept($app, $this->adminInstansi->id);
    }
}
