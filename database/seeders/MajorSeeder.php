<?php

namespace Database\Seeders;

use App\Models\Major;
use App\Models\MajorCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Teknologi Informasi & Rekayasa Komputer',
                'code' => 'TIK',
                'description' => 'Rumpun keilmuan komputer, rekayasa perangkat lunak, sistem informasi, jaringan, dan kecerdasan artifisial.',
                'majors' => [
                    ['name' => 'Teknik Informatika', 'degree_level' => 'S1'],
                    ['name' => 'Teknik Informatika', 'degree_level' => 'D3'],
                    ['name' => 'Teknik Informatika', 'degree_level' => 'D4'],
                    ['name' => 'Sistem Informasi', 'degree_level' => 'S1'],
                    ['name' => 'Ilmu Komputer', 'degree_level' => 'S1'],
                    ['name' => 'Teknologi Informasi', 'degree_level' => 'S1'],
                    ['name' => 'Rekayasa Perangkat Lunak', 'degree_level' => 'SMK'],
                    ['name' => 'Teknik Komputer & Jaringan', 'degree_level' => 'SMK'],
                    ['name' => 'Multimedia & DKV', 'degree_level' => 'SMK'],
                    ['name' => 'Desain Komunikasi Visual', 'degree_level' => 'S1'],
                ],
            ],
            [
                'name' => 'Ekonomi, Bisnis & Manajemen',
                'code' => 'EKBIS',
                'description' => 'Rumpun ilmu akuntansi, perpajakan, keuangan perbankan, dan administrasi bisnis perkantoran.',
                'majors' => [
                    ['name' => 'Akuntansi', 'degree_level' => 'S1'],
                    ['name' => 'Akuntansi', 'degree_level' => 'D3'],
                    ['name' => 'Manajemen', 'degree_level' => 'S1'],
                    ['name' => 'Ekonomi Pembangunan', 'degree_level' => 'S1'],
                    ['name' => 'Bisnis Digital', 'degree_level' => 'S1'],
                    ['name' => 'Perbankan & Keuangan', 'degree_level' => 'D3'],
                    ['name' => 'Otomatisasi & Tata Kelola Perkantoran', 'degree_level' => 'SMK'],
                    ['name' => 'Akuntansi & Keuangan Lembaga', 'degree_level' => 'SMK'],
                ],
            ],
            [
                'name' => 'Hukum, Administrasi Publik & Kebijakan',
                'code' => 'HUKUM_AP',
                'description' => 'Rumpun ilmu hukum tata negara, perdata, administrasi publik, kepemerintahan, dan pelayanan publik.',
                'majors' => [
                    ['name' => 'Ilmu Hukum', 'degree_level' => 'S1'],
                    ['name' => 'Ilmu Administrasi Publik', 'degree_level' => 'S1'],
                    ['name' => 'Ilmu Administrasi Negara', 'degree_level' => 'S1'],
                    ['name' => 'Ilmu Pemerintahan', 'degree_level' => 'S1'],
                    ['name' => 'Hubungan Internasional', 'degree_level' => 'S1'],
                ],
            ],
            [
                'name' => 'Teknik, Sipil & Perencanaan Wilayah',
                'code' => 'TEKNIK',
                'description' => 'Rumpun ilmu konstruksi, tata ruang kota, drainase perkotaan, arsitektur, elektro, dan mesin.',
                'majors' => [
                    ['name' => 'Teknik Sipil', 'degree_level' => 'S1'],
                    ['name' => 'Teknik Sipil', 'degree_level' => 'D3'],
                    ['name' => 'Perencanaan Wilayah & Kota (PWK)', 'degree_level' => 'S1'],
                    ['name' => 'Arsitektur', 'degree_level' => 'S1'],
                    ['name' => 'Teknik Lingkungan', 'degree_level' => 'S1'],
                    ['name' => 'Teknik Mesin', 'degree_level' => 'S1'],
                    ['name' => 'Teknik Elektro', 'degree_level' => 'S1'],
                    ['name' => 'Teknik Permodelan & Informasi Bangunan', 'degree_level' => 'SMK'],
                ],
            ],
            [
                'name' => 'Kesehatan Masyarakat & Medis',
                'code' => 'KESEHATAN',
                'description' => 'Rumpun ilmu epidemiologi, sanitasi lingkungan, keperawatan, farmasi, dan nutrisi.',
                'majors' => [
                    ['name' => 'Kesehatan Masyarakat', 'degree_level' => 'S1'],
                    ['name' => 'Ilmu Keperawatan', 'degree_level' => 'S1'],
                    ['name' => 'Ilmu Keperawatan', 'degree_level' => 'D3'],
                    ['name' => 'Farmasi', 'degree_level' => 'S1'],
                    ['name' => 'Farmasi', 'degree_level' => 'D3'],
                    ['name' => 'Ilmu Gizi', 'degree_level' => 'S1'],
                    ['name' => 'Kebidanan', 'degree_level' => 'D3'],
                ],
            ],
            [
                'name' => 'Sosial, Komunikasi & Humaniora',
                'code' => 'HUMANIORA',
                'description' => 'Rumpun ilmu kehumasan, jurnalistik, hubungan masyarakat, psikologi, dan sosiologi.',
                'majors' => [
                    ['name' => 'Ilmu Komunikasi', 'degree_level' => 'S1'],
                    ['name' => 'Hubungan Masyarakat (PR)', 'degree_level' => 'D3'],
                    ['name' => 'Psikologi', 'degree_level' => 'S1'],
                    ['name' => 'Sosiologi', 'degree_level' => 'S1'],
                    ['name' => 'Sastra / Bahasa Inggris', 'degree_level' => 'S1'],
                    ['name' => 'Pendidikan Guru SD', 'degree_level' => 'S1'],
                ],
            ],
        ];

        foreach ($categories as $catData) {
            $majors = $catData['majors'];
            unset($catData['majors']);

            $category = MajorCategory::updateOrCreate(
                ['code' => $catData['code']],
                $catData
            );

            foreach ($majors as $m) {
                Major::updateOrCreate(
                    [
                        'major_category_id' => $category->id,
                        'name' => $m['name'],
                        'degree_level' => $m['degree_level'],
                    ],
                    ['is_active' => true]
                );
            }
        }

        // Backfill users with major matching
        $allMajors = Major::all();
        User::whereNotNull('major')->whereNull('major_id')->each(function ($user) use ($allMajors) {
            $userMajorText = strtolower(trim((string) $user->major));
            $matched = $allMajors->first(function ($m) use ($userMajorText) {
                return strtolower($m->name) === $userMajorText || str_contains($userMajorText, strtolower($m->name));
            });

            if ($matched) {
                $user->update(['major_id' => $matched->id]);
            }
        });
    }
}
