<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;

class RolePesertaTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Anti-fraud layer aktif (shadow). Rate limit dinaikkan agar test
        // lain di file ini tidak terganggu limiter.
        config([
            'attendance.enabled' => true,
            'attendance.require_nonce' => true,
            'attendance.mode' => 'shadow',
            'attendance.challenge_rate_limit' => 1000,
            'attendance.clock_rate_limit' => 1000,
        ]);
    }

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

        // Test download LoA without signature
        $responseLoa = $this->actingAs($user)->get(route('peserta.loa.download', $app->id));
        $responseLoa->assertStatus(200);
        $this->assertTrue(str_contains($responseLoa->headers->get('Content-Disposition'), 'LoA_'));

        // Test download LoA with signature image
        $instansi->update(['ttd_kepala' => 'signatures/sample.png']);
        $responseLoaWithTtd = $this->actingAs($user)->get(route('peserta.loa.download', $app->id));
        $responseLoaWithTtd->assertStatus(200);

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

    public function test_peserta_can_update_pending_logbook()
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
            'tanggal_mulai' => \Carbon\Carbon::now()->subDays(5)->toDateString(),
            'tanggal_selesai' => \Carbon\Carbon::now()->addDays(95)->toDateString(),
        ]);

        $log = \App\Models\DailyLog::create([
            'application_id' => $app->id,
            'tanggal' => \Carbon\Carbon::now()->toDateString(),
            'kegiatan' => 'Kegiatan awal',
            'status_validasi' => 'pending',
        ]);

        $response = $this->actingAs($user)->put(route('peserta.logbook.update', $log->id), [
            'kegiatan' => 'Kegiatan yang telah diupdate',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('daily_logs', [
            'id' => $log->id,
            'kegiatan' => 'Kegiatan yang telah diupdate',
        ]);
    }

    public function test_peserta_can_delete_pending_logbook()
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
            'tanggal_mulai' => \Carbon\Carbon::now()->subDays(5)->toDateString(),
            'tanggal_selesai' => \Carbon\Carbon::now()->addDays(95)->toDateString(),
        ]);

        $log = \App\Models\DailyLog::create([
            'application_id' => $app->id,
            'tanggal' => \Carbon\Carbon::now()->toDateString(),
            'kegiatan' => 'Kegiatan awal',
            'status_validasi' => 'pending',
        ]);

        $response = $this->actingAs($user)->delete(route('peserta.logbook.destroy', $log->id));

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('daily_logs', [
            'id' => $log->id,
        ]);
    }

    public function test_peserta_cannot_delete_approved_logbook()
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
            'tanggal_mulai' => \Carbon\Carbon::now()->subDays(5)->toDateString(),
            'tanggal_selesai' => \Carbon\Carbon::now()->addDays(95)->toDateString(),
        ]);

        $log = \App\Models\DailyLog::create([
            'application_id' => $app->id,
            'tanggal' => \Carbon\Carbon::now()->toDateString(),
            'kegiatan' => 'Kegiatan awal',
            'status_validasi' => 'disetujui',
        ]);

        $response = $this->actingAs($user)->delete(route('peserta.logbook.destroy', $log->id));

        $response->assertStatus(403);
        $this->assertDatabaseHas('daily_logs', [
            'id' => $log->id,
        ]);
    }

    public function test_peserta_can_download_transkrip_nilai_pdf_with_signatures()
    {
        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);

        $pl = User::factory()->create([
            'role' => 'pembimbing_lapangan',
            'signature' => 'signatures/dummy_pl.png',
        ]);
        $pl->assignRole('pembimbing_lapangan');

        $peserta = User::factory()->create([
            'role' => 'peserta',
            'nik' => '6371012345670001',
            'asal_instansi' => 'Universitas Lambung Mangkurat',
        ]);
        $peserta->assignRole('peserta');

        $instansi = \App\Models\Instansi::create([
            'nama_dinas' => 'Dinas Kominfo Banjarmasin',
            'nama_pejabat' => 'Kadis Kominfo',
            'nip_pejabat' => '198001012005011001',
            'jabatan_pejabat' => 'Kepala Dinas Komunikasi dan Informatika',
            'ttd_kepala' => 'signatures/dummy_kadis.png',
            'kode_unit_kerja' => 'KOMINFO-01',
            'alamat' => 'Jl RE Martadinata',
        ]);

        $position = \App\Models\InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Web Developer Intern',
            'kuota' => 5,
            'status' => 'buka',
        ]);

        $app = \App\Models\Application::create([
            'user_id' => $peserta->id,
            'internship_position_id' => $position->id,
            'pembimbing_lapangan_id' => $pl->id,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'status' => 'selesai',
            'saran_peserta' => 'Pengalaman magang sangat baik dan bermanfaat.',
            'tanggal_mulai' => now()->subMonths(3)->toDateString(),
            'tanggal_selesai' => now()->toDateString(),
            'nilai_integritas' => 90,
            'nilai_keahlian' => 88,
            'nilai_disiplin' => 92,
            'nilai_kerjasama' => 85,
            'nilai_inisiatif' => 87,
            'nilai_kehadiran' => 95,
            'nilai_rata_rata' => 89.5,
        ]);

        $response = $this->actingAs($peserta)->get(route('peserta.download.nilai', $app->id));
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('Content-Disposition') ?? '', '.pdf') || $response->headers->get('Content-Type') === 'application/pdf');
    }

    public function test_peserta_can_download_sertifikat_pdf_with_signatures()
    {
        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);

        $pl = User::factory()->create([
            'role' => 'pembimbing_lapangan',
            'signature' => 'signatures/dummy_pl.png',
        ]);
        $pl->assignRole('pembimbing_lapangan');

        $peserta = User::factory()->create([
            'role' => 'peserta',
            'nik' => '6371012345670001',
            'asal_instansi' => 'Universitas Lambung Mangkurat',
        ]);
        $peserta->assignRole('peserta');

        $instansi = \App\Models\Instansi::create([
            'nama_dinas' => 'Dinas Pendidikan Banjarmasin',
            'nama_pejabat' => 'Kadis Pendidikan',
            'nip_pejabat' => '197501012000011002',
            'jabatan_pejabat' => 'Kepala Dinas Pendidikan',
            'ttd_kepala' => 'signatures/dummy_disdik.png',
            'kode_unit_kerja' => 'DISDIK-01',
            'alamat' => 'Jl Kapten Pierre Tendean',
        ]);

        $position = \App\Models\InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Administrasi Pendidikan',
            'kuota' => 5,
            'status' => 'buka',
        ]);

        $app = \App\Models\Application::create([
            'user_id' => $peserta->id,
            'internship_position_id' => $position->id,
            'pembimbing_lapangan_id' => $pl->id,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'status' => 'selesai',
            'saran_peserta' => 'Terima kasih atas bimbingannya.',
            'tanggal_mulai' => now()->subMonths(3)->toDateString(),
            'tanggal_selesai' => now()->toDateString(),
            'nomor_sertifikat' => '001/SERTIF/2026',
            'token_verifikasi' => 'TOKEN-TEST-12345',
            'sertifikat_diterbitkan' => true,
        ]);

        $response = $this->actingAs($peserta)->get(route('peserta.sertifikat', $app->id));
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('Content-Disposition') ?? '', '.pdf') || $response->headers->get('Content-Type') === 'application/pdf');
    }

    public function test_peserta_can_submit_automatic_application()
    {
        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);

        \Illuminate\Support\Facades\Storage::fake('private');

        $user = User::factory()->create([
            'role' => 'peserta',
            'major' => 'Teknik Informatika',
        ]);

        $instansi = \App\Models\Instansi::create([
            'nama_dinas' => 'Dinas Komunikasi dan Informatika',
            'kode_unit_kerja' => 'DISKOMINFO-' . uniqid(),
            'alamat' => 'Jl. Pangeran Samudra',
            'max_total_quota' => 10,
        ]);

        $position = \App\Models\InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Programmer',
            'required_major' => 'Teknik Informatika',
            'kuota' => 5,
            'status' => 'buka',
        ]);

        $file = \Illuminate\Http\UploadedFile::fake()->create('surat.pdf', 500, 'application/pdf');

        $response = $this->actingAs($user)->post(route('peserta.apply_automatic.store'), [
            'surat' => $file,
            'tanggal_mulai' => now()->addDays(5)->format('Y-m-d'),
            'tanggal_selesai' => now()->addMonths(2)->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('peserta.dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('applications', [
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'is_automatic_placement' => true,
        ]);
    }
}
