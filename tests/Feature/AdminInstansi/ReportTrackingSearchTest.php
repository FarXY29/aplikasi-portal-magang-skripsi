<?php

namespace Tests\Feature\AdminInstansi;

use App\Models\Application;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ReportTrackingSearchTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_instansi_can_search_pendaftaran_by_major_and_name(): void
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Kominfo Test',
            'kode_unit_kerja' => 'DISKOM-01',
            'alamat' => 'Jl. Pangeran Samudra No. 1',
            'nama_pejabat' => 'Pejabat Test',
            'nip_pejabat' => '198001012005011001',
        ]);

        $admin = User::factory()->create([
            'role' => 'admin_instansi',
            'instansi_id' => $instansi->id,
        ]);

        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Software Engineer Intern',
            'kuota' => 5,
            'deskripsi' => 'Deskripsi lowongan',
            'persyaratan' => 'Persyaratan lowongan',
            'status' => 'buka',
            'tipe_durasi' => '3_bulan',
        ]);

        $peserta = User::factory()->create([
            'role' => 'peserta',
            'name' => 'Budi Setiawan',
            'major' => 'Teknik Informatika',
            'asal_instansi' => 'Universitas Lambung Mangkurat',
        ]);

        $app = Application::create([
            'user_id' => $peserta->id,
            'internship_position_id' => $position->id,
            'nomor_registrasi' => 'REG-202608-00001',
            'status' => 'pending',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
        ]);

        // Search by major keyword
        $response = $this->actingAs($admin)->get(route('dinas.laporan.pendaftaran', [
            'search' => 'Informatika',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Budi Setiawan');
        $response->assertSee('REG-202608-00001');

        // Search by registration number
        $response2 = $this->actingAs($admin)->get(route('dinas.laporan.pendaftaran', [
            'search' => 'REG-202608',
        ]));

        $response2->assertStatus(200);
        $response2->assertSee('Budi Setiawan');
    }

    public function test_admin_instansi_laporan_pendaftaran_renders_interactive_ui_and_tracking_modal(): void
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Pendidikan Test',
            'kode_unit_kerja' => 'DISDIK-01',
            'alamat' => 'Jl. Lambung Mangkurat No. 2',
            'nama_pejabat' => 'Kadis Test',
            'nip_pejabat' => '198202022006021002',
        ]);

        $admin = User::factory()->create([
            'role' => 'admin_instansi',
            'instansi_id' => $instansi->id,
        ]);

        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Staff Administrasi',
            'kuota' => 2,
            'deskripsi' => 'Deskripsi',
            'persyaratan' => 'Syarat',
            'status' => 'buka',
            'tipe_durasi' => '3_bulan',
        ]);

        $peserta = User::factory()->create([
            'role' => 'peserta',
            'name' => 'Siti Nurhaliza',
            'major' => 'Manajemen Pendidikan',
            'asal_instansi' => 'STKIP PGRI',
            'phone' => '081234567890',
        ]);

        $app = Application::create([
            'user_id' => $peserta->id,
            'internship_position_id' => $position->id,
            'nomor_registrasi' => 'REG-DISDIK-001',
            'status' => 'diterima',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(2)->toDateString(),
            'surat_pengantar_path' => 'surat_pengantar/surat_test.pdf',
            'cv_path' => 'cv/cv_test.pdf',
        ]);

        $response = $this->actingAs($admin)->get(route('dinas.laporan.pendaftaran'));

        $response->assertStatus(200);
        // Stats Cards & Banner
        $response->assertSee('Laporan Pendaftaran & Pelacakan Permohonan');
        $response->assertSee('Total Pendaftar');
        $response->assertSee('Pending (Baru)');
        $response->assertSee('Daftar Tunggu');
        $response->assertSee('Diterima / Aktif');
        $response->assertSee('Ditolak');
        $response->assertSee('Selesai Magang');
        $response->assertSee('Pelacakan Status Real-Time di Instansi');

        // Interactive Toolbar Pills
        $response->assertSee('statusFilter');
        $response->assertSee('clientSearch');
        $response->assertSee('Filter cepat tabel di layar...');

        // Dual-View Elements
        $response->assertSee('Daftar Pelacakan Permohonan Magang');
        $response->assertSee('REG-DISDIK-001');
        $response->assertSee('Siti Nurhaliza');
        $response->assertSee('Staff Administrasi');
        $response->assertSee('openDetail');

        // Modal Tracking Elements
        $response->assertSee('Detail & Pelacakan Permohonan', false);
        $response->assertSee('Identitas Pemohon');
        $response->assertSee('Berkas Persyaratan');
        $response->assertSee('Riwayat Mutasi Status & Pelacakan', false);
        $response->assertSee('surat_test.pdf');
        $response->assertSee('cv_test.pdf');
    }
}
