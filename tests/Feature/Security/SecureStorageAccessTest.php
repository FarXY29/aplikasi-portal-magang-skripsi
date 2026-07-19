<?php

namespace Tests\Feature\Security;

use App\Models\Application;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecureStorageAccessTest extends TestCase
{
    use DatabaseTransactions;

    public function test_participant_cannot_open_another_participants_private_letter(): void
    {
        Storage::fake('private');

        $owner = User::factory()->create(['role' => 'peserta']);
        $otherParticipant = User::factory()->create(['role' => 'peserta']);
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Keamanan Test',
            'kode_unit_kerja' => 'SEC-TEST',
            'alamat' => 'Banjarmasin',
            'jam_mulai_masuk' => '08:00',
            'jam_mulai_pulang' => '16:00',
            'max_total_quota' => 10,
        ]);
        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Posisi Keamanan',
            'kuota' => 1,
            'status' => 'buka',
        ]);
        $path = 'documents/surat/private-letter.pdf';
        Storage::disk('private')->put($path, 'private document');

        Application::create([
            'user_id' => $owner->id,
            'internship_position_id' => $position->id,
            'cv_path' => '-',
            'surat_pengantar_path' => $path,
            'status' => 'pending',
            'tanggal_mulai' => now()->addWeek()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
        ]);

        $this->actingAs($otherParticipant)
            ->get(route('storage.access', ['type' => 'surat', 'filename' => 'private-letter.pdf']))
            ->assertForbidden();

        $this->actingAs($owner)
            ->get(route('storage.access', ['type' => 'surat', 'filename' => 'private-letter.pdf']))
            ->assertOk();
    }
}
