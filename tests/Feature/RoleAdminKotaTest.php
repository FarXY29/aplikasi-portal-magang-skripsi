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
}
