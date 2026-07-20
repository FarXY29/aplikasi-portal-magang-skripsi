<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\Application;
use App\Services\ApplicationLifecycleService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class WaitingListPromotionTest extends TestCase
{
    use DatabaseTransactions;

    public function test_completed_application_promotes_next_waiting_to_pending()
    {
        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);

        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Pendidikan',
            'kode_unit_kerja' => 'DISDIK-01',
            'alamat' => 'Alamat',
            'jam_mulai_masuk' => '08:00',
            'jam_mulai_pulang' => '16:00',
            'max_total_quota' => 10,
        ]);

        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Staff',
            'kuota' => 1, // Only 1 kuota
            'status' => 'buka',
            'batas_daftar' => now()->addDays(10)->toDateString()
        ]);

        $activeApp = Application::create([
            'user_id' => User::factory()->create()->id,
            'internship_position_id' => $position->id,
            'status' => 'diterima',
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => now()->addDays(5)->toDateString()
        ]);

        $waitingApp = Application::create([
            'user_id' => User::factory()->create(['email' => 'waiting@example.com'])->id,
            'internship_position_id' => $position->id,
            'status' => 'menunggu', // waiting list candidate
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'tanggal_mulai' => now()->addDays(6)->toDateString(),
            'tanggal_selesai' => now()->addDays(15)->toDateString()
        ]);

        $service = app(ApplicationLifecycleService::class);
        $service->markAsFinished($activeApp);

        $waitingApp->refresh();
        
        // Wait, the status is cast to ApplicationStatus Enum, so we check status->value or match pending
        $this->assertEquals('pending', $waitingApp->status->value);
    }
}
