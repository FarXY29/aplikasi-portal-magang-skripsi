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
}
