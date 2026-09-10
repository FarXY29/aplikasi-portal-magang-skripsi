<?php

namespace Tests\Feature\Security;

use App\Models\Instansi;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * P0-1003: Role Boundary and Unauthorized Route Access Test
 */
class RoleBoundaryTest extends TestCase
{
    use DatabaseTransactions;

    private User $peserta;
    private User $adminInstansi;
    private User $pembimbing;
    private User $adminKota;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);

        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Keamanan',
            'kode_unit_kerja' => 'SEC-01',
            'alamat' => 'Banjarmasin',
            'max_total_quota' => 10,
        ]);

        $this->peserta = User::factory()->create(['role' => 'peserta']);
        $this->adminInstansi = User::factory()->create([
            'role' => 'admin_instansi',
            'instansi_id' => $instansi->id,
        ]);
        $this->pembimbing = User::factory()->create(['role' => 'pembimbing']);
        $this->adminKota = User::factory()->create(['role' => 'admin_kota']);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('dinas.dashboard'))->assertRedirect(route('login'));
        $this->get(route('peserta.dashboard'))->assertRedirect(route('login'));
        $this->get(route('pembimbing.dashboard'))->assertRedirect(route('login'));
    }

    public function test_peserta_cannot_access_admin_or_dinas_routes(): void
    {
        $this->actingAs($this->peserta)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($this->peserta)->get(route('dinas.dashboard'))->assertForbidden();
        $this->actingAs($this->peserta)->get(route('pembimbing.dashboard'))->assertForbidden();
    }

    public function test_admin_instansi_cannot_access_admin_kota_routes(): void
    {
        $this->actingAs($this->adminInstansi)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($this->adminInstansi)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($this->adminInstansi)->get(route('admin.instansi.index'))->assertForbidden();
    }

    public function test_pembimbing_cannot_access_admin_or_dinas_routes(): void
    {
        $this->actingAs($this->pembimbing)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($this->pembimbing)->get(route('dinas.dashboard'))->assertForbidden();
    }
}
