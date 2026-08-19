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

    public function test_admin_kota_can_view_certificate_detail_with_five_grading_criteria()
    {
        $admin = User::factory()->create(['role' => 'admin_kota']);
        $peserta = User::factory()->create([
            'role' => 'peserta',
            'name' => 'Peserta Nilai Sertifikat',
        ]);

        $instansi = \App\Models\Instansi::create([
            'nama_dinas' => 'Dinas Pendidikan Test',
            'alamat' => 'Jl. Veteran',
            'kode_unit_kerja' => 'DISDIK-TEST',
        ]);

        $position = \App\Models\InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Staff Pengajar Test',
            'kuota' => 2,
            'deskripsi' => 'Deskripsi',
            'persyaratan' => 'Syarat',
            'status' => 'buka',
        ]);

        $app = \App\Models\Application::create([
            'user_id' => $peserta->id,
            'internship_position_id' => $position->id,
            'status' => 'selesai',
            'cv_path' => 'cv/test.pdf',
            'surat_pengantar_path' => 'surat/test.pdf',
            'tanggal_mulai' => now()->subDays(30)->toDateString(),
            'tanggal_selesai' => now()->toDateString(),
            'nilai_kerajinan' => 85,
            'nilai_disiplin' => 80,
            'nilai_adaptasi' => 90,
            'nilai_kreatifitas' => 88,
            'nilai_skill_pengetahuan' => 92,
            'nilai_rata_rata' => 87,
            'nilai_angka' => 87,
            'predikat' => 'Sangat Baik',
        ]);

        $cert = \App\Models\Certificate::create([
            'application_id' => $app->id,
            'nomor_sertifikat' => 'CERT-TEST-001',
            'token_verifikasi' => 'tokentest123456',
            'signer_name' => 'Kepala Dinas Test',
            'issue_date' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.certificates.show', $cert->id));
        $response->assertStatus(200);
        $response->assertSee('Evaluasi & Penilaian Kinerja', false);
        $response->assertSee('Kerajinan');
        $response->assertSee('85');
        $response->assertSee('Kedisiplinan');
        $response->assertSee('80');
        $response->assertSee('Adaptasi');
        $response->assertSee('90');
        $response->assertSee('Kreatifitas');
        $response->assertSee('88');
        $response->assertSee('Skill & Pengetahuan', false);
        $response->assertSee('92');
        $response->assertSee('87.0');
        $response->assertSee('(Sangat Baik)');
    }
}
