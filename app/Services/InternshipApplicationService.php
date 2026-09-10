<?php

namespace App\Services;

use App\Models\Application;
use App\Models\InternshipPosition;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationAcceptedMail;
use App\Mail\ApplicationRejectedMail;
use App\Notifications\ApplicationStatusNotification;
use App\Services\AuditLogService;

use App\Services\ApplicationStateTransitionService;

class InternshipApplicationService
{
    private ApplicationStateTransitionService $transitionService;

    public function __construct(
        private AuditLogService $auditLogService,
        ?ApplicationStateTransitionService $transitionService = null
    ) {
        $this->transitionService = $transitionService ?? app(ApplicationStateTransitionService::class);
    }

    /**
     * Check how many accepted/active interns overlap with the requested date range.
     */
    public function checkPositionAvailability(int $positionId, string $startDate, string $endDate): array
    {
        $position = InternshipPosition::with('instansi')->findOrFail($positionId);

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        $acceptedApps = Application::where('internship_position_id', $position->id)
            ->whereIn('status', ['diterima', 'selesai'])
            ->where(function($q) use ($start, $end) {
                $q->where(function($query) use ($start, $end) {
                    $query->where('tanggal_mulai', '<=', $end)
                          ->where('tanggal_selesai', '>=', $start);
                });
            })
            ->get();

        $kuota = $position->kuota ?? 1;
        $terisi = $acceptedApps->count();
        $sisa = max(0, $kuota - $terisi);

        return [
            'position' => $position,
            'kuota' => $kuota,
            'terisi' => $terisi,
            'sisa' => $sisa,
            'is_available' => $sisa > 0,
            'accepted_apps' => $acceptedApps,
        ];
    }

    /**
     * Accept applicant with pessimistic row locking and send notification email if configured.
     * Throws exception if position quota for the date range is already full.
     */
    public function acceptApplicant(Application $application): bool
    {
        $this->transitionService->accept($application, auth()->id());

        return true;
    }

    /**
     * Reject applicant and send notification email if configured.
     */
    public function rejectApplicant(Application $application, ?string $alasan = null): bool
    {
        $this->transitionService->reject($application, $alasan, auth()->id());

        return true;
    }

    /**
     * Cancel / Resign application and auto-reallocate quota from waiting list.
     */
    public function cancelApplicant(Application $application, ?string $alasan = null, string $status = 'dibatalkan'): bool
    {
        $this->transitionService->cancel($application, $alasan, auth()->id(), false);

        return true;
    }

    /**
     * Auto-Reallocate Quota: Memeriksa antrean 'menunggu' (Daftar Tunggu) dan mempromosikannya ke 'pending' jika slot terbuka.
     */
    public function autoReallocateQuota(int $positionId): int
    {
        return $this->transitionService->autoReallocateQuota($positionId);
    }
}
