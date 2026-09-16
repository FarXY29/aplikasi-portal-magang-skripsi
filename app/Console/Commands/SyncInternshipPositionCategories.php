<?php

namespace App\Console\Commands;

use App\Models\InternshipPosition;
use App\Models\MajorCategory;
use Illuminate\Console\Command;

class SyncInternshipPositionCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'positions:sync-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi kategori rumpun ilmu dan kualifikasi jurusan pada lowongan magang agar koheren dan realistis';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Memulai sinkronisasi kategori lowongan magang...');

        $catTIK = MajorCategory::where('code', 'TIK')->first();
        $catEKBIS = MajorCategory::where('code', 'EKBIS')->first();
        $catHUKUM = MajorCategory::where('code', 'HUKUM_AP')->first();
        $catHUM = MajorCategory::where('code', 'HUMANIORA')->first();

        $positions = InternshipPosition::all();
        $updatedCount = 0;

        foreach ($positions as $pos) {
            $title = strtolower($pos->judul_posisi);
            $changed = false;

            if (str_contains($title, 'programmer') || str_contains($title, 'web') || str_contains($title, 'developer') || str_contains($title, 'software')) {
                $pos->required_major_category_id = $catTIK?->id;
                $pos->required_major = 'S1 Teknik Informatika / Sistem Informasi';
                $changed = true;
            } elseif (str_contains($title, 'desain') || str_contains($title, 'grafis') || str_contains($title, 'dkv') || str_contains($title, 'multimedia')) {
                $pos->required_major_category_id = $catTIK?->id;
                $pos->required_major = 'Desain Komunikasi Visual (SMK / S1)';
                $changed = true;
            } elseif (str_contains($title, 'analis') || str_contains($title, 'data')) {
                $pos->required_major_category_id = $catTIK?->id;
                $pos->required_major = 'S1 Sistem Informasi / Informatika';
                $changed = true;
            } elseif (str_contains($title, 'administrasi') || str_contains($title, 'admin') || str_contains($title, 'arsip') || str_contains($title, 'tata usaha')) {
                $pos->required_major_category_id = $catEKBIS?->id;
                $pos->required_major = 'S1 Administrasi / Manajemen';
                $changed = true;
            } elseif (str_contains($title, 'public relation') || str_contains($title, 'humas') || str_contains($title, 'komunikasi')) {
                $pos->required_major_category_id = $catHUM?->id;
                $pos->required_major = 'Ilmu Komunikasi (S1)';
                $changed = true;
            } elseif (str_contains($title, 'legal') || str_contains($title, 'hukum') || str_contains($title, 'perundang')) {
                $pos->required_major_category_id = $catHUKUM?->id;
                $pos->required_major = 'S1 Ilmu Hukum';
                $changed = true;
            } elseif (str_contains($title, 'customer') || str_contains($title, 'pelayanan') || str_contains($title, 'front')) {
                $pos->required_major_category_id = null;
                $pos->required_major = 'Semua Jurusan';
                $changed = true;
            }

            if ($changed) {
                $pos->save();
                $updatedCount++;
            }
        }

        $this->info("Sinkronisasi selesai! {$updatedCount} lowongan berhasil disinkronkan.");

        return Command::SUCCESS;
    }
}

