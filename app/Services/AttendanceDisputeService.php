<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceDispute;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AttendanceDisputeService
{
    public function __construct(private AuditLogService $auditLogService)
    {
    }

    /**
     * Submit an attendance dispute by the participant.
     */
    public function submitDispute(Attendance $attendance, array $data, User $participant): AttendanceDispute
    {
        // 1. Participant ownership check
        if ($attendance->application->user_id !== $participant->id) {
            throw ValidationException::withMessages([
                'attendance_id' => ['Anda tidak memiliki hak akses untuk mengajukan sanggahan pada absensi ini.'],
            ]);
        }

        // 2. Prevent duplicate pending disputes
        if ($attendance->disputes()->where('status', AttendanceDispute::STATUS_PENDING)->exists()) {
            throw ValidationException::withMessages([
                'attendance_id' => ['Pengajuan sanggahan untuk absensi ini masih dalam proses peninjauan.'],
            ]);
        }

        // 3. Handle proof/evidence file upload
        $evidencePath = null;
        if (isset($data['evidence_file']) && $data['evidence_file'] instanceof UploadedFile) {
            $evidencePath = $data['evidence_file']->store('dispute_evidence', 'private');
        }

        $proposedStatus = $data['proposed_status'] ?? 'hadir';
        if (!in_array($proposedStatus, ['hadir', 'izin', 'sakit'], true)) {
            $proposedStatus = 'hadir';
        }

        $dispute = AttendanceDispute::create([
            'attendance_id' => $attendance->id,
            'user_id' => $participant->id,
            'reason' => $data['reason'],
            'evidence_file' => $evidencePath,
            'status' => AttendanceDispute::STATUS_PENDING,
            'proposed_status' => $proposedStatus,
        ]);

        $this->auditLogService->record('attendance.dispute_submitted', $dispute, [
            'attendance_id' => $attendance->id,
            'applicant_user_id' => $participant->id,
            'proposed_status' => $proposedStatus,
            'reason' => $data['reason'],
        ]);

        return $dispute;
    }

    /**
     * Review an attendance dispute by mentor or admin instansi.
     */
    public function reviewDispute(
        AttendanceDispute $dispute,
        string $action,
        ?string $reviewerNotes,
        User $reviewer
    ): AttendanceDispute {
        if (!$dispute->isPending()) {
            throw new \DomainException('Sanggahan ini sudah pernah diproses sebelumnya.');
        }

        if (!in_array($action, ['approve', 'reject'], true)) {
            throw new \InvalidArgumentException("Aksi review [{$action}] tidak dikenali.");
        }

        return DB::transaction(function () use ($dispute, $action, $reviewerNotes, $reviewer) {
            if ($action === 'approve') {
                $dispute->update([
                    'status' => AttendanceDispute::STATUS_APPROVED,
                    'reviewed_by' => $reviewer->id,
                    'reviewed_at' => now(),
                    'reviewer_notes' => $reviewerNotes,
                ]);

                // Update original attendance to approved state with the proposed status
                $attendance = $dispute->attendance;
                $attendance->update([
                    'status' => $dispute->proposed_status ?: 'hadir',
                    'validation_status' => 'approved',
                    'pembimbing_lapangan_note' => 'Disetujui via Sanggahan: ' . ($reviewerNotes ?: 'Sanggahan diterima oleh reviewer'),
                ]);

                $this->auditLogService->record('attendance.dispute_approved', $dispute, [
                    'attendance_id' => $attendance->id,
                    'reviewer_id' => $reviewer->id,
                    'resolved_status' => $attendance->status,
                ]);
            } else {
                $dispute->update([
                    'status' => AttendanceDispute::STATUS_REJECTED,
                    'reviewed_by' => $reviewer->id,
                    'reviewed_at' => now(),
                    'reviewer_notes' => $reviewerNotes,
                ]);

                $this->auditLogService->record('attendance.dispute_rejected', $dispute, [
                    'attendance_id' => $dispute->attendance_id,
                    'reviewer_id' => $reviewer->id,
                    'rejection_reason' => $reviewerNotes,
                ]);
            }

            return $dispute->fresh();
        });
    }
}
