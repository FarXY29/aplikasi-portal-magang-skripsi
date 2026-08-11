<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;
use App\Models\Attendance;
use App\Models\Instansi;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Faker\Factory as Faker;

class AttendanceSeeder extends Seeder
{
    /**
     * Seed data dummy absensi peserta magang.
     *
     * Logika:
     * - Hanya untuk aplikasi berstatus 'diterima' atau 'selesai'.
     * - Tanggal absensi = hari kerja (Senin-Jumat) antara tanggal_mulai s.d. hari ini atau tanggal_selesai.
     * - Mayoritas hadir (~70%), sebagian terlambat (~15%), sakit/izin (~10%), alpa (~5%).
     * - clock_in & clock_out realistis berdasarkan jam_mulai_masuk & jam_mulai_pulang instansi.
     * - Geotagging (latitude_in, longitude_in, latitude_out, longitude_out) disesuaikan dengan posisi instansi.
     * - Beberapa peserta dibuat "bermasalah" (sering telat/alpa) agar laporan disiplin terlihat bervariasi.
     * - Idempoten: tidak menduplikat tanggal yang sudah pernah di-insert per aplikasi.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Cache instansi lengkap
        $instansiMap = Instansi::all()->keyBy('id');

        // Ambil semua aplikasi aktif (diterima / selesai) beserta posisinya
        $applications = Application::whereIn('status', ['diterima', 'selesai'])
            ->with(['position', 'user'])
            ->get();

        if ($applications->isEmpty()) {
            if (isset($this->command)) {
                $this->command->warn('⚠ Tidak ada aplikasi berstatus diterima/selesai. Jalankan DatabaseSeeder terlebih dahulu.');
            }
            return;
        }

        $totalAttendances = 0;
        $output = isset($this->command) ? $this->command->getOutput() : null;
        $bar = $output ? $output->createProgressBar($applications->count()) : null;

        if ($bar) {
            $bar->setFormat(" %current%/%max% [%bar%] %percent:3s%% — %message%");
            $bar->setMessage('Memulai generate absensi...');
            $bar->start();
        }

        // Tentukan ~20% peserta sebagai "bermasalah" (sering telat/alpa)
        $troublemakers = $applications->random(max(1, (int)($applications->count() * 0.2)))->pluck('id')->toArray();

        foreach ($applications as $app) {
            $instansiId = $app->position->instansi_id ?? null;
            $instansi = $instansiMap->get($instansiId);

            $jamMasuk = $instansi->jam_mulai_masuk ?? '07:30:00';
            $jamPulang = $instansi->jam_mulai_pulang ?? '16:00:00';

            $baseLat = (float)($instansi->latitude ?? -3.3194);
            $baseLng = (float)($instansi->longitude ?? 114.5908);

            // Ambil tanggal absensi yang sudah tersimpan agar aman dari constraint duplikasi
            $existingDates = Attendance::where('application_id', $app->id)
                ->pluck('date')
                ->map(fn($d) => is_string($d) ? $d : $d->format('Y-m-d'))
                ->toArray();
            $existingDatesSet = array_flip($existingDates);

            // Tentukan rentang tanggal absensi
            $startDate = Carbon::parse($app->tanggal_mulai);
            $endDate = Carbon::parse($app->tanggal_selesai);
            $today = Carbon::today();

            // Jangan melebihi hari ini
            if ($endDate->gt($today)) {
                $endDate = $today;
            }

            // Jika tanggal mulai di masa depan, skip
            if ($startDate->gt($today)) {
                if ($bar) {
                    $bar->advance();
                }
                continue;
            }

            $isTroublemaker = in_array($app->id, $troublemakers);

            // Generate hari kerja (Senin-Jumat) dalam rentang
            $period = CarbonPeriod::create($startDate, $endDate);
            $workdays = [];
            foreach ($period as $date) {
                if ($date->isWeekday()) {
                    $workdays[] = $date->copy();
                }
            }

            // Batasi jumlah absensi agar realistis (max ~90 hari kerja ≈ 4.5 bulan)
            if (count($workdays) > 90) {
                $workdays = array_slice($workdays, 0, 90);
            }

            $attendanceBatch = [];

            foreach ($workdays as $workday) {
                $dateStr = $workday->format('Y-m-d');
                if (isset($existingDatesSet[$dateStr])) {
                    continue; // Skip jika sudah ada data absensi pada tanggal tersebut
                }

                $roll = $faker->numberBetween(1, 100);

                if ($isTroublemaker) {
                    // Peserta bermasalah: 40% hadir tepat, 30% telat, 10% sakit, 5% izin, 15% alpa
                    if ($roll <= 40) {
                        $scenario = 'hadir_tepat';
                    } elseif ($roll <= 70) {
                        $scenario = 'hadir_telat';
                    } elseif ($roll <= 80) {
                        $scenario = 'sakit';
                    } elseif ($roll <= 85) {
                        $scenario = 'izin';
                    } else {
                        $scenario = 'alpa';
                    }
                } else {
                    // Peserta normal: 70% hadir tepat, 12% telat, 8% sakit, 5% izin, 5% alpa
                    if ($roll <= 70) {
                        $scenario = 'hadir_tepat';
                    } elseif ($roll <= 82) {
                        $scenario = 'hadir_telat';
                    } elseif ($roll <= 90) {
                        $scenario = 'sakit';
                    } elseif ($roll <= 95) {
                        $scenario = 'izin';
                    } else {
                        $scenario = 'alpa';
                    }
                }

                $clockIn = null;
                $clockOut = null;
                $latIn = null;
                $lngIn = null;
                $latOut = null;
                $lngOut = null;
                $ipAddress = null;
                $deviceInfo = null;
                $status = 'hadir';
                $description = null;
                $proofFile = null;
                $validationStatus = 'approved';

                $jamMasukCarbon = Carbon::createFromFormat('H:i:s', strlen($jamMasuk) == 5 ? $jamMasuk.':00' : $jamMasuk);
                $jamPulangCarbon = Carbon::createFromFormat('H:i:s', strlen($jamPulang) == 5 ? $jamPulang.':00' : $jamPulang);

                if (in_array($scenario, ['hadir_tepat', 'hadir_telat'])) {
                    // Variasi koordinat di sekitar lokasi instansi (radius ~50-100 meter)
                    $latIn = round($baseLat + ($faker->numberBetween(-50, 50) / 1000000), 8);
                    $lngIn = round($baseLng + ($faker->numberBetween(-50, 50) / 1000000), 8);
                    $latOut = round($baseLat + ($faker->numberBetween(-50, 50) / 1000000), 8);
                    $lngOut = round($baseLng + ($faker->numberBetween(-50, 50) / 1000000), 8);

                    $ipAddress = '180.252.' . $faker->numberBetween(1, 254) . '.' . $faker->numberBetween(1, 254);
                    $deviceInfo = $faker->randomElement([
                        'Mozilla/5.0 (Linux; Android 14; SM-S918B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Mobile Safari/537.36',
                        'Mozilla/5.0 (iPhone; CPU iPhone OS 17_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Mobile/15E148 Safari/604.1',
                        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
                    ]);
                }

                switch ($scenario) {
                    case 'hadir_tepat':
                        // Datang 0-25 menit sebelum jam masuk
                        $minutesBefore = $faker->numberBetween(0, 25);
                        $clockInTime = $jamMasukCarbon->copy()->subMinutes($minutesBefore);
                        $clockIn = $clockInTime->format('H:i:s');

                        // Pulang tepat atau 0-30 menit setelah jam pulang
                        $minutesAfter = $faker->numberBetween(0, 30);
                        $clockOutTime = $jamPulangCarbon->copy()->addMinutes($minutesAfter);
                        $clockOut = $clockOutTime->format('H:i:s');

                        $status = 'hadir';
                        $description = $faker->randomElement([
                            null, null, null,
                            'Mengerjakan tugas kantor.',
                            'Piket administrasi.',
                            'Input data di sistem.',
                        ]);
                        break;

                    case 'hadir_telat':
                        // Datang 5-90 menit setelah jam masuk
                        $minutesLate = $faker->numberBetween(5, 90);
                        $clockInTime = $jamMasukCarbon->copy()->addMinutes($minutesLate);
                        $clockIn = $clockInTime->format('H:i:s');

                        // Pulang normal
                        $minutesAfter = $faker->numberBetween(0, 15);
                        $clockOutTime = $jamPulangCarbon->copy()->addMinutes($minutesAfter);
                        $clockOut = $clockOutTime->format('H:i:s');

                        $status = 'hadir';
                        $description = $faker->randomElement([
                            'Terlambat karena macet.',
                            'Terlambat, ban kempes di jalan.',
                            'Terlambat karena hujan deras.',
                            'Kendaraan mogok.',
                            'Terlambat, ada urusan mendadak.',
                            null,
                        ]);
                        break;

                    case 'sakit':
                        $status = 'sakit';
                        $description = $faker->randomElement([
                            'Sakit demam.',
                            'Sakit flu dan batuk.',
                            'Sakit perut, ke dokter.',
                            'Tidak enak badan, istirahat di rumah.',
                            'Kontrol ke rumah sakit.',
                        ]);
                        $proofFile = $faker->boolean(60) ? 'dummy/surat_dokter.pdf' : null;
                        $validationStatus = $faker->randomElement(['approved', 'approved', 'pending']);
                        break;

                    case 'izin':
                        $status = 'izin';
                        $description = $faker->randomElement([
                            'Izin keperluan keluarga.',
                            'Izin ada acara kampus.',
                            'Izin mengurus surat di kelurahan.',
                            'Izin ke bank mengurus administrasi.',
                            'Izin ada wisuda saudara.',
                        ]);
                        $proofFile = $faker->boolean(40) ? 'dummy/surat_izin.pdf' : null;
                        $validationStatus = $faker->randomElement(['approved', 'approved', 'pending']);
                        break;

                    case 'alpa':
                        $status = 'alpa';
                        $description = null;
                        $validationStatus = 'approved';
                        break;
                }

                $attendanceBatch[] = [
                    'application_id' => $app->id,
                    'date' => $dateStr,
                    'status' => $status,
                    'validation_status' => $validationStatus,
                    'clock_in' => $clockIn,
                    'clock_out' => $clockOut,
                    'latitude_in' => $latIn,
                    'longitude_in' => $lngIn,
                    'latitude_out' => $latOut,
                    'longitude_out' => $lngOut,
                    'ip_address' => $ipAddress,
                    'device_info' => $deviceInfo,
                    'description' => $description,
                    'proof_file' => $proofFile,
                    'pembimbing_lapangan_note' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Insert batch per aplikasi
            if (!empty($attendanceBatch)) {
                foreach (array_chunk($attendanceBatch, 100) as $chunk) {
                    Attendance::insert($chunk);
                }
                $totalAttendances += count($attendanceBatch);
            }

            if ($bar) {
                $bar->setMessage($app->user->name ?? "App #{$app->id}");
                $bar->advance();
            }
        }

        if ($bar) {
            $bar->setMessage('Selesai!');
            $bar->finish();
        }

        if (isset($this->command)) {
            $this->command->newLine(2);
            $this->command->info("✅ Berhasil membuat {$totalAttendances} data absensi untuk {$applications->count()} peserta aktif/selesai.");
        }
    }
}

