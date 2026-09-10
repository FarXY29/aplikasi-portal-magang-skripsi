<?php

namespace Tests\Feature\Security;

use App\Models\Application;
use App\Models\Attendance;
use App\Models\AttendanceAttempt;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * P0-1003: Attendance & AttendanceAttempt Object-Level Authorization Matrix Test
 */
class AttendanceAuthorizationTest extends TestCase
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
    private Attendance $attendanceA;
    private AttendanceAttempt $attemptA;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);

        $this->instansiA = Instansi::create([
            'nama_dinas' => 'Dinas Alpha',
            'kode_unit_kerja' => 'ALPHA-02',
            'alamat' => 'Banjarmasin',
            'max_total_quota' => 10,
        ]);

        $this->instansiB = Instansi::create([
            'nama_dinas' => 'Dinas Beta',
            'kode_unit_kerja' => 'BETA-02',
            'alamat' => 'Banjarmasin',
            'max_total_quota' => 10,
        ]);

        $positionA = InternshipPosition::create([
            'instansi_id' => $this->instansiA->id,
            'judul_posisi' => 'Teknisi Alpha',
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

        $this->attendanceA = Attendance::create([
            'application_id' => $appA->id,
            'date' => now()->toDateString(),
            'status' => 'hadir',
            'clock_in' => '08:00:00',
        ]);

        $this->attemptA = AttendanceAttempt::create([
            'user_id' => $this->pesertaOwner->id,
            'application_id' => $appA->id,
            'instance_id' => $this->instansiA->id,
            'attendance_id' => $this->attendanceA->id,
            'attendance_type' => 'clock_in',
            'attempt_uuid' => (string) Str::uuid(),
            'server_received_at' => now(),
            'risk_score' => 15,
            'fraud_status' => 'low',
        ]);
    }

    public function test_owner_can_view_own_attendance(): void
    {
        $this->assertTrue(Gate::forUser($this->pesertaOwner)->allows('view', $this->attendanceA));
    }

    public function test_other_participant_cannot_view_attendance(): void
    {
        $this->assertFalse(Gate::forUser($this->pesertaOther)->allows('view', $this->attendanceA));
    }

    public function test_assigned_mentor_can_view_and_validate_attendance(): void
    {
        $this->assertTrue(Gate::forUser($this->mentorA)->allows('view', $this->attendanceA));
        $this->assertTrue(Gate::forUser($this->mentorA)->allows('validate', $this->attendanceA));
    }

    public function test_other_mentor_cannot_view_or_validate_attendance(): void
    {
        $this->assertFalse(Gate::forUser($this->mentorB)->allows('view', $this->attendanceA));
        $this->assertFalse(Gate::forUser($this->mentorB)->allows('validate', $this->attendanceA));
    }

    public function test_same_tenant_admin_can_view_and_validate_attendance(): void
    {
        $this->assertTrue(Gate::forUser($this->adminInstansiA)->allows('view', $this->attendanceA));
        $this->assertTrue(Gate::forUser($this->adminInstansiA)->allows('validate', $this->attendanceA));
    }

    public function test_different_tenant_admin_cannot_view_or_validate_attendance(): void
    {
        $this->assertFalse(Gate::forUser($this->adminInstansiB)->allows('view', $this->attendanceA));
        $this->assertFalse(Gate::forUser($this->adminInstansiB)->allows('validate', $this->attendanceA));
    }

    public function test_same_tenant_admin_can_view_attendance_attempt(): void
    {
        $this->assertTrue(Gate::forUser($this->adminInstansiA)->allows('view', $this->attemptA));
    }

    public function test_different_tenant_admin_cannot_view_attendance_attempt(): void
    {
        $this->assertFalse(Gate::forUser($this->adminInstansiB)->allows('view', $this->attemptA));
    }

    public function test_admin_kota_has_global_access_to_attendance_and_attempt(): void
    {
        $this->assertTrue(Gate::forUser($this->adminKota)->allows('view', $this->attendanceA));
        $this->assertTrue(Gate::forUser($this->adminKota)->allows('view', $this->attemptA));
    }
}
