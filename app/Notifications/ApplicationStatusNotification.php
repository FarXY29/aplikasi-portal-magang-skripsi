<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ApplicationStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Application $application,
        public string $title,
        public string $message,
        public string $type = 'info', // 'success', 'warning', 'danger', 'info'
        public ?string $actionUrl = null
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'nomor_registrasi' => $this->application->nomor_registrasi,
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'action_url' => $this->actionUrl ?? route('peserta.dashboard'),
            'status' => $this->application->status_value,
            'position_title' => $this->application->position?->judul_posisi ?? '-',
            'instansi_name' => $this->application->position?->instansi?->nama_dinas ?? '-',
            'time' => now()->toIso8601String(),
        ];
    }
}
