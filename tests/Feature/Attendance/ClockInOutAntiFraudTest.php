<?php

namespace Tests\Feature\Attendance;

use App\Models\Application;
use App\Models\Attendance;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Test anti-fraud flow clock-in/clock-out: geofence, idempotency,
 * duplicate concurrent, timestamp anomaly, impossible travel,
 * shadow vs enforce mode, dan attempt audit (§33).
 */
class ClockInOutAntiFraudTest extends TestCase
{
    use DatabaseTransactions;

    private User $peserta;
    private array $appSetup;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'attendance.enabled' => true,
            'require_nonce' => true,
            'mode' => 'shadow',
            'challenge_rate_limit' => 100,
            'clock_rate_limit' => 100,
        ]);

        $this->peserta = User::factory()->create(['role' => 'peserta']);
        $this->appSetup = $this->makeApplication();
    }

    private function makeApplication(array $instansiOverrides = []): array
    {
        $instansi = Instansi::create(array_merge([
            'nama_dinas' => 'Dinas Test Fraud',
            'kode_unit_kerja' => 'FR-01',
            'alamat' => 'Banjarmasin',
            'jam_mulai_masuk' => '00:00:00',
            'jam_mulai_pulang' => '00:00:00',
            'max_total_quota' => 10,
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'radius_absen' => 100,
        ], $instansiOverrides));

        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'QA Engineer',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $application = Application::create([
            'user_id' => $this->peserta->id,
            'internship_position_id' => $position->id,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'status' => 'diterima',
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => now()->addDays(85)->toDateString(),
        ]);

        return compact('instansi', 'position', 'application');
    }

    private function validChallenge(): string
    {
        return $this->actingAs($this->peserta)
            ->getJson(route('peserta.absensi.challenge'))
            ->json('nonce');
    }

    private function clockIn(array $overrides = [])
    {
        $payload = array_merge([
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'accuracy' => 10,
            'client_timestamp' => now()->getTimestamp() * 1000,
            'nonce' => $this->validChallenge(),
            'idempotency_key' => 'key-' . uniqid(),
        ], $overrides);

        return $this->actingAs($this->peserta)
            ->post(route('peserta.absen.masuk'), $payload);
    }

    // -----------------------------------------------------------------
    // Geofence (existing behavior tetap bekerja)
    // -----------------------------------------------------------------

    public function test_geofence_inside_radius_succeeds(): void
    {
        $this->clockIn()->assertSessionHas('success');
        $this->assertDatabaseHas('attendances', [
            'application_id' => $this->appSetup['application']->id,
            'status' => 'hadir',
        ]);
    }

    public function test_geofence_outside_radius_rejected(): void
    {
        // ±1 km dari kantor (radius 100m).
        $response = $this->clockIn([
            'latitude' => -3.3257,
            'longitude' => 114.590111,
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('attendances', [
            'application_id' => $this->appSetup['application']->id,
        ]);
    }

    public function test_geofence_boundary_edge_succeeds(): void
    {
        // ±90 m — masih dalam radius 100 m.
        $this->clockIn([
            'latitude' => -3.316694 + 0.0008, // ±89 m
        ])->assertSessionHas('success');
    }

    public function test_missing_coordinates_rejected_when_geofence_active(): void
    {
        // §6: user tidak boleh menghindari geofence dengan menghilangkan lat/lng.
        $response = $this->clockIn([
            'latitude' => null,
            'longitude' => null,
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('attendances', [
            'application_id' => $this->appSetup['application']->id,
        ]);
    }

    public function test_invalid_coordinate_rejected_by_validation(): void
    {
        $response = $this->clockIn(['latitude' => 999]);

        $response->assertSessionHasErrors('latitude');
    }

    public function test_instansi_without_geofence_still_allows_attendance(): void
    {
        // Instansi utama TANPA koordinat — absen tetap boleh (behavior existing).
        $this->appSetup['instansi']->update(['latitude' => null, 'longitude' => null]);

        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'nonce' => $this->validChallenge(),
            'idempotency_key' => 'no-geo-1',
        ]);

        $response->assertSessionHas('success');
    }

    // -----------------------------------------------------------------
    // Idempotency & duplicate
    // -----------------------------------------------------------------

    public function test_idempotent_duplicate_request_returns_previous_result(): void
    {
        $challenge = $this->validChallenge();
        $key = 'dup-key-1';

        $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $challenge,
            'idempotency_key' => $key,
        ])->assertSessionHas('success');

        // Request kedua dengan key sama → hasil sebelumnya (bukan error duplicate).
        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $this->validChallenge(),
            'idempotency_key' => $key,
        ]);

        $response->assertSessionHas('success');

        // Hanya 1 record attendance.
        $this->assertSame(1, Attendance::where(
            'application_id', $this->appSetup['application']->id
        )->count());
    }

    public function test_concurrent_duplicate_clock_in_creates_single_record(): void
    {
        // Simulasi race: 2 request paralel melewati pre-check (double-click).
        $application = $this->appSetup['application'];

        $challengeA = $this->validChallenge();
        $challengeB = $this->validChallenge();

        // Pre-check bypass: insert manual terlebih dulu (unique index bertahan).
        Attendance::create([
            'application_id' => $application->id,
            'date' => now()->toDateString(),
            'status' => 'hadir',
            'clock_in' => now()->format('H:i:s'),
            'latitude_in' => -3.316694,
            'longitude_in' => 114.590111,
            'validation_status' => 'approved',
        ]);

        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $challengeA,
            'idempotency_key' => 'race-1',
        ]);

        // Race → pesan ramah "sudah absen", bukan HTTP 500.
        $response->assertSessionHas('error');
        $this->assertSame(1, Attendance::where('application_id', $application->id)->count());
    }

    // -----------------------------------------------------------------
    // Timestamp anomaly & fraud signals (shadow mode: catat, tidak blokir)
    // -----------------------------------------------------------------

    public function test_normal_client_timestamp_creates_low_risk_attempt(): void
    {
        $this->clockIn()->assertSessionHas('success');

        $attempt = \App\Models\AttendanceAttempt::where('user_id', $this->peserta->id)
            ->where('attendance_type', 'clock_in')
            ->latest('id')->first();

        $this->assertNotNull($attempt);
        $this->assertSame(0, $attempt->risk_score);
        $this->assertSame('low', $attempt->fraud_status);
    }

    public function test_stale_client_timestamp_recorded_as_drift_event(): void
    {
        // Client timestamp 10 menit lampau → CLIENT_TIME_DRIFT +10 (shadow catat).
        $this->clockIn([
            'client_timestamp' => (now()->getTimestamp() - 600) * 1000,
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('attendance_fraud_events', [
            'code' => 'CLIENT_TIME_DRIFT',
        ]);

        $attempt = \App\Models\AttendanceAttempt::where('user_id', $this->peserta->id)
            ->where('attendance_type', 'clock_in')->latest('id')->first();
        $this->assertSame(10, $attempt->risk_score);
    }

    public function test_future_client_timestamp_recorded_as_future_event(): void
    {
        $this->clockIn([
            'client_timestamp' => (now()->getTimestamp() + 600) * 1000,
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('attendance_fraud_events', [
            'code' => 'FUTURE_CLIENT_TIMESTAMP',
        ]);
    }

    public function test_impossible_travel_detected_and_recorded(): void
    {
        // Attendance sebelumnya: clock-in jam 07:30 hari yang sama, di Jakarta.
        $application = $this->appSetup['application'];

        Attendance::create([
            'application_id' => $application->id,
            'date' => now()->toDateString(),
            'status' => 'hadir',
            'clock_in' => now()->subMinutes(10)->format('H:i:s'),
            'latitude_in' => -6.2088,   // Jakarta
            'longitude_in' => 106.8456,
            'validation_status' => 'approved',
        ]);

        // Clock-out sekarang dari Banjarmasin (±950 km dalam 10 menit).
        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.pulang'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'accuracy' => 10,
            'client_timestamp' => now()->getTimestamp() * 1000,
            'nonce' => $this->validChallenge(),
            'idempotency_key' => 'travel-1',
        ]);

        // Shadow mode: attendance TETAP diproses, tetapi event tercatat.
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('attendance_fraud_events', [
            'code' => 'IMPOSSIBLE_TRAVEL',
        ]);

        // Audit log impossible travel tercatat (§31).
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'attendance.impossible_travel',
        ]);
    }

    public function test_boundary_uncertainty_recorded_near_radius_with_poor_accuracy(): void
    {
        // radius 100m, distance ±95m, accuracy 150m → margin 5 < 150 → signal.
        $this->clockIn([
            'latitude' => -3.316694 + 0.00085, // ±94 m
            'accuracy' => 150,
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('attendance_fraud_events', [
            'code' => 'BOUNDARY_UNCERTAINTY',
        ]);
    }

    public function test_high_accuracy_bad_gps_recorded(): void
    {
        // Accuracy 250m → ACCURACY_VERY_HIGH +20 (shadow catat).
        $this->clockIn(['accuracy' => 250])->assertSessionHas('success');

        $this->assertDatabaseHas('attendance_fraud_events', [
            'code' => 'ACCURACY_VERY_HIGH',
        ]);
    }

    // -----------------------------------------------------------------
    // Enforce mode
    // -----------------------------------------------------------------

    public function test_enforce_mode_blocks_replayed_nonce(): void
    {
        config(['attendance.mode' => 'enforce']);

        $challenge = $this->validChallenge();

        // Konsumsi nonce valid pertama (clock-in sukses).
        $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $challenge,
            'idempotency_key' => 'enforce-1',
        ])->assertSessionHas('success');

        Attendance::where('application_id', $this->appSetup['application']->id)->delete();

        // Replay nonce sama → blocked di enforce.
        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $challenge,
            'idempotency_key' => 'enforce-2',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('attendances', [
            'application_id' => $this->appSetup['application']->id,
        ]);

        // Audit blocked tercatat.
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'attendance.request.replayed',
        ]);
    }

    public function test_shadow_mode_never_blocks_even_critical(): void
    {
        $challenge = $this->validChallenge();

        $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $challenge,
        ])->assertSessionHas('success');

        Attendance::where('application_id', $this->appSetup['application']->id)->delete();

        // Replay → shadow: attempt critical tercatat, TIDAK memblokir...
        $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $challenge,
        ]);

        $attempt = \App\Models\AttendanceAttempt::where('user_id', $this->peserta->id)
            ->where('attendance_type', 'clock_in')->latest('id')->first();

        $this->assertNotNull($attempt);
        $this->assertSame(100, $attempt->risk_score);
        $this->assertSame('critical', $attempt->fraud_status);
    }

    // -----------------------------------------------------------------
    // Disabled fraud layer = flow existing persis
    // -----------------------------------------------------------------

    public function test_disabled_fraud_layer_allows_legacy_flow(): void
    {
        config(['attendance.enabled' => false]);

        // Tanpa nonce, tanpa idempotency — seperti request existing.
        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
        ]);

        $response->assertSessionHas('success');

        $attendance = Attendance::where('application_id', $this->appSetup['application']->id)->first();
        $this->assertNotNull($attendance);
        $this->assertNull($attendance->risk_score); // tidak dinilai
        $this->assertNull($attendance->fraud_status);
    }
}
