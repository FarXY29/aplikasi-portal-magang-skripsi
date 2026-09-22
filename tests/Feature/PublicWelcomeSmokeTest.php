<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PublicWelcomeSmokeTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_guest_can_render_public_welcome_landing_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Portal Resmi Magang Kota Banjarmasin');
        $response->assertSee('ALUR PENDAFTARAN');
        $response->assertSee('Pertanyaan Populer (FAQ)');
    }

    public function test_guest_can_fetch_lowongan_partial_grid(): void
    {
        $response = $this->get('/lowongan?partial=grid');

        $response->assertStatus(200);
        $response->assertSee('lowonganGridManager');
    }

    public function test_guest_can_access_qr_scanner_page(): void
    {
        $response = $this->get('/scan-qr');

        $response->assertStatus(200);
    }

    public function test_user_without_portal_role_sees_clean_fallback_dashboard(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Pengguna');
        $response->assertSee('Belum Ada Peran Aktif');
        $response->assertSee('Menunggu Penugasan Peran');
    }
}
