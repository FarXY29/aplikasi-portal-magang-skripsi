<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Attendance;
use App\Models\DailyLog;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PembimbingLapanganViewsTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        Storage::fake('public');
    }

    private function createSetup(): array
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Komunikasi dan Informatika',
            'alamat' => 'Jl. RE Martadinata',
            'kode_unit_kerja' => 'DISKOMINFO-TEST',
        ]);

        $pl = User::factory()->create([
            'role' => 'pembimbing_lapangan',
            'instansi_id' => $instansi->id,
            'email_verified_at' => now(),
        ]);
        $pl->assignRole('pembimbing_lapangan');

        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Junior Web Developer',
            'kuota' => 5,
            'status' => 'buka',
        ]);

        $peserta1 = User::factory()->create([
            'role' => 'peserta',
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'email_verified_at' => now(),
        ]);
        $peserta1->assignRole('peserta');

        $app1 = Application::create([
            'user_id' => $peserta1->id,
            'internship_position_id' => $position->id,
            'pembimbing_lapangan_id' => $pl->id,
            'status' => 'diterima',
            'tanggal_mulai' => now()->subMonth()->toDateString(),
            'tanggal_selesai' => now()->addMonths(2)->toDateString(),
            'cv_path' => 'cv.pdf',
            'surat_pengantar_path' => 'surat.pdf',
        ]);

        $peserta2 = User::factory()->create([
            'role' => 'peserta',
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'email_verified_at' => now(),
        ]);
        $peserta2->assignRole('peserta');

        $app2 = Application::create([
            'user_id' => $peserta2->id,
            'internship_position_id' => $position->id,
            'pembimbing_lapangan_id' => $pl->id,
            'status' => 'selesai',
            'tanggal_mulai' => now()->subMonths(3)->toDateString(),
            'tanggal_selesai' => now()->subDay()->toDateString(),
            'cv_path' => 'cv2.pdf',
            'surat_pengantar_path' => 'surat2.pdf',
            'nilai_rata_rata' => 92.5,
            'nilai_angka' => 92.5,
            'predikat' => 'A',
        ]);

        return compact('instansi', 'pl', 'position', 'peserta1', 'app1', 'peserta2', 'app2');
    }

    public function test_dashboard_renders_metrics_and_intern_list(): void
    {
        $setup = $this->createSetup();

        $response = $this->actingAs($setup['pl'])->get(route('pembimbing_lapangan.dashboard'));
        $response->assertOk();
        $response->assertSee('Dashboard Pembimbing Lapangan');
        $response->assertSee('Total Bimbingan');
        $response->assertSee('Sedang Magang');
        $response->assertSee('Selesai / Lulus');
        $response->assertSee('Budi Santoso');
        $response->assertSee('Siti Nurhaliza');
        $response->assertSee(route('pembimbing_lapangan.logbook', $setup['app1']->id));
        $response->assertSee(route('pembimbing_lapangan.attendance.index'));
        $response->assertSee(route('pembimbing_lapangan.penilaian', $setup['app1']->id));
    }

    public function test_dashboard_renders_empty_state_when_no_interns(): void
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Tanpa Peserta',
            'alamat' => 'Banjarmasin',
            'kode_unit_kerja' => 'DINAS-EMPTY',
        ]);

        $pl = User::factory()->create([
            'role' => 'pembimbing_lapangan',
            'instansi_id' => $instansi->id,
            'email_verified_at' => now(),
        ]);
        $pl->assignRole('pembimbing_lapangan');

        $response = $this->actingAs($pl)->get(route('pembimbing_lapangan.dashboard'));
        $response->assertOk();
        $response->assertSee('Belum ada mahasiswa bimbingan');
    }

    public function test_attendance_monitoring_renders_and_filters(): void
    {
        $setup = $this->createSetup();

        $attHadir = Attendance::create([
            'application_id' => $setup['app1']->id,
            'date' => now()->toDateString(),
            'status' => 'hadir',
            'clock_in' => '07:45:00',
            'clock_out' => '16:00:00',
            'validation_status' => 'approved',
        ]);

        $attIzin = Attendance::create([
            'application_id' => $setup['app1']->id,
            'date' => now()->subDay()->toDateString(),
            'status' => 'izin',
            'description' => 'Izin mengurus KRS di kampus',
            'proof_file' => 'attendance/surat_krs.pdf',
            'validation_status' => 'pending',
        ]);

        // Default view
        $resDefault = $this->actingAs($setup['pl'])->get(route('pembimbing_lapangan.attendance.index', [
            'filter_type' => 'semua',
        ]));
        $resDefault->assertOk();
        $resDefault->assertSee('Monitoring Absensi Mahasiswa');
        $resDefault->assertSee('Budi Santoso');
        $resDefault->assertSee('Izin');

        // Filter status=izin
        $resIzin = $this->actingAs($setup['pl'])->get(route('pembimbing_lapangan.attendance.index', [
            'filter_type' => 'semua',
            'status' => 'izin',
        ]));
        $resIzin->assertOk();
        $resIzin->assertSee('Izin mengurus KRS di kampus');
    }

    public function test_attendance_validation_can_be_approved_and_rejected(): void
    {
        $setup = $this->createSetup();

        $attIzin = Attendance::create([
            'application_id' => $setup['app1']->id,
            'date' => now()->toDateString(),
            'status' => 'sakit',
            'description' => 'Sakit flu dan demam tinggi',
            'proof_file' => 'attendance/surat_dokter.jpg',
            'validation_status' => 'pending',
        ]);

        // Approve attendance
        $resApprove = $this->actingAs($setup['pl'])->post(
            route('pembimbing_lapangan.attendance.validate', $attIzin->id),
            [
                'status_validasi' => 'approved',
                'pembimbing_lapangan_note' => 'Surat dokter valid',
            ]
        );
        $resApprove->assertSessionHas('success');
        $this->assertEquals('approved', $attIzin->fresh()->validation_status);

        // Reject attendance
        $resReject = $this->actingAs($setup['pl'])->post(
            route('pembimbing_lapangan.attendance.validate', $attIzin->id),
            [
                'status_validasi' => 'rejected',
                'pembimbing_lapangan_note' => 'Dokumen tidak dapat diverifikasi',
            ]
        );
        $resReject->assertSessionHas('success');
        $this->assertEquals('rejected', $attIzin->fresh()->validation_status);
    }

    public function test_logbook_view_renders_master_detail_and_single_validation(): void
    {
        $setup = $this->createSetup();

        $log1 = DailyLog::create([
            'application_id' => $setup['app1']->id,
            'tanggal' => now()->toDateString(),
            'kegiatan' => 'Mengembangkan RESTful API untuk modul absensi',
            'status_validasi' => 'pending',
        ]);

        $log2 = DailyLog::create([
            'application_id' => $setup['app1']->id,
            'tanggal' => now()->subDay()->toDateString(),
            'kegiatan' => 'Membuat unit testing database schema',
            'status_validasi' => 'disetujui',
            'komentar_pembimbing_lapangan' => 'Sangat rapi dan sesuai standar.',
        ]);

        $res = $this->actingAs($setup['pl'])->get(route('pembimbing_lapangan.logbook', $setup['app1']->id));
        $res->assertOk();
        $res->assertSee('Validasi Logbook');
        $res->assertSee('Mengembangkan RESTful API untuk modul absensi');
        $res->assertSee('Membuat unit testing database schema');
        $res->assertSee('Sangat rapi dan sesuai standar.');

        // Single log validation
        $resValidate = $this->actingAs($setup['pl'])->post(
            route('pembimbing_lapangan.logbook.validasi', $log1->id),
            [
                'status' => 'disetujui',
                'komentar' => 'Kode bersih dan modular.',
            ]
        );
        $resValidate->assertSessionHas('success');
        $this->assertEquals('disetujui', $log1->fresh()->status_validasi);
    }

    public function test_logbook_batch_validation_approves_multiple_logs(): void
    {
        $setup = $this->createSetup();

        $logA = DailyLog::create([
            'application_id' => $setup['app1']->id,
            'tanggal' => now()->toDateString(),
            'kegiatan' => 'Batch Task A',
            'status_validasi' => 'pending',
        ]);

        $logB = DailyLog::create([
            'application_id' => $setup['app1']->id,
            'tanggal' => now()->subDay()->toDateString(),
            'kegiatan' => 'Batch Task B',
            'status_validasi' => 'pending',
        ]);

        $resBatch = $this->actingAs($setup['pl'])->post(
            route('pembimbing_lapangan.logbook.batch_validasi'),
            [
                'log_ids' => [$logA->id, $logB->id],
                'status' => 'disetujui',
                'komentar' => 'Disetujui secara bersamaan.',
            ]
        );
        $resBatch->assertSessionHas('success');
        $this->assertEquals('disetujui', $logA->fresh()->status_validasi);
        $this->assertEquals('disetujui', $logB->fresh()->status_validasi);
    }

    public function test_penilaian_form_renders_and_saves_grade(): void
    {
        $setup = $this->createSetup();

        $resForm = $this->actingAs($setup['pl'])->get(route('pembimbing_lapangan.penilaian', $setup['app1']->id));
        $resForm->assertOk();
        $resForm->assertSee('Formulir Penilaian Akhir');
        $resForm->assertSee('nilai_kerajinan');
        $resForm->assertSee('nilai_disiplin');
        $resForm->assertSee('nilai_adaptasi');
        $resForm->assertSee('nilai_kreatifitas');
        $resForm->assertSee('nilai_skill_pengetahuan');
        $resForm->assertSee('catatan_pembimbing_lapangan');

        // Submit Grade
        $resSave = $this->actingAs($setup['pl'])->post(
            route('pembimbing_lapangan.simpan_nilai', $setup['app1']->id),
            [
                'nilai_kerajinan' => 95,
                'nilai_disiplin' => 90,
                'nilai_adaptasi' => 92,
                'nilai_kreatifitas' => 88,
                'nilai_skill_pengetahuan' => 95,
                'catatan_pembimbing_lapangan' => 'Mahasiswa sangat berprestasi dan berdedikasi tinggi.',
            ]
        );
        $resSave->assertRedirect(route('pembimbing_lapangan.dashboard'));
        $resSave->assertSessionHas('success');

        $app = $setup['app1']->fresh();
        $this->assertEquals(92.0, (float)$app->nilai_rata_rata);
        $this->assertEquals('A (Sangat Baik)', $app->predikat);
        $this->assertEquals('selesai', $app->status_value);
    }
}
