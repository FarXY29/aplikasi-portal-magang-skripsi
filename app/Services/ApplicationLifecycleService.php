<?php

namespace App\Services;

use App\Actions\GenerateCertificateNumberAction;
use App\Models\Application;

class ApplicationLifecycleService
{
    protected $generateCertificateNumberAction;
    private ApplicationStateTransitionService $transitionService;

    public function __construct(
        GenerateCertificateNumberAction $generateCertificateNumberAction,
        private AuditLogService $auditLogService,
        ?ApplicationStateTransitionService $transitionService = null,
    ) {
        $this->generateCertificateNumberAction = $generateCertificateNumberAction;
        $this->transitionService = $transitionService ?? app(ApplicationStateTransitionService::class);
    }

    /**
     * Menyelesaikan masa magang peserta, menerbitkan nomor sertifikat, dan mempromosikan daftar tunggu.
     */
    public function markAsFinished(Application $application): Application
    {
        return $this->transitionService->finish($application, auth()->id());
    }

    /**
     * Mempromosikan kandidat dalam daftar tunggu (waiting list) apabila ada peserta aktif yang selesai.
     */
    public function promoteNextWaitingCandidate(Application $completedApp): ?Application
    {
        return $this->transitionService->promoteNextWaitingCandidate($completedApp);
    }
}
