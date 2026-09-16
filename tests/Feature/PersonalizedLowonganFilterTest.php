<?php

namespace Tests\Feature;

use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\Major;
use App\Models\MajorCategory;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PersonalizedLowonganFilterTest extends TestCase
{
    use DatabaseTransactions;

    private MajorCategory $catTIK;
    private MajorCategory $catHUKUM;
    private Major $majorIlmuHukum;
    private Major $majorInformatika;
    private Instansi $instansi;
    private InternshipPosition $posisiProgrammer;
    private InternshipPosition $posisiLegal;
    private InternshipPosition $posisiSemuaJurusan;
    private InternshipPosition $posisiDesain;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);

        $this->catTIK = MajorCategory::updateOrCreate(
            ['code' => 'TIK'],
            ['name' => 'Teknologi Informasi & Rekayasa Komputer', 'description' => 'TIK']
        );

        $this->catHUKUM = MajorCategory::updateOrCreate(
            ['code' => 'HUKUM_AP'],
            ['name' => 'Hukum, Administrasi Publik & Kebijakan', 'description' => 'Hukum dan AP']
        );

        $this->majorIlmuHukum = Major::updateOrCreate(
            ['major_category_id' => $this->catHUKUM->id, 'name' => 'Ilmu Hukum', 'degree_level' => 'S1'],
            ['is_active' => true]
        );

        $this->majorInformatika = Major::updateOrCreate(
            ['major_category_id' => $this->catTIK->id, 'name' => 'Teknik Informatika', 'degree_level' => 'S1'],
            ['is_active' => true]
        );

        $this->instansi = Instansi::firstOrCreate(
            ['nama_dinas' => 'Dinas Komunikasi, Informatika dan Statistik'],
            ['alamat' => 'Jl. Balai Kota No. 1', 'kode_unit_kerja' => 'DISKOMINFOTIK']
        );

        // Buat 4 posisi uji
        $this->posisiProgrammer = InternshipPosition::create([
            'instansi_id' => $this->instansi->id,
            'judul_posisi' => 'Programmer / Web Developer',
            'required_major_category_id' => $this->catTIK->id,
            'required_major' => 'S1 Teknik Informatika / Sistem Informasi',
            'kuota' => 5,
            'status' => 'buka',
        ]);

        $this->posisiDesain = InternshipPosition::create([
            'instansi_id' => $this->instansi->id,
            'judul_posisi' => 'Desain Grafis',
            'required_major_category_id' => $this->catTIK->id,
            'required_major' => 'Desain Komunikasi Visual (SMK / S1)',
            'kuota' => 3,
            'status' => 'buka',
        ]);

        $this->posisiLegal = InternshipPosition::create([
            'instansi_id' => $this->instansi->id,
            'judul_posisi' => 'Staff Legal & Perundang-undangan',
            'required_major_category_id' => $this->catHUKUM->id,
            'required_major' => 'S1 Ilmu Hukum',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $this->posisiSemuaJurusan = InternshipPosition::create([
            'instansi_id' => $this->instansi->id,
            'judul_posisi' => 'Customer Service',
            'required_major_category_id' => null,
            'required_major' => 'Semua Jurusan',
            'kuota' => 4,
            'status' => 'buka',
        ]);
    }

    private function createPeserta(?Major $major, ?string $majorString): User
    {
        $peserta = User::factory()->create([
            'role' => 'peserta',
            'major_id' => $major?->id,
            'major' => $majorString,
            'nik' => '63710' . str_pad((string) rand(10000000000, 99999999999), 11, '0', STR_PAD_LEFT),
            'asal_instansi' => 'Universitas Lambung Mangkurat',
        ]);
        $peserta->assignRole('peserta');
        return $peserta;
    }

    public function test_guest_sees_all_open_vacancies(): void
    {
        $response = $this->get(route('home'));
        $response->assertOk();

        $lowongans = $response->viewData('lowongans');
        $lowonganIds = $lowongans->pluck('id');

        $this->assertTrue($lowonganIds->contains($this->posisiProgrammer->id));
        $this->assertTrue($lowonganIds->contains($this->posisiLegal->id));
        $this->assertTrue($lowonganIds->contains($this->posisiSemuaJurusan->id));
        $this->assertTrue($lowonganIds->contains($this->posisiDesain->id));
    }

    public function test_logged_in_participant_with_law_major_defaults_to_matching_vacancies_only(): void
    {
        $pesertaHukum = $this->createPeserta($this->majorIlmuHukum, '[S1] Ilmu Hukum');

        $response = $this->actingAs($pesertaHukum)->get(route('home'));
        $response->assertOk();

        $this->assertTrue($response->viewData('userHasMajor'));
        $this->assertEquals('my_major', $response->viewData('filterJurusanScope'));

        $lowongans = $response->viewData('lowongans');
        $lowonganIds = $lowongans->pluck('id');

        // Lowongan yang cocok (Hukum & Semua Jurusan) harus muncul
        $this->assertTrue(
            $lowonganIds->contains($this->posisiLegal->id),
            'Lowongan Staff Legal harus tampil untuk peserta Ilmu Hukum.'
        );
        $this->assertTrue(
            $lowonganIds->contains($this->posisiSemuaJurusan->id),
            'Lowongan Semua Jurusan harus tampil untuk peserta Ilmu Hukum.'
        );

        // Lowongan yang TIDAK cocok (Programmer & Desain Grafis) TIDAK boleh muncul di default view
        $this->assertFalse(
            $lowonganIds->contains($this->posisiProgrammer->id),
            'Lowongan Programmer TIDAK boleh tampil pada filter default peserta Ilmu Hukum.'
        );
        $this->assertFalse(
            $lowonganIds->contains($this->posisiDesain->id),
            'Lowongan Desain Grafis TIDAK boleh tampil pada filter default peserta Ilmu Hukum.'
        );
    }

    public function test_logged_in_participant_can_toggle_to_all_vacancies(): void
    {
        $pesertaHukum = $this->createPeserta($this->majorIlmuHukum, '[S1] Ilmu Hukum');

        $response = $this->actingAs($pesertaHukum)->get(route('home', ['filter_jurusan' => 'all']));
        $response->assertOk();

        $this->assertEquals('all', $response->viewData('filterJurusanScope'));

        $lowongans = $response->viewData('lowongans');
        $lowonganIds = $lowongans->pluck('id');

        // Saat memilih melihat semua, seluruh lowongan harus muncul
        $this->assertTrue($lowonganIds->contains($this->posisiProgrammer->id));
        $this->assertTrue($lowonganIds->contains($this->posisiLegal->id));
        $this->assertTrue($lowonganIds->contains($this->posisiSemuaJurusan->id));
        $this->assertTrue($lowonganIds->contains($this->posisiDesain->id));
    }

    public function test_logged_in_participant_without_major_sees_all_vacancies_and_receives_prompt(): void
    {
        $pesertaTanpaJurusan = $this->createPeserta(null, null);

        $response = $this->actingAs($pesertaTanpaJurusan)->get(route('home'));
        $response->assertOk();

        $this->assertFalse($response->viewData('userHasMajor'));
        $this->assertEquals('all', $response->viewData('filterJurusanScope'));

        $lowongans = $response->viewData('lowongans');
        $lowonganIds = $lowongans->pluck('id');

        $this->assertTrue($lowonganIds->contains($this->posisiProgrammer->id));
        $this->assertTrue($lowonganIds->contains($this->posisiLegal->id));
    }
}

