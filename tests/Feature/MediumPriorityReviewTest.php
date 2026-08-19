<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Application;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MediumPriorityReviewTest extends TestCase
{
    use DatabaseTransactions;

    public function test_audit_trail_displays_the_primary_spatie_role(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $admin = User::factory()->create(['role' => 'admin_kota']);
        $actor = User::factory()->create(['role' => 'peserta']);
        $actor->assignRole('admin_instansi');

        AuditLog::create([
            'user_id' => $actor->id,
            'action' => 'user.updated',
            'metadata' => ['field' => 'role'],
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.audit_trail'));

        $response->assertOk()
            ->assertSee($actor->email.' (Admin Instansi)', false);
    }

    public function test_new_instansi_admin_is_synchronized_to_spatie_role(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $admin = User::factory()->create(['role' => 'admin_kota']);

        $response = $this->actingAs($admin)->post(route('admin.instansi.store'), [
            'nama_dinas' => 'Dinas P2P',
            'kode_unit_kerja' => 'D-P2P',
            'alamat' => 'Jalan P2P No. 1',
            'latitude' => -3.3186,
            'longitude' => 114.5944,
            'radius_absen' => 50,
            'email_admin' => 'admin.p2p@example.test',
            'password_admin' => 'password-rahasia',
        ]);

        $response->assertRedirect(route('admin.instansi.index'));

        $adminInstansi = User::where('email', 'admin.p2p@example.test')->firstOrFail();

        $this->assertTrue($adminInstansi->hasRole('admin_instansi'));
        $this->assertSame('admin_instansi', $adminInstansi->fresh()->getPrimaryPortalRole());
    }

    public function test_logbook_monitoring_searches_email_and_displays_latest_application(): void
    {
        $admin = User::factory()->create(['role' => 'admin_kota']);
        $participant = User::factory()->create([
            'role' => 'peserta',
            'email' => 'peserta-logbook@example.test',
            'asal_instansi' => 'Kampus P2P',
        ]);

        $oldInstansi = Instansi::create([
            'nama_dinas' => 'Instansi Lama',
            'alamat' => 'Alamat Lama',
            'kode_unit_kerja' => 'LAMA-P2P',
        ]);
        $newInstansi = Instansi::create([
            'nama_dinas' => 'Instansi Terbaru',
            'alamat' => 'Alamat Terbaru',
            'kode_unit_kerja' => 'BARU-P2P',
        ]);
        $oldPosition = InternshipPosition::create([
            'instansi_id' => $oldInstansi->id,
            'judul_posisi' => 'Posisi Lama',
            'deskripsi' => 'Deskripsi lama',
            'kuota' => 1,
            'batas_daftar' => now()->addDay()->toDateString(),
            'status' => 'buka',
        ]);
        $newPosition = InternshipPosition::create([
            'instansi_id' => $newInstansi->id,
            'judul_posisi' => 'Posisi Terbaru',
            'deskripsi' => 'Deskripsi terbaru',
            'kuota' => 1,
            'batas_daftar' => now()->addDay()->toDateString(),
            'status' => 'buka',
        ]);

        $oldApplication = Application::create([
            'user_id' => $participant->id,
            'internship_position_id' => $oldPosition->id,
            'cv_path' => 'cv-lama.pdf',
            'surat_pengantar_path' => 'surat-lama.pdf',
            'status' => 'diterima',
        ]);
        $oldApplication->forceFill([
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ])->saveQuietly();

        Application::create([
            'user_id' => $participant->id,
            'internship_position_id' => $newPosition->id,
            'cv_path' => 'cv-baru.pdf',
            'surat_pengantar_path' => 'surat-baru.pdf',
            'status' => 'diterima',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.users.logbooks', [
            'search' => $participant->email,
        ]));

        $response->assertOk()
            ->assertSee($participant->name)
            ->assertSee('Instansi Terbaru')
            ->assertDontSee('Instansi Lama');
    }

    public function test_dashboard_does_not_show_uncomputed_growth_badges(): void
    {
        $admin = User::factory()->create(['role' => 'admin_kota']);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('+100%')
            ->assertSee('function loadChartJs()', false)
            ->assertSee('window.adminDashboardChartPromise', false)
            ->assertSee('function bootDashboard()', false);
    }

    public function test_demografi_jurusan_handles_positions_without_major(): void
    {
        $admin = User::factory()->create(['role' => 'admin_kota']);
        $instansi = Instansi::create([
            'nama_dinas' => 'Instansi Jurusan P2P',
            'alamat' => 'Alamat Jurusan',
            'kode_unit_kerja' => 'JURUSAN-P2P',
        ]);

        InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Posisi Tanpa Kualifikasi',
            'required_major' => null,
            'deskripsi' => 'Deskripsi posisi',
            'kuota' => 1,
            'batas_daftar' => now()->addDay()->toDateString(),
            'status' => 'buka',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.laporan.demografi_jurusan'))
            ->assertOk()
            ->assertSee('title="Jumlah kualifikasi atau jurusan yang memiliki data lowongan pada laporan."', false);
    }

    public function test_report_metric_cards_expose_hover_descriptions_for_both_admin_roles(): void
    {
        $adminKota = User::factory()->create(['role' => 'admin_kota']);

        $this->actingAs($adminKota)
            ->get(route('admin.laporan.peserta_global'))
            ->assertOk()
            ->assertSee('title="Jumlah seluruh peserta yang sesuai dengan filter laporan saat ini."', false);

        $instansi = Instansi::create([
            'nama_dinas' => 'Instansi Tooltip P2P',
            'alamat' => 'Alamat Tooltip',
            'kode_unit_kerja' => 'TOOLTIP-P2P',
        ]);
        $adminInstansi = User::factory()->create([
            'role' => 'admin_instansi',
            'instansi_id' => $instansi->id,
        ]);

        $this->actingAs($adminInstansi)
            ->get(route('dinas.laporan.rekap'))
            ->assertOk()
            ->assertSee('title="Jumlah seluruh lamaran peserta yang tercatat pada laporan."', false);
    }

    public function test_global_participant_report_handles_pending_application_without_period(): void
    {
        $admin = User::factory()->create(['role' => 'admin_kota']);
        $participant = User::factory()->create(['role' => 'peserta']);
        $instansi = Instansi::create([
            'nama_dinas' => 'Instansi Pending P2P',
            'alamat' => 'Alamat Pending',
            'kode_unit_kerja' => 'PENDING-P2P',
        ]);
        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Posisi Pending',
            'deskripsi' => 'Deskripsi posisi pending',
            'kuota' => 1,
            'batas_daftar' => now()->addDay()->toDateString(),
            'status' => 'buka',
        ]);

        Application::create([
            'user_id' => $participant->id,
            'internship_position_id' => $position->id,
            'cv_path' => 'cv-pending.pdf',
            'surat_pengantar_path' => 'surat-pending.pdf',
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.laporan.peserta_global', ['status' => 'pending']))
            ->assertOk()
            ->assertSee('Tanggal belum ditentukan');
    }
}
