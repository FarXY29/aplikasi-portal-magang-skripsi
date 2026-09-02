@php
    \Carbon\Carbon::setLocale('id');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Penilaian PKL - {{ $app->user->name }}</title>
    <style>
        @page {
            margin: 1.2cm 1.8cm 1.2cm 2cm;
            size: A4 portrait;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 10pt;
            line-height: 1.2;
            margin: 0;
            padding: 0;
            color: #000;
        }
        
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .text-justify { text-align: justify; }
        
        .judul-formulir {
            text-align: center;
            font-weight: bold;
            font-size: 11.5pt;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 0.5px;
            margin: 4px 0 8px 0;
        }

        /* Layout Tabel Informasi (Header) */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
            font-size: 9.5pt;
        }
        .info-table td {
            vertical-align: top;
            padding: 1.5px 0;
        }
        .label-col { width: 34%; }
        .sep-col { width: 2%; text-align: center; }
        
        /* Layout Tabel Nilai */
        .grade-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            margin-bottom: 8px;
        }
        .grade-table th, .grade-table td {
            border: 1px solid #000;
            padding: 3.5px 6px;
            font-size: 9.5pt;
        }
        .grade-table th {
            text-align: center;
            background-color: #f1f5f9;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9pt;
        }
        .col-no { width: 6%; text-align: center; }
        .col-nilai { width: 22%; text-align: center; font-weight: bold; }
        .col-predikat { width: 24%; text-align: center; font-weight: bold; }

        /* Layout Tanda Tangan */
        .signature-table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .signature-table td {
            text-align: center;
            vertical-align: top;
            font-size: 9.5pt;
        }
        .sign-space {
            height: 52px;
        }

        .qr-box {
            display: inline-block;
            text-align: center;
            padding: 3px;
            border: 1px solid #cbd5e1;
            border-radius: 3px;
            background: #fff;
        }
        .qr-caption {
            font-size: 6.8pt;
            color: #555;
            margin-top: 1px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    @include('pdf.partials.kop_admin_instansi', ['instansi' => $app->position->instansi])

    <div class="judul-formulir">FORMULIR PENILAIAN PRAKTIK KERJA LAPANGAN (PKL) / MAGANG</div>

    <table class="info-table">
        <tr>
            <td class="label-col">Nama Pembimbing Lapangan</td>
            <td class="sep-col">:</td>
            <td class="text-bold">{{ $app->pembimbing_lapangan->name ?? '........................................' }}</td>
        </tr>
        <tr>
            <td class="label-col">Instansi Tempat Magang</td>
            <td class="sep-col">:</td>
            <td><strong>{{ $app->position->instansi->nama_dinas }}</strong> Kota Banjarmasin</td>
        </tr>
    </table>

    <p style="margin: 4px 0; font-size: 9.5pt;">Menyatakan bahwa peserta Praktik Kerja Lapangan (PKL) / Magang berikut ini:</p>

    <table class="info-table">
        <tr>
            <td class="label-col">Nama Lengkap Mahasiswa</td>
            <td class="sep-col">:</td>
            <td class="text-bold">{{ $app->user->name }}</td>
        </tr>
        <tr>
            <td class="label-col">Nomor Induk (NIM / NPM / NISN)</td>
            <td class="sep-col">:</td>
            <td>{{ $app->user->nik ?? ($app->user->nim ?? '-') }}</td> 
        </tr>
        <tr>
            <td class="label-col">Asal Sekolah / Universitas</td>
            <td class="sep-col">:</td>
            <td>{{ $app->user->asal_instansi ?? ($app->user->university->name ?? ($app->user->school->name ?? '-')) }}</td> 
        </tr>
        <tr>
            <td class="label-col">Program Studi / Jurusan</td>
            <td class="sep-col">:</td>
            <td>{{ $app->user->majorDetail?->name ?? ($app->user->major ?? '-') }}</td> 
        </tr>
        <tr>
            <td class="label-col">Posisi &amp; Waktu Pelaksanaan</td>
            <td class="sep-col">:</td>
            <td>
                {{ $app->position->judul_posisi ?? '-' }} &bull;
                {{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d F Y') }} s.d. {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d F Y') }}
            </td>
        </tr>
    </table>

    <div class="text-justify" style="margin-top: 5px; margin-bottom: 5px; font-size: 9.2pt; line-height: 1.25;">
        Telah menyelesaikan Praktik Kerja Lapangan di Instansi kami. Berdasarkan evaluasi kinerja, kedisiplinan, dan pelaksanaan tugas selama periode magang, kami memberikan hasil penilaian akhir sebagai berikut:
    </div>

    @php
        $getPredikat = function($val) {
            if (is_null($val) || $val === '') return '-';
            $v = (float) $val;
            if ($v >= 80) return 'Baik Sekali';
            if ($v >= 65) return 'Baik';
            if ($v >= 51) return 'Cukup';
            if ($v >= 30) return 'Kurang';
            return 'Kurang Sekali';
        };

        $kriteria = [
            'Kerajinan' => $app->nilai_kerajinan,
            'Disiplin' => $app->nilai_disiplin,
            'Adaptasi' => $app->nilai_adaptasi,
            'Kreatifitas' => $app->nilai_kreatifitas,
            'Skill dan Pengetahuan' => $app->nilai_skill_pengetahuan,
        ];

        $validScores = array_filter($kriteria, fn($v) => !is_null($v) && $v !== '');
        $avgScore = count($validScores) > 0 ? round(array_sum($validScores) / count($validScores), 1) : 0;
        $predikatAkhir = $getPredikat($avgScore);
        $no = 1;
    @endphp

    <table class="grade-table">
        <thead>
            <tr>
                <th class="col-no">No.</th>
                <th>Aktivitas &amp; Komponen Yang Dinilai</th>
                <th class="col-nilai">Nilai (Angka)</th>
                <th class="col-predikat">Predikat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kriteria as $label => $nilai)
            <tr>
                <td class="col-no">{{ $no++ }}</td>
                <td>{{ $label }}</td>
                <td class="col-nilai">{{ !is_null($nilai) ? $nilai : '-' }}</td>
                <td class="col-predikat">{{ $getPredikat($nilai) }}</td>
            </tr>
            @endforeach
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td colspan="2" style="text-align: center;">NILAI RATA-RATA AKHIR</td>
                <td class="col-nilai" style="color: #0f766e; font-size: 10pt;">{{ number_format((float)$avgScore, 1, ',', '.') }}</td>
                <td class="col-predikat" style="color: #0f766e;">{{ $predikatAkhir }}</td>
            </tr>
        </tbody>
    </table>

    @php
        $instansi = $app->position->instansi;
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

        $ttdPlPath = null;
        if (!empty($app->pembimbing_lapangan?->signature)) {
            $rawPl = $app->pembimbing_lapangan->signature;
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
    @endphp

    <table class="signature-table">
        <tr>
            {{-- Left Header: Mengetahui Pejabat Instansi --}}
            <td style="width: 38%; text-align: center; vertical-align: top; padding-bottom: 2px;">
                Mengetahui,<br>
                <span class="text-bold" style="text-transform: uppercase;">{{ $instansi->jabatan_pejabat ?? 'Kepala Dinas' }}</span><br>
                <span style="font-size: 8.5pt; font-weight: bold; text-transform: uppercase;">{{ $instansi->nama_dinas ?? '-' }}</span>
            </td>

            {{-- Center: QR Code Scan Validasi --}}
            <td style="width: 24%; text-align: center; vertical-align: middle;">
                <div class="qr-box">
                    <img src="data:image/svg+xml;base64, {{ base64_encode(QrCode::format('svg')->size(48)->generate(route('certificate.verify', $app->token_verifikasi ?? 'invalid'))) }}" style="display: block; margin: 0 auto; width: 48px; height: 48px;">
                    <div class="qr-caption">Scan Validasi Nilai</div>
                </div>
            </td>

            {{-- Right Header: Pembimbing Lapangan --}}
            <td style="width: 38%; text-align: center; vertical-align: top; padding-bottom: 2px;">
                Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                <span class="text-bold" style="text-transform: uppercase;">Pembimbing Lapangan</span><br>
                <span style="visibility: hidden; font-size: 8.5pt;">&nbsp;</span>
            </td>
        </tr>
        <tr>
            {{-- Left Signature Image --}}
            <td style="width: 38%; text-align: center; vertical-align: middle; height: 52px;">
                @if($ttdKepalaPath && file_exists($ttdKepalaPath))
                    <img src="{{ $ttdKepalaPath }}" style="max-height: 48px; max-width: 140px; display: block; margin: 0 auto;">
                @else
                    <div class="sign-space"></div>
                @endif
            </td>
            {{-- Center space --}}
            <td style="width: 24%;"></td>
            {{-- Right Signature Image --}}
            <td style="width: 38%; text-align: center; vertical-align: middle; height: 52px;">
                @if($ttdPlPath && file_exists($ttdPlPath))
                    <img src="{{ $ttdPlPath }}" style="max-height: 48px; max-width: 140px; display: block; margin: 0 auto;">
                @else
                    <div class="sign-space"></div>
                @endif
            </td>
        </tr>
        <tr>
            {{-- Left Name & NIP --}}
            <td style="width: 38%; text-align: center; vertical-align: top;">
                <span class="text-bold" style="text-decoration: underline; text-transform: uppercase;">
                    {{ $instansi->nama_pejabat ?? '........................................' }}
                </span><br>
                <span style="font-size: 9pt;">NIP. {{ $instansi->nip_pejabat ?? '....................' }}</span>
            </td>
            {{-- Center space --}}
            <td style="width: 24%;"></td>
            {{-- Right Name & NIP --}}
            <td style="width: 38%; text-align: center; vertical-align: top;">
                <span class="text-bold" style="text-decoration: underline; text-transform: uppercase;">
                    {{ $app->pembimbing_lapangan->name ?? '........................................' }}
                </span><br>
                <span style="font-size: 9pt;">NIP/NIK. {{ $app->pembimbing_lapangan->nik ?? ($app->pembimbing_lapangan->nomor_induk ?? '-') }}</span>
            </td>
        </tr>
    </table>

</body>
</html>