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

        // 1. Jika lowongan terbuka untuk semua jurusan / umum (tanpa kategori spesifik)
        $isGeneralMajor = (empty($reqMajorText) || $reqMajorText === '-' || str_contains($reqMajorLower, 'semua jurusan') || str_contains($reqMajorLower, 'semua'));
        if (empty($reqCategoryId) && $isGeneralMajor) {
            return true;
        }

        // 2. Ambil data jurusan, jenjang & rumpun dari User
        $userMajorDetail = $user->majorDetail;
        $userMajorName = trim((string) ($userMajorDetail?->name ?? ''));
        $userMajorRaw = trim((string) ($user->major ?? ''));
        $userMajorCombined = trim($userMajorName . ' ' . $userMajorRaw);
        $userMajorLower = strtolower($userMajorCombined);
        
        $userDegreeLevel = strtoupper(trim((string) ($userMajorDetail?->degree_level ?? '')));
        if (empty($userDegreeLevel) && !empty($userMajorRaw)) {
            if (preg_match('/\[?(S1|D3|D4|SMK|SMA|S2)\]?/i', $userMajorRaw, $matches)) {
                $userDegreeLevel = strtoupper($matches[1]);
            }
        }

        $userCategoryId = $userMajorDetail?->major_category_id;

        // 3. Validasi Jenjang Pendidikan (Degree Level Check)
        // Posisi khusus SMK saja (tidak membuka S1/D3/Sederajat)
        $isReqSmkOnly = (preg_match('/\b(smk|sma)\b/i', $reqMajorLower) && !preg_match('/\b(s1|d3|d4|sederajat|semua)\b/i', $reqMajorLower));
        // Posisi khusus perguruan tinggi saja (tidak membuka SMK/SMA/Sederajat)
        $isReqHigherEdOnly = (preg_match('/\b(s1|d3|d4|s2)\b/i', $reqMajorLower) && !preg_match('/\b(smk|sma|sederajat|semua)\b/i', $reqMajorLower));

        $isUserHigherEd = in_array($userDegreeLevel, ['S1', 'D3', 'D4', 'S2']);
        $isUserSmk = in_array($userDegreeLevel, ['SMK', 'SMA']);

        if ($isReqSmkOnly && $isUserHigherEd) {
            return false;
        }
        if ($isReqHigherEdOnly && $isUserSmk) {
            return false;
        }

        // Jika lowongan adalah "Semua Jurusan" (dan lolos syarat jenjang di atas jika ada)
        if ($isGeneralMajor && empty($reqCategoryId)) {
            return true;
        }

        // 4. Jika lowongan memiliki required_major_category_id spesifik (Primary Boundary)
        if ($reqCategoryId) {
            // Pelamar wajib memiliki major_id dengan kategori rumpun yang sama
            if (!$userCategoryId || (int) $userCategoryId !== (int) $reqCategoryId) {
                return false;
            }

            // Jika lowongan terbuka umum untuk seluruh jurusan di rumpun tersebut
            if ($isGeneralMajor) {
                return true;
            }

            $reqCatName = strtolower(trim((string) ($this->requiredMajorCategory?->name ?? '')));
            if (!empty($reqCatName) && $reqMajorLower === $reqCatName) {
                return true;
            }
        }

        if (empty($userMajorLower)) {
            return false;
        }

        // 5. Normalisasi & Token Keyword Matching (Hanya dari nama jurusan spesifik peserta)
        $stopWords = [
            'dan', 'atau', 'jurusan', 'program', 'studi', 'prodi', 'fakultas', 'bidang', 'keahlian', 
            'semua', 'khusus', 'sederajat', 'min', 'minimal', 'jenjang', 'diutamakan', 'terbuka', 'untuk',
            'ilmu', 'teknologi', 'rekayasa', 'sains', 'pendidikan', 'pengembangan', 'tingkat', 'ahli'
        ];
        
        $cleanReq = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $reqMajorLower);
        $cleanReq = preg_replace('/\b(smk|sma|s1|d3|d4|s2)\b/i', ' ', $cleanReq);

        $cleanUser = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $userMajorLower);
        $cleanUser = preg_replace('/\b(smk|sma|s1|d3|d4|s2)\b/i', ' ', $cleanUser);

        $reqTokens = array_values(array_filter(explode(' ', $cleanReq), fn($t) => strlen($t) >= 3 && !in_array($t, $stopWords)));
        $userTokens = array_values(array_filter(explode(' ', $cleanUser), fn($t) => strlen($t) >= 3 && !in_array($t, $stopWords)));

        // Jika tidak ada token spesifik lagi pada syarat lowongan
        if (empty($reqTokens)) {
            return true;
        }

        if (empty($userTokens)) {
            return false;
        }

        // Direct Substring Check pada nama jurusan spesifik peserta
        if (!empty($userMajorName)) {
            $cleanUserMajorName = strtolower(trim($userMajorName));
            if (str_contains($reqMajorLower, $cleanUserMajorName) || str_contains($cleanUserMajorName, $reqMajorLower)) {
                return true;
            }
        }

        // Cek irisan kata langsung (misal: "informatika", "komputer", "akuntansi", "hukum")
        $intersect = array_intersect($reqTokens, $userTokens);
        if (!empty($intersect)) {
            return true;
        }

        // 6. Synonym & Keilmuan Cluster Map yang Bersih (tanpa kata generik)
        $clusters = [
            'ti' => ['informatika', 'komputer', 'it', 'rpl', 'tkj', 'perangkat', 'lunak', 'software', 'programming', 'programmer', 'jaringan', 'cyber', 'multimedia', 'database'],
            'ekbis' => ['akuntansi', 'keuangan', 'akt', 'finance', 'perbankan', 'pajak', 'perpajakan', 'manajemen', 'bisnis', 'ekonomi', 'pemasaran', 'marketing'],
            'adm' => ['administrasi', 'adm', 'perkantoran', 'sekretaris', 'kearsipan', 'arsip', 'pemerintahan'],
            'hukum' => ['hukum', 'syariah', 'perdata', 'pidana', 'tatanegara', 'notariat', 'advokat', 'legal'],
            'desain' => ['desain', 'dkv', 'grafis', 'multimedia', 'animasi', 'visual', 'seni', 'kreatif'],
            'kesehatan' => ['kesehatan', 'medis', 'keperawatan', 'perawat', 'kebidanan', 'bidan', 'farmasi', 'apoteker', 'epidemiologi', 'kesmas', 'gizi'],
            'teknik' => ['sipil', 'arsitektur', 'konstruksi', 'bangunan', 'elektro', 'mesin', 'industri', 'lingkungan', 'planologi', 'tata kota'],
            'guru' => ['keguruan', 'guru', 'pgsd', 'kurikulum', 'pengajaran'],
            'sosial' => ['sosiologi', 'komunikasi', 'humas', 'jurnalistik', 'politik'],
        ];

        foreach ($clusters as $cluster) {
            $reqHasCluster = !empty(array_intersect($reqTokens, $cluster));
            $userHasCluster = !empty(array_intersect($userTokens, $cluster));
            if ($reqHasCluster && $userHasCluster) {
                return true;
            }
        }

        return false;
    }
}