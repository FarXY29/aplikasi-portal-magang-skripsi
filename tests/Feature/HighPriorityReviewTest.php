<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Attendance;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use App\Services\ReportService;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Tests\TestCase;

class HighPriorityReviewTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_user_views_prefer_spatie_role_over_legacy_role(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $admin = User::factory()->create(['role' => 'admin_kota']);
        $user = User::factory()->create(['role' => 'peserta']);
        $user->assignRole('admin_instansi');

        $this->assertSame('admin_instansi', $user->fresh()->getPrimaryPortalRole());

        $response = $this->actingAs($admin)->get(route('admin.users.edit', $user));

        $response->assertOk()
            ->assertSee('<option value="admin_instansi" selected>', false);
    }

    public function test_instansi_without_attendance_is_excluded_from_discipline_ranking(): void
    {
        $rankedInstansi = Instansi::create([
            'nama_dinas' => 'Instansi Dengan Presensi',
            'alamat' => 'Alamat A',
            'kode_unit_kerja' => 'UNIT-A',
        ]);
        $emptyInstansi = Instansi::create([
            'nama_dinas' => 'Instansi Tanpa Presensi',
            'alamat' => 'Alamat B',
            'kode_unit_kerja' => 'UNIT-B',
        ]);
        $position = InternshipPosition::create([
            'instansi_id' => $rankedInstansi->id,
            'judul_posisi' => 'Pengembang Aplikasi',
            'deskripsi' => 'Deskripsi posisi',
            'kuota' => 1,
            'batas_daftar' => now()->addDay()->toDateString(),
            'status' => 'buka',
        ]);
        $participant = User::factory()->create(['role' => 'peserta']);
        $application = Application::create([
            'user_id' => $participant->id,
            'internship_position_id' => $position->id,
            'cv_path' => 'cv.pdf',
            'surat_pengantar_path' => 'surat.pdf',
            'status' => 'diterima',
        ]);
        Attendance::create([
            'application_id' => $application->id,
            'date' => now()->toDateString(),
            'status' => 'hadir',
            'clock_in' => '08:00:00',
        ]);

        $data = app(ReportService::class)->getInstansiDisiplinData(Request::create('/'));

        $this->assertTrue($data['instansis']->contains('id', $rankedInstansi->id));
        $this->assertFalse($data['instansis']->contains('id', $emptyInstansi->id));
        $this->assertSame(1, $data['stats']['total_instansi']);
        $this->assertSame(1, $data['stats']['total_kehadiran']);
    }

    public function test_admin_instansi_cannot_mutate_non_mentor_users_in_their_instansi(): void
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Instansi Pembatasan Akun',
            'alamat' => 'Alamat Test',
            'kode_unit_kerja' => 'UNIT-AKUN',
        ]);
        $admin = User::factory()->create([
            'role' => 'admin_instansi',
            'instansi_id' => $instansi->id,
        ]);
        $target = User::factory()->create([
            'role' => 'peserta',
            'instansi_id' => $instansi->id,
            'name' => 'Peserta Tetap',
        ]);

        $update = $this->actingAs($admin)->put(
            route('dinas.pembimbing_lapangan.update', $target->id),
            [
                'name' => 'Akun Diubah',
                'email' => 'diubah@example.com',
                'nip' => '123',
            ]
        );
        $delete = $this->actingAs($admin)->delete(
            route('dinas.pembimbing_lapangan.destroy', $target->id)
        );

        $update->assertNotFound();
        $delete->assertNotFound();
        $this->assertDatabaseHas('users', [
            'id' => $target->id,
            'name' => 'Peserta Tetap',
        ]);
    }

    public function test_certificate_post_requires_completed_and_graded_application(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $instansi = Instansi::create([
            'nama_dinas' => 'Instansi Sertifikat',
            'alamat' => 'Alamat Test',
            'kode_unit_kerja' => 'UNIT-SERT',
        ]);
        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Posisi Sertifikat',
            'deskripsi' => 'Deskripsi posisi',
            'kuota' => 1,
            'batas_daftar' => now()->addDay()->toDateString(),
            'status' => 'buka',
        ]);
        $admin = User::factory()->create([
            'role' => 'peserta',
            'instansi_id' => $instansi->id,
        ]);
        $admin->assignRole('admin_instansi');
        $participant = User::factory()->create(['role' => 'peserta']);
        $application = Application::create([
            'user_id' => $participant->id,
            'internship_position_id' => $position->id,
            'cv_path' => 'cv.pdf',
            'surat_pengantar_path' => 'surat.pdf',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post(
            route('dinas.sertifikat.store', $application->id),
            [
                'nomor_sertifikat' => 'SERT-TEST-001',
                'tanggal_sertifikat' => now()->toDateString(),
            ]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => 'pending',
            'nomor_sertifikat' => null,
        ]);
    }

    public function test_spatie_only_admin_can_create_instansi_resources(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $instansi = Instansi::create([
            'nama_dinas' => 'Instansi Spatie',
            'alamat' => 'Alamat Test',
            'kode_unit_kerja' => 'UNIT-SPATIE',
        ]);
        $admin = User::factory()->create([
            'role' => 'peserta',
            'instansi_id' => $instansi->id,
        ]);
        $admin->assignRole('admin_instansi');

        $response = $this->actingAs($admin)->post(route('dinas.lowongan.store'), [
            'judul_posisi' => 'Posisi Spatie',
            'required_major' => 'Teknik Informatika',
            'deskripsi' => 'Deskripsi posisi',
            'kuota' => 1,
            'batas_daftar' => now()->addDay()->toDateString(),
        ]);

        $response->assertRedirect(route('dinas.lowongan.index'));
        $this->assertDatabaseHas('internship_positions', [
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Posisi Spatie',
        ]);
    }
}
