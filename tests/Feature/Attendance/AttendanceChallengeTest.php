<?php

namespace Tests\Feature\Attendance;

use App\Models\Application;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Test attendance challenge nonce (P0 §5.3): valid, expired, reused,
 * invalid, cross-user, dan rate limit.
 */
class AttendanceChallengeTest extends TestCase
{
    use DatabaseTransactions;

    private User $peserta;
    private array $appSetup;

    protected function setUp(): void
    {
        parent::setUp();
        config(['attendance.enabled' => true, 'require_nonce' => true, 'mode' => 'shadow']);

        $this->peserta = User::factory()->create(['role' => 'peserta']);
        $this->appSetup = $this->makeApplication();
    }

    private function makeApplication(array $instansiOverrides = []): array
    {
        $instansi = Instansi::create(array_merge([
            'nama_dinas' => 'Dinas Test Challenge',
            'kode_unit_kerja' => 'CH-01',
            'alamat' => 'Banjarmasin',
            'jam_mulai_masuk' => '00:00:00', // selalu terbuka utk test
            'jam_mulai_pulang' => '00:00:00',
            'max_total_quota' => 10,
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'radius_absen' => 100,
        ], $instansiOverrides));

        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Backend Engineer',
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

    private function getChallenge(): array
    {
        $response = $this->actingAs($this->peserta)
            ->getJson(route('peserta.absensi.challenge'));

        $response->assertOk()->assertJsonStructure(['nonce', 'expires_at', 'ttl', 'server_time']);

        return $response->json();
    }

    public function test_challenge_returns_cryptographically_random_nonce(): void
    {
        $a = $this->getChallenge();
        $b = $this->getChallenge();

        $this->assertNotSame($a['nonce'], $b['nonce']);
        $this->assertEquals(64, strlen($a['nonce'])); // 32 byte hex
        $this->assertEquals(60, $a['ttl']);
    }

    public function test_clock_in_with_valid_nonce_succeeds(): void
    {
        $challenge = $this->getChallenge();

        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'accuracy' => 10,
            'client_timestamp' => now()->getTimestamp() * 1000,
            'nonce' => $challenge['nonce'],
            'idempotency_key' => 'test-key-valid-1',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('attendances', [
            'application_id' => $this->appSetup['application']->id,
            'status' => 'hadir',
        ]);
    }

    public function test_clock_in_without_nonce_is_rejected(): void
    {
        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('attendances', [
            'application_id' => $this->appSetup['application']->id,
            'status' => 'hadir',
        ]);
    }

    public function test_reused_nonce_is_rejected(): void
    {
        $challenge = $this->getChallenge();

        // Request pertama: sukses.
        $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $challenge['nonce'],
            'idempotency_key' => 'test-reuse-1',
        ])->assertSessionHas('success');

        // Hapus attendance agar bisa clock-in lagi (test reuse murni nonce).
        \App\Models\Attendance::where('application_id', $this->appSetup['application']->id)->delete();

        // Request kedua memakai nonce SAMA → replay → ditolak.
        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $challenge['nonce'],
            'idempotency_key' => 'test-reuse-2',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('attendances', [
            'application_id' => $this->appSetup['application']->id,
            'status' => 'hadir',
        ]);

        // Attempt replay tercatat sebagai evidence.
        $this->assertDatabaseHas('attendance_attempts', [
            'user_id' => $this->peserta->id,
            'fraud_status' => 'critical',
        ]);
    }

    public function test_invalid_nonce_is_rejected(): void
    {
        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => 'forge-fake-nonce-value-not-issued-by-server',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('attendances', [
            'application_id' => $this->appSetup['application']->id,
        ]);
    }

    public function test_cross_user_nonce_is_rejected(): void
    {
        // Nonce milik user lain (terbitkan sebagai user A).
        $other = User::factory()->create(['role' => 'peserta']);
        $challenge = $this->actingAs($other)->getJson(route('peserta.absensi.challenge'))->json();

        // Dipakai oleh user B (peserta utama).
        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $challenge['nonce'],
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('attendances', [
            'application_id' => $this->appSetup['application']->id,
        ]);
    }

    public function test_challenge_rate_limit_returns_429(): void
    {
        config(['attendance.challenge_rate_limit' => 3]);

        for ($i = 0; $i < 3; $i++) {
            $this->actingAs($this->peserta)
                ->getJson(route('peserta.absensi.challenge'))
                ->assertOk();
        }

        $this->actingAs($this->peserta)
            ->getJson(route('peserta.absensi.challenge'))
            ->assertStatus(429);
    }

    public function test_clock_in_rate_limit_returns_429(): void
    {
        config(['attendance.clock_rate_limit' => 3]);

        for ($i = 0; $i < 3; $i++) {
            $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
                'latitude' => -3.316694,
                'longitude' => 114.590111,
                'nonce' => $this->getChallenge()['nonce'],
            ]);
        }

        $response = $this->actingAs($this->peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'nonce' => $this->getChallenge()['nonce'],
        ]);

        $response->assertStatus(429);
    }
}
