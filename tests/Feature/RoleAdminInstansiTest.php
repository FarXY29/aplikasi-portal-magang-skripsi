<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Instansi;

class RoleAdminInstansiTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_instansi_can_access_their_dashboard()
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Test',
            'kode_unit_kerja' => 'DIN-01',
            'alamat' => 'Alamat Test',
            'nama_pejabat' => 'Pejabat Test',
            'nip_pejabat' => '123456789'
        ]);

        $user = User::factory()->create([
            'role' => 'admin_instansi',
            'instansi_id' => $instansi->id
        ]);

        $response = $this->actingAs($user)->get(route('dinas.dashboard'));
        $response->assertStatus(200);
    }

    public function test_admin_instansi_cannot_access_super_admin_dashboard()
    {
        $user = User::factory()->create(['role' => 'admin_instansi']);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }
}
