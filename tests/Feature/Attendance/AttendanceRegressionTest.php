<?php

namespace Tests\Feature\Attendance;

use App\Models\Application;
use App\Models\Attendance;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
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
}
