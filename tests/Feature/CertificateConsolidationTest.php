<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CertificateConsolidationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_instansi_cannot_issue_certificate_for_another_instansi_candidate()
    {
        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);

        $instansiA = Instansi::create([
            'nama_dinas' => 'Dinas Pendidikan A',
            'kode_unit_kerja' => 'DISDIK-0A',
            'alamat' => 'Alamat A',
            'jam_mulai_masuk' => '08:00',
            'jam_mulai_pulang' => '16:00',
            'max_total_quota' => 10,
        ]);

        $instansiB = Instansi::create([
            'nama_dinas' => 'Dinas Pendidikan B',
            'kode_unit_kerja' => 'DISDIK-0B',
            'alamat' => 'Alamat B',
            'jam_mulai_masuk' => '08:00',
            'jam_mulai_pulang' => '16:00',
            'max_total_quota' => 10,
        ]);

        $adminA = User::factory()->create([
            'role' => 'admin_instansi',
            'instansi_id' => $instansiA->id
        ]);
        $adminA->assignRole('admin_instansi');

        $positionB = InternshipPosition::create([
            'instansi_id' => $instansiB->id,
            'judul_posisi' => 'Staff B',
            'kuota' => 5,
            'status' => 'buka',
            'batas_daftar' => now()->addDays(10)->toDateString()
        ]);

        $appB = Application::create([
            'user_id' => User::factory()->create()->id,
            'internship_position_id' => $positionB->id,
            'status' => 'diterima',
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => now()->addDays(5)->toDateString(),
            'nilai_rata_rata' => 90 // Set grade to pass validation check
        ]);

        // Attempting to store certificate for candidate B by Admin A should be forbidden (403)
        $response = $this->actingAs($adminA)->post(route('dinas.sertifikat.store', $appB->id), [
            'nomor_sertifikat' => 'MG-2026-00001',
            'tanggal_sertifikat' => now()->toDateString()
        ]);

        $response->assertStatus(403);
    }
}
