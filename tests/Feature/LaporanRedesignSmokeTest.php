<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;

class LaporanRedesignSmokeTest extends TestCase
{
    use DatabaseTransactions;

    private function adminKota(): User
    {
        return User::factory()->create(['role' => 'admin_kota']);
    }

    public function test_durasi_magang_renders(): void
    {
        $this->actingAs($this->adminKota())
            ->get(route('admin.laporan.durasi_magang'))
            ->assertOk()
            ->assertSee('Rata-Rata Durasi Magang Instansi')
            ->assertSee('chart-top-durasi');
    }

    public function test_instansi_disiplin_renders(): void
    {
        $this->actingAs($this->adminKota())
            ->get(route('admin.laporan.instansi_disiplin'))
            ->assertOk()
            ->assertSee('Analisis Kedisiplinan Instansi')
            ->assertSee('chart-top-disiplin')
            ->assertSee('chart-pelanggaran');
    }

    public function test_penyerapan_kuota_renders(): void
    {
        $this->actingAs($this->adminKota())
            ->get(route('admin.laporan.penyerapan_kuota'))
            ->assertOk()
            ->assertSee('Laporan Penyerapan Kuota')
            ->assertSee('chart-top-penyerapan')
            ->assertSee('chart-status-kuota');
    }

    public function test_demografi_jurusan_renders(): void
    {
        $this->actingAs($this->adminKota())
            ->get(route('admin.laporan.demografi_jurusan'))
            ->assertOk()
            ->assertSee('Demografi Kualifikasi Jurusan')
            ->assertSee('chart-top-jurusan');
    }

    public function test_peserta_global_renders(): void
    {
        $this->actingAs($this->adminKota())
            ->get(route('admin.laporan.peserta_global'))
            ->assertOk()
            ->assertSee('Rekapitulasi Global Peserta Magang')
            ->assertSee('chart-status-global');
    }

    public function test_grading_renders(): void
    {
        $this->actingAs($this->adminKota())
            ->get(route('admin.laporan.grading'))
            ->assertOk()
            ->assertSee('Analisis Kompetensi & Performa')
            ->assertSee('chart-predikat');
    }

    public function test_laporan_instansi_renders(): void
    {
        $this->actingAs($this->adminKota())
            ->get(route('admin.laporan'))
            ->assertOk()
            ->assertSee('Laporan Statistik Instansi')
            ->assertSee('chart-top-pelamar')
            ->assertSee('chart-seleksi');
    }
}
