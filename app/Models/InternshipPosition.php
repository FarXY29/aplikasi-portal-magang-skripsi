<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'instansi_id',
        'judul_posisi',
        'required_major', // Syarat Jurusan
        'required_major_category_id', // Relasi Rumpun Keilmuan
        'deskripsi',
        'kuota',
        'batas_daftar',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($position) {
            $position->applications()->get()->each->delete();
        });
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function requiredMajorCategory()
    {
        return $this->belongsTo(MajorCategory::class, 'required_major_category_id');
    }

    // --- TAMBAHAN PENTING (RELASI KE PELAMAR) ---
    public function applications()
    {
        // Satu lowongan bisa memiliki BANYAK pelamar (applications)
        return $this->hasMany(Application::class);
    }

    /**
     * Memeriksa apakah kualifikasi lowongan ini cocok dengan jurusan / rumpun ilmu peserta.
     */
    public function matchesUser(?User $user): bool
    {
        if (!$user) {
            return true;
        }

        // Syarat lowongan
        $reqMajorText = trim((string) $this->required_major);
        $reqMajorLower = strtolower($reqMajorText);
        $reqCategoryId = $this->required_major_category_id;

        // 1. Jika lowongan terbuka untuk semua jurusan / umum
        if (
            empty($reqCategoryId) &&
            (empty($reqMajorText) || $reqMajorText === '-' || str_contains($reqMajorLower, 'semua'))
        ) {
            return true;
        }

        // 2. Ambil data jurusan & rumpun dari User
        $userMajorDetail = $user->majorDetail;
        $userMajorName = trim((string) ($userMajorDetail?->name ?? ''));
        $userMajorRaw = trim((string) ($user->major ?? ''));
        $userMajorCombined = trim($userMajorName . ' ' . $userMajorRaw);
        $userMajorLower = strtolower($userMajorCombined);
        
        $userCategoryId = $userMajorDetail?->major_category_id;
        $userCategoryName = strtolower(trim((string) ($userMajorDetail?->category?->name ?? '')));
        $userCategoryCode = strtolower(trim((string) ($userMajorDetail?->category?->code ?? '')));

        // 3. Jika lowongan memiliki required_major_category_id spesifik
        if ($reqCategoryId) {
            // Jika user memiliki major_id dengan category_id yang sama
            if ($userCategoryId && (int) $userCategoryId === (int) $reqCategoryId) {
                return true;
            }
            
            // Cek jika kode/nama kategori lowongan cocok dengan teks kategori user
            $posCat = $this->requiredMajorCategory;
            $posCatName = strtolower((string) ($posCat?->name ?? ''));
            $posCatCode = strtolower((string) ($posCat?->code ?? ''));

            if (!empty($posCatCode) && !empty($userCategoryCode) && $posCatCode === $userCategoryCode) {
                return true;
            }
            if (!empty($posCatName) && !empty($userMajorLower)) {
                if (str_contains($userMajorLower, $posCatName) || str_contains($posCatName, $userMajorLower)) {
                    return true;
                }
            }
        }

        // Jika teks lowongan adalah "Semua Jurusan"
        if (empty($reqMajorText) || $reqMajorText === '-' || str_contains($reqMajorLower, 'semua')) {
            return true;
        }

        if (empty($userMajorLower)) {
            return false;
        }

        // 4. Direct Substring Check (dua arah)
        if (str_contains($reqMajorLower, $userMajorLower) || str_contains($userMajorLower, $reqMajorLower)) {
            return true;
        }
        if (!empty($userMajorName) && (str_contains($reqMajorLower, strtolower($userMajorName)) || str_contains(strtolower($userMajorName), $reqMajorLower))) {
            return true;
        }
        if (!empty($userMajorRaw) && (str_contains($reqMajorLower, strtolower($userMajorRaw)) || str_contains(strtolower($userMajorRaw), $reqMajorLower))) {
            return true;
        }

        // 5. Normalisasi & Token Keyword Matching
        $stopWords = ['dan', 'atau', 'jurusan', 'program', 'studi', 'prodi', 'fakultas', 'bidang', 'keahlian', 'semua', 'khusus', 'sederajat', 'min', 'minimal', 'jenjang'];
        
        $cleanReq = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $reqMajorLower);
        $cleanUser = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $userMajorLower);
        $cleanUserCat = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $userCategoryName);

        $reqTokens = array_values(array_filter(explode(' ', $cleanReq), fn($t) => strlen($t) >= 3 && !in_array($t, $stopWords)));
        $userTokens = array_values(array_filter(explode(' ', $cleanUser), fn($t) => strlen($t) >= 3 && !in_array($t, $stopWords)));
        $userCatTokens = array_values(array_filter(explode(' ', $cleanUserCat), fn($t) => strlen($t) >= 3 && !in_array($t, $stopWords)));

        $allUserTokens = array_unique(array_merge($userTokens, $userCatTokens));

        // Cek irisan kata langsung (misal: "informatika", "komputer", "akuntansi", "manajemen", "hukum", "desain")
        $intersect = array_intersect($reqTokens, $allUserTokens);
        if (!empty($intersect)) {
            return true;
        }

        // 6. Synonym & Keilmuan Cluster Map
        $clusters = [
            'ti' => ['informatika', 'komputer', 'it', 'rpl', 'tkj', 'perangkat', 'lunak', 'software', 'programming', 'programmer', 'sistem', 'informasi', 'ilmu', 'jaringan', 'cyber', 'multimedia', 'teknologi', 'database'],
            'ekbis' => ['akuntansi', 'keuangan', 'akt', 'finance', 'perbankan', 'pajak', 'perpajakan', 'manajemen', 'bisnis', 'ekonomi', 'pemasaran', 'marketing', 'administrasi'],
            'adm' => ['administrasi', 'adm', 'perkantoran', 'sekretaris', 'tata', 'kelola', 'kearsipan', 'arsip', 'pemerintahan', 'publik', 'kebijakan'],
            'hukum' => ['hukum', 'syariah', 'perdata', 'pidana', 'tatanegara', 'notariat', 'advokat', 'legal'],
            'desain' => ['desain', 'dkv', 'grafis', 'multimedia', 'animasi', 'visual', 'komunikasi', 'seni', 'kreatif'],
            'kesehatan' => ['kesehatan', 'medis', 'keperawatan', 'perawat', 'kebidanan', 'bidan', 'farmasi', 'apoteker', 'epidemiologi', 'kesmas', 'gizi'],
            'teknik' => ['sipil', 'arsitektur', 'konstruksi', 'bangunan', 'elektro', 'mesin', 'industri', 'lingkungan', 'planologi', 'tata kota'],
            'pendidikan' => ['pendidikan', 'keguruan', 'guru', 'pgsd', 'bimbingan', 'konseling', 'kurikulum', 'pengajaran'],
            'sosial' => ['sosial', 'sosiologi', 'komunikasi', 'humas', 'hubungan', 'masyarakat', 'jurnalistik', 'politik'],
        ];

        foreach ($clusters as $cluster) {
            $reqHasCluster = !empty(array_intersect($reqTokens, $cluster));
            $userHasCluster = !empty(array_intersect($allUserTokens, $cluster));
            if ($reqHasCluster && $userHasCluster) {
                return true;
            }
        }

        return false;
    }
}