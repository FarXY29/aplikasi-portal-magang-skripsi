<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\University;
use Illuminate\Database\Seeder;

class UniversityAndSchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $universities = [
            [
                'code_pddikti' => '001018',
                'name' => 'Universitas Lambung Mangkurat',
                'acronym' => 'ULM',
                'city' => 'Banjarmasin',
                'is_active' => true,
            ],
            [
                'code_pddikti' => '201004',
                'name' => 'UIN Antasari Banjarmasin',
                'acronym' => 'UIN Antasari',
                'city' => 'Banjarmasin',
                'is_active' => true,
            ],
            [
                'code_pddikti' => '005009',
                'name' => 'Politeknik Negeri Banjarmasin',
                'acronym' => 'POLIBAN',
                'city' => 'Banjarmasin',
                'is_active' => true,
            ],
            [
                'code_pddikti' => '111014',
                'name' => 'Universitas Islam Kalimantan Muhammad Arsyad Al Banjari',
                'acronym' => 'UNISKA MAB',
                'city' => 'Banjarmasin',
                'is_active' => true,
            ],
            [
                'code_pddikti' => '111025',
                'name' => 'Universitas Muhammadiyah Banjarmasin',
                'acronym' => 'UMB',
                'city' => 'Banjarmasin',
                'is_active' => true,
            ],
        ];

        foreach ($universities as $univ) {
            University::updateOrCreate(['code_pddikti' => $univ['code_pddikti']], $univ);
        }

        $schools = [
            [
                'npsn' => '30304269',
                'name' => 'SMK Negeri 1 Banjarmasin',
                'level' => 'SMK',
                'address' => 'Jl. Mulawarman No.45, Banjarmasin',
                'is_active' => true,
            ],
            [
                'npsn' => '30304268',
                'name' => 'SMK Negeri 2 Banjarmasin',
                'level' => 'SMK',
                'address' => 'Jl. Brigjen H. Hasan Basri No.6, Banjarmasin',
                'is_active' => true,
            ],
            [
                'npsn' => '30304267',
                'name' => 'SMK Negeri 3 Banjarmasin',
                'level' => 'SMK',
                'address' => 'Jl. Pramuka Pemurus Luar, Banjarmasin',
                'is_active' => true,
            ],
            [
                'npsn' => '30304266',
                'name' => 'SMK Negeri 4 Banjarmasin',
                'level' => 'SMK',
                'address' => 'Jl. Brigjen H. Hasan Basri, Banjarmasin',
                'is_active' => true,
            ],
            [
                'npsn' => '30304265',
                'name' => 'SMK Negeri 5 Banjarmasin',
                'level' => 'SMK',
                'address' => 'Jl. Mayjen Sutoyo S No.330, Banjarmasin',
                'is_active' => true,
            ],
        ];

        foreach ($schools as $school) {
            School::updateOrCreate(['npsn' => $school['npsn']], $school);
        }
    }
}
