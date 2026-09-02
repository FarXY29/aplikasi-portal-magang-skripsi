<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Balasan Persetujuan Magang - {{ $app->user->name }}</title>
    <style>
        @page {
            margin: 1.2cm 2cm 1.5cm 2.2cm;
            size: A4 portrait;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.25;
            color: #000;
        }

        /* TANGGAL */
        .date-section {
            text-align: right;
            margin-bottom: 8px;
            font-size: 11pt;
        }

        /* TABEL INFO (Nomor & Tujuan) */
        .info-container {
            width: 100%;
            margin-bottom: 12px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            vertical-align: top;
            padding: 1.5px 0;
            font-size: 10.5pt;
        }
        
        .col-left-label { width: 14%; white-space: nowrap; }
        .col-left-sep { width: 2%; text-align: center; }
        .col-left-content { width: 44%; padding-right: 10px; }
        .col-right { width: 40%; }

        /* ISI SURAT */
        .content {
            margin-bottom: 10px;
        }
        .paragraph {
            text-indent: 32px;
            margin-bottom: 8px;
            text-align: justify;
            line-height: 1.3;
        }

        /* DATA MAHASISWA */
        .student-table {
            width: 95%;
            margin-left: 20px;
            margin-top: 4px;
            margin-bottom: 10px;
            border-collapse: collapse;
        }
        .student-table td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 10.5pt;
        }
        .st-num { width: 4%; text-align: center; }
        .st-label { width: 24%; }
        .st-sep { width: 3%; }
        .st-content { width: 69%; font-weight: bold; }

        /* TANDA TANGAN & VALIDASI */
        .signature-table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .signature-table td {
            vertical-align: top;
            padding: 0;
        }
        .signature-col-left {
            width: 45%;
            text-align: center;
            vertical-align: bottom;
            padding-bottom: 8px;
        }
        .signature-col-right {
            width: 55%;
            text-align: center;
        }
        .ttd-img-box {
            height: 60px;
            margin: 3px 0;
            text-align: center;
        }
        .ttd-img {
            max-height: 58px;
            max-width: 160px;
            display: inline-block;
            vertical-align: middle;
        }
        .ttd-space {
            height: 58px;
        }
        
        .qr-box {
            display: inline-block;
            text-align: center;
            padding: 4px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            background: #fff;
        }
        .qr-caption {
            font-size: 7.5pt;
            color: #555;
            margin-top: 2px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .bold { font-weight: bold; }
        .underline { text-decoration: underline; }
    </style>
</head>
<body>

    @include('pdf.partials.kop_admin_instansi', ['instansi' => $app->position->instansi])

    <div class="date-section">
        Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
    </div>

    <div class="info-container">
        <table class="info-table">
            <tr>
                <td class="col-left-label">Nomor</td>
                <td class="col-left-sep">:</td>
                <td class="col-left-content">
                    800/{{ str_pad($app->id, 3, '0', STR_PAD_LEFT) }}-Sekr/{{ $app->position->instansi->singkatan ?? 'INSTANSI' }}/{{ \Carbon\Carbon::now()->format('m') }}/{{ date('Y') }}
                </td>

                <td class="col-right" rowspan="4" style="vertical-align: top;">
                    Kepada Yth.<br>
                    <strong>Pimpinan / Dekan</strong><br>
                    {{ $app->user->asal_instansi ?? ($app->user->university->name ?? ($app->user->school->name ?? 'Institusi Terkait')) }}<br>
                    di -<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tempat
                </td>
            </tr>
            <tr>
                <td class="col-left-label">Sifat</td>
                <td class="col-left-sep">:</td>
                <td class="col-left-content">Biasa / Resmi</td>
            </tr>
            <tr>
                <td class="col-left-label">Lampiran</td>
                <td class="col-left-sep">:</td>
                <td class="col-left-content">-</td>
            </tr>
            <tr>
                <td class="col-left-label">Hal</td>
                <td class="col-left-sep">:</td>
                <td class="col-left-content" style="font-weight: bold;">Surat Balasan Persetujuan Magang</td>
            </tr>
        </table>
    </div>

    <div class="content">
        <p class="paragraph">
            Sehubungan dengan surat permohonan dari <strong>{{ $app->user->asal_instansi ?? 'Institusi Pemohon' }}</strong> perihal Permohonan Kesediaan Menerima Praktik Kerja Lapangan (PKL) / Magang Mahasiswa/i.
        </p>

        <p class="paragraph">
            Berkaitan dengan hal tersebut di atas, maka <strong>{{ $app->position->instansi->nama_dinas }}</strong> Pemerintah Kota Banjarmasin pada prinsipnya <strong>MENYETUJUI / MENERIMA</strong> pelaksanaan Magang / PKL atas nama:
        </p>

        <table class="student-table">
            <tr>
                <td class="st-num">1.</td>
                <td class="st-label">Nama Lengkap</td>
                <td class="st-sep">:</td>
                <td class="st-content">{{ $app->user->name }}</td>
            </tr>
            <tr>
                <td class="st-num"></td>
                <td class="st-label">NIM / NPM / NISN</td>
                <td class="st-sep">:</td>
                <td class="st-content" style="font-weight: normal;">{{ $app->user->nik ?? ($app->user->nim ?? '-') }}</td>
            </tr>
            <tr>
                <td class="st-num"></td>
                <td class="st-label">Program Studi / Jurusan</td>
                <td class="st-sep">:</td>
                <td class="st-content" style="font-weight: normal;">{{ $app->user->majorDetail?->name ?? ($app->user->major ?? '-') }}</td>
            </tr>
            <tr>
                <td class="st-num"></td>
                <td class="st-label">Posisi / Bidang</td>
                <td class="st-sep">:</td>
                <td class="st-content" style="font-weight: normal;">{{ $app->position->judul_posisi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="st-num"></td>
                <td class="st-label">Waktu Pelaksanaan</td>
                <td class="st-sep">:</td>
                <td class="st-content" style="font-weight: normal;">
                    {{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d F Y') }} s.d. {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d F Y') }}
                </td>
            </tr>
        </table>

        <p class="paragraph">
            Selama mengikuti program magang tersebut, diharapkan peserta yang bersangkutan dapat mematuhi segala ketentuan, jam kerja kedinasan, dan tata tertib yang berlaku pada lingkungan <strong>{{ $app->position->instansi->nama_dinas }}</strong>.
        </p>

        <p class="paragraph">
            Demikian surat persetujuan ini disampaikan untuk dapat dipergunakan sebagaimana mestinya. Atas perhatian dan kerja samanya, diucapkan terima kasih.
        </p>
    </div>

    @php
        $instansi = $app->position->instansi;
        $jabatan = trim($instansi->jabatan_pejabat ?? 'Kepala Dinas');
        $namaPejabat = $instansi->nama_pejabat ?? '........................................';
        $nipPejabat = $instansi->nip_pejabat ?? '....................';
        $isKepala = stripos($jabatan, 'kepala dinas') !== false 
            || stripos($jabatan, 'kepala badan') !== false 
            || stripos($jabatan, 'kepala kantor') !== false 
            || stripos($jabatan, 'direktur') !== false 
            || stripos($jabatan, 'camat') !== false 
            || stripos($jabatan, 'lurah') !== false;
        
        $ttdPath = null;
        if (!empty($instansi->ttd_kepala)) {
            $rawPath = $instansi->ttd_kepala;
            if (\Illuminate\Support\Facades\Storage::disk('private')->exists($rawPath)) {
                $ttdPath = \Illuminate\Support\Facades\Storage::disk('private')->path($rawPath);
            } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($rawPath)) {
                $ttdPath = \Illuminate\Support\Facades\Storage::disk('public')->path($rawPath);
            } elseif (file_exists(storage_path('app/public/' . $rawPath))) {
                $ttdPath = storage_path('app/public/' . $rawPath);
            } elseif (file_exists(public_path('storage/' . $rawPath))) {
                $ttdPath = public_path('storage/' . $rawPath);
            }
        }
    @endphp

    <table class="signature-table">
        <tr>
            {{-- QR Code Validasi di Kiri Bawah --}}
            <td class="signature-col-left">
                <div class="qr-box">
                    <img src="data:image/svg+xml;base64, {{ base64_encode(QrCode::format('svg')->size(52)->generate(route('id_card.verify', $app->token_verifikasi ?? 'invalid'))) }}" style="display: block; margin: 0 auto; width: 52px; height: 52px;">
                    <div class="qr-caption">Scan Validasi Dokumen</div>
                </div>
            </td>

            {{-- Blok Tanda Tangan Pejabat di Kanan Bawah --}}
            <td class="signature-col-right">
                @if(!$isKepala && !empty($jabatan))
                    <div style="font-weight: normal; font-size: 10pt; text-transform: uppercase;">a.n. KEPALA DINAS</div>
                    <div style="font-weight: bold; font-size: 10.5pt; text-transform: uppercase;">{{ $jabatan }}</div>
                @else
                    <div style="font-weight: bold; font-size: 10.5pt; text-transform: uppercase;">{{ $jabatan }}</div>
                @endif
                <div style="font-size: 9.5pt; font-weight: bold; text-transform: uppercase;">{{ $instansi->nama_dinas }}</div>
                
                <div class="ttd-img-box">
                    @if($ttdPath && file_exists($ttdPath))
                        <img src="{{ $ttdPath }}" class="ttd-img" alt="TTD">
                    @else
                        <div class="ttd-space"></div>
                    @endif
                </div>
                
                <div class="bold underline" style="text-transform: uppercase; font-size: 10.5pt; margin-bottom: 2px;">
                    {{ $namaPejabat }}
                </div>
                @if(!empty($instansi->pangkat_pejabat))
                    <div style="font-size: 9.5pt;">{{ $instansi->pangkat_pejabat }}</div>
                @endif
                <div style="font-size: 9.5pt;">NIP. {{ $nipPejabat }}</div>
            </td>
        </tr>
    </table>

</body>
</html>