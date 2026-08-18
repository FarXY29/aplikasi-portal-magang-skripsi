<?php

namespace App\Jobs;

use App\Mail\AnnouncementBroadcastMail;
use App\Models\Announcement;
use App\Models\BroadcastLog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendAnnouncementBroadcastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    public function __construct(
        public Announcement $announcement,
        public ?BroadcastLog $broadcastLog = null
    ) {
    }

    public function handle(): void
    {
        $log = $this->broadcastLog ?? BroadcastLog::create([
            'announcement_id' => $this->announcement->id,
            'recipient_role' => $this->announcement->target_audience,
            'status' => 'processing',
        ]);

        $log->update(['status' => 'processing']);

        try {
            $query = User::whereNotNull('email')->where('email', '!=', '');

            if ($this->announcement->target_audience !== 'all') {
                if ($this->announcement->target_audience === 'pembimbing') {
                    $query->where(function ($q) {
                        $q->where('role', 'pembimbing')
                          ->orWhere('role', 'pembimbing_lapangan')
                          ->orWhereHas('roles', function ($sq) {
                              $sq->whereIn('name', ['pembimbing', 'pembimbing_lapangan']);
                          });
                    });
                } else {
                    $role = $this->announcement->target_audience;
                    $query->where(function ($q) use ($role) {
                        $q->where('role', $role)
                          ->orWhereHas('roles', function ($sq) use ($role) {
                              $sq->where('name', $role);
                          });
                    });
                }
            }

            $count = 0;
            $query->chunk(50, function ($recipients) use (&$count) {
                foreach ($recipients as $recipient) {
                    try {
                        Mail::to($recipient->email)->send(new AnnouncementBroadcastMail($this->announcement, $recipient));
                        $count++;
                    } catch (Throwable $e) {
                        Log::warning("Gagal mengirim email pengumuman ke {$recipient->email}: " . $e->getMessage());
                    }
                }
            });

            $log->update([
                'status' => 'completed',
                'total_recipients' => $count,
                'sent_at' => now(),
            ]);

            $this->announcement->update([
                'send_email_broadcast' => true,
            ]);
        } catch (Throwable $e) {
            Log::error("Error processing announcement broadcast job: " . $e->getMessage());
            $log->update(['status' => 'failed']);
            throw $e;
        }
    }
}
