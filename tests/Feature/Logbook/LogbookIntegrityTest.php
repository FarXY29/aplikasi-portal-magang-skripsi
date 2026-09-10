<?php

namespace Tests\Feature\Logbook;

use App\Models\Application;
use App\Models\DailyLog;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LogbookIntegrityTest extends TestCase
{
    use DatabaseTransactions;

    private User $peserta;
    private Application $application;
    private Instansi $instansi;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);

        $this->peserta = User::factory()->create([
            'role' => 'peserta',
        ]);
        $this->peserta->assignRole('peserta');

        $this->instansi = Instansi::create([
            'nama_dinas' => 'Dinas Kominfo Uji',
            'kode_unit_kerja' => 'KOM-01',
            'alamat' => 'Banjarmasin',
            'max_total_quota' => 10,
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'radius_absen' => 500,
        ]);

        $position = InternshipPosition::create([
            'instansi_id' => $this->instansi->id,
            'judul_posisi' => 'Software Engineer Intern',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $this->application = Application::create([
            'user_id' => $this->peserta->id,
            'internship_position_id' => $position->id,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'status' => 'diterima',
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => now()->addDays(85)->toDateString(),
        ]);
    }

    public function test_logbook_creation_persists_in_transaction_and_audit(): void
    {
        Storage::fake('private');

        $photo = UploadedFile::fake()->image('kegiatan.png');

        $response = $this->actingAs($this->peserta)->post(route('peserta.logbook.store'), [
            'kegiatan' => 'Mengerjakan perbaikan bug modul absensi dan unit test',
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'foto' => $photo,
        ]);

        $response->assertSessionHas('success', 'Logbook hari ini berhasil disimpan!');

        $log = DailyLog::where('application_id', $this->application->id)->first();
        $this->assertNotNull($log);
        $this->assertSame('pending', $log->status_validasi);
        $this->assertNotNull($log->bukti_foto_path);
        Storage::disk('private')->assertExists($log->bukti_foto_path);
    }

    public function test_duplicate_logbook_same_day_rejected_and_cleans_up_uploaded_file(): void
    {
        Storage::fake('private');

        // First logbook succeeds
        $photo1 = UploadedFile::fake()->image('log1.png');
        $resp1 = $this->actingAs($this->peserta)->post(route('peserta.logbook.store'), [
            'kegiatan' => 'Kegiatan pertama pagi hari',
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'foto' => $photo1,
        ]);
        $resp1->assertSessionHas('success');

        $firstLog = DailyLog::where('application_id', $this->application->id)->first();
        $this->assertNotNull($firstLog);
        Storage::disk('private')->assertExists($firstLog->bukti_foto_path);

        // Second logbook on same day rejected (duplicate pre-check)
        $photo2 = UploadedFile::fake()->image('log2_duplicate.png');
        $resp2 = $this->actingAs($this->peserta)->post(route('peserta.logbook.store'), [
            'kegiatan' => 'Kegiatan kedua di hari yang sama',
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'foto' => $photo2,
        ]);
        $resp2->assertSessionHas('error', 'Anda sudah mengisi logbook untuk hari ini.');

        // Verify only 1 logbook exists and only 1 file exists on disk
        $this->assertSame(1, DailyLog::where('application_id', $this->application->id)->count());
        $allFiles = Storage::disk('private')->allFiles('documents/logbook');
        $this->assertCount(1, $allFiles);
        $this->assertSame([$firstLog->bukti_foto_path], $allFiles);
    }
}
