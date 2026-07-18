<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MassDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Memulai generasi Mass Dummy Data untuk UAT...');

        $now = Carbon::now();
        $password = Hash::make('password');

        // Pastikan ada Instansi dan Lowongan dummy
        $instansiId = DB::table('instansis')->insertGetId([
            'nama_dinas' => 'Dinas Komunikasi dan Informatika Test',
            'kode_unit_kerja' => 'TEST01',
            'alamat' => 'Jl. Test No. 99',
            'contact_whatsapp' => '081234567890',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $positionId = DB::table('internship_positions')->insertGetId([
            'instansi_id' => $instansiId,
            'judul_posisi' => 'Software Engineer Intern (Test)',
            'deskripsi' => 'Posisi magang Test untuk pengujian load',
            'kuota' => 1000,
            'status' => 'buka',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $this->command->info("Instansi dan Posisi dibuat.");

        // Buat 500 Peserta
        $users = [];
        for ($i = 1; $i <= 500; $i++) {
            $users[] = [
                'name' => "Peserta Test $i",
                'email' => "peserta.test$i@example.com",
                'role' => 'peserta',
                'password' => $password,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($users, 100) as $chunk) {
            DB::table('users')->insert($chunk);
        }

        $userIds = DB::table('users')->where('email', 'like', 'peserta.test%@example.com')->pluck('id')->toArray();
        $this->command->info("500 User Peserta dibuat.");

        // Buat 500 Applications (Diterima)
        $applications = [];
        foreach ($userIds as $uid) {
            $applications[] = [
                'user_id' => $uid,
                'internship_position_id' => $positionId,
                'status' => 'diterima',
                'tanggal_mulai' => $now->copy()->subDays(30),
                'tanggal_selesai' => $now->copy()->addDays(30),
                'created_at' => $now,
                'updated_at' => $now,
                'surat_pengantar_path' => '-',
                'cv_path' => '-',
            ];
        }
        foreach (array_chunk($applications, 100) as $chunk) {
            DB::table('applications')->insert($chunk);
        }
        
        $appIds = DB::table('applications')->where('internship_position_id', $positionId)->pluck('id')->toArray();
        $this->command->info("500 Application (Diterima) dibuat.");

        // Buat 10000 Attendances (20 hari x 500 user)
        $attendances = [];
        for ($day = 1; $day <= 20; $day++) {
            $date = $now->copy()->subDays(30 - $day)->format('Y-m-d');
            foreach ($appIds as $appId) {
                $attendances[] = [
                    'application_id' => $appId,
                    'date' => $date,
                    'clock_in' => '08:00:00',
                    'clock_out' => '17:00:00',
                    'status' => 'hadir',
                    'validation_status' => 'approved',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        foreach (array_chunk($attendances, 1000) as $chunk) {
            DB::table('attendances')->insert($chunk);
        }
        $this->command->info("10000 Data Absensi dibuat.");

        // Buat 10000 Daily Logs
        $logbooks = [];
        for ($day = 1; $day <= 20; $day++) {
            $date = $now->copy()->subDays(30 - $day)->format('Y-m-d');
            foreach ($appIds as $appId) {
                $logbooks[] = [
                    'application_id' => $appId,
                    'tanggal' => $date,
                    'kegiatan' => "Mengerjakan tugas UAT hari ke-$day",
                    'status_validasi' => 'disetujui',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        foreach (array_chunk($logbooks, 1000) as $chunk) {
            DB::table('daily_logs')->insert($chunk);
        }
        $this->command->info("10000 Data Daily Log dibuat.");

        $this->command->info("Selesai! Mass Dummy Data berhasil digenerate.");
    }
}
