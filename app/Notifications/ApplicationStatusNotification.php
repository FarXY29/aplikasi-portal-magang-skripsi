<?php

namespace App\Notifications;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ApplicationStatusNotification extends Notification implements ShouldQueue, ShouldQueueAfterCommit
{
    use Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;
    public array $backoff = [10, 60, 300];

    public function __construct(
        public Application $application,
        public string $title,
        public string $message,
        public string $type = 'info', // 'success', 'warning', 'danger', 'info'
        public ?string $actionUrl = null
    ) {
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $status = $this->application->status instanceof ApplicationStatus
            ? $this->application->status->value
            : (string) ($this->application->status_value ?? $this->application->status ?? '');

        return [
            'application_id' => $this->application->id,
            'nomor_registrasi' => $this->application->nomor_registrasi,
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'action_url' => $this->actionUrl ?? route('peserta.dashboard'),
            'status' => $status,
            'position_title' => $this->application->position?->judul_posisi ?? '-',
            'instansi_name' => $this->application->position?->instansi?->nama_dinas ?? '-',
            'time' => now()->toIso8601String(),
        ];
    }

    /**
     * Handle a notification job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Notifikasi status lamaran gagal diproses melalui antrean.', [
            'application_id' => $this->application->id ?? null,
            'user_id' => $this->application->user_id ?? null,
            'title' => $this->title,
            'type' => $this->type,
            'exception' => $exception->getMessage(),
        ]);
    }
}
