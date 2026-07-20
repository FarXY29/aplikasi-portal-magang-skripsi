<?php

namespace App\Services;

use App\Models\Application;
use App\Actions\GenerateCertificateNumberAction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ApplicationLifecycleService
{
    protected $generateCertificateNumberAction;

    public function __construct(
        GenerateCertificateNumberAction $generateCertificateNumberAction,
        private AuditLogService $auditLogService,
    )
    {
        $this->generateCertificateNumberAction = $generateCertificateNumberAction;
    }

    /**
     * Menyelesaikan masa magang peserta, menerbitkan nomor sertifikat, dan mempromosikan daftar tunggu.
     */
    public function markAsFinished(Application $application): Application
    {
        return DB::transaction(function () use ($application) {
            $this->generateCertificateNumberAction->execute($application);
            $application->status = 'selesai';
            $application->save();
            $this->auditLogService->record('application.finished', $application, [
                'applicant_user_id' => $application->user_id,
                'automatic' => auth()->id() === null,
            ]);

            Cache::forget('expired_internships_checked');

            $this->promoteNextWaitingCandidate($application);

            return $application;
        });
    }

    /**
     * Mempromosikan kandidat dalam daftar tunggu (waiting list) apabila ada peserta aktif yang selesai.
     */
    public function promoteNextWaitingCandidate(Application $completedApp): ?Application
    {
        $nextWaiting = Application::where('internship_position_id', $completedApp->internship_position_id)
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'asc')
            ->lockForUpdate()
            ->first();

        if ($nextWaiting) {
            $nextWaiting->update([
                'status' => 'pending',
            ]);
            $this->auditLogService->record('application.promoted_from_waiting_list', $nextWaiting, [
                'position_id' => $nextWaiting->internship_position_id,
                'status' => 'pending'
            ]);
        }

        return $nextWaiting;
    }
}
