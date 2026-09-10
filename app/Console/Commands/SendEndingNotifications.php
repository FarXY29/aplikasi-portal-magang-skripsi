<?php

namespace App\Console\Commands;

use App\Mail\InternshipEndingMail;
use App\Models\Application;
use App\Notifications\ApplicationStatusNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEndingNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-ending-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email and in-app notifications to interns whose internship ends in 7 days.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Cari aplikasi magang yang statusnya diterima dan tanggal selesai tepat 7 hari dari sekarang
        $targetDate = Carbon::now()->addDays(7)->startOfDay();

        $applications = Application::with('user', 'position.instansi')
            ->where('status', 'diterima')
            ->whereDate('tanggal_selesai', $targetDate)
            ->get();

        $count = 0;
        foreach ($applications as $app) {
            try {
                if ($app->user && $app->user->email) {
                    Mail::to($app->user->email)->queue(new InternshipEndingMail($app));
                    $count++;
                }
            } catch (\Throwable $e) {
                Log::error('Gagal mendispatch email peringatan magang ke antrean.', [
                    'application_id' => $app->id,
                    'user_id' => $app->user_id,
                    'exception' => $e,
                ]);
            }

            try {
                if ($app->user) {
                    $instansiName = $app->position?->instansi?->nama_dinas ?? 'Instansi';
                    $app->user->notify(new ApplicationStatusNotification(
                        $app,
                        'Peringatan Masa Magang Berakhir',
                        "Masa magang Anda di {$instansiName} akan berakhir dalam 7 hari. Pastikan seluruh logbook harian dan presensi telah dilengkapi.",
                        'warning',
                        route('peserta.dashboard')
                    ));
                }
            } catch (\Throwable $e) {
                Log::error('Gagal mendispatch notifikasi in-app peringatan magang ke antrean.', [
                    'application_id' => $app->id,
                    'user_id' => $app->user_id,
                    'exception' => $e,
                ]);
            }
        }

        $this->info("Berhasil mendispatch $count email & notifikasi peringatan magang berakhir.");

        return Command::SUCCESS;
    }
}
