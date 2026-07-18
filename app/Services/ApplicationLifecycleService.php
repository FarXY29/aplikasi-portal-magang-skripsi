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

    public function __construct(GenerateCertificateNumberAction $generateCertificateNumberAction)
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
                'status' => 'diterima',
                'tanggal_mulai' => $newStartDate->format('Y-m-d'),
                'tanggal_selesai' => $newEndDate->format('Y-m-d'),
            ]);
        }

        return $nextWaiting;
    }
}
