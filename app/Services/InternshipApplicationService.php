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

class InternshipApplicationService
{
    public function __construct(private AuditLogService $auditLogService)
    {
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
        DB::transaction(function () use ($application) {
            $position = InternshipPosition::where('id', $application->internship_position_id)
                ->lockForUpdate()
                ->firstOrFail();

            $start = $application->tanggal_mulai;
            $end = $application->tanggal_selesai;

            // Cek kuota posisi untuk rentang tanggal
            $conflictingCount = Application::where('internship_position_id', $position->id)
                ->where('id', '!=', $application->id)
                ->whereIn('status', ['diterima'])
                ->where(function ($q) use ($start, $end) {
                    $q->where('tanggal_mulai', '<=', $end)
                      ->where('tanggal_selesai', '>=', $start);
                })
                ->count();

            if ($conflictingCount >= $position->kuota) {
                throw new \Exception("Gagal menerima peserta: Kuota maksimal ({$position->kuota}) untuk rentang tanggal tersebut sudah penuh terisi.");
            }

            // Cek kuota global instansi
            $instansi = $position->instansi()->lockForUpdate()->first();
            if ($instansi && $instansi->max_total_quota > 0) {
                $instansiActiveCount = Application::whereHas('position', fn($q) => $q->where('instansi_id', $instansi->id))
                    ->where('id', '!=', $application->id)
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

            $application->update([
                'status' => 'diterima',
                'verified_by' => auth()->id() ?? null,
            ]);
        });

        try {
            if ($application->user && $application->user->email) {
                Mail::to($application->user->email)->send(new ApplicationAcceptedMail($application));
            }
        } catch (\Exception $e) {
            // Log mail failure gracefully without aborting database update
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
        } catch (\Exception $e) {
            // Log notification error gracefully
        }

        $this->auditLogService->record('application.accepted', $application, [
            'applicant_user_id' => $application->user_id,
            'position_id' => $application->internship_position_id,
        ]);

        return true;
    }

    /**
     * Reject applicant and send notification email if configured.
     */
    public function rejectApplicant(Application $application, ?string $alasan = null): bool
    {
        $oldStatus = $application->status;

        $application->update([
            'status' => 'ditolak',
            'rejected_reason' => $alasan,
            'verified_by' => auth()->id() ?? null,
        ]);

        if (in_array($oldStatus, ['diterima', 'pending'])) {
            $this->autoReallocateQuota($application->internship_position_id);
        }

        try {
            if ($application->user && $application->user->email) {
                Mail::to($application->user->email)->send(new ApplicationRejectedMail($application));
            }
        } catch (\Exception $e) {
            // Log mail failure gracefully without aborting database update
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
        } catch (\Exception $e) {
            // Log notification error gracefully
        }

        $this->auditLogService->record('application.rejected', $application, [
            'applicant_user_id' => $application->user_id,
            'position_id' => $application->internship_position_id,
            'rejected_reason' => $alasan,
        ]);

        return true;
    }

    /**
     * Cancel / Resign application and auto-reallocate quota from waiting list.
     */
    public function cancelApplicant(Application $application, ?string $alasan = null, string $status = 'dibatalkan'): bool
    {
        $oldStatus = $application->status;

        $application->update([
            'status' => $status,
            'rejected_reason' => $alasan,
            'canceled_at' => now(),
        ]);

        if ($oldStatus === 'diterima') {
            $this->autoReallocateQuota($application->internship_position_id);
        }

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
        } catch (\Exception $e) {
            // Log notification error gracefully
        }

        $this->auditLogService->record('application.cancelled', $application, [
            'previous_status' => $oldStatus instanceof \BackedEnum ? $oldStatus->value : $oldStatus,
            'new_status' => $status,
            'reason' => $alasan,
        ]);

        return true;
    }

    /**
     * Auto-Reallocate Quota: Memeriksa antrean 'menunggu' (Daftar Tunggu) dan mempromosikannya ke 'pending' jika slot terbuka.
     */
    public function autoReallocateQuota(int $positionId): int
    {
        return DB::transaction(function () use ($positionId) {
            $position = InternshipPosition::where('id', $positionId)->lockForUpdate()->first();
            if (!$position) return 0;

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

                if ($conflictingCount >= $position->kuota) {
                    continue;
                }

                if ($instansi && $instansi->max_total_quota > 0) {
                    $instansiActiveCount = Application::whereHas('position', fn($q) => $q->where('instansi_id', $instansi->id))
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

                $candidate->update(['status' => 'pending']);

                try {
                    if ($candidate->user) {
                        $instansiName = $position->instansi?->nama_dinas ?? 'Instansi';
                        $candidate->user->notify(new ApplicationStatusNotification(
                            $candidate,
                            'Kabar Baik: Kuota Tersedia! 🔔',
                            "Ada slot yang terbuka di {$instansiName}. Lamaran Anda telah dipromosikan dari Daftar Tunggu ke tahap Peninjauan.",
                            'info'
                        ));
                    }
                } catch (\Exception $e) {
                    // Ignore notification errors
                }

                $this->auditLogService->record('application.promoted_from_waiting_list', $candidate, [
                    'position_id' => $position->id,
                ]);
                $promotedCount++;
            }

            return $promotedCount;
        });
    }
}
