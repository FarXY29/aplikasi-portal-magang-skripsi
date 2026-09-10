<?php

namespace Tests\Feature\Security;

use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

/**
 * P0-1003: Multi-tenant Institution Isolation Security Test
 */
class InstitutionIsolationTest extends TestCase
{
    use DatabaseTransactions;

    private Instansi $instansiA;
    private Instansi $instansiB;
    private User $adminA;
    private User $adminB;
    private InternshipPosition $posisiA;
    private InternshipPosition $posisiB;
    private User $mentorA;
    private User $mentorB;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);

        $this->instansiA = Instansi::create([
            'nama_dinas' => 'Dinas Alpha Isolation',
            'kode_unit_kerja' => 'ISO-A',
            'alamat' => 'Banjarmasin',
            'max_total_quota' => 10,
        ]);

        $this->instansiB = Instansi::create([
            'nama_dinas' => 'Dinas Beta Isolation',
            'kode_unit_kerja' => 'ISO-B',
            'alamat' => 'Banjarmasin',
            'max_total_quota' => 10,
        ]);

        $this->adminA = User::factory()->create([
            'role' => 'admin_instansi',
            'instansi_id' => $this->instansiA->id,
        ]);

        $this->adminB = User::factory()->create([
            'role' => 'admin_instansi',
            'instansi_id' => $this->instansiB->id,
        ]);

        $this->posisiA = InternshipPosition::create([
            'instansi_id' => $this->instansiA->id,
            'judul_posisi' => 'Staff Alpha',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $this->posisiB = InternshipPosition::create([
            'instansi_id' => $this->instansiB->id,
            'judul_posisi' => 'Staff Beta',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $this->mentorA = User::factory()->create([
            'role' => 'pembimbing_lapangan',
            'instansi_id' => $this->instansiA->id,
        ]);

        $this->mentorB = User::factory()->create([
            'role' => 'pembimbing_lapangan',
            'instansi_id' => $this->instansiB->id,
        ]);
    }

    public function test_admin_a_cannot_manage_position_belonging_to_instansi_b(): void
    {
        $this->assertTrue(Gate::forUser($this->adminA)->allows('manage', $this->posisiA));
        $this->assertFalse(Gate::forUser($this->adminA)->allows('manage', $this->posisiB));
    }

    public function test_admin_b_cannot_manage_position_belonging_to_instansi_a(): void
    {
        $this->assertTrue(Gate::forUser($this->adminB)->allows('manage', $this->posisiB));
        $this->assertFalse(Gate::forUser($this->adminB)->allows('manage', $this->posisiA));
    }

    public function test_admin_a_cannot_access_mentor_edit_page_of_instansi_b(): void
    {
        // Route is protected by query filter where('instansi_id', Auth::user()->instansi_id) -> 404
        $this->actingAs($this->adminA)
            ->get(route('dinas.pembimbing_lapangan.edit', $this->mentorB->id))
            ->assertNotFound();
    }

    public function test_admin_a_cannot_update_mentor_of_instansi_b(): void
    {
        $this->actingAs($this->adminA)
            ->put(route('dinas.pembimbing_lapangan.update', $this->mentorB->id), [
                'name' => 'Hacked Mentor',
                'email' => 'hacked@example.com',
            ])
            ->assertNotFound();
    }
}
