<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Attendance;
use App\Models\DailyLog;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PesertaViewRegressionTest extends TestCase
{
    use DatabaseTransactions;

    public function test_position_description_is_escaped_on_application_form(): void
    {
        $user = $this->createPeserta();
        $position = $this->createPosition([
            'deskripsi' => '<script>alert("x")</script>Deskripsi aman',
        ]);

        $response = $this->actingAs($user)->get(route('peserta.daftar.form', $position->id));

        $response->assertOk();
        $response->assertSee('&lt;script&gt;', false);
        $response->assertDontSee('<script>alert("x")</script>', false);
    }

    public function test_participant_can_explicitly_choose_waiting_list(): void
    {
        Storage::fake('private');

        $user = $this->createPeserta();
        $position = $this->createPosition(['kuota' => 2]);
        $start = now()->addDays(5)->toDateString();
        $end = now()->addDays(10)->toDateString();

        $response = $this->actingAs($user)->post(route('peserta.daftar', $position->id), [
            'surat' => UploadedFile::fake()->create('surat-pengantar.pdf', 10, 'application/pdf'),
            'tanggal_mulai' => $start,
            'tanggal_selesai' => $end,
            'is_waiting_list' => '1',
        ]);

        $response->assertRedirect(route('peserta.dashboard'));
        $this->assertDatabaseHas('applications', [
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'status' => 'menunggu',
        ]);
    }

    public function test_logbook_status_filter_is_applied_before_pagination(): void
    {
        $user = $this->createPeserta();
        $position = $this->createPosition();
        $application = Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'status' => 'diterima',
            'tanggal_mulai' => now()->subDay()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
        ]);

        DailyLog::create([
            'application_id' => $application->id,
            'tanggal' => now()->toDateString(),
            'kegiatan' => 'Jurnal pending yang tidak boleh tampil',
            'status_validasi' => 'pending',
        ]);
        DailyLog::create([
            'application_id' => $application->id,
            'tanggal' => now()->subDay()->toDateString(),
            'kegiatan' => 'Jurnal revisi yang harus tampil',
            'status_validasi' => 'revisi',
        ]);

        $response = $this->actingAs($user)->get(route('peserta.logbook.index', [
            'status' => 'revisi',
        ]));

        $response->assertOk();
        $response->assertSee('Jurnal revisi yang harus tampil');
        $response->assertDontSee('Jurnal pending yang tidak boleh tampil');
    }

    public function test_attendance_summary_counts_all_records_not_only_current_page(): void
    {
        $user = $this->createPeserta();
        $position = $this->createPosition();
        $application = Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'status' => 'diterima',
            'tanggal_mulai' => now()->subMonth()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
        ]);

        for ($day = 0; $day < 15; $day++) {
            Attendance::create([
                'application_id' => $application->id,
                'date' => now()->subDays($day)->toDateString(),
                'status' => 'hadir',
                'validation_status' => 'pending',
            ]);
        }

        Attendance::create([
            'application_id' => $application->id,
            'date' => now()->subDays(15)->toDateString(),
            'status' => 'alpa',
            'validation_status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get(route('peserta.absensi.index'));

        $response->assertOk();
        $response->assertSee('font-mono">16</p>', false);
        $response->assertSee('font-mono">15</p>', false);
        $response->assertSee('font-mono">1</p>', false);
    }

    public function test_each_completed_application_links_to_its_own_certificate(): void
    {
        $user = $this->createPeserta();
        $firstPosition = $this->createPosition(['judul_posisi' => 'Posisi Pertama']);
        $secondPosition = $this->createPosition(['judul_posisi' => 'Posisi Kedua']);

        $firstApplication = $this->createFinishedApplication($user, $firstPosition, 'Saran pertama');
        $secondApplication = $this->createFinishedApplication($user, $secondPosition, 'Saran kedua');

        $response = $this->actingAs($user)->get(route('peserta.dashboard'));

        $response->assertOk();
        $response->assertSee(route('peserta.sertifikat', $firstApplication->id), false);
        $response->assertSee(route('peserta.sertifikat', $secondApplication->id), false);
    }

    private function createPeserta(): User
    {
        return User::factory()->create([
            'role' => 'peserta',
            'major' => 'Teknik Informatika',
            'nik' => '1234567890123456',
            'asal_instansi' => 'Universitas Test',
        ]);
    }

    private function createPosition(array $overrides = []): InternshipPosition
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Test '.uniqid(),
            'kode_unit_kerja' => 'TEST-'.uniqid(),
            'alamat' => 'Alamat Test',
            'jam_mulai_masuk' => '08:00:00',
            'jam_mulai_pulang' => '16:00:00',
            'max_total_quota' => 10,
        ]);

        return InternshipPosition::create(array_merge([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Developer',
            'required_major' => 'Semua Jurusan',
            'deskripsi' => 'Deskripsi posisi',
            'kuota' => 2,
            'batas_daftar' => now()->addMonth()->toDateString(),
            'status' => 'buka',
        ], $overrides));
    }

    private function createFinishedApplication(User $user, InternshipPosition $position, string $feedback): Application
    {
        return Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'status' => 'selesai',
            'tanggal_mulai' => now()->subMonths(3)->toDateString(),
            'tanggal_selesai' => now()->subDay()->toDateString(),
            'saran_peserta' => $feedback,
        ]);
    }
}
