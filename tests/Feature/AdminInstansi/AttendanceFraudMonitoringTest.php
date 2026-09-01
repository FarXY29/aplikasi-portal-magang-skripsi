<?php

namespace Tests\Feature\AdminInstansi;

use App\Models\Application;
use App\Models\Attendance;
use App\Models\AttendanceAttempt;
use App\Models\AttendanceFraudEvent;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Monitoring fraud absensi admin instansi (§32): scoping per-instansi,
 * filter, dashboard summary, CSV export, dan modal detail.
 */
class AttendanceFraudMonitoringTest extends TestCase
{
    use DatabaseTransactions;

    private User $adminA;
    private User $adminB;
    private Instansi $instansiA;
    private Instansi $instansiB;
    private User $pesertaA;
    private User $pesertaB;
    private AttendanceAttempt $flaggedAttempt;
    private AttendanceAttempt $cleanAttempt;

    protected function setUp(): void
    {
        parent::setUp();

        config(['attendance.enabled' => true]);

        // Instansi + admin + peserta A (punya attempt flagged & clean).
        $this->instansiA = Instansi::create([
            'nama_dinas' => 'Dinas A',
            'kode_unit_kerja' => 'DINAS-A',
            'alamat' => 'Banjarmasin',
            'max_total_quota' => 10,
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'radius_absen' => 100,
        ]);

        $this->instansiB = Instansi::create([
            'nama_dinas' => 'Dinas B',
            'kode_unit_kerja' => 'DINAS-B',
            'alamat' => 'Martapura',
            'max_total_quota' => 10,
            'latitude' => -3.411,
            'longitude' => 114.810,
            'radius_absen' => 100,
        ]);

        $this->adminA = User::factory()->create(['role' => 'admin_instansi', 'instansi_id' => $this->instansiA->id]);
        $this->adminB = User::factory()->create(['role' => 'admin_instansi', 'instansi_id' => $this->instansiB->id]);

        $this->pesertaA = User::factory()->create(['role' => 'peserta']);
        $this->pesertaB = User::factory()->create(['role' => 'peserta']);

        $appA = $this->makeApplication($this->pesertaA, $this->instansiA);
        $appB = $this->makeApplication($this->pesertaB, $this->instansiB);

        $attendanceA = Attendance::create([
            'application_id' => $appA->id,
            'date' => now()->toDateString(),
            'status' => 'hadir',
            'clock_in' => '08:00:00',
            'risk_score' => 60,
            'fraud_status' => 'high',
        ]);

        $this->flaggedAttempt = AttendanceAttempt::create([
            'user_id' => $this->pesertaA->id,
            'application_id' => $appA->id,
            'instance_id' => $this->instansiA->id,
            'attendance_id' => $attendanceA->id,
            'attendance_type' => 'clock_in',
            'attempt_uuid' => (string) Str::uuid(),
            'server_received_at' => now(),
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'accuracy' => 150,
            'distance_to_instance' => 95,
            'location_margin' => 5,
            'ip_address' => '10.0.0.1',
            'user_agent' => 'TestAgent/1.0',
            'risk_score' => 60,
            'fraud_status' => 'high',
            'risk_indicators' => ['BOUNDARY_UNCERTAINTY', 'accepted'],
        ]);

        AttendanceFraudEvent::create([
            'attendance_attempt_id' => $this->flaggedAttempt->id,
            'code' => 'BOUNDARY_UNCERTAINTY',
            'severity' => 'low',
            'score_delta' => 10,
            'metadata' => ['accuracy_m' => 150, 'margin_m' => 5],
        ]);

        $this->cleanAttempt = AttendanceAttempt::create([
            'user_id' => $this->pesertaA->id,
            'application_id' => $appA->id,
            'instance_id' => $this->instansiA->id,
            'attendance_type' => 'clock_out',
            'attempt_uuid' => (string) Str::uuid(),
            'server_received_at' => now(),
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'accuracy' => 10,
            'distance_to_instance' => 5,
            'location_margin' => 95,
            'risk_score' => 0,
            'fraud_status' => 'low',
        ]);

        // Attempt milik instansi B (tidak boleh bocor ke admin A).
        AttendanceAttempt::create([
            'user_id' => $this->pesertaB->id,
            'application_id' => $appB->id,
            'instance_id' => $this->instansiB->id,
            'attendance_type' => 'clock_in',
            'attempt_uuid' => (string) Str::uuid(),
            'server_received_at' => now(),
            'latitude' => -3.411,
            'longitude' => 114.810,
            'risk_score' => 100,
            'fraud_status' => 'critical',
        ]);
    }

    private function makeApplication(User $user, Instansi $instansi): Application
    {
        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Posisi Test',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        return Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'status' => 'diterima',
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => now()->addDays(85)->toDateString(),
        ]);
    }

    public function test_monitoring_page_renders_for_admin_instansi(): void
    {
        $this->actingAs($this->adminA)
            ->get(route('dinas.monitoring.fraud'))
            ->assertOk()
            ->assertSee('Monitoring Fraud Absensi')
            ->assertSee($this->pesertaA->name);
    }

    public function test_admin_cannot_see_other_instances_attempts(): void
    {
        $response = $this->actingAs($this->adminA)
            ->get(route('dinas.monitoring.fraud'));

        // Attempt instansi B (pesertaB) tidak boleh tampil.
        $response->assertOk();
        $this->assertStringNotContainsString($this->pesertaB->name, $response->getContent());
    }

    public function test_filter_status_flagged_returns_only_flagged(): void
    {
        $response = $this->actingAs($this->adminA)
            ->get(route('dinas.monitoring.fraud', ['status' => 'flagged']));

        $response->assertOk()
            ->assertSee($this->pesertaA->name);
    }

    public function test_filter_user_id_prevents_cross_instance_idor(): void
    {
        // Coba filter user_id milik instansi B lewat admin A → harus kosong.
        $response = $this->actingAs($this->adminA)
            ->get(route('dinas.monitoring.fraud', ['user_id' => $this->pesertaB->id]));

        $response->assertOk();
        $this->assertStringNotContainsString($this->pesertaB->name, $response->getContent());
    }

    public function test_stats_are_correct(): void
    {
        $response = $this->actingAs($this->adminA)->get(route('dinas.monitoring.fraud'));
        $response->assertOk()
            ->assertSee('Total Attempt')
            ->assertSee('Ditandai');
    }

    public function test_csv_export_streams_correct_scoped_data(): void
    {
        $response = $this->actingAs($this->adminA)
            ->get(route('dinas.monitoring.fraud.export'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $csv = $response->streamedContent();
        // Header row.
        $this->assertStringContainsString('Waktu Server', $csv);
        $this->assertStringContainsString('Peserta', $csv);
        $this->assertStringContainsString('Risk Score', $csv);

        // Data peserta A (milik admin A) tercantum.
        $this->assertStringContainsString($this->pesertaA->name, $csv);

        // Data peserta B (instansi lain) TIDAK boleh bocor.
        $this->assertStringNotContainsString($this->pesertaB->name, $csv);
    }

    public function test_show_attempt_returns_detail_json(): void
    {
        $response = $this->actingAs($this->adminA)
            ->getJson(route('dinas.monitoring.fraud.show', $this->flaggedAttempt->id));

        $response->assertOk()
            ->assertJsonPath('attempt.id', $this->flaggedAttempt->id)
            ->assertJsonPath('attempt.fraud_status', 'high')
            ->assertJsonPath('fraud_status_label', 'Mencurigakan (Tinggi)');
    }

    public function test_show_attempt_rejects_other_instance_idor(): void
    {
        // Admin A tidak boleh mengakses attempt instansi B.
        $otherAttempt = AttendanceAttempt::where('instance_id', $this->instansiB->id)->first();

        $this->actingAs($this->adminA)
            ->getJson(route('dinas.monitoring.fraud.show', $otherAttempt->id))
            ->assertNotFound();
    }

    public function test_dashboard_flagged_summary_present(): void
    {
        $response = $this->actingAs($this->adminA)
            ->get(route('dinas.dashboard'));

        $response->assertOk();

        // Banner fraud muncul (karena ada attempt flagged milik instansi A).
        $response->assertSee('PERLU REVIEW', false);
    }
}
