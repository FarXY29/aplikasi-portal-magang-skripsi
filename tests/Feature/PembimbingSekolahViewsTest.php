<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Attendance;
use App\Models\DailyLog;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PembimbingSekolahViewsTest extends TestCase
{
    use DatabaseTransactions;

    private function makeMentoredApplication(User $pembimbing, string $status = 'diterima'): Application
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Pendidikan Test',
            'alamat' => 'Banjarmasin',
            'kode_unit_kerja' => 'DINDIK-TEST',
        ]);
        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Staf Administrasi',
            'kuota' => 1,
            'status' => 'buka',
        ]);
        $participant = User::factory()->create([
            'role' => 'peserta',
            'pembimbing_sekolah_id' => $pembimbing->id,
            'major' => 'Sistem Informasi',
        ]);

        return Application::create([
            'user_id' => $participant->id,
            'internship_position_id' => $position->id,
            'cv_path' => 'cv.pdf',
            'surat_pengantar_path' => 'surat.pdf',
            'status' => $status,
            'tanggal_mulai' => now()->subMonth()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
        ]);
    }

    public function test_pembimbing_cannot_view_another_pembimbings_student(): void
    {
        $pembimbingA = User::factory()->create(['role' => 'pembimbing']);
        $pembimbingB = User::factory()->create(['role' => 'pembimbing']);

        $participant = User::factory()->create([
            'role' => 'peserta',
            'pembimbing_sekolah_id' => $pembimbingB->id,
        ]);
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Lain',
            'alamat' => 'Banjarmasin',
            'kode_unit_kerja' => 'DINAS-LAIN',
        ]);
        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Posisi Lain',
            'kuota' => 1,
            'status' => 'buka',
        ]);
        $app = Application::create([
            'user_id' => $participant->id,
            'internship_position_id' => $position->id,
            'cv_path' => 'cv.pdf',
            'surat_pengantar_path' => 'surat.pdf',
            'status' => 'diterima',
        ]);

        $this->actingAs($pembimbingA)
            ->get(route('pembimbing.peserta.logbook', $app->id))
            ->assertForbidden();
        $this->actingAs($pembimbingA)
            ->get(route('pembimbing.peserta.absensi', $app->id))
            ->assertForbidden();
    }

    public function test_logbook_lists_and_filters_by_validation_status(): void
    {
        $pembimbing = User::factory()->create(['role' => 'pembimbing']);
        $app = $this->makeMentoredApplication($pembimbing);

        DailyLog::create([
            'application_id' => $app->id,
            'tanggal' => now()->toDateString(),
            'kegiatan' => 'Menyusun laporan magang',
            'status_validasi' => 'disetujui',
        ]);
        DailyLog::create([
            'application_id' => $app->id,
            'tanggal' => now()->subDay()->toDateString(),
            'kegiatan' => 'Mengikuti rapat koordinasi',
            'status_validasi' => 'pending',
        ]);

        $this->actingAs($pembimbing)
            ->get(route('pembimbing.peserta.logbook', $app->id))
            ->assertOk()
            ->assertSee('Menyusun laporan magang')
            ->assertSee('Mengikuti rapat koordinasi');

        $this->actingAs($pembimbing)
            ->get(route('pembimbing.peserta.logbook', ['id' => $app->id, 'status_validasi' => 'disetujui']))
            ->assertOk()
            ->assertSee('Menyusun laporan magang')
            ->assertDontSee('Mengikuti rapat koordinasi');
    }

    public function test_absensi_lists_and_renders_approved_validation_badge(): void
    {
        $pembimbing = User::factory()->create(['role' => 'pembimbing']);
        $app = $this->makeMentoredApplication($pembimbing);

        Attendance::create([
            'application_id' => $app->id,
            'date' => now()->toDateString(),
            'status' => 'hadir',
            'clock_in' => '08:00:00',
            'clock_out' => '16:30:00',
            'validation_status' => 'approved',
        ]);
        Attendance::create([
            'application_id' => $app->id,
            'date' => now()->subDay()->toDateString(),
            'status' => 'izin',
            'validation_status' => 'pending',
        ]);

        $this->actingAs($pembimbing)
            ->get(route('pembimbing.peserta.absensi', $app->id))
            ->assertOk()
            ->assertSee('Valid', false)
            ->assertSee('Jumlah Catatan', false);
    }

    public function test_absensi_filters_by_status(): void
    {
        $pembimbing = User::factory()->create(['role' => 'pembimbing']);
        $app = $this->makeMentoredApplication($pembimbing);

        Attendance::create([
            'application_id' => $app->id,
            'date' => now()->toDateString(),
            'status' => 'hadir',
            'description' => 'Hadir tepat waktu dan mengerjakan tugas administrasi',
        ]);
        Attendance::create([
            'application_id' => $app->id,
            'date' => now()->subDay()->toDateString(),
            'status' => 'alpa',
            'description' => 'Tidak hadir tanpa keterangan',
        ]);

        $this->actingAs($pembimbing)
            ->get(route('pembimbing.peserta.absensi', ['id' => $app->id, 'status' => 'alpa']))
            ->assertOk()
            ->assertSee('Tidak hadir tanpa keterangan')
            ->assertDontSee('Hadir tepat waktu dan mengerjakan tugas administrasi');
    }

    public function test_dashboard_paginates_and_filters_status(): void
    {
        $pembimbing = User::factory()->create(['role' => 'pembimbing']);
        $this->makeMentoredApplication($pembimbing, 'diterima');
        $this->makeMentoredApplication($pembimbing, 'selesai');

        $this->actingAs($pembimbing)
            ->get(route('pembimbing.dashboard', ['status' => 'aktif']))
            ->assertOk()
            ->assertSee('Sedang Aktif', false);
    }
}
