<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Application;
use App\Models\Attendance;
use Carbon\Carbon;

$applications = Application::whereIn('status', ['diterima', 'selesai'])->get();
$count = 0;

foreach ($applications as $app) {
    if (Attendance::where('application_id', $app->id)->exists()) continue;

    $startDate = Carbon::parse($app->tanggal_mulai);
    $endDate = $app->status?->value == 'selesai' ? Carbon::parse($app->tanggal_selesai) : Carbon::now();
    
    // limit to 20 days max so it doesn't take too long
    $days = min($startDate->diffInDays($endDate), 20);
    
    for ($i = 0; $i < $days; $i++) {
        $date = $startDate->copy()->addDays($i);
        if ($date->isWeekend()) continue;
        
        $rand = rand(1, 100);
        $status = 'hadir';
        $clockIn = '07:30:00';
        
        if ($rand > 90) {
            $status = 'alpa';
            $clockIn = null;
        } elseif ($rand > 85) {
            $status = 'izin';
            $clockIn = null;
        } elseif ($rand > 75) {
            $clockIn = '08:15:00'; // terlambat
        }

        Attendance::create([
            'application_id' => $app->id,
            'date' => $date->format('Y-m-d'),
            'clock_in' => $clockIn,
            'clock_out' => $clockIn ? '16:00:00' : null,
            'status' => $status,
            'status_validasi' => 'disetujui'
        ]);
        $count++;
    }
}
echo "Generated $count attendance records.\n";
