<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Instansi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KioskPresensiTest extends TestCase
{
    use RefreshDatabase;

    private Instansi $instansi;
    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->instansi = Instansi::create([
            'nama_dinas' => 'Dinas Komunikasi dan Informatika',
            'kode_unit_kerja' => 'DISKOMINFO-01',
            'alamat' => 'Jl. Pemuda No. 1',
            'qr_absensi_enabled' => true,
            'kiosk_token' => 'test-kiosk-token-secret-12345678',
        ]);

        $this->adminUser = User::create([
            'name' => 'Admin Dinas',
            'email' => 'admin.dinas@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin_instansi',
            'instansi_id' => $this->instansi->id,
        ]);
    }

    public function test_public_kiosk_page_accessible_with_valid_token(): void
    {
        $response = $this->get(route('kiosk.presensi.public', $this->instansi->kiosk_token));

        $response->assertStatus(200);
        $response->assertSee('Dinas Komunikasi dan Informatika');
        $response->assertSee('Dynamic QR Presensi Kantor');
    }

    public function test_public_kiosk_page_returns_404_with_invalid_token(): void
    {
        $response = $this->get('/kiosk/presensi/invalid-fake-token');

        $response->assertStatus(404);
    }

    public function test_auth_kiosk_page_accessible_by_admin_instansi(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('dinas.kiosk.presensi'));

        $response->assertStatus(200);
        $response->assertSee('Dinas Komunikasi dan Informatika');
    }

    public function test_fetch_live_qr_returns_json_payload(): void
    {
        $response = $this->getJson(route('kiosk.live_qr', $this->instansi->kiosk_token));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'svg',
            'remaining_seconds',
            'interval',
            'expires_at',
            'server_time',
        ]);
        $this->assertTrue($response->json('success'));
        $this->assertStringContainsString('<svg', $response->json('svg'));
    }
}

