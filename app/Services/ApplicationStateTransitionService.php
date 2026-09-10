<?php

namespace App\Services;

use App\Actions\GenerateCertificateNumberAction;
use App\Enums\ApplicationStatus;
use App\Exceptions\CancellationPolicyException;
use App\Exceptions\InvalidApplicationStateTransitionException;
use App\Mail\ApplicationAcceptedMail;
use App\Mail\ApplicationRejectedMail;
use App\Mail\InternshipCompleted;
use App\Models\Application;
use App\Models\ApplicationTimeline;
use App\Models\AttendanceDispute;
use App\Models\InternshipPosition;
use App\Notifications\ApplicationStatusNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ApplicationStateTransitionService
{
    /**
     * Allowed state transitions map (P1-2001 Formal State Machine).
     */
    private const ALLOWED_TRANSITIONS = [
        'draft' => ['pending', 'menunggu'],
        'pending' => ['diterima', 'ditolak', 'menunggu', 'dibatalkan'],
        'menunggu' => ['pending', 'diterima', 'ditolak', 'dibatalkan'],
        'diterima' => ['selesai', 'dibatalkan', 'dikeluarkan'],
        'ditolak' => [],
        'selesai' => [],
        'dikeluarkan' => [],
        'dibatalkan' => [],
    ];

    public function __construct(
        private AuditLogService $auditLogService,
        private GenerateCertificateNumberAction $generateCertificateNumberAction,
    ) {
    }

    /**
     * Check whether a transition from one status to another is valid.
     */
    public function canTransition(Application|string $from, ApplicationStatus|string $to): bool
    {
        $fromStr = $this->normalizeStatus($from);
        $toStr = $this->normalizeStatus($to);

        $allowed = self::ALLOWED_TRANSITIONS[$fromStr] ?? [];

        return in_array($toStr, $allowed, true);
    }

    /**
     * Get list of allowed target statuses for a given source status.
     *
     * @return array<string>
     */
    public function getAllowedTransitions(Application|ApplicationStatus|string $from): array
    {
        $fromStr = $this->normalizeStatus($from);

        return self::ALLOWED_TRANSITIONS[$fromStr] ?? [];
    }

    /**
     * Assert that a transition is permitted, otherwise throw a domain exception.
     *
     * @throws InvalidApplicationStateTransitionException
     */
    public function assertCanTransition(Application|string $from, ApplicationStatus|string $to, ?int $applicationId = null): void
    {
        $fromStr = $this->normalizeStatus($from);
        $toStr = $this->normalizeStatus($to);

        if (!$this->canTransition($fromStr, $toStr)) {
            throw new InvalidApplicationStateTransitionException($fromStr, $toStr, $applicationId);
        }
    }

    /**
     * Centralized transition runner with validation, transactions, audit logs, and timeline events.
     */
    public function transition(
        Application $application,
        ApplicationStatus|string $targetStatus,
        array $context = []
    ): Application {
        $fromStatus = $this->normalizeStatus($application->status);
        $toStatus = $this->normalizeStatus($targetStatus);

        $this->assertCanTransition($fromStatus, $toStatus, $application->id);

        $actorId = $context['actor_id'] ?? auth()->id();

        return DB::transaction(function () use ($application, $fromStatus, $toStatus, $actorId, $context) {
            // Lock application row for consistency
            $app = Application::where('id', $application->id)->lockForUpdate()->firstOrFail();

            return match ($toStatus) {
                'diterima' => $this->handleTransitionToDiterima($app, $fromStatus, $actorId, $context),
                'ditolak' => $this->handleTransitionToDitolak($app, $fromStatus, $actorId, $context),
                'dibatalkan' => $this->handleTransitionToDibatalkan($app, $fromStatus, $actorId, $context),
                'selesai' => $this->handleTransitionToSelesai($app, $fromStatus, $actorId, $context),
                'dikeluarkan' => $this->handleTransitionToDikeluarkan($app, $fromStatus, $actorId, $context),
                'pending' => $this->handleTransitionToPending($app, $fromStatus, $actorId, $context),
                'menunggu' => $this->handleTransitionToMenunggu($app, $fromStatus, $actorId, $context),
                default => throw new InvalidApplicationStateTransitionException($fromStatus, $toStatus, $app->id),
            };
        });
    }

    /**
     * Convenience method: Accept application into internship.
     */
    public function accept(Application $application, ?int $actorId = null): Application
    {
        return $this->transition($application, ApplicationStatus::Diterima, [
            'actor_id' => $actorId ?? auth()->id(),
        ]);
    }

    /**
     * Convenience method: Reject application with reason.
     */
    public function reject(Application $application, ?string $reason = null, ?int $actorId = null): Application
    {
        return $this->transition($application, ApplicationStatus::Ditolak, [
            'actor_id' => $actorId ?? auth()->id(),
            'reason' => $reason,
        ]);
    }

    /**
     * Convenience method: Cancel application (enforcing Cancellation & Withdrawal Policies).
     */
    public function cancel(
        Application $application,
        ?string $reason = null,
        ?int $actorId = null,
        bool $isParticipant = false
    ): Application {
        $this->assertCanCancel($application, $isParticipant);

        return $this->transition($application, ApplicationStatus::Dibatalkan, [
            'actor_id' => $actorId ?? auth()->id(),
            'reason' => $reason,
            'is_participant' => $isParticipant,
        ]);
    }

    /**
     * Convenience method: Mark internship as completed/graduated.
     */
    public function finish(Application $application, ?int $actorId = null): Application
    {
        return $this->transition($application, ApplicationStatus::Selesai, [
            'actor_id' => $actorId ?? auth()->id(),
        ]);
    }

    /**
     * Convenience method: Expel intern due to violations.
     */
    public function expel(Application $application, ?string $reason = null, ?int $actorId = null): Application
    {
        return $this->transition($application, ApplicationStatus::Dikeluarkan, [
            'actor_id' => $actorId ?? auth()->id(),
            'reason' => $reason,
        ]);
    }

    /**
     * Check whether an application can be cancelled under policy rules.
     *
     * @throws CancellationPolicyException
     */
    public function assertCanCancel(Application $application, bool $isParticipant = true): void
    {
        $status = $this->normalizeStatus($application->status);

        if (in_array($status, ['ditolak', 'selesai', 'dikeluarkan', 'dibatalkan'], true)) {
            throw new CancellationPolicyException(
                $application->id,
                "Lamaran dengan status [{$status}] sudah bersifat final dan tidak dapat dibatalkan."
            );
        }

        if ($status === 'diterima' && $isParticipant && !empty($application->tanggal_mulai)) {
            $startDate = Carbon::parse($application->tanggal_mulai)->startOfDay();
            if (Carbon::now()->startOfDay()->gte($startDate)) {
                throw new CancellationPolicyException(
                    $application->id,
                    'Lamaran ini tidak dapat dibatalkan karena masa magang sudah dimulai. Silakan hubungi admin instansi untuk proses penarikan diri / pengunduran diri.'
                );
            }
        }
    }

    /**
     * Auto-reallocate quota from waiting list candidates.
     */
    public function autoReallocateQuota(int $positionId): int
    {
        return DB::transaction(function () use ($positionId) {
            $position = InternshipPosition::where('id', $positionId)->lockForUpdate()->first();
            if (!$position) {
                return 0;
            }

            $instansi = $position->instansi()->lockForUpdate()->first();

            $waitingCandidates = Application::where('internship_position_id', $positionId)
                ->where('status', 'menunggu')
                ->orderBy('created_at', 'asc')
                ->get();

            $promotedCount = 0;

            foreach ($waitingCandidates as $candidate) {
                $start = $candidate->tanggal_mulai;
                $end = $candidate->tanggal_selesai;

                $conflictingCount = Application::where('internship_position_id', $position->id)
                    ->whereIn('status', ['diterima', 'pending'])
                    ->where(function ($q) use ($start, $end) {
                        $q->where('tanggal_mulai', '<=', $end)
                          ->where('tanggal_selesai', '>=', $start);
                    })
                    ->count();

                $kuota = max(0, (int) ($position->kuota ?? 1));
                if ($conflictingCount >= $kuota) {
                    continue;
                }

                if ($instansi && $instansi->max_total_quota > 0) {
                    $instansiActiveCount = Application::whereHas('position', fn ($q) => $q->where('instansi_id', $instansi->id))
                        ->whereIn('status', ['diterima', 'pending'])
                        ->where(function ($q) use ($start, $end) {
                            $q->where('tanggal_mulai', '<=', $end)
                              ->where('tanggal_selesai', '>=', $start);
                        })
                        ->count();

                    if ($instansiActiveCount >= $instansi->max_total_quota) {
                        continue;
                    }
                }

                $this->transition($candidate, ApplicationStatus::Pending, [
                    'actor_id' => auth()->id(),
                    'reason' => 'Dipromosikan otomatis karena kuota tersedia.',
                ]);

                $promotedCount++;
            }

            return $promotedCount;
        });
    }

    /**
     * Promote next waiting candidate when an intern completes their period.
     */
    public function promoteNextWaitingCandidate(Application $completedApp): ?Application
    {
        $nextWaiting = Application::where('internship_position_id', $completedApp->internship_position_id)
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'asc')
            ->first();

        if ($nextWaiting) {
            $startDate = Carbon::parse($nextWaiting->tanggal_mulai);
            $endDate = Carbon::parse($nextWaiting->tanggal_selesai);
            $durationDays = $startDate->diffInDays($endDate);

            $newStartDate = Carbon::parse($completedApp->tanggal_selesai)->addDay();
            if ($newStartDate->isPast()) {
                $newStartDate = Carbon::tomorrow();
            }
            $newEndDate = $newStartDate->copy()->addDays($durationDays);

            $nextWaiting->update([
                'tanggal_mulai' => $newStartDate->format('Y-m-d'),
                'tanggal_selesai' => $newEndDate->format('Y-m-d'),
            ]);

            $this->transition($nextWaiting, ApplicationStatus::Diterima, [
                'actor_id' => auth()->id(),
                'reason' => 'Promosi langsung dari daftar tunggu setelah peserta sebelumnya lulus.',
            ]);
        }

        return $nextWaiting;
    }

    // --- Private Transition Handlers ---

    private function handleTransitionToDiterima(
        Application $app,
        string $fromStatus,
        ?int $actorId,
        array $context
    ): Application {
        $position = InternshipPosition::where('id', $app->internship_position_id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($position->status === 'tutup') {
            throw new \Exception("Gagal menerima peserta: Posisi magang ini sudah ditutup.");
        }

        $start = $app->tanggal_mulai;
        $end = $app->tanggal_selesai;

        // Verify position capacity
        $conflictingCount = Application::where('internship_position_id', $position->id)
            ->where('id', '!=', $app->id)
            ->whereIn('status', ['diterima'])
            ->where(function ($q) use ($start, $end) {
                $q->where('tanggal_mulai', '<=', $end)
                  ->where('tanggal_selesai', '>=', $start);
            })
            ->count();

        $kuota = max(0, (int) ($position->kuota ?? 1));
        if ($conflictingCount >= $kuota) {
            throw new \Exception("Gagal menerima peserta: Kuota maksimal ({$kuota}) untuk rentang tanggal tersebut sudah penuh terisi.");
        }

        // Verify global instansi capacity
        $instansi = $position->instansi()->lockForUpdate()->first();
        if ($instansi && $instansi->max_total_quota > 0) {
            $instansiActiveCount = Application::whereHas('position', fn ($q) => $q->where('instansi_id', $instansi->id))
                ->where('id', '!=', $app->id)
                ->whereIn('status', ['diterima'])
                ->where(function ($q) use ($start, $end) {
                    $q->where('tanggal_mulai', '<=', $end)
                      ->where('tanggal_selesai', '>=', $start);
                })
                ->count();

            if ($instansiActiveCount >= $instansi->max_total_quota) {
                throw new \Exception("Gagal menerima peserta: Kuota global instansi ({$instansi->max_total_quota}) sudah penuh.");
            }
        }

        $app->update([
            'status' => 'diterima',
            'verified_by' => $actorId,
        ]);

        $app->recordTimeline(
            ApplicationTimeline::EVENT_ACCEPTED,
            $fromStatus,
            'diterima',
            ['verified_by' => $actorId],
            $actorId
        );

        $this->auditLogService->record('application.accepted', $app, [
            'applicant_user_id' => $app->user_id,
            'position_id' => $app->internship_position_id,
        ]);

        $this->dispatchAcceptedNotification($app);

        return $app;
    }

    private function handleTransitionToDitolak(
        Application $app,
        string $fromStatus,
        ?int $actorId,
        array $context
    ): Application {
        $reason = $context['reason'] ?? null;

        $app->update([
            'status' => 'ditolak',
            'rejected_reason' => $reason,
            'verified_by' => $actorId,
        ]);

        $app->recordTimeline(
            ApplicationTimeline::EVENT_REJECTED,
            $fromStatus,
            'ditolak',
            ['reason' => $reason, 'verified_by' => $actorId],
            $actorId
        );

        $this->auditLogService->record('application.rejected', $app, [
            'applicant_user_id' => $app->user_id,
            'position_id' => $app->internship_position_id,
            'rejected_reason' => $reason,
        ]);

        if (in_array($fromStatus, ['diterima', 'pending'], true)) {
            $this->autoReallocateQuota($app->internship_position_id);
        }

        $this->dispatchRejectedNotification($app, $reason);

        return $app;
    }

    private function handleTransitionToDibatalkan(
        Application $app,
        string $fromStatus,
        ?int $actorId,
        array $context
    ): Application {
        $isParticipant = $context['is_participant'] ?? false;
        $this->assertCanCancel($app, $isParticipant);

        $reason = $context['reason'] ?? 'Dibatalkan oleh pemohon';

        $app->update([
            'status' => 'dibatalkan',
            'rejected_reason' => $reason,
            'canceled_at' => now(),
        ]);

        $app->recordTimeline(
            ApplicationTimeline::EVENT_CANCELLED,
            $fromStatus,
            'dibatalkan',
            ['reason' => $reason, 'cancelled_by' => $actorId],
            $actorId
        );

        $this->auditLogService->record('application.cancelled', $app, [
            'previous_status' => $fromStatus,
            'new_status' => 'dibatalkan',
            'reason' => $reason,
        ]);

        // Auto-reject any pending attendance disputes to avoid orphaned state
        AttendanceDispute::whereHas('attendance', fn ($q) => $q->where('application_id', $app->id))
            ->where('status', AttendanceDispute::STATUS_PENDING)
            ->update([
                'status' => AttendanceDispute::STATUS_REJECTED,
                'reviewed_by' => $actorId,
                'reviewed_at' => now(),
                'reviewer_notes' => 'Otomatis ditolak karena lamaran magang telah dibatalkan.',
            ]);

        if ($fromStatus === 'diterima') {
            $this->autoReallocateQuota($app->internship_position_id);
        }

        $this->dispatchCancelledNotification($app);

        return $app;
    }

    private function handleTransitionToSelesai(
        Application $app,
        string $fromStatus,
        ?int $actorId,
        array $context
    ): Application {
        $this->generateCertificateNumberAction->execute($app);

        $app->update([
            'status' => 'selesai',
        ]);

        $app->recordTimeline(
            ApplicationTimeline::EVENT_COMPLETED,
            $fromStatus,
            'selesai',
            ['completed_by' => $actorId],
            $actorId
        );

        $this->auditLogService->record('application.finished', $app, [
            'applicant_user_id' => $app->user_id,
            'automatic' => $actorId === null,
        ]);

        Cache::forget('expired_internships_checked');

        $this->promoteNextWaitingCandidate($app);

        $this->dispatchCompletedNotification($app);

        return $app;
    }

    private function handleTransitionToDikeluarkan(
        Application $app,
        string $fromStatus,
        ?int $actorId,
        array $context
    ): Application {
        $reason = $context['reason'] ?? 'Dikeluarkan dari program magang';

        $app->update([
            'status' => 'dikeluarkan',
            'rejected_reason' => $reason,
        ]);

        $app->recordTimeline(
            ApplicationTimeline::EVENT_EXPELLED,
            $fromStatus,
            'dikeluarkan',
            ['reason' => $reason, 'actor_id' => $actorId],
            $actorId
        );

        $this->auditLogService->record('application.expelled', $app, [
            'applicant_user_id' => $app->user_id,
            'reason' => $reason,
        ]);

        // Auto-reject any pending attendance disputes to avoid orphaned state
        AttendanceDispute::whereHas('attendance', fn ($q) => $q->where('application_id', $app->id))
            ->where('status', AttendanceDispute::STATUS_PENDING)
            ->update([
                'status' => AttendanceDispute::STATUS_REJECTED,
                'reviewed_by' => $actorId,
                'reviewed_at' => now(),
                'reviewer_notes' => 'Otomatis ditolak karena peserta telah dikeluarkan dari program magang.',
            ]);

        if ($fromStatus === 'diterima') {
            $this->autoReallocateQuota($app->internship_position_id);
        }

        return $app;
    }

    private function handleTransitionToPending(
        Application $app,
        string $fromStatus,
        ?int $actorId,
        array $context
    ): Application {
        $app->update([
            'status' => 'pending',
        ]);

        $event = $fromStatus === 'menunggu' ? ApplicationTimeline::EVENT_PROMOTED : ApplicationTimeline::EVENT_SUBMITTED;

        $app->recordTimeline(
            $event,
            $fromStatus,
            'pending',
            $context,
            $actorId
        );

        $this->auditLogService->record('application.promoted_from_waiting_list', $app, [
            'position_id' => $app->internship_position_id,
        ]);

        $this->dispatchPromotedNotification($app);

        return $app;
    }

    private function handleTransitionToMenunggu(
        Application $app,
        string $fromStatus,
        ?int $actorId,
        array $context
    ): Application {
        $app->update([
            'status' => 'menunggu',
        ]);

        $app->recordTimeline(
            ApplicationTimeline::EVENT_WAITING_LIST,
            $fromStatus,
            'menunggu',
            $context,
            $actorId
        );

        return $app;
    }

    private function normalizeStatus(Application|ApplicationStatus|string $status): string
    {
        if ($status instanceof Application) {
            $val = $status->status;
            return $val instanceof ApplicationStatus ? $val->value : (string) $val;
        }

        if ($status instanceof ApplicationStatus) {
            return $status->value;
        }

        return (string) $status;
    }

    private function dispatchAcceptedNotification(Application $application): void
    {
        try {
            if ($application->user && $application->user->email) {
                Mail::to($application->user->email)->queue(new ApplicationAcceptedMail($application));
            }
        } catch (\Throwable $e) {
            Log::error('Gagal mendispatch email penerimaan ke antrean.', [
                'application_id' => $application->id,
                'user_id' => $application->user_id,
                'exception' => $e,
            ]);
        }

        try {
            if ($application->user) {
                $instansiName = $application->position?->instansi?->nama_dinas ?? 'Instansi';
                $application->user->notify(new ApplicationStatusNotification(
                    $application,
                    'Lamaran Diterima! 🎉',
                    "Selamat! Pengajuan magang Anda di {$instansiName} telah DITERIMA. Silakan cek dashboard untuk mengunduh LoA & ID Card.",
                    'success'
                ));
            }
        } catch (\Throwable $e) {
            Log::error('Gagal mendispatch notifikasi penerimaan ke antrean.', [
                'application_id' => $application->id,
                'user_id' => $application->user_id,
                'exception' => $e,
            ]);
        }
    }

    private function dispatchRejectedNotification(Application $application, ?string $alasan): void
    {
        try {
            if ($application->user && $application->user->email) {
                Mail::to($application->user->email)->queue(new ApplicationRejectedMail($application));
            }
        } catch (\Throwable $e) {
            Log::error('Gagal mendispatch email penolakan ke antrean.', [
                'application_id' => $application->id,
                'user_id' => $application->user_id,
                'exception' => $e,
            ]);
        }

        try {
            if ($application->user) {
                $instansiName = $application->position?->instansi?->nama_dinas ?? 'Instansi';
                $reasonText = $alasan ? " Alasan: {$alasan}" : '';
                $application->user->notify(new ApplicationStatusNotification(
                    $application,
                    'Pemberitahuan Status Lamaran',
                    "Mohon maaf, lamaran magang Anda di {$instansiName} belum dapat diterima.{$reasonText}",
                    'danger'
                ));
            }
        } catch (\Throwable $e) {
            Log::error('Gagal mendispatch notifikasi penolakan ke antrean.', [
                'application_id' => $application->id,
                'user_id' => $application->user_id,
                'exception' => $e,
            ]);
        }
    }

    private function dispatchCancelledNotification(Application $application): void
    {
        try {
            if ($application->user) {
                $instansiName = $application->position?->instansi?->nama_dinas ?? 'Instansi';
                $application->user->notify(new ApplicationStatusNotification(
                    $application,
                    'Lamaran Dibatalkan',
                    "Lamaran magang Anda di {$instansiName} telah berhasil dibatalkan.",
                    'warning'
                ));
            }
        } catch (\Throwable $e) {
            Log::error('Gagal mendispatch notifikasi pembatalan ke antrean.', [
                'application_id' => $application->id,
                'user_id' => $application->user_id,
                'exception' => $e,
            ]);
        }
    }

    private function dispatchPromotedNotification(Application $candidate): void
    {
        try {
            if ($candidate->user) {
                $instansiName = $candidate->position?->instansi?->nama_dinas ?? 'Instansi';
                $candidate->user->notify(new ApplicationStatusNotification(
                    $candidate,
                    'Kabar Baik: Kuota Tersedia! 🔔',
                    "Ada slot yang terbuka di {$instansiName}. Lamaran Anda telah dipromosikan dari Daftar Tunggu ke tahap Peninjauan.",
                    'info'
                ));
            }
        } catch (\Throwable $e) {
            Log::error('Gagal mendispatch notifikasi promosi antrean ke antrean.', [
                'application_id' => $candidate->id,
                'user_id' => $candidate->user_id,
                'exception' => $e,
            ]);
        }
    }

    private function dispatchCompletedNotification(Application $application): void
    {
        try {
            if ($application->user && $application->user->email) {
                Mail::to($application->user->email)->queue(new InternshipCompleted($application));
            }
        } catch (\Throwable $e) {
            Log::error('Gagal mendispatch email kelulusan magang ke antrean.', [
                'application_id' => $application->id,
                'user_id' => $application->user_id,
                'exception' => $e,
            ]);
        }

        try {
            if ($application->user) {
                $instansiName = $application->position?->instansi?->nama_dinas ?? 'Instansi';
                $application->user->notify(new ApplicationStatusNotification(
                    $application,
                    'Magang Telah Selesai! 🎓',
                    "Selamat! Masa magang Anda di {$instansiName} telah selesai dan Anda dinyatakan lulus. Sertifikat kini dapat diunduh di dashboard.",
                    'success',
                    route('peserta.sertifikat')
                ));
            }
        } catch (\Throwable $e) {
            Log::error('Gagal mendispatch notifikasi kelulusan magang ke antrean.', [
                'application_id' => $application->id,
                'user_id' => $application->user_id,
                'exception' => $e,
            ]);
        }
    }
}
