<?php

namespace Tests\Feature\Public;

use App\Models\Application;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TrackingTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_guest_can_access_tracking_page()
    {
        $response = $this->get(route('tracking.index'));
        $response->assertStatus(200);
        $response->assertSee('Lacak Status Permohonan Magang');
    }

    public function test_guest_can_search_application_by_registration_number()
    {
        $user = User::factory()->create([
            'name' => 'Ahmad Syahril',
            'email' => 'ahmad@example.com',
            'role' => 'peserta',
        ]);

        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Komunikasi dan Informatika',
            'kode_unit_kerja' => 'DISKOMINFO-' . uniqid(),
            'alamat' => 'Jl. RE Martadinata',
        ]);

        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Web Developer Intern',
            'kuota' => 5,
            'status' => 'buka',
            'deskripsi' => 'Lowongan magang developer.',
        ]);

        $application = Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'status' => 'pending',
            'tanggal_mulai' => now()->addDays(5)->format('Y-m-d'),
            'tanggal_selesai' => now()->addMonths(2)->format('Y-m-d'),
        ]);

        $this->assertNotEmpty($application->nomor_registrasi);

        $response = $this->get(route('tracking.search', ['keyword' => $application->nomor_registrasi]));

        $response->assertStatus(200);
        $response->assertSee($application->nomor_registrasi);
        $response->assertSee('Dinas Komunikasi dan Informatika');
        $response->assertSee('Web Developer Intern');
        // Name should be masked
        $response->assertSee('A**** S******');
    }

    public function test_guest_cannot_search_application_by_email_to_prevent_enumeration()
    {
        $user = User::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@kampus.ac.id',
            'role' => 'peserta',
        ]);

        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Pendidikan',
            'kode_unit_kerja' => 'DISDIK-' . uniqid(),
            'alamat' => 'Jl. Pierre Tendean',
        ]);

        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Admin IT',
            'kuota' => 2,
            'status' => 'buka',
            'deskripsi' => 'Magang admin.',
        ]);

        $application = Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'status' => 'diterima',
            'tanggal_mulai' => now()->addDays(2)->format('Y-m-d'),
            'tanggal_selesai' => now()->addMonths(1)->format('Y-m-d'),
        ]);

        // Email search should NOT return records (enumeration protection)
        $response = $this->get(route('tracking.search', ['keyword' => 'budi.santoso@kampus.ac.id']));
        $response->assertStatus(200);
        $response->assertSee('Permohonan Tidak Ditemukan');
        $response->assertDontSee($application->nomor_registrasi);

        // Searching by token verifikasi SHOULD return records
        if (!empty($application->token_verifikasi)) {
            $tokenResponse = $this->get(route('tracking.search', ['keyword' => $application->token_verifikasi]));
            $tokenResponse->assertStatus(200);
            $tokenResponse->assertSee($application->nomor_registrasi);
        }
    }

    public function test_guest_can_search_via_ajax_json()
    {
        $user = User::factory()->create([
            'name' => 'Citra Lestari',
            'email' => 'citra@example.com',
            'role' => 'peserta',
        ]);

        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Perhubungan',
            'kode_unit_kerja' => 'DISHUB-' . uniqid(),
            'alamat' => 'Jl. Perhubungan',
        ]);

        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Traffic Analyst',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $application = Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'status' => 'diterima',
            'tanggal_mulai' => now()->addDays(1)->format('Y-m-d'),
            'tanggal_selesai' => now()->addMonths(1)->format('Y-m-d'),
        ]);

        $response = $this->getJson(route('tracking.search', ['keyword' => $application->nomor_registrasi]));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'count' => 1,
        ]);
        $response->assertJsonFragment([
            'nomor_registrasi' => $application->nomor_registrasi,
            'instansi' => 'Dinas Perhubungan',
            'posisi' => 'Traffic Analyst',
        ]);
    }

    public function test_tracking_search_returns_empty_state_when_not_found()
    {
        $response = $this->get(route('tracking.search', ['keyword' => 'REG-999999-NOTFOUND']));

        $response->assertStatus(200);
        $response->assertSee('Permohonan Tidak Ditemukan');
    }
}
