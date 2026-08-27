<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat & Daftar Nilai Magang - {{ $app->user->name }}</title>
    <style>
        /* =========================================================
           1. Konfigurasi Halaman DomPDF A4 Landscape (297mm x 210mm)
           ========================================================= */
        @page {
            size: 297mm 210mm;
            margin: 0;
        }

        *, *:before, *:after {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 297mm;
            font-family: "Helvetica", "Arial", sans-serif;
            color: #1e293b;
            background-color: #ffffff;
            font-size: 9.5pt;
            line-height: 1.25;
        }

        /* Container Halaman */
        .page {
            position: relative;
            width: 297mm;
            height: 209mm;
            max-height: 209mm;
            overflow: hidden;
            page-break-inside: avoid;
        }

        .page-1 {
            page-break-after: always;
        }

        .page-2 {
            page-break-after: avoid;
        }

        /* Garis Bingkai / Frame Elegan (Ukuran Eksplisit agar tidak overflow di DomPDF) */
        .frame-border {
            position: absolute;
            top: 8mm;
            left: 8mm;
            width: 281mm;
            height: 194mm;
            border: 2px solid #0284c7;
            border-radius: 4px;
        }
        
        .frame-inner-border {
            position: absolute;
            top: 9.5mm;
            left: 9.5mm;
            width: 278mm;
            height: 191mm;
            border: 0.75px solid #d97706;
            border-radius: 2px;
        }

        /* Ornamen Sudut Sasirangan */
        .corner-pattern-left {
            position: absolute;
            top: 0;
            left: 0;
            width: 65mm;
            height: 32mm;
            z-index: 1;
        }

        .corner-pattern-right {
            position: absolute;
            top: 0;
            left: 232mm;
            width: 65mm;
            height: 32mm;
            z-index: 1;
        }

        /* =========================================================
           2. Styling Halaman 1: Piagam Sertifikat Depan
           ========================================================= */
        .cert-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 16mm 22mm 14mm 22mm;
        }

        .cert-header-instansi {
            font-size: 15pt;
            font-weight: bold;
            color: #0369a1;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 3px;
        }

        .cert-header-dinas {
            font-size: 11.5pt;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 8px;
        }

        .cert-divider {
            width: 40%;
            height: 2px;
            background-color: #0284c7;
            margin: 0 auto 12px auto;
        }

        .cert-main-title {
            font-size: 28pt;
            font-weight: bold;
            color: #0369a1;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin: 4px 0 2px 0;
        }

        .cert-number {
            font-size: 10pt;
            font-weight: bold;
            color: #475569;
            margin-bottom: 14px;
        }

        .cert-intro {
            font-size: 11.5pt;
            font-style: italic;
            color: #475569;
            margin: 4px 0 8px 0;
        }

        .cert-name-wrapper {
            margin: 6px 0 10px 0;
        }

        .cert-participant-name {
            font-size: 24pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            display: inline-block;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 3px;
        }

        .cert-sub-info {
            font-size: 10pt;
            color: #334155;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .cert-body-text {
            font-size: 11pt;
            color: #1e293b;
            line-height: 1.45;
            margin: 6px auto;
            max-width: 88%;
        }

        .cert-predikat-badge {
            font-weight: bold;
            color: #0369a1;
            text-transform: uppercase;
        }

        .cert-period-text {
            font-size: 10pt;
            font-style: italic;
            color: #475569;
            margin-top: 4px;
            margin-bottom: 20px;
        }

        /* Signatures Halaman 1 */
        .cert-sig-table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .cert-sig-table td {
            vertical-align: top;
            text-align: center;
            font-size: 9.5pt;
            color: #1e293b;
            line-height: 1.3;
        }

        .sig-space-box {
            height: 48px;
            text-align: center;
            vertical-align: middle;
        }

        .sig-name-underline {
            font-weight: bold;
            display: inline-block;
            border-bottom: 1px solid #1e293b;
            padding-bottom: 1px;
            margin-bottom: 2px;
        }

        .qr-wrapper {
            display: inline-block;
            padding: 4px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
        }

        .qr-caption {
            font-size: 7.5pt;
            font-weight: bold;
            color: #64748b;
            display: block;
            margin-top: 3px;
            text-transform: uppercase;
        }

        /* =========================================================
           3. Styling Halaman 2: Lembar Daftar Nilai (Sesuai Foto)
           ========================================================= */
        .grade-content {
            position: relative;
            z-index: 2;
            padding: 12mm 18mm 10mm 18mm;
        }

        .grade-header-box {
            text-align: center;
            margin-top: 0;
            margin-bottom: 10px;
        }

        .grade-title {
            font-size: 23pt;
            font-weight: bold;
            color: #0284c7;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 0;
            line-height: 1.1;
        }

        .grade-subtitle {
            font-size: 11pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-top: 2px;
        }

        /* Identitas Peserta */
        .identity-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            margin-bottom: 10px;
            font-size: 9pt;
        }

        .identity-table td {
            padding: 1.5px 0;
            vertical-align: top;
        }

        .id-label {
            width: 18%;
            color: #1e293b;
            font-weight: 500;
        }

        .id-sep {
            width: 2%;
            text-align: center;
            color: #1e293b;
        }

        .id-val {
            width: 80%;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }

        /* Tabel Komponen Penilaian Utama */
        .main-grade-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 8.5pt;
        }

        .main-grade-table th {
            background-color: #0284c7;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px 8px;
            border: 1px solid #0284c7;
            text-align: center;
            font-size: 8.5pt;
        }

        .main-grade-table td {
            padding: 4.5px 8px;
            border: 1px solid #cbd5e1;
            color: #1e293b;
        }

        .main-grade-table tr.avg-row td {
            background-color: #f8fafc;
            font-weight: bold;
            border-top: 1.5px solid #0284c7;
        }

        /* Badge Nilai Akhir Card */
        .final-score-container {
            margin-bottom: 10px;
        }

        .final-score-card {
            display: inline-block;
            background-color: #0284c7;
            color: #ffffff;
            padding: 4px 16px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 9.5pt;
            letter-spacing: 0.5px;
        }

        .final-score-val {
            color: #ffffff;
            font-size: 10pt;
            font-weight: bold;
            margin-left: 4px;
        }

        .final-score-predikat {
            color: #ffffff;
            font-weight: bold;
            margin-left: 4px;
        }

        .final-score-desc {
            font-size: 7.5pt;
            color: #64748b;
            font-style: italic;
            margin-top: 2px;
        }

        /* Bottom Section: Indikator & Tanda Tangan (2 Kolom) */
        .bottom-layout-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
        }

        .bottom-layout-table td {
            vertical-align: top;
        }

        /* Tabel Indikator Kwalifikasi */
        .indicator-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 6.8pt;
            line-height: 1.25;
        }

        .indicator-table th {
            background-color: #475569;
            color: #ffffff;
            padding: 3px 4px;
            border: 0.5px solid #334155;
            text-align: center;
            font-size: 7pt;
            font-weight: bold;
        }

        .indicator-table td {
            padding: 2.5px 4px;
            border: 0.5px solid #cbd5e1;
            color: #334155;
        }

        /* Tanda Tangan Halaman 2 */
        .grade-signature-box {
            text-align: center;
            font-size: 9pt;
            color: #1e293b;
            line-height: 1.3;
            padding-left: 15px;
        }
    </style>
</head>
<body>

@php
    \Carbon\Carbon::setLocale('id');

    // Helper predikat sesuai skala foto
    $getPredikat = function($val) {
        if (is_null($val) || $val === '') return '-';
        $v = (float) $val;
        if ($v >= 80) return 'Baik Sekali';
        if ($v >= 65) return 'Baik';
        if ($v >= 51) return 'Cukup';
        if ($v >= 30) return 'Kurang';
        return 'Kurang Sekali';
    };

    $instansi = $app->position?->instansi;
    $pembimbing = $app->pembimbing_lapangan;
    $user = $app->user;

    // TTD Kepala
    $ttdKepalaPath = null;
    if (!empty($instansi?->ttd_kepala)) {
        $rawK = $instansi->ttd_kepala;
        if (\Illuminate\Support\Facades\Storage::disk('private')->exists($rawK)) {
            $ttdKepalaPath = \Illuminate\Support\Facades\Storage::disk('private')->path($rawK);
        } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($rawK)) {
            $ttdKepalaPath = \Illuminate\Support\Facades\Storage::disk('public')->path($rawK);
        } elseif (file_exists(storage_path('app/public/' . $rawK))) {
            $ttdKepalaPath = storage_path('app/public/' . $rawK);
        } elseif (file_exists(public_path('storage/' . $rawK))) {
            $ttdKepalaPath = public_path('storage/' . $rawK);
        }
    }

    // TTD Pembimbing Lapangan
    $ttdPlPath = null;
    if (!empty($pembimbing?->signature)) {
        $rawPl = $pembimbing->signature;
        if (\Illuminate\Support\Facades\Storage::disk('private')->exists($rawPl)) {
            $ttdPlPath = \Illuminate\Support\Facades\Storage::disk('private')->path($rawPl);
        } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($rawPl)) {
            $ttdPlPath = \Illuminate\Support\Facades\Storage::disk('public')->path($rawPl);
        } elseif (file_exists(storage_path('app/public/' . $rawPl))) {
            $ttdPlPath = storage_path('app/public/' . $rawPl);
        } elseif (file_exists(public_path('storage/' . $rawPl))) {
            $ttdPlPath = public_path('storage/' . $rawPl);
        }
    }

    // Komponen Penilaian dari Aplikasi
    $nilaiList = [
        ['no' => 1, 'nama' => 'Kerajinan', 'nilai' => $app->nilai_kerajinan],
        ['no' => 2, 'nama' => 'Disiplin', 'nilai' => $app->nilai_disiplin],
        ['no' => 3, 'nama' => 'Adaptasi', 'nilai' => $app->nilai_adaptasi],
        ['no' => 4, 'nama' => 'Kreatifitas', 'nilai' => $app->nilai_kreatifitas],
        ['no' => 5, 'nama' => 'Skill dan Pengetahuan', 'nilai' => $app->nilai_skill_pengetahuan],
    ];

    $avgScore = $app->nilai_rata_rata;
    if (is_null($avgScore) || (float) $avgScore <= 0) {
        $validScores = array_filter(
            [$app->nilai_kerajinan, $app->nilai_disiplin, $app->nilai_adaptasi, $app->nilai_kreatifitas, $app->nilai_skill_pengetahuan], 
            fn($v) => !is_null($v) && $v !== ''
        );
        $avgScore = count($validScores) > 0 ? round(array_sum($validScores) / count($validScores), 1) : 0;
    }
    $predikatAkhir = $getPredikat($avgScore);

    $tanggalCetak = !empty($tanggal) 
        ? $tanggal 
        : (!empty($app->tanggal_selesai) 
            ? \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d F Y') 
            : \Carbon\Carbon::now()->translatedFormat('d F Y'));
    
    // Kampus / Sekolah / Identitas
    $kampusSekolah = $user->university?->name ?? $user->school?->name ?? $user->asal_instansi ?? '-';
    $jurusanProdi = $user->majorDetail?->name ?? $user->major ?? '-';
    $nomorInduk = $user->nik ?? $user->nim ?? '-';
    $posisiPenempatan = $app->position?->title ?? $app->position?->name ?? ($instansi?->nama_dinas ?? 'Bidang Terkait');

    // SVG Ornamen Sasirangan Sudut Kiri (Embedded Base64)
    $svgLeft = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 100" width="200" height="100">
        <!-- Arcs and Curves -->
        <path d="M 0,0 L 200,0 C 170,10 130,45 100,75 C 70,95 30,100 0,100 Z" fill="#0369a1" />
        <path d="M 0,0 L 175,0 C 145,15 110,48 85,73 C 58,92 25,95 0,95 Z" fill="#0284c7" />
        <path d="M 0,0 L 145,0 C 120,18 90,45 68,68 C 45,84 20,88 0,88 Z" fill="#0f172a" />
        <path d="M 0,0 L 115,0 C 95,15 70,40 50,60 C 32,74 15,78 0,78 Z" fill="#0284c7" />
        
        <!-- Gold Trim -->
        <path d="M 0,100 C 30,100 70,95 100,75 C 130,45 170,10 200,0" fill="none" stroke="#f59e0b" stroke-width="3" />
        <path d="M 0,88 C 20,88 45,84 68,68 C 90,45 120,18 145,0" fill="none" stroke="#fbbf24" stroke-width="1.5" />
        
        <!-- Sasirangan Stitch Dots / Beads Motif -->
        <circle cx="15" cy="15" r="2.5" fill="#fef08a" />
        <circle cx="35" cy="18" r="2.5" fill="#ffffff" />
        <circle cx="55" cy="22" r="2.5" fill="#fef08a" />
        <circle cx="75" cy="28" r="2.5" fill="#ffffff" />
        <circle cx="95" cy="36" r="2.5" fill="#fef08a" />
        <circle cx="115" cy="46" r="2.5" fill="#ffffff" />
        <circle cx="135" cy="58" r="2.5" fill="#fef08a" />
        
        <!-- Inner Pattern Dots -->
        <circle cx="12" cy="35" r="2" fill="#ffffff" />
        <circle cx="28" cy="42" r="2" fill="#fef08a" />
        <circle cx="45" cy="52" r="2" fill="#ffffff" />
        <circle cx="62" cy="64" r="2" fill="#fef08a" />
        
        <!-- Diamond accents -->
        <polygon points="35,30 38,34 35,38 32,34" fill="#38bdf8" />
        <polygon points="65,40 68,44 65,48 62,44" fill="#38bdf8" />
        <polygon points="95,50 98,54 95,58 92,54" fill="#38bdf8" />
        <polygon points="125,25 128,29 125,33 122,29" fill="#fef08a" />
        <polygon points="150,15 153,19 150,23 147,19" fill="#ffffff" />
    </svg>';
    $svgLeftBase64 = 'data:image/svg+xml;base64,' . base64_encode($svgLeft);

    // SVG Ornamen Sasirangan Sudut Kanan (Mirrored)
    $svgRight = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 100" width="200" height="100">
        <g transform="translate(200, 0) scale(-1, 1)">
            <!-- Arcs and Curves -->
            <path d="M 0,0 L 200,0 C 170,10 130,45 100,75 C 70,95 30,100 0,100 Z" fill="#0369a1" />
            <path d="M 0,0 L 175,0 C 145,15 110,48 85,73 C 58,92 25,95 0,95 Z" fill="#0284c7" />
            <path d="M 0,0 L 145,0 C 120,18 90,45 68,68 C 45,84 20,88 0,88 Z" fill="#0f172a" />
            <path d="M 0,0 L 115,0 C 95,15 70,40 50,60 C 32,74 15,78 0,78 Z" fill="#0284c7" />
            
            <!-- Gold Trim -->
            <path d="M 0,100 C 30,100 70,95 100,75 C 130,45 170,10 200,0" fill="none" stroke="#f59e0b" stroke-width="3" />
            <path d="M 0,88 C 20,88 45,84 68,68 C 90,45 120,18 145,0" fill="none" stroke="#fbbf24" stroke-width="1.5" />
            
            <!-- Sasirangan Stitch Dots / Beads Motif -->
            <circle cx="15" cy="15" r="2.5" fill="#fef08a" />
            <circle cx="35" cy="18" r="2.5" fill="#ffffff" />
            <circle cx="55" cy="22" r="2.5" fill="#fef08a" />
            <circle cx="75" cy="28" r="2.5" fill="#ffffff" />
            <circle cx="95" cy="36" r="2.5" fill="#fef08a" />
            <circle cx="115" cy="46" r="2.5" fill="#ffffff" />
            <circle cx="135" cy="58" r="2.5" fill="#fef08a" />
            
            <!-- Inner Pattern Dots -->
            <circle cx="12" cy="35" r="2" fill="#ffffff" />
            <circle cx="28" cy="42" r="2" fill="#fef08a" />
            <circle cx="45" cy="52" r="2" fill="#ffffff" />
            <circle cx="62" cy="64" r="2" fill="#fef08a" />
            
            <!-- Diamond accents -->
            <polygon points="35,30 38,34 35,38 32,34" fill="#38bdf8" />
            <polygon points="65,40 68,44 65,48 62,44" fill="#38bdf8" />
            <polygon points="95,50 98,54 95,58 92,54" fill="#38bdf8" />
            <polygon points="125,25 128,29 125,33 122,29" fill="#fef08a" />
            <polygon points="150,15 153,19 150,23 147,19" fill="#ffffff" />
        </g>
    </svg>';
    $svgRightBase64 = 'data:image/svg+xml;base64,' . base64_encode($svgRight);
@endphp

    {{-- =========================================================================
         HALAMAN 1: PIAGAM SERTIFIKAT DEPAN
         ========================================================================= --}}
    <div class="page page-1">
        <!-- Bingkai Luar & Dalam -->
        <div class="frame-border"></div>
        <div class="frame-inner-border"></div>

        <!-- Ornamen Sasirangan Sudut -->
        <img class="corner-pattern-left" src="{{ $svgLeftBase64 }}" alt="Sasirangan Corner Left">
        <img class="corner-pattern-right" src="{{ $svgRightBase64 }}" alt="Sasirangan Corner Right">

        <div class="cert-content">
            <!-- Kop Instansi -->
            <div class="cert-header-instansi">PEMERINTAH KOTA BANJARMASIN</div>
            <div class="cert-header-dinas">{{ $instansi->nama_dinas ?? 'DINAS KOMUNIKASI, INFORMATIKA DAN STATISTIK' }}</div>
            <div class="cert-divider"></div>

            <!-- Judul & Nomor Sertifikat -->
            <div class="cert-main-title">SERTIFIKAT MAGANG</div>
            <div class="cert-number">Nomor: {{ $app->nomor_sertifikat ?? 'Draft' }}</div>

            <!-- Penerima Sertifikat -->
            <p class="cert-intro">Diberikan apresiasi setinggi-tingginya kepada:</p>

            <div class="cert-name-wrapper">
                <span class="cert-participant-name">{{ $user->name }}</span>
            </div>

            <div class="cert-sub-info">
                NIS/NIM/NPM: <strong>{{ $nomorInduk }}</strong> &nbsp;|&nbsp; 
                <strong>{{ $kampusSekolah }}</strong> &nbsp;|&nbsp; 
                Jurusan: <strong>{{ $jurusanProdi }}</strong>
            </div>

            <!-- Deskripsi Kelulusan -->
            <p class="cert-body-text">
                Telah menyelesaikan program Praktik Kerja Lapangan (PKL) / Magang pada 
                <strong>{{ $instansi->nama_dinas ?? 'Pemerintah Kota Banjarmasin' }}</strong> 
                dengan hasil evaluasi kualifikasi 
                <span class="cert-predikat-badge">"{{ strtoupper($predikatAkhir) }}"</span>.
            </p>

            <p class="cert-period-text">
                Dilaksanakan mulai tanggal {{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d F Y') }} 
                sampai dengan {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d F Y') }}.
            </p>

            <!-- Tabel Tanda Tangan & QR Code -->
            <table class="cert-sig-table">
                <tr>
                    <!-- Kiri: Pejabat / Kepala Dinas -->
                    <td style="width: 38%;">
                        Mengetahui,<br>
                        <strong>{{ $instansi->jabatan_pejabat ?? 'Kepala Dinas' }}</strong>
                        
                        <div class="sig-space-box">
                            @if($ttdKepalaPath)
                                <img src="{{ $ttdKepalaPath }}" style="max-height: 44px; max-width: 130px; display: block; margin: 0 auto;">
                            @endif
                        </div>

                        <span class="sig-name-underline">
                            {{ $instansi->nama_pejabat ?? '................................' }}
                        </span><br>
                        <span style="font-size: 8pt; color: #475569;">NIP. {{ $instansi->nip_pejabat ?? '....................' }}</span>
                    </td>

                    <!-- Tengah: QR Code Scan Validasi -->
                    <td style="width: 24%; vertical-align: top; padding-top: 2px;">
                        <div class="qr-wrapper">
                            <img src="data:image/svg+xml;base64, {{ base64_encode(QrCode::format('svg')->size(56)->generate(route('certificate.verify', $app->token_verifikasi ?? 'invalid'))) }}" style="display: block; margin: 0 auto; width: 56px; height: 56px;">
                        </div>
                        <span class="qr-caption">Scan Validasi</span>
                    </td>

                    <!-- Kanan: Pembimbing Lapangan -->
                    <td style="width: 38%;">
                        Banjarmasin, {{ $tanggalCetak }}<br>
                        <strong>Pembimbing Lapangan</strong>
                        
                        <div class="sig-space-box">
                            @if($ttdPlPath)
                                <img src="{{ $ttdPlPath }}" style="max-height: 44px; max-width: 130px; display: block; margin: 0 auto;">
                            @endif
                        </div>

                        <span class="sig-name-underline">
                            {{ $pembimbing->name ?? '................................' }}
                        </span><br>
                        <span style="font-size: 8pt; color: #475569;">NIP/NIK. {{ $pembimbing->nik ?? $pembimbing->nomor_induk ?? '-' }}</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- =========================================================================
         HALAMAN 2: LEMBAR DAFTAR NILAI (SESUAI FOTO)
         ========================================================================= --}}
    <div class="page page-2">
        <!-- Bingkai Luar & Dalam -->
        <div class="frame-border"></div>
        <div class="frame-inner-border"></div>

        <!-- Ornamen Sasirangan Sudut -->
        <img class="corner-pattern-left" src="{{ $svgLeftBase64 }}" alt="Sasirangan Corner Left">
        <img class="corner-pattern-right" src="{{ $svgRightBase64 }}" alt="Sasirangan Corner Right">

        <div class="grade-content">
            <!-- Header Judul Daftar Nilai -->
            <div class="grade-header-box">
                <div class="grade-title">DAFTAR NILAI</div>
                <div class="grade-subtitle">PRAKTIK KERJA LAPANGAN (PKL)/MAGANG</div>
            </div>

            <!-- Tabel Identitas Peserta -->
            <table class="identity-table">
                <tr>
                    <td class="id-label">Nama</td>
                    <td class="id-sep">:</td>
                    <td class="id-val">{{ $user->name }}</td>
                </tr>
                <tr>
                    <td class="id-label">NIS/NIM/NPM</td>
                    <td class="id-sep">:</td>
                    <td class="id-val">{{ $nomorInduk }}</td>
                </tr>
                <tr>
                    <td class="id-label">Jurusan/Prodi</td>
                    <td class="id-sep">:</td>
                    <td class="id-val">{{ $jurusanProdi }}</td>
                </tr>
                <tr>
                    <td class="id-label">Sekolah/Universitas</td>
                    <td class="id-sep">:</td>
                    <td class="id-val">{{ $kampusSekolah }}</td>
                </tr>
                <tr>
                    <td class="id-label">Penempatan</td>
                    <td class="id-sep">:</td>
                    <td class="id-val">{{ $posisiPenempatan }}</td>
                </tr>
            </table>

            <!-- Tabel Komponen Penilaian (5 Kriteria Sesuai Aplikasi) -->
            <table class="main-grade-table">
                <thead>
                    <tr>
                        <th style="width: 7%;">No</th>
                        <th style="width: 53%; text-align: left; padding-left: 10px;">Komponen Penilaian</th>
                        <th style="width: 20%;">Nilai</th>
                        <th style="width: 20%;">Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nilaiList as $item)
                    <tr>
                        <td style="text-align: center;">{{ $item['no'] }}</td>
                        <td style="padding-left: 10px;">{{ $item['nama'] }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ !is_null($item['nilai']) ? $item['nilai'] : '-' }}</td>
                        <td style="text-align: center;">{{ $getPredikat($item['nilai']) }}</td>
                    </tr>
                    @endforeach
                    <tr class="avg-row">
                        <td colspan="2" style="text-align: center; font-weight: bold;">Rata-rata</td>
                        <td style="text-align: center; font-weight: bold; font-size: 8.5pt; color: #0284c7;">
                            {{ number_format((float)$avgScore, 1, ',', '.') }}
                        </td>
                        <td style="text-align: center; font-weight: bold; color: #0284c7;">
                            {{ $predikatAkhir }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Badge Highlight Nilai Akhir -->
            <div class="final-score-container">
                <div class="final-score-card">
                    NILAI AKHIR <span class="final-score-val">{{ number_format((float)$avgScore, 1, ',', '.') }}</span> &nbsp;|&nbsp; <span class="final-score-predikat">{{ $predikatAkhir }}</span>
                </div>
                <div class="final-score-desc">(Rata-rata dari Nilai Komponen Penilaian Magang)</div>
            </div>

            <!-- Bagian Bawah: Indikator Nilai (Kiri) & Tanda Tangan Pembimbing (Kanan) -->
            <table class="bottom-layout-table">
                <tr>
                    <!-- Kiri: Tabel Indikator Kwalifikasi -->
                    <td style="width: 58%; vertical-align: top;">
                        <table class="indicator-table">
                            <thead>
                                <tr>
                                    <th style="width: 6%;">No</th>
                                    <th style="width: 60%; text-align: left; padding-left: 5px;">Indikator</th>
                                    <th style="width: 14%;">Nilai</th>
                                    <th style="width: 20%;">Kwalifikasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align: center;">1</td>
                                    <td style="padding-left: 5px;">Tidak mengerjakan tidak menghasilkan tanpa satu nilai atau tidak berguna</td>
                                    <td style="text-align: center;">0-29</td>
                                    <td style="text-align: center;">Kurang Sekali</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">2</td>
                                    <td style="padding-left: 5px;">Tidak mencukupi untuk memenuhi persyaratan minimal yang diharapkan dan hasil kerja</td>
                                    <td style="text-align: center;">30-50</td>
                                    <td style="text-align: center;">Kurang</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">3</td>
                                    <td style="padding-left: 5px;">Hanya mencukupi untuk persyaratan minimal yang diharapkan dari hasil pekerjaan</td>
                                    <td style="text-align: center;">51-64</td>
                                    <td style="text-align: center;">Cukup</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">4</td>
                                    <td style="padding-left: 5px;">Semua tugas yang dibebankan dilaksanakan dengan lancar hanya terdapat kesalahan-kesalahan kecil mutu tinggi dalam pekerjaan</td>
                                    <td style="text-align: center;">65-79</td>
                                    <td style="text-align: center;">Baik</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">5</td>
                                    <td style="padding-left: 5px;">Semua tugas yang dibebankan berhasil dengan baik mutu paling tinggi dalam standar pekerjaan</td>
                                    <td style="text-align: center;">80-100</td>
                                    <td style="text-align: center; font-weight: bold;">Baik Sekali</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>

                    <!-- Kanan: Tanda Tangan Pembimbing Lapangan -->
                    <td style="width: 42%; vertical-align: top;">
                        <div class="grade-signature-box">
                            Banjarmasin, {{ $tanggalCetak }}<br>
                            <strong>Pembimbing,</strong>
                            
                            <div class="sig-space-box" style="height: 44px; margin: 2px 0;">
                                @if($ttdPlPath)
                                    <img src="{{ $ttdPlPath }}" style="max-height: 44px; max-width: 130px; display: block; margin: 0 auto;">
                                @endif
                            </div>

                            <span class="sig-name-underline">
                                {{ $pembimbing->name ?? '................................' }}
                            </span><br>
                            <span style="font-size: 8pt; color: #475569;">NIP/NIK. {{ $pembimbing->nik ?? $pembimbing->nomor_induk ?? '-' }}</span>
                        </div>
                    </td>
                </tr>
            </table>

        </div>
    </div>

</body>
</html>