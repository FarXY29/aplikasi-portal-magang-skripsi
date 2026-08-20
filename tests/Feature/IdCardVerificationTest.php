<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Certificate;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\Major;
use App\Models\MajorCategory;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class IdCardVerificationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_guest_can_verify_active_intern_id_card(): void
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Komunikasi dan Informatika Banjarmasin',
            'kode_unit_kerja' => 'DISKOMINFO-01',
            'alamat' => 'Jl. RE Martadinata No. 1',
        ]);

        $category = MajorCategory::create([
            'code' => 'TIK',
            'name' => 'Teknologi Informasi & Komputer',
        ]);

        $major = Major::create([
            'major_category_id' => $category->id,
            'name' => 'Teknik Informatika',
            'degree_level' => 'S1',
            'is_active' => true,
        ]);

        $posisi = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Fullstack Developer Intern',
            'kuota' => 5,
            'status' => 'buka',
        ]);

        $pl = User::factory()->create([
            'role' => 'pembimbing_lapangan',
            'name' => 'Budi Santoso, S.Kom',
        ]);
        $pl->assignRole('pembimbing_lapangan');

        $peserta = User::factory()->create([
            'role' => 'peserta',
            'name' => 'Muhammad Rifqi',
            'nik' => '6371012304950001',
            'asal_instansi' => 'Universitas Lambung Mangkurat',
            'major_id' => $major->id,
            'major' => 'Teknik Informatika',
        ]);
        $peserta->assignRole('peserta');

        $token = 'IDCTOKEN-ACTIVE-1234567890';

        $app = Application::create([
            'user_id' => $peserta->id,
            'internship_position_id' => $posisi->id,
            'pembimbing_lapangan_id' => $pl->id,
            'status' => 'diterima',
            'tanggal_mulai' => now()->subDays(10)->toDateString(),
            'tanggal_selesai' => now()->addDays(80)->toDateString(),
            'token_verifikasi' => $token,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
        ]);

        $response = $this->get(route('id_card.verify', $token));

        $response->assertOk();
        $response->assertViewIs('public.verifikasi.id_card');
        $response->assertSee('Muhammad Rifqi');
        $response->assertSee('6371012304950001');
        $response->assertSee('Universitas Lambung Mangkurat');
        $response->assertSee('Dinas Komunikasi dan Informatika Banjarmasin');
        $response->assertSee('Fullstack Developer Intern');
        $response->assertSee('Peserta Magang Aktif');
        $response->assertSee('Budi Santoso, S.Kom');
    }

    public function test_guest_can_verify_finished_intern_id_card(): void
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Pendidikan Banjarmasin',
            'kode_unit_kerja' => 'DISDIK-01',
            'alamat' => 'Jl. Kapten Pierre Tendean',
        ]);

        $posisi = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Staff Administrasi',
            'kuota' => 3,
            'status' => 'buka',
        ]);

        $peserta = User::factory()->create([
            'role' => 'peserta',
            'name' => 'Siti Nurhaliza',
            'asal_instansi' => 'Politeknik Negeri Banjarmasin',
        ]);
        $peserta->assignRole('peserta');

        $token = 'IDCTOKEN-FINISHED-9876543210';

        $app = Application::create([
            'user_id' => $peserta->id,
            'internship_position_id' => $posisi->id,
            'status' => 'selesai',
            'tanggal_mulai' => now()->subMonths(3)->toDateString(),
            'tanggal_selesai' => now()->subDays(5)->toDateString(),
            'token_verifikasi' => $token,
            'nomor_sertifikat' => '005/DISDIK/2026',
            'sertifikat_diterbitkan' => true,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
        ]);

        Certificate::create([
            'application_id' => $app->id,
            'nomor_sertifikat' => $app->nomor_sertifikat,
            'token_verifikasi' => $app->token_verifikasi,
            'status' => 'active',
            'signer_name' => 'Kepala Dinas Pendidikan',
            'published_at' => now(),
        ]);

        $response = $this->get(route('id_card.verify', $token));

        $response->assertOk();
        $response->assertSee('Siti Nurhaliza');
        $response->assertSee('Masa Magang Selesai');
        $response->assertSee('Periode Magang Berakhir');
        $response->assertSee('Sertifikat Kelulusan Tersedia');
        $response->assertSee('005/DISDIK/2026');
    }

    public function test_guest_can_verify_expelled_or_cancelled_id_card(): void
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Perhubungan Banjarmasin',
            'kode_unit_kerja' => 'DISHUB-01',
            'alamat' => 'Jl. Kertak Baru',
        ]);

        $posisi = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Staff Operasional',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $peserta = User::factory()->create([
            'role' => 'peserta',
            'name' => 'Ahmad Dibatalkan',
            'asal_instansi' => 'STMIK Indonesia Banjarmasin',
        ]);
        $peserta->assignRole('peserta');

        $token = 'IDCTOKEN-REVOKED-5555555555';

        Application::create([
            'user_id' => $peserta->id,
            'internship_position_id' => $posisi->id,
            'status' => 'dibatalkan',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(2)->toDateString(),
            'token_verifikasi' => $token,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
        ]);

        $response = $this->get(route('id_card.verify', $token));

        $response->assertOk();
        $response->assertSee('ID Card Tidak Berlaku');
        $response->assertSee('Ahmad Dibatalkan');
        $response->assertSee('Dibatalkan');
    }

    public function test_guest_verifying_invalid_id_card_token_shows_error(): void
    {
        $response = $this->get(route('id_card.verify', 'TOKEN-TIDAK-ADA-12345'));

        $response->assertOk();
        $response->assertSee('ID Card Tidak Ditemukan');
        $response->assertSee('Verifikasi Gagal');
    }

    public function test_search_certificate_or_id_card_routes_appropriately(): void
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Kesehatan Banjarmasin',
            'kode_unit_kerja' => 'DINKES-01',
            'alamat' => 'Jl. Tirta Dharma',
        ]);

        $posisi = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Asisten Apoteker',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $pesertaActive = User::factory()->create(['role' => 'peserta']);
        $pesertaActive->assignRole('peserta');

        $tokenActive = 'IDCTOKEN-SEARCH-ACTIVE-1111';

        Application::create([
            'user_id' => $pesertaActive->id,
            'internship_position_id' => $posisi->id,
            'status' => 'diterima',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(2)->toDateString(),
            'token_verifikasi' => $tokenActive,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
        ]);

        // Search with active token should redirect to ID Card verification
        $resActive = $this->post(route('certificate.search'), [
            'nomor_sertifikat' => $tokenActive,
        ]);
        $resActive->assertRedirect(route('id_card.verify', $tokenActive));

        // Search with certificate number should redirect to Certificate verification
        $pesertaCert = User::factory()->create(['role' => 'peserta']);
        $pesertaCert->assignRole('peserta');

        $certNumber = '099/DINKES/2026';
        $tokenCert = 'CERT-TOKEN-SEARCH-2222';

        $appCert = Application::create([
            'user_id' => $pesertaCert->id,
            'internship_position_id' => $posisi->id,
            'status' => 'selesai',
            'tanggal_mulai' => now()->subMonths(3)->toDateString(),
            'tanggal_selesai' => now()->toDateString(),
            'nomor_sertifikat' => $certNumber,
            'token_verifikasi' => $tokenCert,
            'sertifikat_diterbitkan' => true,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
        ]);

        Certificate::create([
            'application_id' => $appCert->id,
            'nomor_sertifikat' => $certNumber,
            'token_verifikasi' => $tokenCert,
            'status' => 'active',
            'signer_name' => 'Kepala Dinkes',
            'published_at' => now(),
        ]);

        $resCert = $this->post(route('certificate.search'), [
            'nomor_sertifikat' => $certNumber,
        ]);
        $resCert->assertRedirect(route('certificate.verify', $tokenCert));
    }
}
