<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class VacancyApplicationValidationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_cannot_apply_to_closed_position()
    {
        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);

        $user = User::factory()->create(['role' => 'peserta', 'major' => 'Informatika']);
        $user->assignRole('peserta');

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
            'kuota' => 5,
            'status' => 'tutup', // Closed vacancy
            'batas_daftar' => now()->addDays(10)->toDateString(),
            'required_major' => 'Informatika'
        ]);

        $response = $this->actingAs($user)->post(route('peserta.daftar', $position->id), [
            'tanggal_mulai' => now()->addDays(2)->toDateString(),
            'tanggal_selesai' => now()->addMonths(2)->toDateString(),
            'surat' => UploadedFile::fake()->create('surat.pdf', 500)
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error'); // Validation/Check failure redirects back with error
    }

    public function test_cannot_apply_past_deadline()
    {
        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);

        $user = User::factory()->create(['role' => 'peserta', 'major' => 'Informatika']);
        $user->assignRole('peserta');

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
            'kuota' => 5,
            'status' => 'buka',
            'batas_daftar' => now()->subDays(1)->toDateString(), // Past deadline
            'required_major' => 'Informatika'
        ]);

        $response = $this->actingAs($user)->post(route('peserta.daftar', $position->id), [
            'tanggal_mulai' => now()->addDays(2)->toDateString(),
            'tanggal_selesai' => now()->addMonths(2)->toDateString(),
            'surat' => UploadedFile::fake()->create('surat.pdf', 500)
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_cannot_apply_with_unmatched_major()
    {
        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);

        $user = User::factory()->create(['role' => 'peserta', 'major' => 'Kedokteran']);
        $user->assignRole('peserta');

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
            'kuota' => 5,
            'status' => 'buka',
            'batas_daftar' => now()->addDays(10)->toDateString(),
            'required_major' => 'Informatika' // Requires Informatiks, user is Kedokteran
        ]);

        $response = $this->actingAs($user)->post(route('peserta.daftar', $position->id), [
            'tanggal_mulai' => now()->addDays(2)->toDateString(),
            'tanggal_selesai' => now()->addMonths(2)->toDateString(),
            'surat' => UploadedFile::fake()->create('surat.pdf', 500)
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_cannot_apply_if_has_active_internship()
    {
        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);

        $user = User::factory()->create(['role' => 'peserta', 'major' => 'Informatika']);
        $user->assignRole('peserta');

        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Pendidikan',
            'kode_unit_kerja' => 'DISDIK-01',
            'alamat' => 'Alamat',
            'jam_mulai_masuk' => '08:00',
            'jam_mulai_pulang' => '16:00',
            'max_total_quota' => 10,
        ]);

        $position1 = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Staff 1',
            'kuota' => 5,
            'status' => 'buka',
            'batas_daftar' => now()->addDays(10)->toDateString(),
            'required_major' => 'Informatika'
        ]);

        $position2 = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Staff 2',
            'kuota' => 5,
            'status' => 'buka',
            'batas_daftar' => now()->addDays(10)->toDateString(),
            'required_major' => 'Informatika'
        ]);

        // Already has an active internship (diterima)
        Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $position1->id,
            'status' => 'diterima',
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => now()->addDays(5)->toDateString()
        ]);

        $response = $this->actingAs($user)->post(route('peserta.daftar', $position2->id), [
            'tanggal_mulai' => now()->addDays(2)->toDateString(),
            'tanggal_selesai' => now()->addMonths(2)->toDateString(),
            'surat' => UploadedFile::fake()->create('surat.pdf', 500)
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
