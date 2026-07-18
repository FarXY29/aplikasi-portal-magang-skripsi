<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApplicationTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     */
    public function test_peserta_can_view_internship_positions(): void
    {
        $user = User::factory()->create([
            'role' => 'peserta'
        ]);

        $response = $this->actingAs($user)->get('/lowongan');

        $response->assertStatus(200);
    }

    public function test_peserta_can_see_application_form(): void
    {
        $user = User::factory()->create([
            'role' => 'peserta'
        ]);

        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Test',
            'kode_unit_kerja' => 'TEST02',
            'alamat' => 'Alamat Test',
            'jam_mulai_masuk' => '08:00',
            'jam_mulai_pulang' => '16:00',
            'max_total_quota' => 10,
        ]);

        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Software Engineer Test',
            'kuota' => 5,
            'status' => 'buka'
        ]);

        $response = $this->actingAs($user)->get('/peserta/daftar/' . $position->id);

        $response->assertStatus(200);
    }
}
