<?php

namespace Tests\Feature\Security;

use App\Models\Application;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

/**
 * P0-1003: Application Object-Level Authorization Matrix Test
 * Scenarios: Owner, Same tenant, Different tenant, Unassigned mentor, Assigned mentor, Admin global
 */
class ApplicationAuthorizationTest extends TestCase
{
    use DatabaseTransactions;

    private Instansi $instansiA;
    private Instansi $instansiB;
    private InternshipPosition $positionA;
    private InternshipPosition $positionB;
    private User $pesertaOwner;
    private User $pesertaOther;
    private User $adminInstansiA;
    private User $adminInstansiB;
    private User $pembimbingSekolahAssigned;
    private User $pembimbingSekolahOther;
    private User $pembimbingLapanganAssigned;
    private User $pembimbingLapanganOther;
    private User $adminKota;
    private Application $applicationA;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);

        $this->instansiA = Instansi::create([
            'nama_dinas' => 'Dinas Alpha',
            'kode_unit_kerja' => 'ALPHA-01',
            'alamat' => 'Banjarmasin',
            'max_total_quota' => 10,
        ]);

        $this->instansiB = Instansi::create([
            'nama_dinas' => 'Dinas Beta',
            'kode_unit_kerja' => 'BETA-01',
            'alamat' => 'Banjarmasin',
            'max_total_quota' => 10,
        ]);

        $this->positionA = InternshipPosition::create([
            'instansi_id' => $this->instansiA->id,
            'judul_posisi' => 'Programmer Alpha',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $this->positionB = InternshipPosition::create([
            'instansi_id' => $this->instansiB->id,
            'judul_posisi' => 'Programmer Beta',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $this->pembimbingSekolahAssigned = User::factory()->create(['role' => 'pembimbing']);
        $this->pembimbingSekolahOther = User::factory()->create(['role' => 'pembimbing']);

        $this->pesertaOwner = User::factory()->create([
            'role' => 'peserta',
            'pembimbing_sekolah_id' => $this->pembimbingSekolahAssigned->id,
        ]);
        $this->pesertaOther = User::factory()->create(['role' => 'peserta']);

        $this->adminInstansiA = User::factory()->create([
            'role' => 'admin_instansi',
            'instansi_id' => $this->instansiA->id,
        ]);
        $this->adminInstansiB = User::factory()->create([
            'role' => 'admin_instansi',
            'instansi_id' => $this->instansiB->id,
        ]);

        $this->pembimbingLapanganAssigned = User::factory()->create([
            'role' => 'pembimbing_lapangan',
            'instansi_id' => $this->instansiA->id,
        ]);
        $this->pembimbingLapanganOther = User::factory()->create([
            'role' => 'pembimbing_lapangan',
            'instansi_id' => $this->instansiB->id,
        ]);

        $this->adminKota = User::factory()->create(['role' => 'admin_kota']);

        $this->applicationA = Application::create([
            'user_id' => $this->pesertaOwner->id,
            'internship_position_id' => $this->positionA->id,
            'pembimbing_lapangan_id' => $this->pembimbingLapanganAssigned->id,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'status' => 'diterima',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
        ]);
    }

    public function test_owner_can_view_own_application(): void
    {
        $this->assertTrue(Gate::forUser($this->pesertaOwner)->allows('view', $this->applicationA));
    }

    public function test_other_participant_cannot_view_application(): void
    {
        $this->assertFalse(Gate::forUser($this->pesertaOther)->allows('view', $this->applicationA));
    }

    public function test_same_tenant_admin_can_view_and_manage_application(): void
    {
        $this->assertTrue(Gate::forUser($this->adminInstansiA)->allows('view', $this->applicationA));
        $this->assertTrue(Gate::forUser($this->adminInstansiA)->allows('manageActiveIntern', $this->applicationA));
    }

    public function test_different_tenant_admin_cannot_view_or_manage_application(): void
    {
        $this->assertFalse(Gate::forUser($this->adminInstansiB)->allows('view', $this->applicationA));
        $this->assertFalse(Gate::forUser($this->adminInstansiB)->allows('manageActiveIntern', $this->applicationA));
    }

    public function test_assigned_school_mentor_can_view_application(): void
    {
        $this->assertTrue(Gate::forUser($this->pembimbingSekolahAssigned)->allows('view', $this->applicationA));
    }

    public function test_unassigned_school_mentor_cannot_view_application(): void
    {
        $this->assertFalse(Gate::forUser($this->pembimbingSekolahOther)->allows('view', $this->applicationA));
    }

    public function test_assigned_field_mentor_can_view_application(): void
    {
        $this->assertTrue(Gate::forUser($this->pembimbingLapanganAssigned)->allows('view', $this->applicationA));
    }

    public function test_different_instansi_field_mentor_cannot_view_application(): void
    {
        $this->assertFalse(Gate::forUser($this->pembimbingLapanganOther)->allows('view', $this->applicationA));
    }

    public function test_admin_kota_has_global_access_to_all_application_abilities(): void
    {
        $this->assertTrue(Gate::forUser($this->adminKota)->allows('view', $this->applicationA));
        $this->assertTrue(Gate::forUser($this->adminKota)->allows('manageActiveIntern', $this->applicationA));
    }
}
