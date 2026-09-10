<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Attendance;
use App\Models\AttendanceDispute;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * P1-2003: Attendance Dispute Workflow Feature Tests
 */
class AttendanceDisputeTest extends TestCase
{
    use DatabaseTransactions;

    private Instansi $instansiA;
    private Instansi $instansiB;
    private InternshipPosition $positionA;
    private User $pesertaOwner;
    private User $pesertaOther;
    private User $mentorAssigned;
    private User $mentorOther;
    private User $adminInstansiA;
    private Application $application;
    private Attendance $attendance;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('attendance_disputes') || !Schema::hasTable('application_timelines')) {
            Artisan::call('migrate');
        }

        $this->seed(RoleAndPermissionSeeder::class);

        $this->instansiA = Instansi::factory()->create(['nama_dinas' => 'Dinas Alpha']);
        $this->instansiB = Instansi::factory()->create(['nama_dinas' => 'Dinas Beta']);

        $this->positionA = InternshipPosition::factory()->create([
            'instansi_id' => $this->instansiA->id,
            'kuota' => 5,
        ]);

        $this->mentorAssigned = User::factory()->create(['instansi_id' => $this->instansiA->id, 'role' => 'pembimbing_lapangan']);
        $this->mentorAssigned->assignRole('pembimbing_lapangan');

        $this->mentorOther = User::factory()->create(['instansi_id' => $this->instansiB->id, 'role' => 'pembimbing_lapangan']);
        $this->mentorOther->assignRole('pembimbing_lapangan');

        $this->adminInstansiA = User::factory()->create(['instansi_id' => $this->instansiA->id, 'role' => 'admin_instansi']);
        $this->adminInstansiA->assignRole('admin_instansi');

        $this->pesertaOwner = User::factory()->create(['role' => 'peserta']);
        $this->pesertaOwner->assignRole('peserta');

        $this->pesertaOther = User::factory()->create(['role' => 'peserta']);
        $this->pesertaOther->assignRole('peserta');

        $this->application = Application::factory()->create([
            'user_id' => $this->pesertaOwner->id,
            'internship_position_id' => $this->positionA->id,
            'pembimbing_lapangan_id' => $this->mentorAssigned->id,
            'status' => 'diterima',
            'tanggal_mulai' => Carbon::now()->subDays(10)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now()->addDays(20)->format('Y-m-d'),
        ]);

        $this->attendance = Attendance::create([
            'application_id' => $this->application->id,
            'date' => Carbon::now()->subDay()->format('Y-m-d'),
            'status' => 'alpa',
            'validation_status' => 'rejected',
            'description' => 'Tidak ada keterangan kehadiran',
        ]);
    }

    public function test_peserta_can_submit_dispute_with_reason_and_evidence(): void
    {
        Storage::fake('private');

        $file = UploadedFile::fake()->create('surat_tugas.pdf', 300, 'application/pdf');

        $response = $this->actingAs($this->pesertaOwner)
            ->post(route('peserta.absensi.dispute', $this->attendance->id), [
                'reason' => 'Saya hadir namun server presensi sedang maintenance.',
                'proposed_status' => 'hadir',
                'evidence_file' => $file,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('attendance_disputes', [
            'attendance_id' => $this->attendance->id,
            'user_id' => $this->pesertaOwner->id,
            'status' => AttendanceDispute::STATUS_PENDING,
            'proposed_status' => 'hadir',
            'reason' => 'Saya hadir namun server presensi sedang maintenance.',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'attendance.dispute_submitted',
        ]);
    }

    public function test_other_peserta_cannot_submit_dispute_for_another_attendance(): void
    {
        $response = $this->actingAs($this->pesertaOther)
            ->post(route('peserta.absensi.dispute', $this->attendance->id), [
                'reason' => 'Mencoba sanggah absen orang lain.',
                'proposed_status' => 'hadir',
            ]);

        $response->assertForbidden();
    }

    public function test_cannot_submit_duplicate_pending_dispute(): void
    {
        AttendanceDispute::create([
            'attendance_id' => $this->attendance->id,
            'user_id' => $this->pesertaOwner->id,
            'reason' => 'Dispute 1',
            'proposed_status' => 'hadir',
            'status' => AttendanceDispute::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->pesertaOwner)
            ->post(route('peserta.absensi.dispute', $this->attendance->id), [
                'reason' => 'Dispute duplicate',
                'proposed_status' => 'hadir',
            ]);

        $response->assertSessionHasErrors(['attendance_id']);
    }

    public function test_assigned_mentor_can_approve_dispute_and_update_attendance(): void
    {
        $dispute = AttendanceDispute::create([
            'attendance_id' => $this->attendance->id,
            'user_id' => $this->pesertaOwner->id,
            'reason' => 'Lupa absen karena tugas lapangan mendesak.',
            'proposed_status' => 'hadir',
            'status' => AttendanceDispute::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->mentorAssigned)
            ->post(route('pembimbing_lapangan.attendance.dispute.review', $dispute->id), [
                'action' => 'approve',
                'reviewer_notes' => 'Tugas lapangan terkonfirmasi oleh koordinator.',
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertEquals(AttendanceDispute::STATUS_APPROVED, $dispute->fresh()->status);
        $this->assertEquals($this->mentorAssigned->id, $dispute->fresh()->reviewed_by);

        $attendance = $this->attendance->fresh();
        $this->assertEquals('hadir', $attendance->status);
        $this->assertEquals('approved', $attendance->validation_status);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'attendance.dispute_approved',
        ]);
    }

    public function test_assigned_mentor_can_reject_dispute(): void
    {
        $dispute = AttendanceDispute::create([
            'attendance_id' => $this->attendance->id,
            'user_id' => $this->pesertaOwner->id,
            'reason' => 'Bukti foto selfie di rumah.',
            'proposed_status' => 'hadir',
            'status' => AttendanceDispute::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->mentorAssigned)
            ->post(route('pembimbing_lapangan.attendance.dispute.review', $dispute->id), [
                'action' => 'reject',
                'reviewer_notes' => 'Bukti tidak valid dan tidak ada izin tertulis.',
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertEquals(AttendanceDispute::STATUS_REJECTED, $dispute->fresh()->status);
        $this->assertEquals('alpa', $this->attendance->fresh()->status);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'attendance.dispute_rejected',
        ]);
    }

    public function test_different_mentor_cannot_review_dispute_due_to_tenancy(): void
    {
        $dispute = AttendanceDispute::create([
            'attendance_id' => $this->attendance->id,
            'user_id' => $this->pesertaOwner->id,
            'reason' => 'Sanggahan absensi',
            'proposed_status' => 'hadir',
            'status' => AttendanceDispute::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->mentorOther)
            ->post(route('pembimbing_lapangan.attendance.dispute.review', $dispute->id), [
                'action' => 'approve',
            ]);

        $response->assertForbidden();
    }

    public function test_peserta_cannot_review_dispute(): void
    {
        $dispute = AttendanceDispute::create([
            'attendance_id' => $this->attendance->id,
            'user_id' => $this->pesertaOwner->id,
            'reason' => 'Sanggahan absensi',
            'proposed_status' => 'hadir',
            'status' => AttendanceDispute::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->pesertaOwner)
            ->post(route('pembimbing_lapangan.attendance.dispute.review', $dispute->id), [
                'action' => 'approve',
            ]);

        $response->assertForbidden();
    }

    public function test_admin_instansi_can_approve_dispute_for_their_institution(): void
    {
        $dispute = AttendanceDispute::create([
            'attendance_id' => $this->attendance->id,
            'user_id' => $this->pesertaOwner->id,
            'reason' => 'Admin instansi review test',
            'proposed_status' => 'hadir',
            'status' => AttendanceDispute::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->adminInstansiA)
            ->post(route('dinas.peserta.absensi.dispute.review', $dispute->id), [
                'action' => 'approve',
                'reviewer_notes' => 'Disetujui oleh admin instansi setelah verifikasi berkas.',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals(AttendanceDispute::STATUS_APPROVED, $dispute->fresh()->status);
        $this->assertEquals($this->adminInstansiA->id, $dispute->fresh()->reviewed_by);
        $this->assertEquals('hadir', $this->attendance->fresh()->status);
    }

    public function test_cannot_review_already_processed_dispute(): void
    {
        $dispute = AttendanceDispute::create([
            'attendance_id' => $this->attendance->id,
            'user_id' => $this->pesertaOwner->id,
            'reason' => 'Sanggahan sudah disetujui',
            'proposed_status' => 'hadir',
            'status' => AttendanceDispute::STATUS_APPROVED,
            'reviewed_by' => $this->mentorAssigned->id,
            'reviewed_at' => now(),
        ]);

        $response = $this->actingAs($this->mentorAssigned)
            ->post(route('pembimbing_lapangan.attendance.dispute.review', $dispute->id), [
                'action' => 'approve',
            ]);

        $response->assertSessionHas('error');
    }

    public function test_expelling_intern_auto_rejects_pending_attendance_disputes(): void
    {
        $dispute = AttendanceDispute::create([
            'attendance_id' => $this->attendance->id,
            'user_id' => $this->pesertaOwner->id,
            'reason' => 'Sanggahan sebelum dikeluarkan',
            'proposed_status' => 'hadir',
            'status' => AttendanceDispute::STATUS_PENDING,
        ]);

        $transitionService = app(\App\Services\ApplicationStateTransitionService::class);
        $transitionService->expel($this->application, 'Melanggar aturan berat', $this->adminInstansiA->id);

        $this->assertEquals(AttendanceDispute::STATUS_REJECTED, $dispute->fresh()->status);
        $this->assertStringContainsString('dikeluarkan', $dispute->fresh()->reviewer_notes);
    }

    public function test_authorized_user_can_access_dispute_evidence_via_storage_service(): void
    {
        Storage::fake('private');
        $filePath = 'dispute_evidence/test_proof.pdf';
        Storage::disk('private')->put($filePath, 'PDF CONTENT');

        $dispute = AttendanceDispute::create([
            'attendance_id' => $this->attendance->id,
            'user_id' => $this->pesertaOwner->id,
            'reason' => 'Bukti PDF',
            'evidence_file' => $filePath,
            'proposed_status' => 'hadir',
            'status' => AttendanceDispute::STATUS_PENDING,
        ]);

        // Participant owner can view
        $response = $this->actingAs($this->pesertaOwner)
            ->get(route('storage.access', ['type' => 'dispute', 'filename' => 'test_proof.pdf']));
        $response->assertOk();

        // Assigned mentor can view
        $response = $this->actingAs($this->mentorAssigned)
            ->get(route('storage.access', ['type' => 'dispute', 'filename' => 'test_proof.pdf']));
        $response->assertOk();

        // Unrelated participant cannot view
        $response = $this->actingAs($this->pesertaOther)
            ->get(route('storage.access', ['type' => 'dispute', 'filename' => 'test_proof.pdf']));
        $response->assertForbidden();
    }
}
