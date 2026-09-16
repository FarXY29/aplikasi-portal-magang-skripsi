<?php

namespace Tests\Feature;

use App\Models\Application;
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

class InternshipPositionQualificationMatchingTest extends TestCase
{
    use DatabaseTransactions;

    private MajorCategory $catTIK;
    private MajorCategory $catEKBIS;
    private MajorCategory $catHUKUM;
    private Major $majorIlmuHukum;
    private Major $majorInformatikaS1;
    private Major $majorRPLSMK;
    private Instansi $instansi;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);

        $this->catTIK = MajorCategory::updateOrCreate(
            ['code' => 'TIK'],
            ['name' => 'Teknologi Informasi & Rekayasa Komputer', 'description' => 'TIK']
        );

        $this->catEKBIS = MajorCategory::updateOrCreate(
            ['code' => 'EKBIS'],
            ['name' => 'Ekonomi, Bisnis & Manajemen', 'description' => 'EKBIS']
        );

        $this->catHUKUM = MajorCategory::updateOrCreate(
            ['code' => 'HUKUM_AP'],
            ['name' => 'Hukum, Administrasi Publik & Kebijakan', 'description' => 'Hukum dan AP']
        );

        $this->majorIlmuHukum = Major::updateOrCreate(
            ['major_category_id' => $this->catHUKUM->id, 'name' => 'Ilmu Hukum', 'degree_level' => 'S1'],
            ['is_active' => true]
        );

        $this->majorInformatikaS1 = Major::updateOrCreate(
            ['major_category_id' => $this->catTIK->id, 'name' => 'Teknik Informatika', 'degree_level' => 'S1'],
            ['is_active' => true]
        );

        $this->majorRPLSMK = Major::updateOrCreate(
            ['major_category_id' => $this->catTIK->id, 'name' => 'Rekayasa Perangkat Lunak', 'degree_level' => 'SMK'],
            ['is_active' => true]
        );

        $this->instansi = Instansi::firstOrCreate(
            ['nama_dinas' => 'Dinas Komunikasi, Informatika dan Statistik'],
            ['alamat' => 'Jl. Balai Kota No. 1', 'kode_unit_kerja' => 'DISKOMINFOTIK']
        );
    }

    private function createPeserta(Major $major, string $majorString): User
    {
        $peserta = User::factory()->create([
            'role' => 'peserta',
            'major_id' => $major->id,
            'major' => $majorString,
            'nik' => '63710' . str_pad((string) rand(10000000000, 99999999999), 11, '0', STR_PAD_LEFT),
            'asal_instansi' => 'Universitas Lambung Mangkurat',
        ]);
        $peserta->assignRole('peserta');
        return $peserta;
    }

    public function test_law_student_cannot_match_programmer_position_with_tik_category_or_it_text(): void
    {
        $pesertaHukum = $this->createPeserta($this->majorIlmuHukum, '[S1] Ilmu Hukum');

        $posisiProgrammer = InternshipPosition::create([
            'instansi_id' => $this->instansi->id,
            'judul_posisi' => 'Programmer / Web Developer',
            'required_major_category_id' => $this->catTIK->id,
            'required_major' => 'S1 Komputer / Informatika',
            'kuota' => 5,
            'status' => 'buka',
        ]);

        $this->assertFalse(
            $posisiProgrammer->matchesUser($pesertaHukum),
            'Mahasiswa [S1] Ilmu Hukum tidak boleh lolos kualifikasi Programmer / Web Developer (TIK).'
        );
    }

    public function test_law_student_cannot_match_graphic_design_position(): void
    {
        $pesertaHukum = $this->createPeserta($this->majorIlmuHukum, '[S1] Ilmu Hukum');

        $posisiDesain = InternshipPosition::create([
            'instansi_id' => $this->instansi->id,
            'judul_posisi' => 'Desain Grafis',
            'required_major_category_id' => $this->catTIK->id,
            'required_major' => 'Desain Komunikasi Visual (SMK / S1)',
            'kuota' => 3,
            'status' => 'buka',
        ]);

        $this->assertFalse(
            $posisiDesain->matchesUser($pesertaHukum),
            'Mahasiswa [S1] Ilmu Hukum tidak boleh lolos kualifikasi Desain Grafis.'
        );
    }

    public function test_law_student_cannot_match_administration_or_office_position(): void
    {
        $pesertaHukum = $this->createPeserta($this->majorIlmuHukum, '[S1] Ilmu Hukum');

        $posisiAdmin = InternshipPosition::create([
            'instansi_id' => $this->instansi->id,
            'judul_posisi' => 'Staf Administrasi',
            'required_major_category_id' => $this->catEKBIS->id,
            'required_major' => 'S1 Administrasi / Manajemen',
            'kuota' => 3,
            'status' => 'buka',
        ]);

        $this->assertFalse(
            $posisiAdmin->matchesUser($pesertaHukum),
            'Mahasiswa [S1] Ilmu Hukum tidak boleh lolos Administrasi / Manajemen hanya karena nama kategori rumpun mengandung kata Administrasi.'
        );
    }

    public function test_law_student_can_match_law_position_or_all_majors(): void
    {
        $pesertaHukum = $this->createPeserta($this->majorIlmuHukum, '[S1] Ilmu Hukum');

        $posisiLegal = InternshipPosition::create([
            'instansi_id' => $this->instansi->id,
            'judul_posisi' => 'Staff Legal & Perundang-undangan',
            'required_major_category_id' => $this->catHUKUM->id,
            'required_major' => 'S1 Ilmu Hukum',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $posisiSemuaJurusan = InternshipPosition::create([
            'instansi_id' => $this->instansi->id,
            'judul_posisi' => 'Customer Service',
            'required_major_category_id' => null,
            'required_major' => 'Semua Jurusan',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $this->assertTrue(
            $posisiLegal->matchesUser($pesertaHukum),
            'Mahasiswa [S1] Ilmu Hukum harus lolos posisi Staff Legal (HUKUM_AP).'
        );

        $this->assertTrue(
            $posisiSemuaJurusan->matchesUser($pesertaHukum),
            'Mahasiswa [S1] Ilmu Hukum harus lolos posisi berkualifikasi Semua Jurusan.'
        );
    }

    public function test_degree_level_restriction_s1_cannot_match_smk_only_position(): void
    {
        $pesertaS1TI = $this->createPeserta($this->majorInformatikaS1, '[S1] Teknik Informatika');

        $posisiKhususSMK = InternshipPosition::create([
            'instansi_id' => $this->instansi->id,
            'judul_posisi' => 'Teknisi Jaringan Komputer',
            'required_major_category_id' => $this->catTIK->id,
            'required_major' => 'Teknik Komputer Jaringan (SMK)',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $this->assertFalse(
            $posisiKhususSMK->matchesUser($pesertaS1TI),
            'Mahasiswa [S1] tidak boleh lolos posisi yang secara eksplisit khusus (SMK).'
        );
    }

    public function test_degree_level_restriction_smk_cannot_match_s1_only_position(): void
    {
        $pesertaSMK = $this->createPeserta($this->majorRPLSMK, '[SMK] Rekayasa Perangkat Lunak');

        $posisiKhususS1 = InternshipPosition::create([
            'instansi_id' => $this->instansi->id,
            'judul_posisi' => 'Software Architect',
            'required_major_category_id' => $this->catTIK->id,
            'required_major' => 'S1 Teknik Informatika',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $this->assertFalse(
            $posisiKhususS1->matchesUser($pesertaSMK),
            'Siswa [SMK] tidak boleh lolos posisi yang secara eksplisit mensyaratkan jenjang S1.'
        );
    }

    public function test_multi_degree_or_open_position_allows_both_s1_and_smk(): void
    {
        $pesertaS1 = $this->createPeserta($this->majorInformatikaS1, '[S1] Teknik Informatika');
        $pesertaSMK = $this->createPeserta($this->majorRPLSMK, '[SMK] Rekayasa Perangkat Lunak');

        $posisiMulti = InternshipPosition::create([
            'instansi_id' => $this->instansi->id,
            'judul_posisi' => 'Junior Web Developer',
            'required_major_category_id' => $this->catTIK->id,
            'required_major' => 'Teknik Informatika / RPL (SMK / S1)',
            'kuota' => 4,
            'status' => 'buka',
        ]);

        $this->assertTrue(
            $posisiMulti->matchesUser($pesertaS1),
            'Mahasiswa [S1] harus lolos posisi yang menerima (SMK / S1).'
        );

        $this->assertTrue(
            $posisiMulti->matchesUser($pesertaSMK),
            'Siswa [SMK] harus lolos posisi yang menerima (SMK / S1).'
        );
    }

    public function test_store_application_blocks_user_with_unmatched_qualification(): void
    {
        Storage::fake('private');

        $pesertaHukum = $this->createPeserta($this->majorIlmuHukum, '[S1] Ilmu Hukum');

        $posisiProgrammer = InternshipPosition::create([
            'instansi_id' => $this->instansi->id,
            'judul_posisi' => 'Backend Developer',
            'required_major_category_id' => $this->catTIK->id,
            'required_major' => 'S1 Komputer / Informatika',
            'kuota' => 5,
            'status' => 'buka',
        ]);

        $file = UploadedFile::fake()->create('surat.pdf', 200, 'application/pdf');

        $response = $this->actingAs($pesertaHukum)->post(route('peserta.daftar', $posisiProgrammer->id), [
            'tanggal_mulai' => now()->addDays(7)->format('Y-m-d'),
            'tanggal_selesai' => now()->addDays(37)->format('Y-m-d'),
            'surat' => $file,
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('applications', [
            'user_id' => $pesertaHukum->id,
            'internship_position_id' => $posisiProgrammer->id,
        ]);
    }
}

