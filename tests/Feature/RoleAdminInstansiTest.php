<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Instansi;
use App\Models\Attendance;

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

    public function test_laporan_kinerja_peserta_accurately_counts_hadir_and_pending_attendance()
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Test Kinerja',
            'kode_unit_kerja' => 'DIN-02',
            'alamat' => 'Alamat Test',
            'nama_pejabat' => 'Pejabat Test',
            'nip_pejabat' => '123456789'
        ]);

        $admin = User::factory()->create([
            'role' => 'admin_instansi',
            'instansi_id' => $instansi->id,
        ]);

        $peserta = User::factory()->create([
            'role' => 'peserta',
            'name' => 'Peserta Kinerja Test',
        ]);

        $position = \App\Models\InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Staff IT Test',
            'kuota' => 5,
            'deskripsi' => 'Deskripsi test',
            'persyaratan' => 'Persyaratan test',
            'status' => 'buka',
        ]);

        $app = \App\Models\Application::create([
            'user_id' => $peserta->id,
            'internship_position_id' => $position->id,
            'status' => 'diterima',
            'cv_path' => 'cv/test.pdf',
            'surat_pengantar_path' => 'surat/test.pdf',
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => now()->addDays(25)->toDateString(),
        ]);

        // 1. Absensi Hadir (validation_status = approved)
        Attendance::create([
            'application_id' => $app->id,
            'date' => now()->subDays(1)->toDateString(),
            'status' => 'hadir',
            'clock_in' => '08:00:00',
            'validation_status' => 'approved',
        ]);

        $response = $this->actingAs($admin)->get(route('dinas.laporan.kinerja_peserta'));
        $response->assertStatus(200);
        $response->assertSee('Peserta Kinerja Test');
        $response->assertSee('1 hari');
        $response->assertSee('0 hari'); // Sakit, Izin, Alfa, Izin/Sakit Pending harus 0 hari
    }
}
