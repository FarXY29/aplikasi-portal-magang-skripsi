<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;

class RolePembimbingLapanganTest extends TestCase
{
    use DatabaseTransactions;

    public function test_pembimbing_lapangan_can_access_dashboard()
    {
        $user = User::factory()->create(['role' => 'pembimbing_lapangan']);

        $response = $this->actingAs($user)->get(route('pembimbing_lapangan.dashboard'));
        $response->assertStatus(200);
    }

    public function test_pembimbing_lapangan_can_upload_signature_in_profile()
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $user = User::factory()->create(['role' => 'pembimbing_lapangan']);

        $file = \Illuminate\Http\UploadedFile::fake()->image('signature.png', 200, 100);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'signature' => $file,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('profile.edit'));

        $user->refresh();
        $this->assertNotNull($user->signature);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($user->signature);
    }

    public function test_pembimbing_lapangan_signature_is_preserved_when_updating_profile_without_new_file()
    {
        $user = User::factory()->create([
            'role' => 'pembimbing_lapangan',
            'signature' => 'signatures/existing_signature.png',
        ]);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'Nama Pembimbing Baru',
            'email' => $user->email,
            'phone' => '08123456789',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('profile.edit'));

        $user->refresh();
        $this->assertEquals('Nama Pembimbing Baru', $user->name);
        $this->assertEquals('signatures/existing_signature.png', $user->signature);
    }

    public function test_pembimbing_lapangan_profile_edit_page_renders_signature_section()
    {
        \Illuminate\Support\Facades\Storage::fake('public');
        \Illuminate\Support\Facades\Storage::disk('public')->put('signatures/existing_signature.png', 'dummy_content');

        $user = User::factory()->create([
            'role' => 'pembimbing_lapangan',
            'signature' => 'signatures/existing_signature.png',
        ]);

        $response = $this->actingAs($user)->get(route('profile.edit'));
        $response->assertStatus(200);
        $response->assertSee('Tanda Tangan &amp; Paraf Digital', false);
        $response->assertSee('signatures/existing_signature.png', false);
    }

    public function test_peserta_logbook_rekap_pdf_renders_with_paraf_and_signature()
    {
        $pl = User::factory()->create([
            'role' => 'pembimbing_lapangan',
            'signature' => 'signatures/dummy_signature.png',
        ]);

        $peserta = User::factory()->create([
            'role' => 'peserta',
            'asal_instansi' => 'Universitas Lambung Mangkurat',
        ]);

        $instansi = \App\Models\Instansi::create([
            'nama_dinas' => 'Dinas Kominfo',
            'kode_unit_kerja' => 'DISKOMINFO-01',
            'alamat' => 'Banjarmasin',
            'ttd_kepala' => 'signatures/dummy_kepala.png',
        ]);

        $position = \App\Models\InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Software Engineer Intern',
            'kuota' => 5,
            'status' => 'buka',
        ]);

        $app = \App\Models\Application::create([
            'user_id' => $peserta->id,
            'internship_position_id' => $position->id,
            'pembimbing_lapangan_id' => $pl->id,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'status' => 'diterima',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
        ]);

        \App\Models\DailyLog::create([
            'application_id' => $app->id,
            'tanggal' => now()->toDateString(),
            'kegiatan' => 'Mengerjakan modul backend dan integrasi API.',
            'status_validasi' => 'disetujui',
            'komentar_pembimbing_lapangan' => 'Kerja bagus!',
        ]);

        $response = $this->actingAs($peserta)->get(route('peserta.logbook.print', $app->id));
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('Content-Disposition') ?? '', '.pdf') || $response->headers->get('Content-Type') === 'application/pdf');
    }
}
