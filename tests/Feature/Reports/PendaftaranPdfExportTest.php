<?php

namespace Tests\Feature\Reports;

use App\Models\Application;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PendaftaranPdfExportTest extends TestCase
{
    use DatabaseTransactions;

    protected $adminKota;
    protected $adminInstansi;
    protected $instansi;
    protected $position;
    protected $application;

    protected function setUp(): void
    {
        parent::setUp();

        $this->instansi = Instansi::create([
            'nama_dinas' => 'Dinas Koperasi dan Usaha Mikro',
            'alamat' => 'Jl. Pangeran Hidayatullah No. 12',
            'kode_unit_kerja' => 'DISKOP-01',
            'contact_whatsapp' => '081122334455',
            'email' => 'diskop@banjarmasinkota.go.id',
            'nama_pejabat' => 'Drs. H. Isa Ansari, M.Si.',
            'nip_pejabat' => '197005121995031002',
            'jabatan_pejabat' => 'Kepala Dinas Koperasi dan Usaha Mikro',
        ]);

        $this->adminKota = User::factory()->create([
            'role' => 'admin_kota',
            'name' => 'Bakesbangpol Admin',
        ]);

        $this->adminInstansi = User::factory()->create([
            'role' => 'admin_instansi',
            'instansi_id' => $this->instansi->id,
            'name' => 'Admin Diskop',
        ]);

        $pl = User::factory()->create([
            'role' => 'pembimbing_lapangan',
            'instansi_id' => $this->instansi->id,
            'name' => 'Rahmat Hidayat, S.Kom.',
        ]);

        $this->position = InternshipPosition::create([
            'instansi_id' => $this->instansi->id,
            'judul_posisi' => 'Analis Data UMKM',
            'kuota' => 3,
            'deskripsi' => 'Pengolahan data binaan',
            'persyaratan' => 'Menguasai Excel dan database',
            'status' => 'buka',
            'tipe_durasi' => '3_bulan',
        ]);

        $peserta = User::factory()->create([
            'role' => 'peserta',
            'name' => 'Muhammad Rizky',
            'asal_instansi' => 'Politeknik Negeri Banjarmasin',
            'major' => 'Teknik Komputer',
            'phone' => '085233445566',
        ]);

        $this->application = Application::create([
            'user_id' => $peserta->id,
            'internship_position_id' => $this->position->id,
            'pembimbing_lapangan_id' => $pl->id,
            'nomor_registrasi' => 'REG-DISKOP-001',
            'status' => 'diterima',
            'is_automatic_placement' => true,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
        ]);
    }

    public function test_admin_kota_can_export_pendaftaran_pdf_with_filters(): void
    {
        $response = $this->actingAs($this->adminKota)->get(route('admin.laporan.pendaftaran.print', [
            'status' => 'diterima',
            'instansi_id' => $this->instansi->id,
            'search' => 'Rizky',
        ]));

        $response->assertOk();
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type') ?? '');
    }

    public function test_admin_instansi_can_export_pendaftaran_pdf_with_filters(): void
    {
        $response = $this->actingAs($this->adminInstansi)->get(route('dinas.laporan.pendaftaran.print', [
            'status' => 'diterima',
            'posisi_id' => $this->position->id,
            'search' => 'Rizky',
        ]));

        $response->assertOk();
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type') ?? '');
    }
}
