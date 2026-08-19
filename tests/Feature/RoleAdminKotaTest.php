<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;

class RoleAdminKotaTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_kota_can_access_dashboard()
    {
        $user = User::factory()->create(['role' => 'admin_kota']);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_admin_kota_can_access_instansi_list()
    {
        $user = User::factory()->create(['role' => 'admin_kota']);

        $response = $this->actingAs($user)->get(route('admin.instansi.index'));
        $response->assertStatus(200);
    }

    public function test_admin_kota_can_access_grading_report()
    {
        $user = User::factory()->create(['role' => 'admin_kota']);

        $response = $this->actingAs($user)->get(route('admin.laporan.grading'));
        $response->assertStatus(200);
    }

    public function test_admin_kota_can_access_general_report()
    {
        $user = User::factory()->create(['role' => 'admin_kota']);

        $response = $this->actingAs($user)->get(route('admin.laporan'));
        $response->assertStatus(200);
    }

    public function test_admin_kota_can_access_instansi_create_and_edit_page()
    {
        $user = User::factory()->create(['role' => 'admin_kota']);
        $instansi = \App\Models\Instansi::create([
            'nama_dinas' => 'Dinas Kominfo Test',
            'alamat' => 'Jl. Lambung Mangkurat',
            'kode_unit_kerja' => 'DISKOMINFO-TEST',
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'radius_absen' => 100,
        ]);

        $responseCreate = $this->actingAs($user)->get(route('admin.instansi.create'));
        $responseCreate->assertStatus(200);

        $responseEdit = $this->actingAs($user)->get(route('admin.instansi.edit', $instansi->id));
        $responseEdit->assertStatus(200);
    }
}
