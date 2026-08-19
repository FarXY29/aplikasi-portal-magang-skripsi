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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MultiRoleHighPriorityIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_admin_instansi_can_create_lowongan_with_major_category(): void
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Komunikasi dan Informatika',
            'alamat' => 'Jl. RE Martadinata No. 1 Banjarmasin',
            'kode_unit_kerja' => 'DISKOMINFO-BJM',
        ]);

        $adminDinas = User::factory()->create([
            'role' => 'admin_instansi',
            'instansi_id' => $instansi->id,
        ]);
        $adminDinas->assignRole('admin_instansi');

        $category = MajorCategory::create([
            'code' => 'TIK',
            'name' => 'Teknologi Informasi & Komputer',
        ]);

        $response = $this->actingAs($adminDinas)->post(route('dinas.lowongan.store'), [
            'judul_posisi' => 'Fullstack Web Developer',
            'required_major_category_id' => $category->id,
            'required_major' => 'Teknik Informatika / Sistem Informasi',
            'deskripsi' => 'Pengembangan portal layanan publik kota',
            'kuota' => 3,
            'batas_daftar' => now()->addMonth()->toDateString(),
        ]);

        $response->assertRedirect(route('dinas.lowongan.index'));
        $this->assertDatabaseHas('internship_positions', [
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Fullstack Web Developer',
            'required_major_category_id' => $category->id,
            'kuota' => 3,
        ]);
    }

    public function test_peserta_major_category_qualification_check(): void
    {
        Storage::fake('private');

        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Kesehatan Kota Banjarmasin',
            'alamat' => 'Jl. Pramuka Banjarmasin',
            'kode_unit_kerja' => 'DINKES-BJM',
        ]);

        $categoryHealth = MajorCategory::create([
            'code' => 'KESH',
            'name' => 'Kesehatan Masyarakat & Medis',
        ]);

        $categoryIT = MajorCategory::create([
            'code' => 'TIK',
            'name' => 'Teknologi Informasi & Komputer',
        ]);

        $majorHealth = Major::create([
            'major_category_id' => $categoryHealth->id,
            'name' => 'Epidemiologi Kesehatan',
            'degree_level' => 'S1',
            'is_active' => true,
        ]);

        $majorIT = Major::create([
            'major_category_id' => $categoryIT->id,
            'name' => 'Teknik Informatika',
            'degree_level' => 'S1',
            'is_active' => true,
        ]);

        $positionHealth = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Analis Data Epidemiologi',
            'required_major_category_id' => $categoryHealth->id,
            'required_major' => 'Kesehatan Masyarakat',
            'deskripsi' => 'Analisis data kesehatan',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $pesertaIT = User::factory()->create([
            'role' => 'peserta',
            'major_id' => $majorIT->id,
            'major' => 'Teknik Informatika',
            'nik' => '6371012345678901',
            'asal_instansi' => 'Universitas Lambung Mangkurat',
        ]);
        $pesertaIT->assignRole('peserta');

        // Peserta IT applying to Health position should be rejected
        $responseFail = $this->actingAs($pesertaIT)->get(route('peserta.daftar.form', $positionHealth->id));
        $responseFail->assertRedirect(route('home'));
        $responseFail->assertSessionHas('error');

        // Peserta Health applying should succeed to view apply form
        $pesertaHealth = User::factory()->create([
            'role' => 'peserta',
            'major_id' => $majorHealth->id,
            'major' => 'Epidemiologi Kesehatan',
            'nik' => '6371012345678902',
            'asal_instansi' => 'Universitas Lambung Mangkurat',
        ]);
        $pesertaHealth->assignRole('peserta');

        $responseSuccess = $this->actingAs($pesertaHealth)->get(route('peserta.daftar.form', $positionHealth->id));
        $responseSuccess->assertOk();
        $responseSuccess->assertViewIs('peserta.apply');
    }

    public function test_peserta_blocked_from_downloading_revoked_certificate(): void
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Pendidikan Kota Banjarmasin',
            'alamat' => 'Jl. Kapten Piere Tendean',
            'kode_unit_kerja' => 'DISDIK-BJM',
        ]);

        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Asisten Edukasi',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $peserta = User::factory()->create([
            'role' => 'peserta',
            'nik' => '6371019999990001',
            'asal_instansi' => 'Universitas Lambung Mangkurat',
        ]);
        $peserta->assignRole('peserta');

        $application = Application::create([
            'user_id' => $peserta->id,
            'internship_position_id' => $position->id,
            'status' => 'selesai',
            'tanggal_mulai' => now()->subMonths(3)->toDateString(),
            'tanggal_selesai' => now()->subDay()->toDateString(),
            'nilai_rata_rata' => 90,
            'nomor_sertifikat' => '001/DISDIK/2026',
            'token_verifikasi' => 'TOKENREVOKEDTEST123',
            'saran_peserta' => 'Pengalaman yang sangat baik.',
            'cv_path' => 'documents/surat/dummy_cv.pdf',
            'surat_pengantar_path' => 'documents/surat/dummy_surat.pdf',
        ]);

        $cert = Certificate::create([
            'application_id' => $application->id,
            'nomor_sertifikat' => $application->nomor_sertifikat,
            'token_verifikasi' => $application->token_verifikasi,
            'status' => 'revoked',
            'revoked_at' => now(),
            'revoked_reason' => 'Pelanggaran integritas data peserta.',
            'published_at' => now()->subDays(5),
        ]);

        $response = $this->actingAs($peserta)->get(route('peserta.sertifikat', $application->id));
        $response->assertRedirect(route('peserta.dashboard'));
        $response->assertSessionHas('error');

        // Public verify should show revoked screen
        $publicVerify = $this->get(route('certificate.verify', $cert->token_verifikasi));
        $publicVerify->assertOk();
        $publicVerify->assertSee('Sertifikat Dibatalkan');
        $publicVerify->assertSee('Pelanggaran integritas data peserta.');
    }

    public function test_peserta_with_s1_teknik_informatika_matches_s1_komputer_informatika_position(): void
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Komunikasi dan Informatika',
            'alamat' => 'Jl. RE Martadinata',
            'kode_unit_kerja' => 'DISKOMINFO-TEST',
        ]);

        $categoryIT = MajorCategory::create([
            'code' => 'TIK',
            'name' => 'Teknologi Informasi & Komputer',
        ]);

        $majorIT = Major::create([
            'major_category_id' => $categoryIT->id,
            'name' => 'Teknik Informatika',
            'degree_level' => 'S1',
            'is_active' => true,
        ]);

        // Position with seeded text "S1 Komputer / Informatika"
        $positionKomputer = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Junior Web Programmer',
            'required_major' => 'S1 Komputer / Informatika',
            'required_major_category_id' => null,
            'kuota' => 2,
            'status' => 'buka',
        ]);

        // Position with category relation
        $positionCategory = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'IT Support',
            'required_major' => 'Teknologi Informasi & Komputer',
            'required_major_category_id' => $categoryIT->id,
            'kuota' => 2,
            'status' => 'buka',
        ]);

        // Position with unrelated requirement "S1 Akuntansi"
        $positionAkuntansi = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Staff Keuangan',
            'required_major' => 'S1 Akuntansi',
            'required_major_category_id' => null,
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $peserta = User::factory()->create([
            'role' => 'peserta',
            'major_id' => $majorIT->id,
            'major' => '[S1] Teknik Informatika',
            'nik' => '6371010000000001',
            'asal_instansi' => 'Universitas Lambung Mangkurat',
        ]);
        $peserta->assignRole('peserta');

        // Check model method matchesUser
        $this->assertTrue($positionKomputer->matchesUser($peserta));
        $this->assertTrue($positionCategory->matchesUser($peserta));
        $this->assertFalse($positionAkuntansi->matchesUser($peserta));

        // Can access apply form for S1 Komputer / Informatika
        $response = $this->actingAs($peserta)->get(route('peserta.daftar.form', $positionKomputer->id));
        $response->assertOk();
        $response->assertViewIs('peserta.apply');

        // Blocked for S1 Akuntansi
        $responseBlocked = $this->actingAs($peserta)->get(route('peserta.daftar.form', $positionAkuntansi->id));
        $responseBlocked->assertRedirect(route('home'));
        $responseBlocked->assertSessionHas('error');
    }
}
