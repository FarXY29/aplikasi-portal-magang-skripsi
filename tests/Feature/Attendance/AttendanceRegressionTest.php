<?php

namespace Tests\Feature\Attendance;

use App\Models\Application;
use App\Models\Attendance;
use App\Models\AttendanceAttempt;
use App\Models\DailyLog;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use App\Services\Attendance\AttendanceLockService;
use App\Services\Attendance\DynamicQrService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Regression test (§33): past attendance behavior TETAP berfungsi setelah
 * layer anti-fraud ditambahkan — permission flow, clock-out dependency,
 * double clock-out, attendance lama terbaca.
 */
class AttendanceRegressionTest extends TestCase
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

    private function makeApplication(): array
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Regresi',
            'kode_unit_kerja' => 'RG-01',
            'alamat' => 'Banjarmasin',
            'jam_mulai_masuk' => '00:00:00',
            'jam_mulai_pulang' => '00:00:00',
            'max_total_quota' => 10,
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'radius_absen' => 100,
        ]);

        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Fullstack Developer',
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

    public function test_full_clock_in_and_clock_out_flow(): void
    {
        // Clock-in.
        $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'accuracy' => 10,
            'client_timestamp' => now()->getTimestamp() * 1000,
            'nonce' => $this->validChallenge(),
            'idempotency_key' => 'reg-clockin',
        ])->assertSessionHas('success');

        // Clock-out.
        $this->actingAs($this->peserta)->post(route('peserta.absen.pulang'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'accuracy' => 10,
            'client_timestamp' => now()->getTimestamp() * 1000,
            'nonce' => $this->validChallenge(),
            'idempotency_key' => 'reg-clockout',
        ])->assertSessionHas('success');

        $attendance = Attendance::where('application_id', $this->appSetup['application']->id)->first();

        $this->assertNotNull($attendance->clock_in);
        $this->assertNotNull($attendance->clock_out);

        // Jam dari SERVER (Asia/Makassar), bukan client — toleran boundary
        // menit (POST dan assert bisa berbeda beberapa detik).
        $clockInMinute = \Carbon\Carbon::parse($attendance->clock_in);
        $this->assertTrue(
            abs($clockInMinute->diffInSeconds(now())) <= 120,
            "clock_in ({$attendance->clock_in}) seharusnya waktu server terkini"
        );
    }

    public function test_clock_out_without_clock_in_rejected(): void
    {
        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.pulang'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $this->validChallenge(),
        ]);

        $response->assertSessionHas('error');
    }

    public function test_double_clock_out_rejected(): void
    {
        $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694, 'longitude' => 114.590111,
            'nonce' => $this->validChallenge(), 'idempotency_key' => 'dbl-1',
        ])->assertSessionHas('success');

        $this->actingAs($this->peserta)->post(route('peserta.absen.pulang'), [
            'latitude' => -3.316694, 'longitude' => 114.590111,
            'nonce' => $this->validChallenge(), 'idempotency_key' => 'dbl-2',
        ])->assertSessionHas('success');

        // Clock-out kedua → ditolak.
        $this->actingAs($this->peserta)->post(route('peserta.absen.pulang'), [
            'latitude' => -3.316694, 'longitude' => 114.590111,
            'nonce' => $this->validChallenge(), 'idempotency_key' => 'dbl-3',
        ])->assertSessionHas('error');

        // clock_out tidak berubah (tetap satu).
        $attendance = Attendance::where('application_id', $this->appSetup['application']->id)->first();
        $firstClockOut = $attendance->clock_out;
        $this->assertNotNull($firstClockOut);
    }

    public function test_duplicate_daily_attendance_rejected(): void
    {
        $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694, 'longitude' => 114.590111,
            'nonce' => $this->validChallenge(), 'idempotency_key' => 'dup-1',
        ])->assertSessionHas('success');

        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694, 'longitude' => 114.590111,
            'nonce' => $this->validChallenge(), 'idempotency_key' => 'dup-2',
        ]);

        $response->assertSessionHas('error');
        $this->assertSame(1, Attendance::where(
            'application_id', $this->appSetup['application']->id
        )->count());
    }

    public function test_permission_flow_still_works(): void
    {
        // Izin flow tidak tersentuh layer GPS fraud (§30).
        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.izin'), [
            'status' => 'izin',
            'description' => 'Keperluan akademik kampus',
            'proof_file' => UploadedFile::fake()->image('surat.png'),
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('attendances', [
            'application_id' => $this->appSetup['application']->id,
            'status' => 'izin',
            'validation_status' => 'pending',
        ]);
    }

    public function test_legacy_attendance_without_fraud_fields_still_readable(): void
    {
        // Simulasi attendance lama (sebelum kolom fraud ada) — tanpa risk_score.
        $legacy = Attendance::create([
            'application_id' => $this->appSetup['application']->id,
            'date' => now()->subDay()->toDateString(),
            'status' => 'hadir',
            'clock_in' => '07:45:00',
            'clock_out' => '16:30:00',
            'latitude_in' => -3.316694,
            'longitude_in' => 114.590111,
            'validation_status' => 'approved',
        ]);

        $this->assertNull($legacy->fresh()->risk_score);
        $this->assertNull($legacy->fresh()->fraud_status);

        // Halaman riwayat absensi peserta tetap terbaca (format view existing).
        $this->actingAs($this->peserta)
            ->get(route('peserta.absensi.index'))
            ->assertOk()
            ->assertSee(\Carbon\Carbon::parse($legacy->date)->translatedFormat('d M Y'));
    }

    public function test_attendance_history_page_renders_with_mixed_records(): void
    {
        // Record lama + record baru (dengan fraud fields) berdampingan.
        Attendance::create([
            'application_id' => $this->appSetup['application']->id,
            'date' => now()->subDays(2)->toDateString(),
            'status' => 'hadir',
            'clock_in' => '08:00:00',
            'validation_status' => 'approved',
        ]);

        $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694, 'longitude' => 114.590111,
            'nonce' => $this->validChallenge(), 'idempotency_key' => 'mixed-1',
        ])->assertSessionHas('success');

        $this->actingAs($this->peserta)
            ->get(route('peserta.absensi.index'))
            ->assertOk();
    }

    public function test_clock_in_before_internship_starts_rejected(): void
    {
        $this->appSetup['application']->update([
            'tanggal_mulai' => now()->addDays(5)->toDateString(),
        ]);

        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $this->validChallenge(),
        ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('Masa magang Anda belum dimulai', (string) session('error'));
    }

    public function test_clock_in_after_internship_ends_rejected(): void
    {
        $this->appSetup['application']->update([
            'tanggal_selesai' => now()->subDays(5)->toDateString(),
        ]);

        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $this->validChallenge(),
        ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('Masa magang Anda telah berakhir', (string) session('error'));
    }

    public function test_clock_in_before_schedule_window_rejected(): void
    {
        $this->appSetup['instansi']->update([
            'jam_mulai_masuk' => '23:59:59',
        ]);

        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $this->validChallenge(),
        ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('Absen datang belum dibuka', (string) session('error'));
    }

    public function test_clock_out_before_schedule_window_rejected(): void
    {
        // First clock in successfully
        $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $this->validChallenge(),
            'idempotency_key' => 'sched-in',
        ])->assertSessionHas('success');

        // Set pulang time in the future
        $this->appSetup['instansi']->update([
            'jam_mulai_pulang' => '23:59:59',
        ]);

        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.pulang'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $this->validChallenge(),
            'idempotency_key' => 'sched-out',
        ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('Belum waktunya pulang', (string) session('error'));
    }

    public function test_missing_gps_rejected_when_geofence_configured(): void
    {
        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'nonce' => $this->validChallenge(),
        ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('Lokasi GPS Anda tidak ditemukan', (string) session('error'));
    }

    public function test_outside_geofence_rejected(): void
    {
        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.500000,
            'longitude' => 114.800000,
            'nonce' => $this->validChallenge(),
        ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('di luar radius kantor', (string) session('error'));
    }

    public function test_invalid_or_expired_nonce_rejected(): void
    {
        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => 'invalid-expired-nonce-string',
        ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('Sesi keamanan absensi tidak valid atau sudah kedaluwarsa', (string) session('error'));
    }

    public function test_reused_nonce_rejected(): void
    {
        $nonce = $this->validChallenge();

        // 1st request with nonce succeeds
        $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $nonce,
            'idempotency_key' => 'nonce-use-1',
        ])->assertSessionHas('success');

        // 2nd request reusing the same nonce is rejected
        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.pulang'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $nonce,
            'idempotency_key' => 'nonce-use-2',
        ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('Sesi keamanan absensi tidak valid atau sudah kedaluwarsa', (string) session('error'));
    }

    public function test_duplicate_click_returns_cached_idempotent_result(): void
    {
        $idemKey = 'idem-duplicate-test-key';
        $nonce1 = $this->validChallenge();

        $resp1 = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $nonce1,
            'idempotency_key' => $idemKey,
        ]);
        $resp1->assertSessionHas('success');

        // Second click with same idempotency key
        $resp2 = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $this->validChallenge(),
            'idempotency_key' => $idemKey,
        ]);
        $resp2->assertSessionHas('success', 'Berhasil Absen Datang! Selamat beraktivitas.');

        $this->assertSame(1, Attendance::where('application_id', $this->appSetup['application']->id)->count());
    }

    public function test_concurrent_lock_prevents_simultaneous_processing(): void
    {
        $lockService = app(AttendanceLockService::class);
        $acquired = $lockService->acquire($this->peserta);
        $this->assertTrue($acquired);

        try {
            $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
                'latitude' => -3.316694,
                'longitude' => 114.590111,
                'nonce' => $this->validChallenge(),
            ]);

            $response->assertSessionHas('error');
            $this->assertStringContainsString('sedang diproses', (string) session('error'));
        } finally {
            $lockService->release($this->peserta);
        }
    }

    public function test_dynamic_qr_validation_scenarios(): void
    {
        $this->appSetup['instansi']->update(['qr_absensi_enabled' => true]);
        $qrService = app(DynamicQrService::class);

        // 1. Missing token
        $resp1 = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $this->validChallenge(),
        ]);
        $resp1->assertSessionHas('error');
        $this->assertStringContainsString('mewajibkan scan Dynamic QR', (string) session('error'));

        // 2. Expired token (90 seconds ago)
        $expiredToken = $qrService->generateTokenData($this->appSetup['instansi'], now()->getTimestamp() - 90)['token'];
        $resp2 = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $this->validChallenge(),
            'qr_token' => $expiredToken,
        ]);
        $resp2->assertSessionHas('error');
        $this->assertStringContainsString('sudah kedaluwarsa', (string) session('error'));

        // 3. Wrong instansi token
        $otherInstansi = Instansi::create([
            'nama_dinas' => 'Dinas Lain',
            'kode_unit_kerja' => 'LN-01',
            'alamat' => 'Banjarmasin',
            'jam_mulai_masuk' => '00:00:00',
            'jam_mulai_pulang' => '00:00:00',
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'radius_absen' => 100,
        ]);
        $otherToken = $qrService->generateTokenData($otherInstansi, now()->getTimestamp())['token'];

        $resp3 = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $this->validChallenge(),
            'qr_token' => $otherToken,
        ]);
        $resp3->assertSessionHas('error');
        $this->assertStringContainsString('milik kantor instansi lain', (string) session('error'));
    }

    public function test_suspicious_device_telemetry_records_fraud_signals_and_decision(): void
    {
        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'accuracy' => 1500, // Highly inaccurate / suspicious
            'speed' => 150,     // Impossibly high speed (150 m/s)
            'nonce' => $this->validChallenge(),
            'idempotency_key' => 'suspicious-telemetry-1',
        ]);

        $response->assertSessionHas('success');

        $attempt = AttendanceAttempt::where('user_id', $this->peserta->id)
            ->where('attendance_type', 'clock_in')
            ->latest()
            ->first();

        $this->assertNotNull($attempt);
        $this->assertGreaterThan(0, (int) $attempt->risk_score);
        $this->assertIsArray($attempt->risk_indicators);
        $this->assertArrayHasKey('decision', $attempt->risk_indicators);
    }

    public function test_database_level_unique_constraint_enforced_on_attendances_table(): void
    {
        $date = '2026-09-01';

        Attendance::create([
            'application_id' => $this->appSetup['application']->id,
            'date' => $date,
            'status' => 'hadir',
            'clock_in' => '08:00:00',
            'validation_status' => 'approved',
        ]);

        $this->expectException(QueryException::class);

        // Attempting direct database insert with duplicate (application_id, date) MUST violate DB constraint
        Attendance::create([
            'application_id' => $this->appSetup['application']->id,
            'date' => $date,
            'status' => 'hadir',
            'clock_in' => '08:05:00',
            'validation_status' => 'approved',
        ]);
    }

    public function test_database_level_unique_constraint_enforced_on_daily_logs_table(): void
    {
        $date = '2026-09-01';

        DailyLog::create([
            'application_id' => $this->appSetup['application']->id,
            'tanggal' => $date,
            'kegiatan' => 'Kegiatan Hari Ini Pertama',
            'bukti_foto_path' => '-',
            'status_validasi' => 'pending',
        ]);

        $this->expectException(QueryException::class);

        // Attempting direct database insert with duplicate (application_id, tanggal) MUST violate DB constraint
        DailyLog::create([
            'application_id' => $this->appSetup['application']->id,
            'tanggal' => $date,
            'kegiatan' => 'Kegiatan Hari Ini Kedua (Duplikat)',
            'bukti_foto_path' => '-',
            'status_validasi' => 'pending',
        ]);
    }

    public function test_permission_duplicate_rejected_and_handled_gracefully(): void
    {
        $resp1 = $this->actingAs($this->peserta)->post(route('peserta.absen.izin'), [
            'status' => 'izin',
            'description' => 'Izin pertama',
            'proof_file' => UploadedFile::fake()->image('surat1.png'),
        ]);
        $resp1->assertSessionHas('success');

        // Pengajuan izin kedua di hari yang sama
        $resp2 = $this->actingAs($this->peserta)->post(route('peserta.absen.izin'), [
            'status' => 'izin',
            'description' => 'Izin kedua di hari yang sama',
            'proof_file' => UploadedFile::fake()->image('surat2.png'),
        ]);
        $resp2->assertSessionHas('error');
        $this->assertStringContainsString('sudah mengisi data absensi/izin hari ini', (string) session('error'));

        $this->assertSame(1, Attendance::where('application_id', $this->appSetup['application']->id)->count());
    }

    public function test_malformed_gps_coordinates_rejected_by_validation(): void
    {
        // 1. Non-numeric latitude
        $resp1 = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => 'bukan-angka-valid',
            'longitude' => 114.590111,
            'nonce' => $this->validChallenge(),
        ]);
        $resp1->assertSessionHasErrors('latitude');

        // 2. Latitude out of range (-90 to 90)
        $resp2 = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => 125.500000,
            'longitude' => 114.590111,
            'nonce' => $this->validChallenge(),
        ]);
        $resp2->assertSessionHasErrors('latitude');

        // 3. Longitude out of range (-180 to 180)
        $resp3 = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 200.000000,
            'nonce' => $this->validChallenge(),
        ]);
        $resp3->assertSessionHasErrors('longitude');
    }

    public function test_tampered_or_invalid_qr_signature_rejected(): void
    {
        $this->appSetup['instansi']->update(['qr_absensi_enabled' => true]);

        // Tampered token payload with invalid signature
        $tamperedPayload = [
            'i' => (int) $this->appSetup['instansi']->id,
            's' => (int) floor(time() / 30),
            'sig' => 'tampered-or-invalid-signature-hash-value',
        ];
        $tamperedToken = base64_encode(json_encode($tamperedPayload));

        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $this->validChallenge(),
            'qr_token' => $tamperedToken,
        ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('Kode Dynamic QR tidak valid atau rusak', (string) session('error'));
    }

    public function test_double_tab_or_rapid_duplicate_requests_without_shared_idempotency_handled(): void
    {
        // First request succeeds
        $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $this->validChallenge(),
            'idempotency_key' => 'tab-1-key',
        ])->assertSessionHas('success');

        // Second tab without idempotency key or different key
        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $this->validChallenge(),
            'idempotency_key' => 'tab-2-key',
        ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('Anda sudah mengisi data absensi hari ini', (string) session('error'));
        $this->assertSame(1, Attendance::where('application_id', $this->appSetup['application']->id)->count());
    }

    public function test_permission_file_cleaned_up_on_duplicate_or_failure(): void
    {
        Storage::fake('private');

        // 1st permission succeeds
        $file1 = UploadedFile::fake()->image('surat_pertama.png');
        $this->actingAs($this->peserta)->post(route('peserta.absen.izin'), [
            'status' => 'izin',
            'description' => 'Izin pertama',
            'proof_file' => $file1,
        ])->assertSessionHas('success');

        $firstAttendance = Attendance::where('application_id', $this->appSetup['application']->id)->first();
        Storage::disk('private')->assertExists($firstAttendance->proof_file);

        // 2nd permission fails (duplicate) - the uploaded file must NOT remain on disk
        $file2 = UploadedFile::fake()->image('surat_kedua_duplicate.png');
        $this->actingAs($this->peserta)->post(route('peserta.absen.izin'), [
            'status' => 'izin',
            'description' => 'Izin kedua duplicate',
            'proof_file' => $file2,
        ])->assertSessionHas('error');

        // Only 1 file should exist in the directory
        $allFiles = Storage::disk('private')->allFiles('documents/attendance');
        $this->assertCount(1, $allFiles);
        $this->assertSame([$firstAttendance->proof_file], $allFiles);
    }

    public function test_is_duplicate_entry_helper_detects_all_driver_unique_violations(): void
    {
        $controller = app(\App\Http\Controllers\AttendanceController::class);

        // 1. MySQL duplicate entry error (code string "23000", errorInfo[1] = 1062)
        $pdoException = new \PDOException("SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '1-2026-09-01' for key 'attendances_application_id_date_unique'", 23000);
        $pdoException->errorInfo = ['23000', 1062, "Duplicate entry '1-2026-09-01' for key 'attendances_application_id_date_unique'"];
        $mySqlDuplicate = new QueryException('mysql', 'INSERT INTO attendances...', [], $pdoException);
        $this->assertTrue($controller->isDuplicateEntry($mySqlDuplicate));

        // 2. PostgreSQL unique violation (code string "23505")
        $pgPdo = new \PDOException("SQLSTATE[23505]: Unique violation: 7 ERROR: duplicate key value violates unique constraint", 23505);
        $pgPdo->errorInfo = ['23505', 7, 'duplicate key value violates unique constraint'];
        $pgDuplicate = new QueryException('pgsql', 'INSERT INTO attendances...', [], $pgPdo);
        $this->assertTrue($controller->isDuplicateEntry($pgDuplicate));

        // 3. SQLite unique violation
        $sqlitePdo = new \PDOException("SQLSTATE[23000]: Integrity constraint violation: 19 UNIQUE constraint failed: attendances.application_id, attendances.date", 23000);
        $sqlitePdo->errorInfo = ['23000', 19, 'UNIQUE constraint failed: attendances.application_id, attendances.date'];
        $sqliteDuplicate = new QueryException('sqlite', 'INSERT INTO attendances...', [], $sqlitePdo);
        $this->assertTrue($controller->isDuplicateEntry($sqliteDuplicate));

        // 4. Non-duplicate QueryException (e.g. foreign key or column not found)
        $fkPdo = new \PDOException("SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: a foreign key constraint fails", 23000);
        $fkPdo->errorInfo = ['23000', 1452, 'Cannot add or update a child row: a foreign key constraint fails'];
        $fkException = new QueryException('mysql', 'INSERT INTO attendances...', [], $fkPdo);
        $this->assertFalse($controller->isDuplicateEntry($fkException));
    }
}

