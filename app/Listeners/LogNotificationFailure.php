<?php

namespace App\Listeners;

use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Support\Facades\Log;

class LogNotificationFailure
{
    /**
     * Handle the event.
     */
    public function handle(NotificationFailed $event): void
    {
        $applicationId = null;
        if (isset($event->notification->application) && isset($event->notification->application->id)) {
            $applicationId = $event->notification->application->id;
        }
        $notifiableType = is_object($event->notifiable) ? get_class($event->notifiable) : null;
        $notifiableId = isset($event->notifiable->id) ? $event->notifiable->id : (method_exists($event->notifiable, 'getKey') ? $event->notifiable->getKey() : null);

        Log::error('Notifikasi gagal dikirim ke channel.', [
            'channel' => $event->channel,
            'notification' => is_object($event->notification) ? get_class($event->notification) : (string) $event->notification,
            'application_id' => $applicationId,
            'notifiable_type' => $notifiableType,
            'notifiable_id' => $notifiableId,
        ]);
    }
}
