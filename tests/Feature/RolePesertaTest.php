<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;

class RolePesertaTest extends TestCase
{
    use DatabaseTransactions;

    public function test_peserta_can_access_their_dashboard()
    {
        $user = User::factory()->create(['role' => 'peserta']);

        $response = $this->actingAs($user)->get(route('peserta.dashboard'));
        $response->assertStatus(200);
    }

    public function test_peserta_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create(['role' => 'peserta']);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        // Usually redirects or 403
        $response->assertStatus(403);
    }

    public function test_peserta_can_download_id_card_and_loa_when_accepted()
    {
        $user = User::factory()->create([
            'role' => 'peserta',
            'nik' => '1234567890123456',
            'asal_instansi' => 'Universitas Indonesia',
        ]);

        $instansi = \App\Models\Instansi::create([
            'nama_dinas' => 'Dinas Pendidikan',
            'kode_unit_kerja' => 'DISDIK-01',
            'alamat' => 'Jakarta',
            'jam_mulai_masuk' => '08:00',
            'jam_mulai_pulang' => '16:00',
            'max_total_quota' => 10,
        ]);

        $position = \App\Models\InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Frontend Developer',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $app = \App\Models\Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'status' => 'diterima',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
        ]);

        // Test download LoA
        $responseLoa = $this->actingAs($user)->get(route('peserta.loa.download', $app->id));
        $responseLoa->assertStatus(200);
        $this->assertTrue(str_contains($responseLoa->headers->get('Content-Disposition'), 'LoA_'));

        // Test download ID Card
        $responseIdCard = $this->actingAs($user)->get(route('peserta.id_card.download', $app->id));
        $responseIdCard->assertStatus(200);
        $this->assertTrue(str_contains($responseIdCard->headers->get('Content-Disposition'), 'ID_Card_'));
    }

    public function test_peserta_cannot_absen_outside_internship_period()
    {
        $user = User::factory()->create([
            'role' => 'peserta',
            'nik' => '1234567890123456',
            'asal_instansi' => 'Universitas Indonesia',
        ]);

        $instansi = \App\Models\Instansi::create([
            'nama_dinas' => 'Dinas Test',
            'kode_unit_kerja' => 'TEST-01',
            'alamat' => 'Alamat Test',
            'jam_mulai_masuk' => '07:30:00',
            'jam_mulai_pulang' => '16:00:00',
            'max_total_quota' => 10,
        ]);

        $position = \App\Models\InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'QA Engineer',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $app = \App\Models\Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'status' => 'diterima',
            'tanggal_mulai' => \Carbon\Carbon::now()->addDays(5)->toDateString(), // Future start
            'tanggal_selesai' => \Carbon\Carbon::now()->addDays(95)->toDateString(),
        ]);

        $response = $this->actingAs($user)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
        ]);

        $response->assertSessionHas('error');
    }

    public function test_peserta_cannot_absen_outside_internship_period_past()
    {
        $user = User::factory()->create([
            'role' => 'peserta',
            'nik' => '1234567890123456',
            'asal_instansi' => 'Universitas Indonesia',
        ]);

        $instansi = \App\Models\Instansi::create([
            'nama_dinas' => 'Dinas Test',
            'kode_unit_kerja' => 'TEST-01',
            'alamat' => 'Alamat Test',
            'jam_mulai_masuk' => '07:30:00',
            'jam_mulai_pulang' => '16:00:00',
            'max_total_quota' => 10,
        ]);

        $position = \App\Models\InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'QA Engineer',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $app = \App\Models\Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'status' => 'diterima',
            'tanggal_mulai' => \Carbon\Carbon::now()->subDays(95)->toDateString(),
            'tanggal_selesai' => \Carbon\Carbon::now()->subDays(5)->toDateString(), // Past end
        ]);

        $response = $this->actingAs($user)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
        ]);

        // This should fail (have error) if we enforce past end date checks
        $response->assertSessionHas('error');
    }

    public function test_peserta_cannot_submit_logbook_outside_internship_period()
    {
        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);

        $user = User::factory()->create([
            'role' => 'peserta',
            'nik' => '1234567890123456',
            'asal_instansi' => 'Universitas Indonesia',
        ]);
        $user->assignRole('peserta');

        $instansi = \App\Models\Instansi::create([
            'nama_dinas' => 'Dinas Test',
            'kode_unit_kerja' => 'TEST-01',
            'alamat' => 'Alamat Test',
            'jam_mulai_masuk' => '07:30:00',
            'jam_mulai_pulang' => '16:00:00',
            'max_total_quota' => 10,
        ]);

        $position = \App\Models\InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'QA Engineer',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $app = \App\Models\Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'status' => 'diterima',
            'tanggal_mulai' => \Carbon\Carbon::now()->addDays(5)->toDateString(), // Future start
            'tanggal_selesai' => \Carbon\Carbon::now()->addDays(95)->toDateString(),
        ]);

        $response = $this->actingAs($user)->post(route('peserta.logbook.store'), [
            'kegiatan' => 'Menulis test case',
            'latitude' => -3.316694,
            'longitude' => 114.590111,
        ]);

        $response->assertSessionHas('error');
    }

    public function test_peserta_cannot_download_certificate_without_saran()
    {
        $user = User::factory()->create([
            'role' => 'peserta',
            'nik' => '1234567890123456',
            'asal_instansi' => 'Universitas Indonesia',
        ]);

        $instansi = \App\Models\Instansi::create([
            'nama_dinas' => 'Dinas Test',
            'kode_unit_kerja' => 'TEST-02',
            'alamat' => 'Alamat Test',
            'jam_mulai_masuk' => '07:30:00',
            'jam_mulai_pulang' => '16:00:00',
            'max_total_quota' => 10,
        ]);

        $position = \App\Models\InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Developer',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $app = \App\Models\Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'status' => 'selesai',
            'tanggal_mulai' => \Carbon\Carbon::now()->subMonths(3)->toDateString(),
            'tanggal_selesai' => \Carbon\Carbon::now()->subDay()->toDateString(),
            'saran_peserta' => null, // Empty
        ]);

        $response = $this->actingAs($user)->get(route('peserta.sertifikat'));
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
