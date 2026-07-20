<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\Application;
use App\Models\DailyLog;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LogbookStatusTest extends TestCase
{
    use DatabaseTransactions;

    public function test_validation_requires_comment_when_status_is_revisi_or_ditolak()
    {
        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);

        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Test PL',
            'kode_unit_kerja' => 'TEST-PL1',
            'alamat' => 'Alamat',
            'jam_mulai_masuk' => '08:00',
            'jam_mulai_pulang' => '16:00',
            'max_total_quota' => 10,
        ]);

        $user = User::factory()->create([
            'role' => 'pembimbing_lapangan',
            'instansi_id' => $instansi->id
        ]);
        $user->assignRole('pembimbing_lapangan');

        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Staff',
            'kuota' => 5,
            'status' => 'buka',
            'batas_daftar' => now()->addDays(10)->toDateString()
        ]);

        // Create active application
        $app = Application::create([
            'user_id' => User::factory()->create()->id,
            'internship_position_id' => $position->id,
            'pembimbing_lapangan_id' => $user->id,
            'status' => 'diterima',
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => now()->addDays(5)->toDateString()
        ]);

        $log = DailyLog::create([
            'application_id' => $app->id,
            'tanggal' => now()->toDateString(),
            'kegiatan' => 'Some activity',
            'status_validasi' => 'pending'
        ]);

        // Test revisi without comment should fail in validation
        $response = $this->actingAs($user)->post(route('pembimbing_lapangan.logbook.validasi', $log->id), [
            'status' => 'revisi',
            'komentar' => ''
        ]);
        $response->assertSessionHasErrors('komentar');

        // Test ditolak without comment should fail in validation
        $response = $this->actingAs($user)->post(route('pembimbing_lapangan.logbook.validasi', $log->id), [
            'status' => 'ditolak',
            'komentar' => ''
        ]);
        $response->assertSessionHasErrors('komentar');
    }

    public function test_dashboard_counts_disetujui_instead_of_valid()
    {
        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);

        $user = User::factory()->create(['role' => 'peserta']);
        $user->assignRole('peserta');

        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Test',
            'kode_unit_kerja' => 'TEST-L1',
            'alamat' => 'Alamat',
            'jam_mulai_masuk' => '08:00',
            'jam_mulai_pulang' => '16:00',
            'max_total_quota' => 10,
        ]);

        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Staff',
            'kuota' => 5,
            'status' => 'buka',
            'batas_daftar' => now()->addDays(10)->toDateString()
        ]);

        $app = Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'status' => 'diterima',
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => now()->addDays(5)->toDateString()
        ]);

        DailyLog::create([
            'application_id' => $app->id,
            'tanggal' => now()->toDateString(),
            'kegiatan' => 'Some activity',
            'status_validasi' => 'disetujui'
        ]);

        $response = $this->actingAs($user)->get(route('peserta.dashboard'));
        $response->assertStatus(200);
        $response->assertViewHas('stats', function ($stats) {
            return $stats['logs_validated'] === 1;
        });
    }
}
