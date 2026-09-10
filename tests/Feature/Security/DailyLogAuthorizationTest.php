<?php

namespace Tests\Feature\Security;

use App\Models\Application;
use App\Models\DailyLog;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

/**
 * P0-1003: DailyLog Object-Level Authorization Matrix Test
 */
class DailyLogAuthorizationTest extends TestCase
{
    use DatabaseTransactions;

    private Instansi $instansiA;
    private Instansi $instansiB;
    private User $pesertaOwner;
    private User $pesertaOther;
    private User $adminInstansiA;
    private User $adminInstansiB;
    private User $mentorA;
    private User $mentorB;
    private User $adminKota;
    private DailyLog $logPending;
    private DailyLog $logApproved;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);

        $this->instansiA = Instansi::create([
            'nama_dinas' => 'Dinas Alpha',
            'kode_unit_kerja' => 'ALPHA-03',
            'alamat' => 'Banjarmasin',
            'max_total_quota' => 10,
        ]);

        $this->instansiB = Instansi::create([
            'nama_dinas' => 'Dinas Beta',
            'kode_unit_kerja' => 'BETA-03',
            'alamat' => 'Banjarmasin',
            'max_total_quota' => 10,
        ]);

        $positionA = InternshipPosition::create([
            'instansi_id' => $this->instansiA->id,
            'judul_posisi' => 'Staff Alpha',
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

        $this->pesertaOwner = User::factory()->create(['role' => 'peserta']);
        $this->pesertaOther = User::factory()->create(['role' => 'peserta']);

        $this->adminInstansiA = User::factory()->create([
            'role' => 'admin_instansi',
            'instansi_id' => $this->instansiA->id,
        ]);
        $this->adminInstansiB = User::factory()->create([
            'role' => 'admin_instansi',
            'instansi_id' => $this->instansiB->id,
        ]);

        $this->adminKota = User::factory()->create(['role' => 'admin_kota']);

        $appA = Application::create([
            'user_id' => $this->pesertaOwner->id,
            'internship_position_id' => $positionA->id,
            'pembimbing_lapangan_id' => $this->mentorA->id,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
            'status' => 'diterima',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
        ]);

        $this->logPending = DailyLog::create([
            'application_id' => $appA->id,
            'tanggal' => now()->toDateString(),
            'kegiatan' => 'Membuat modul autentikasi',
            'status_validasi' => 'pending',
        ]);

        $this->logApproved = DailyLog::create([
            'application_id' => $appA->id,
            'tanggal' => now()->subDay()->toDateString(),
            'kegiatan' => 'Orientasi sistem kantor',
            'status_validasi' => 'disetujui',
        ]);
    }

    public function test_owner_can_view_update_delete_pending_logbook(): void
    {
        $this->assertTrue(Gate::forUser($this->pesertaOwner)->allows('view', $this->logPending));
        $this->assertTrue(Gate::forUser($this->pesertaOwner)->allows('update', $this->logPending));
        $this->assertTrue(Gate::forUser($this->pesertaOwner)->allows('delete', $this->logPending));
    }

    public function test_owner_cannot_update_or_delete_approved_logbook(): void
    {
        $this->assertTrue(Gate::forUser($this->pesertaOwner)->allows('view', $this->logApproved));
        $this->assertFalse(Gate::forUser($this->pesertaOwner)->allows('update', $this->logApproved));
        $this->assertFalse(Gate::forUser($this->pesertaOwner)->allows('delete', $this->logApproved));
    }

    public function test_other_participant_cannot_access_logbook(): void
    {
        $this->assertFalse(Gate::forUser($this->pesertaOther)->allows('view', $this->logPending));
        $this->assertFalse(Gate::forUser($this->pesertaOther)->allows('update', $this->logPending));
        $this->assertFalse(Gate::forUser($this->pesertaOther)->allows('delete', $this->logPending));
    }

    public function test_assigned_mentor_can_validate_logbook(): void
    {
        $this->assertTrue(Gate::forUser($this->mentorA)->allows('validate', $this->logPending));
    }

    public function test_different_tenant_mentor_cannot_validate_logbook(): void
    {
        $this->assertFalse(Gate::forUser($this->mentorB)->allows('validate', $this->logPending));
    }

    public function test_same_tenant_admin_can_validate_logbook(): void
    {
        $this->assertTrue(Gate::forUser($this->adminInstansiA)->allows('validate', $this->logPending));
    }

    public function test_different_tenant_admin_cannot_validate_logbook(): void
    {
        $this->assertFalse(Gate::forUser($this->adminInstansiB)->allows('validate', $this->logPending));
    }

    public function test_admin_kota_has_global_access(): void
    {
        $this->assertTrue(Gate::forUser($this->adminKota)->allows('view', $this->logPending));
        $this->assertTrue(Gate::forUser($this->adminKota)->allows('validate', $this->logPending));
    }
}
