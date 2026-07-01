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
     * - Beberapa peserta dibuat "bermasalah" (sering telat/alpa) agar laporan disiplin terlihat bervariasi.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Hapus data absensi lama agar bisa dijalankan ulang tanpa duplikat
        Attendance::truncate();

        // Cache instansi jam kerja
        $instansiJam = Instansi::pluck('jam_mulai_masuk', 'id')->toArray();
        $instansiJamPulang = Instansi::pluck('jam_mulai_pulang', 'id')->toArray();

        // Ambil semua aplikasi aktif (diterima / selesai) beserta posisinya
        $applications = Application::whereIn('status', ['diterima', 'selesai'])
            ->with('position')
            ->get();

        if ($applications->isEmpty()) {
            $this->command->warn('⚠ Tidak ada aplikasi berstatus diterima/selesai. Jalankan DatabaseSeeder terlebih dahulu.');
            return;
        }

        $totalAttendances = 0;
        $bar = $this->command->getOutput()->createProgressBar($applications->count());
        $bar->setFormat(" %current%/%max% [%bar%] %percent:3s%% — %message%");
        $bar->setMessage('Memulai generate absensi...');
        $bar->start();

        // Tentukan ~20% peserta sebagai "bermasalah" (sering telat/alpa)
        $troublemakers = $applications->random(max(1, (int)($applications->count() * 0.2)))->pluck('id')->toArray();

        foreach ($applications as $app) {
            $instansiId = $app->position->instansi_id ?? null;
            $jamMasuk = $instansiJam[$instansiId] ?? '07:30:00';
            $jamPulang = $instansiJamPulang[$instansiId] ?? '16:00:00';

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
                $bar->advance();
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
                $status = 'hadir';
                $description = null;
                $proofFile = null;
                $validationStatus = 'approved';

                $jamMasukCarbon = Carbon::createFromFormat('H:i:s', $jamMasuk);
                $jamPulangCarbon = Carbon::createFromFormat('H:i:s', $jamPulang);

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
                    'date' => $workday->format('Y-m-d'),
                    'status' => $status,
                    'validation_status' => $validationStatus,
                    'clock_in' => $clockIn,
                    'clock_out' => $clockOut,
                    'description' => $description,
                    'proof_file' => $proofFile,
                    'pembimbing_lapangan_note' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Insert batch per aplikasi untuk performa
            if (!empty($attendanceBatch)) {
                // Insert dalam chunk 100 baris agar tidak terlalu berat
                foreach (array_chunk($attendanceBatch, 100) as $chunk) {
                    Attendance::insert($chunk);
                }
                $totalAttendances += count($attendanceBatch);
            }

            $bar->setMessage($app->user->name ?? "App #{$app->id}");
            $bar->advance();
        }

        $bar->setMessage('Selesai!');
        $bar->finish();
        $this->command->newLine(2);
        $this->command->info("✅ Berhasil membuat {$totalAttendances} data absensi untuk {$applications->count()} peserta aktif.");
    }
}
