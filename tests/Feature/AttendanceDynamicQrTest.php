<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\Application;
use App\Models\Attendance;
use App\Models\User;
use App\Services\Attendance\DynamicQrService;
use App\Services\Attendance\AttendanceChallengeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class AttendanceDynamicQrTest extends TestCase
{
    use RefreshDatabase;

    private User $peserta;
    private Instansi $instansi;
    private Application $application;
    private DynamicQrService $qrService;
    private AttendanceChallengeService $challengeService;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2026-09-01 08:30:00'));

        $this->instansi = Instansi::create([
            'nama_dinas' => 'Dinas Kominfo',
            'kode_unit_kerja' => 'DISKOMINFO-01',
            'alamat' => 'Jl. Pemuda No. 1',
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'radius_absen' => 100,
            'jam_mulai_masuk' => '07:00:00',
            'jam_mulai_pulang' => '16:00:00',
            'qr_absensi_enabled' => true,
        ]);

        $position = InternshipPosition::create([
            'instansi_id' => $this->instansi->id,
            'judul_posisi' => 'Software Engineer Intern',
            'kuota' => 5,
            'status' => 'buka',
        ]);

        $this->peserta = User::create([
            'name' => 'Peserta Uji',
            'email' => 'peserta.uji@example.com',
            'password' => bcrypt('password'),
            'role' => 'peserta',
            'email_verified_at' => now(),
        ]);

        $this->application = Application::create([
            'user_id' => $this->peserta->id,
            'internship_position_id' => $position->id,
            'status' => 'diterima',
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-11-30',
        ]);

        $this->qrService = app(DynamicQrService::class);
        $this->challengeService = app(AttendanceChallengeService::class);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_clock_in_fails_when_qr_enabled_but_qr_token_missing(): void
    {
        $nonceData = $this->challengeService->issue($this->peserta);

        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $nonceData['nonce'],
            // qr_token missing
        ]);

        $response->assertSessionHas('error', 'Gagal Absen! Instansi Anda mewajibkan scan Dynamic QR di layar kantor.');
        $this->assertDatabaseMissing('attendances', [
            'application_id' => $this->application->id,
            'date' => '2026-09-01',
        ]);
    }

    public function test_clock_in_fails_when_qr_token_is_expired(): void
    {
        $nonceData = $this->challengeService->issue($this->peserta);
        // Token dari 90 detik yang lalu
        $oldTimestamp = now()->getTimestamp() - 90;
        $oldTokenData = $this->qrService->generateTokenData($this->instansi, $oldTimestamp);

        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $nonceData['nonce'],
            'qr_token' => $oldTokenData['token'],
        ]);

        $response->assertSessionHas('error', 'Gagal Absen! Kode Dynamic QR sudah kedaluwarsa (berputar tiap 30 detik). Silakan scan ulang dari layar monitor kantor.');
        $this->assertDatabaseMissing('attendances', [
            'application_id' => $this->application->id,
            'date' => '2026-09-01',
        ]);
    }

    public function test_clock_in_succeeds_with_valid_gps_and_valid_dynamic_qr(): void
    {
        $nonceData = $this->challengeService->issue($this->peserta);
        $liveTokenData = $this->qrService->generateTokenData($this->instansi, now()->getTimestamp());

        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $nonceData['nonce'],
            'qr_token' => $liveTokenData['token'],
            'accuracy' => 15,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('attendances', [
            'application_id' => $this->application->id,
            'date' => '2026-09-01',
            'status' => 'hadir',
        ]);
    }

    public function test_clock_in_succeeds_without_qr_token_if_instansi_disables_qr(): void
    {
        $this->instansi->update(['qr_absensi_enabled' => false]);

        $nonceData = $this->challengeService->issue($this->peserta);

        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $nonceData['nonce'],
            'accuracy' => 15,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('attendances', [
            'application_id' => $this->application->id,
            'date' => '2026-09-01',
            'status' => 'hadir',
        ]);
    }
}
