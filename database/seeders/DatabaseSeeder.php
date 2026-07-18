<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\Application;
use App\Models\DailyLog;
use Faker\Factory as Faker;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $this->call([
            UniversityAndSchoolSeeder::class,
            RoleAndPermissionSeeder::class,
        ]);

        $univList = \App\Models\University::all();
        $schoolList = \App\Models\School::all();

        // 1. Buat Akun Super Admin (Admin Kota)
        User::updateOrCreate(
            ['email' => 'admin@banjarmasin.go.id'],
            [
                'name' => 'Super Admin Kota',
                'username' => 'superadmin',
                'password' => Hash::make('password'),
                'role' => 'admin_kota',
            ]
        );

        // List Nama Dinas Realistis
        $dinasList = [
            'Dinas Komunikasi, Informatika dan Statistik',
            'Dinas Pendidikan',
            'Dinas Kesehatan',
            'Dinas Pekerjaan Umum dan Penataan Ruang',
            'Dinas Perumahan Rakyat dan Kawasan Permukiman',
            'Dinas Sosial',
            'Dinas Kependudukan dan Pencatatan Sipil',
            'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu',
            'Dinas Koperasi, Usaha Mikro dan Tenaga Kerja',
            'Dinas Perdagangan dan Perindustrian',
            'Dinas Perhubungan',
            'Dinas Kebudayaan, Kepemudaan, Olahraga dan Pariwisata',
            'Badan Perencanaan Pembangunan Daerah, Penelitian dan Pengembangan',
            'Badan Keuangan Daerah',
            'Badan Kepegawaian Daerah, Pendidikan dan Pelatihan',
        ];

        $instansis = [];
        $pembimbings = [];
        $positions = [];

        // 2. Buat Instansi, Admin INSTANSI, Posisi Magang, dan Pembimbing
        foreach ($dinasList as $index => $namaDinas) {
            // Buat INSTANSI
            $instansi = Instansi::updateOrCreate(
                ['kode_unit_kerja' => 'INSTANSI-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT)],
                [
                    'nama_dinas' => $namaDinas,
                    'alamat' => $faker->address,
                    'nama_pejabat' => $faker->name,
                    'nip_pejabat' => $faker->numerify('19########## 2 0##'),
                    'latitude' => $faker->latitude(-3.35, -3.25),
                    'longitude' => $faker->longitude(114.55, 114.65),
                ]
            );
            $instansis[] = $instansi;

            // Buat Admin INSTANSI
            User::updateOrCreate(
                ['email' => 'admin.instansi' . ($index + 1) . '@banjarmasin.go.id'],
                [
                    'name' => 'Admin ' . explode(',', $namaDinas)[0],
                    'username' => 'admin_instansi_' . ($index + 1),
                    'password' => Hash::make('password'),
                    'role' => 'admin_instansi',
                    'instansi_id' => $instansi->id,
                ]
            );

            // Buat 2-3 Pembimbing Lapangan per INSTANSI
            $numPembimbing = rand(2, 3);
            for ($i = 0; $i < $numPembimbing; $i++) {
                $pembimbingLapangan = User::updateOrCreate(
                    ['email' => 'pembimbing.lapangan.' . $instansi->id . '_' . $i . '@banjarmasin.go.id'],
                    [
                        'name' => 'Pembimbing ' . $faker->name,
                        'username' => 'pembimbing_lapangan_' . $instansi->id . '_' . $i,
                        'password' => Hash::make('password'),
                        'role' => 'pembimbing_lapangan',
                        'instansi_id' => $instansi->id,
                    ]
                );
                $pembimbings[$instansi->id][] = $pembimbingLapangan;
            }

            // Buat 2-4 Posisi Magang per INSTANSI
            $posisiList = ['Programmer / Web Developer', 'Staf Administrasi', 'Desain Grafis', 'Analis Data', 'Public Relations', 'Customer Service'];
            $numPositions = rand(2, 4);
            $selectedPosisi = $faker->randomElements($posisiList, $numPositions);

            foreach ($selectedPosisi as $posisiStr) {
                $jurusanList = [
                    'S1 Komputer / Informatika',
                    'S1 Administrasi / Manajemen',
                    'Semua Jurusan',
                    'Teknik Komputer Jaringan (SMK)',
                    'Otomatisasi dan Tata Kelola Perkantoran (SMK)',
                    'Desain Komunikasi Visual (SMK / S1)',
                    'SMA/SMK Sederajat'
                ];
                $reqMajor = $faker->randomElement($jurusanList);

                $position = InternshipPosition::updateOrCreate(
                    [
                        'instansi_id' => $instansi->id,
                        'judul_posisi' => $posisiStr,
                    ],
                    [
                        'required_major' => $reqMajor,
                        'deskripsi' => $faker->paragraph,
                        'kuota' => rand(2, 5),
                        'status' => $faker->boolean(80) ? 'buka' : 'tutup',
                    ]
                );
                $positions[] = $position;
            }
        }

        // Buat Pembimbing Akademik / Sekolah (10 orang)
        $pembimbingSekolahList = [];
        for ($k = 1; $k <= 10; $k++) {
            $isUniv = $faker->boolean();
            $univ = $isUniv && $univList->isNotEmpty() ? $univList->random() : null;
            $school = !$isUniv && $schoolList->isNotEmpty() ? $schoolList->random() : null;

            $pembimbingSekolahList[] = User::updateOrCreate(
                ['email' => 'pembimbing' . $k . '@kampus.ac.id'],
                [
                    'name' => 'Dosen/Guru ' . $faker->name,
                    'username' => 'pembimbing_' . $k,
                    'password' => Hash::make('password'),
                    'role' => 'pembimbing',
                    'university_id' => $univ?->id,
                    'school_id' => $school?->id,
                    'asal_instansi' => $univ?->name ?? $school?->name ?? 'Kampus Merdeka',
                ]
            );
        }

        // 3. Buat Akun Peserta & Lamaran
        $institusiList = ['Universitas Lambung Mangkurat', 'Politeknik Negeri Banjarmasin', 'Universitas Islam Kalimantan', 'SMKN 1 Banjarmasin', 'SMKN 2 Banjarmasin'];
        
        // Buat 60 Peserta
        for ($i = 1; $i <= 60; $i++) {
            $pembimbingSekolah = $faker->randomElement($pembimbingSekolahList);
            $univId = $pembimbingSekolah->university_id;
            $schoolId = $pembimbingSekolah->school_id;
            $asalName = $pembimbingSekolah->asal_instansi ?? $faker->randomElement($institusiList);
            
            $peserta = User::updateOrCreate(
                ['email' => 'peserta' . $i . '@gmail.com'],
                [
                    'name' => $faker->name,
                    'username' => 'peserta_' . $i,
                    'password' => Hash::make('password'),
                    'role' => 'peserta',
                    'university_id' => $univId,
                    'school_id' => $schoolId,
                    'asal_instansi' => $asalName,
                    'pembimbing_sekolah_id' => $pembimbingSekolah->id,
                    'nama_pembimbing_sekolah' => $pembimbingSekolah->name,
                    'phone' => $faker->phoneNumber,
                    'nik' => $faker->numerify('6371##########'),
                ]
            );

            // Peserta melamar 1-2 posisi
            $numLamar = rand(1, 2);
            $lamarPosisi = $faker->randomElements($positions, $numLamar);
            
            $isAccepted = false;

            foreach ($lamarPosisi as $idx => $pos) {
                // Tentukan status lamaran secara acak
                $statusList = ['pending', 'ditolak', 'diterima', 'selesai', 'menunggu'];
                
                // Jika sudah ada yg diterima/selesai, lamaran lain otomatis ditolak atau pending
                if ($isAccepted) {
                    $status = $faker->randomElement(['pending', 'ditolak']);
                } else {
                    $status = $faker->randomElement($statusList);
                    if (in_array($status, ['diterima', 'selesai'])) {
                        $isAccepted = true;
                    }
                }

                $tanggalMulai = $faker->dateTimeBetween('-3 months', 'now');
                $tanggalSelesai = (clone $tanggalMulai)->modify('+3 months');

                // Ambil pembimbing dari INSTANSI tempat melamar jika diterima/selesai
                $pembimbing_lapanganId = null;
                if (in_array($status, ['diterima', 'selesai'])) {
                    $instansiPembimbings = $pembimbings[$pos->instansi_id];
                    $pembimbing_lapanganId = $instansiPembimbings[array_rand($instansiPembimbings)]->id;
                }

                $app = Application::updateOrCreate(
                    [
                        'user_id' => $peserta->id,
                        'internship_position_id' => $pos->id,
                    ],
                    [
                        'status' => $status,
                        'tanggal_mulai' => $tanggalMulai,
                        'tanggal_selesai' => $tanggalSelesai,
                        'pembimbing_lapangan_id' => $pembimbing_lapanganId,
                        'cv_path' => 'dummy/cv.pdf',
                        'surat_pengantar_path' => 'dummy/surat.pdf',
                    ]
                );

                // Generate DailyLogs jika aktif/selesai
                if (in_array($status, ['diterima', 'selesai'])) {
                    $numLogs = rand(5, 15);
                    for ($j = 0; $j < $numLogs; $j++) {
                        DailyLog::updateOrCreate(
                            [
                                'application_id' => $app->id,
                                'tanggal' => (clone $tanggalMulai)->modify('+' . $j . ' days'),
                            ],
                            [
                                'kegiatan' => $faker->sentence(10),
                                'status_validasi' => $faker->randomElement(['pending', 'disetujui', 'revisi']),
                                'komentar_pembimbing_lapangan' => $faker->boolean(30) ? $faker->sentence() : null,
                            ]
                        );
                    }
                }

                // Generate Nilai Akhir jika selesai
                if ($status === 'selesai') {
                    $teknis = rand(70, 95);
                    $disiplin = rand(75, 95);
                    $perilaku = rand(80, 98);
                    
                    $app->update([
                        'nilai_teknis' => $teknis,
                        'nilai_disiplin' => $disiplin,
                        'nilai_perilaku' => $perilaku,
                        'nilai_angka' => ($teknis + $disiplin + $perilaku) / 3,
                        'nomor_sertifikat' => 'MG-' . date('Y') . '-' . str_pad($app->id, 5, '0', STR_PAD_LEFT),
                        'token_verifikasi' => Str::random(10),
                    ]);
                }
            }
        }

        // 4. Sinkronisasikan Role Spatie untuk Seluruh User
        User::all()->each(function ($user) {
            if ($user->role && \Spatie\Permission\Models\Role::where('name', $user->role)->exists()) {
                $user->assignRole($user->role);
            }
        });
    }
}