<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DeleteProtectionTest extends TestCase
{
    use DatabaseTransactions;

    public function test_cannot_delete_instansi_with_active_interns()
    {
        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);

        $admin = User::factory()->create(['role' => 'admin_kota']);
        $admin->assignRole('admin_kota');

        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Test D',
            'kode_unit_kerja' => 'TEST-09D',
            'alamat' => 'Alamat D',
            'jam_mulai_masuk' => '08:00',
            'jam_mulai_pulang' => '16:00',
            'max_total_quota' => 10
        ]);

        $pos = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Staff',
            'kuota' => 1,
            'status' => 'buka',
            'batas_daftar' => now()->addDays(10)->toDateString()
        ]);

        Application::create([
            'user_id' => User::factory()->create()->id,
            'internship_position_id' => $pos->id,
            'status' => 'diterima',
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => now()->addDays(5)->toDateString()
        ]);

        // When trying to delete instansi that has active interns, it should fail
        $response = $this->actingAs($admin)->delete(route('admin.instansi.destroy', $instansi->id));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('instansis', ['id' => $instansi->id]);
    }
}
