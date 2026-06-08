<?php

namespace Database\Seeders;

use App\Models\Application;
use Illuminate\Database\Seeder;

class PenilaianDummySeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua aplikasi yang statusnya 'diterima' atau 'selesai'
        $applications = Application::whereIn('status', ['diterima', 'selesai'])->get();

        foreach ($applications as $app) {
            $app->update([
                'nilai_teknis' => rand(70, 95),   // Nilai acak antara 70-95
                'nilai_disiplin' => rand(75, 98),
                'nilai_perilaku' => rand(80, 95),
                'catatan_pembimbing_lapangan' => 'Peserta menunjukkan progres yang sangat baik selama masa magang.',
            ]);
        }
    }
}