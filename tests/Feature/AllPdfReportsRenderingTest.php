<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Attendance;
use App\Models\DailyLog;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use App\Services\PdfExportService;
use App\Services\ReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AllPdfReportsRenderingTest extends TestCase
{
    use RefreshDatabase;

    protected $adminKota;
    protected $adminInstansi;
    protected $pembimbing;
    protected $peserta;
    protected $instansi;
    protected $position;
    protected $appModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->instansi = Instansi::create([
            'nama_dinas' => 'Dinas Komunikasi, Informatika dan Statistik',
            'alamat' => 'Jl. RE Martadinata No. 1, Banjarmasin',
            'kode_unit_kerja' => 'DISKOMINFO-01',
            'contact_whatsapp' => '081234567890',
            'nama_pejabat' => 'Dr. H. Windiasti Kartika, S.T., M.T.',
            'nip_pejabat' => '197501012000031001',
            'jabatan_pejabat' => 'Kepala Dinas Komunikasi, Informatika dan Statistik',
        ]);

        $this->adminKota = User::factory()->create([
            'role' => 'admin_kota',
            'name' => 'Super Admin Bakesbangpol',
        ]);

        $this->adminInstansi = User::factory()->create([
            'role' => 'admin_instansi',
            'instansi_id' => $this->instansi->id,
            'name' => 'Admin Diskominfo',
        ]);

        $this->pembimbing = User::factory()->create([
            'role' => 'pembimbing_lapangan',
            'instansi_id' => $this->instansi->id,
            'name' => 'Pembimbing IT',
            'nik' => '198501012010011002',
        ]);

        $this->peserta = User::factory()->create([
            'role' => 'peserta',
            'name' => 'Ahmad Fauzi',
            'nik' => '6371012345670001',
            'asal_instansi' => 'Universitas Lambung Mangkurat',
            'major' => 'Teknik Informatika',
        ]);

        $this->position = InternshipPosition::create([
            'instansi_id' => $this->instansi->id,
            'judul_posisi' => 'Web Developer',
            'deskripsi' => 'Pengembangan portal',
            'persyaratan' => 'PHP, Laravel',
            'kuota' => 5,
            'status' => 'buka',
        ]);

        $this->appModel = Application::create([
            'user_id' => $this->peserta->id,
            'internship_position_id' => $this->position->id,
            'pembimbing_lapangan_id' => $this->pembimbing->id,
            'status' => 'diterima',
            'tanggal_mulai' => now()->subMonth()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
            'cv_path' => 'dummy.pdf',
            'surat_pengantar_path' => 'dummy_sp.pdf',
        ]);

        Attendance::create([
            'application_id' => $this->appModel->id,
            'date' => now()->toDateString(),
            'clock_in' => '07:55:00',
            'clock_out' => '16:05:00',
            'status' => 'hadir',
        ]);

        DailyLog::create([
            'application_id' => $this->appModel->id,
            'tanggal' => now()->toDateString(),
            'kegiatan' => 'Mengerjakan perbaikan modul pelaporan dan kop surat',
            'status_validasi' => 'disetujui',
        ]);
    }

    public function test_all_admin_kota_pdf_reports_render_properly()
    {
        $this->actingAs($this->adminKota);

        $routes = [
            route('admin.laporan.print'),
            route('admin.laporan.peserta_global.print'),
            route('admin.laporan.penyerapan_kuota.print'),
            route('admin.instansi.print_pdf'),
            route('admin.laporan.instansi_disiplin.print'),
            route('admin.laporan.demografi_jurusan.print'),
            route('admin.laporan.durasi_magang.print'),
            route('admin.laporan.grading.print'),
            route('admin.certificates.export_pdf'),
            route('admin.users.peserta.pdf'),
        ];

        foreach ($routes as $url) {
            $response = $this->get($url);
            $response->assertOk();
            $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type') ?? '');
        }
    }

    public function test_all_admin_instansi_pdf_reports_render_properly()
    {
        $this->actingAs($this->adminInstansi);

        $routes = [
            route('dinas.laporan.rekap.print'),
            route('dinas.peserta.absensi.pdf', $this->appModel->id),
            route('dinas.laporan.jurnal_harian.print'),
            route('dinas.laporan.beban_pembimbing.print'),
            route('dinas.laporan.kinerja_peserta.print'),
            route('dinas.laporan.demografi_kampus.print'),
        ];

        foreach ($routes as $url) {
            $response = $this->get($url);
            $response->assertOk();
            $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type') ?? '');
        }
    }
}
