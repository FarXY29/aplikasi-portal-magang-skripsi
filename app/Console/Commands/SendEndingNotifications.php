<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Application;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\InternshipEndingMail;

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
    protected $description = 'Send email notifications to interns whose internship ends in 7 days.';

    /**
     * Execute the console command.
     */
    public function handle()
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
                Mail::to($app->user->email)->send(new InternshipEndingMail($app));
                $count++;
            } catch (\Exception $e) {
                \Log::error('Gagal mengirim email peringatan magang (ID: ' . $app->id . '): ' . $e->getMessage());
            }
        }

        $this->info("Berhasil mengirim $count email peringatan magang berakhir.");
    }
}
