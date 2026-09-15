<?php

namespace Tests\Feature\AdminKota;

use App\Models\Application;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportApplicationAndRekapGlobalTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminKota;
    protected User $peserta1;
    protected User $peserta2;
    protected User $peserta3;
    protected Instansi $instansiA;
    protected Instansi $instansiB;
    protected InternshipPosition $posisiA;
    protected InternshipPosition $posisiB;
    protected Application $app1;
    protected Application $app2;
    protected Application $app3;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminKota = User::factory()->create([
            'role' => 'admin_kota',
            'name' => 'Super Admin Pemko',
        ]);

        $this->instansiA = Instansi::create([
            'nama_dinas' => 'Dinas Komunikasi, Informatika dan Statistik',
            'alamat' => 'Jl. RE Martadinata No. 1, Banjarmasin',
            'kode_unit_kerja' => 'DISKOMINFO-01',
            'contact_whatsapp' => '081234567890',
            'nama_pejabat' => 'Dr. H. Windiasti Kartika, S.T., M.T.',
            'nip_pejabat' => '197501012000031001',
            'jabatan_pejabat' => 'Kepala Diskominfotik',
        ]);

        $this->instansiB = Instansi::create([
            'nama_dinas' => 'Badan Kepegawaian Daerah',
            'alamat' => 'Jl. RE Martadinata No. 2, Banjarmasin',
            'kode_unit_kerja' => 'BKD-01',
            'contact_whatsapp' => '081234567891',
            'nama_pejabat' => 'Drs. Totok Agus Daryanto, M.Pd.',
            'nip_pejabat' => '196801011990031002',
            'jabatan_pejabat' => 'Kepala BKD',
        ]);

        $this->posisiA = InternshipPosition::create([
            'instansi_id' => $this->instansiA->id,
            'judul_posisi' => 'Software Engineer Intern',
            'deskripsi' => 'Pengembangan sistem informasi',
            'persyaratan' => 'Laravel, Vue/Blade',
            'kuota' => 5,
            'status' => 'buka',
        ]);

        $this->posisiB = InternshipPosition::create([
            'instansi_id' => $this->instansiB->id,
            'judul_posisi' => 'Staff Administrasi Magang',
            'deskripsi' => 'Pengelolaan arsip digital',
            'persyaratan' => 'MS Office, Ketelitian',
            'kuota' => 3,
            'status' => 'buka',
        ]);

        $this->peserta1 = User::factory()->create([
            'role' => 'peserta',
            'name' => 'Budi Santoso',
            'email' => 'budi@ulm.ac.id',
            'asal_instansi' => 'Universitas Lambung Mangkurat',
            'nik' => '6371012010817001',
            'major' => 'Teknologi Informasi',
        ]);

        $this->peserta2 = User::factory()->create([
            'role' => 'peserta',
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@poliban.ac.id',
            'asal_instansi' => 'Politeknik Negeri Banjarmasin',
            'nik' => '6371012010817002',
            'major' => 'Teknik Informatika',
        ]);

        $this->peserta3 = User::factory()->create([
            'role' => 'peserta',
            'name' => 'Rian Hidayat',
            'email' => 'rian@ulm.ac.id',
            'asal_instansi' => 'Universitas Lambung Mangkurat',
            'nik' => '6371012010817003',
            'major' => 'Ilmu Komputer',
        ]);

        $this->app1 = Application::create([
            'user_id' => $this->peserta1->id,
            'internship_position_id' => $this->posisiA->id,
            'status' => 'diterima',
            'tanggal_mulai' => now()->subDays(10)->toDateString(),
            'tanggal_selesai' => now()->addDays(50)->toDateString(),
            'cv_path' => 'dummy1.pdf',
            'surat_pengantar_path' => 'dummy_sp1.pdf',
            'nomor_registrasi' => 'REG-2026-0001',
        ]);

        $this->app2 = Application::create([
            'user_id' => $this->peserta2->id,
            'internship_position_id' => $this->posisiB->id,
            'status' => 'selesai',
            'tanggal_mulai' => now()->subMonths(3)->toDateString(),
            'tanggal_selesai' => now()->subDays(5)->toDateString(),
            'cv_path' => 'dummy2.pdf',
            'surat_pengantar_path' => 'dummy_sp2.pdf',
            'nomor_registrasi' => 'REG-2026-0002',
        ]);

        $this->app3 = Application::create([
            'user_id' => $this->peserta3->id,
            'internship_position_id' => $this->posisiA->id,
            'status' => 'pending',
            'cv_path' => 'dummy3.pdf',
            'surat_pengantar_path' => 'dummy_sp3.pdf',
            'nomor_registrasi' => 'REG-2026-0003',
        ]);
    }

    public function test_admin_kota_can_view_laporan_pendaftaran_page(): void
    {
        $response = $this->actingAs($this->adminKota)
            ->get(route('admin.laporan.pendaftaran'));

        $response->assertStatus(200);
        $response->assertViewIs('admin_kota.laporan.pendaftaran');
        $response->assertViewHasAll(['applications', 'stats', 'positions', 'listDinas']);
        $response->assertSee('Laporan Pendaftaran & Pelacakan Permohonan');
        $response->assertSee('Budi Santoso');
        $response->assertSee('Siti Nurhaliza');
        $response->assertSee('Rian Hidayat');
    }

    public function test_admin_kota_can_filter_laporan_pendaftaran(): void
    {
        // Filter by Instansi A
        $response = $this->actingAs($this->adminKota)
            ->get(route('admin.laporan.pendaftaran', [
                'instansi_id' => $this->instansiA->id,
            ]));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee('Rian Hidayat');
        $response->assertDontSee('Siti Nurhaliza');

        // Filter by Status "diterima"
        $responseStatus = $this->actingAs($this->adminKota)
            ->get(route('admin.laporan.pendaftaran', [
                'status' => 'diterima',
            ]));

        $responseStatus->assertStatus(200);
        $responseStatus->assertSee('Budi Santoso');
        $responseStatus->assertDontSee('Rian Hidayat');
    }

    public function test_admin_kota_can_print_laporan_pendaftaran_pdf(): void
    {
        $response = $this->actingAs($this->adminKota)
            ->get(route('admin.laporan.pendaftaran.print'));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }

    public function test_admin_kota_laporan_peserta_global_includes_rekap_kampus_matrix(): void
    {
        $response = $this->actingAs($this->adminKota)
            ->get(route('admin.laporan.peserta_global'));

        $response->assertStatus(200);
        $response->assertViewHas('rekapKampus');
        $response->assertSee('Rekapitulasi Asal Instansi Pendidikan');
        $response->assertSee('Universitas Lambung Mangkurat');
        $response->assertSee('Politeknik Negeri Banjarmasin');

        /** @var \Illuminate\Support\Collection $rekapKampus */
        $rekapKampus = $response->viewData('rekapKampus');
        $this->assertNotEmpty($rekapKampus);

        $ulm = $rekapKampus->firstWhere('asal_instansi', 'Universitas Lambung Mangkurat');
        $this->assertNotNull($ulm);
        $this->assertEquals(1, $ulm->total_aktif);
        $this->assertEquals(0, $ulm->total_selesai);
        $this->assertEquals(1, $ulm->total_pending);
        $this->assertEquals(2, $ulm->total_peserta);

        $poliban = $rekapKampus->firstWhere('asal_instansi', 'Politeknik Negeri Banjarmasin');
        $this->assertNotNull($poliban);
        $this->assertEquals(0, $poliban->total_aktif);
        $this->assertEquals(1, $poliban->total_selesai);
        $this->assertEquals(1, $poliban->total_peserta);
    }

    public function test_admin_kota_can_print_laporan_peserta_global_pdf(): void
    {
        $response = $this->actingAs($this->adminKota)
            ->get(route('admin.laporan.peserta_global.print'));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }

    public function test_non_admin_kota_cannot_access_laporan_pendaftaran(): void
    {
        $response = $this->actingAs($this->peserta1)
            ->get(route('admin.laporan.pendaftaran'));

        $response->assertStatus(403);
    }

    public function test_admin_kota_can_access_cv_document_via_storage_route(): void
    {
        \Illuminate\Support\Facades\Storage::disk('private')->put('documents/cv/dummy1.pdf', 'dummy cv content');
        $this->app1->update(['cv_path' => 'documents/cv/dummy1.pdf']);

        $response = $this->actingAs($this->adminKota)
            ->get(route('storage.access', ['type' => 'cv', 'filename' => 'dummy1.pdf']));

        $response->assertStatus(200);
        $this->assertStringContainsString('dummy cv content', $response->streamedContent());
    }

    public function test_laporan_pendaftaran_eager_loads_timelines_and_renders_tracking_ui(): void
    {
        $this->app1->recordTimeline('submitted', null, 'pending', ['notes' => 'Pendaftaran online']);

        $response = $this->actingAs($this->adminKota)
            ->get(route('admin.laporan.pendaftaran'));

        $response->assertStatus(200);
        $response->assertSee('Detail & Pelacakan Permohonan', false);
        $response->assertSee('Daftar Pelacakan Permohonan Magang', false);
        $response->assertSee('REG-2026-0001', false);
    }

    public function test_admin_kota_can_filter_laporan_peserta_global_by_preset_periode(): void
    {
        // 1 Bulan preset
        $response1 = $this->actingAs($this->adminKota)
            ->get(route('admin.laporan.peserta_global', ['periode_preset' => '1_bulan']));
        $response1->assertStatus(200);
        $response1->assertSee('Filter Cepat Periode:');
        $response1->assertSee('Periode 1 Bulan Terakhir Aktif');

        // 3 Bulan preset
        $response3 = $this->actingAs($this->adminKota)
            ->get(route('admin.laporan.peserta_global', ['periode_preset' => '3_bulan']));
        $response3->assertStatus(200);
        $response3->assertSee('Periode 3 Bulan (Triwulan) Aktif');

        // Semester preset
        $responseSem = $this->actingAs($this->adminKota)
            ->get(route('admin.laporan.peserta_global', ['periode_preset' => 'semester']));
        $responseSem->assertStatus(200);
        $responseSem->assertSee('Periode 1 Semester (6 Bulan) Aktif');

        // Tahun preset
        $responseTahun = $this->actingAs($this->adminKota)
            ->get(route('admin.laporan.peserta_global', ['periode_preset' => 'tahun']));
        $responseTahun->assertStatus(200);
        $responseTahun->assertSee('Periode 1 Tahun Aktif');
    }

    public function test_admin_kota_can_print_laporan_peserta_global_pdf_with_preset(): void
    {
        $response = $this->actingAs($this->adminKota)
            ->get(route('admin.laporan.peserta_global.print', ['periode_preset' => 'semester']));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }
}
