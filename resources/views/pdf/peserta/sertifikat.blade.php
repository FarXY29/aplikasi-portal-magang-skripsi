<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat Magang - {{ $app->user->name }}</title>
    <style>
        /* 1. Margin Halaman A4 Landscape (Full Edge-to-Edge Background) */
        @page {
            size: A4 landscape;
            margin: 0;
        }

        /* 2. Reset Body */
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: "Times New Roman", Times, serif;
            color: #1e293b;
            background-image: url('{{ public_path("images/certificate_frame.png") }}');
            background-size: 100% 100%;
            background-repeat: no-repeat;
            background-position: center;
        }

        /* 3. Container Utama (Mengisi area & terdistribusi seimbang dari atas ke bawah) */
        .container {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            box-sizing: border-box; 
            padding: 68px 110px 55px 110px;
            text-align: center;
        }

        /* 4. Kop Instansi */
        .header-text {
            font-size: 19pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #0F766E; /* Dark Teal */
            margin-top: 0px;
            margin-bottom: 4px;
            line-height: 1.2;
        }

        .sub-header-text {
            font-size: 13.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #334155;
            margin-bottom: 12px;
        }

        .divider-line {
            width: 50%;
            height: 2px;
            background-color: #0F766E;
            margin: 0 auto 14px auto;
        }

        /* 5. Judul Sertifikat & Nomor */
        .title {
            font-size: 32pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #0F766E;
            margin: 8px 0 4px 0;
            font-family: "Helvetica", "Arial", sans-serif;
            letter-spacing: 3px;
        }

        .nomor-surat {
            font-size: 11pt;
            margin-bottom: 18px;
            color: #475569;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        /* 6. Isi Teks & Nama Peserta */
        .content-subtitle {
            font-size: 12.5pt;
            font-style: italic;
            margin: 6px 0;
            color: #475569;
        }

        .candidate-wrapper {
            margin: 12px 0 16px 0;
        }

        .candidate-name {
            font-size: 27pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: inline-block;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 3px;
        }

        .content-text {
            font-size: 12.5pt;
            margin: 8px 0;
            color: #1e293b;
            line-height: 1.4;
        }

        .predikat-label {
            font-weight: bold;
            color: #0F766E;
            text-transform: uppercase;
        }

        .duration-text {
            font-size: 11.5pt;
            font-style: italic;
            margin-top: 6px;
            margin-bottom: 32px;
            color: #475569;
        }

        /* 7. Layout Tabel Tanda Tangan & QR Code */
        .signature-section {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
        }

        .signature-section td {
            vertical-align: top;
            text-align: center;
            font-size: 10.5pt;
            color: #1e293b;
            line-height: 1.35;
        }

        .sign-space {
            height: 52px;
        }

        .sign-name {
            font-weight: bold;
            display: inline-block;
            border-bottom: 1px solid #1e293b;
            padding-bottom: 1px;
            margin-bottom: 2px;
        }

        .qr-box {
            display: inline-block;
            padding: 5px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }

        .qr-label {
            font-size: 7.5pt;
            font-weight: bold;
            color: #64748b;
            display: block;
            margin-top: 4px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    <div class="container">
        
        {{-- Kop Dinas --}}
        <div class="header-text">PEMERINTAH KOTA BANJARMASIN</div>
        <div class="sub-header-text">{{ $app->position->instansi->nama_dinas }}</div>
        <div class="divider-line"></div>
        
        {{-- Judul & Nomor Sertifikat --}}
        <div class="title">SERTIFIKAT MAGANG</div>
        <div class="nomor-surat">
            Nomor: {{ $app->nomor_sertifikat ?? 'Draft' }}
        </div>

        {{-- Penerima Sertifikat --}}
        <p class="content-subtitle">Diberikan apresiasi setinggi-tingginya kepada:</p>

        <div class="candidate-wrapper">
            <span class="candidate-name">{{ $app->user->name }}</span>
        </div>
        
        {{-- Keterangan Kelulusan --}}
        <p class="content-text">
            Telah menyelesaikan program Praktek Kerja Lapangan (PKL) / Magang dengan hasil evaluasi
            <span class="predikat-label">
                "{{ $app->nilai_rata_rata >= 85 ? 'SANGAT BAIK' : ($app->nilai_rata_rata >= 75 ? 'BAIK' : ($app->nilai_rata_rata >= 60 ? 'CUKUP' : 'KURANG')) }}"
            </span>
        </p>

        <p class="duration-text">
            Dilaksanakan mulai tanggal {{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d F Y') }} 
            sampai dengan {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d F Y') }}.
        </p>

        @php
            $instansi = $app->position->instansi; 
        @endphp

        {{-- Tabel Tanda Tangan & QR Code --}}
        <table class="signature-section">
            <tr>
                {{-- Left: Pejabat / Kepala Dinas --}}
                <td style="width: 38%;">
                    Mengetahui,<br>
                    <span style="font-weight: bold;">{{ $instansi->jabatan_pejabat ?? 'Kepala Dinas' }}</span>
                    
                    @if($instansi->ttd_kepala && file_exists(public_path('storage/' . $instansi->ttd_kepala)))
                        <div style="margin: 4px 0;">
                            <img src="{{ public_path('storage/' . $instansi->ttd_kepala) }}" style="height: 52px; width: auto; display: block; margin: 0 auto;">
                        </div>
                    @else
                        <div class="sign-space"></div>
                    @endif

                    <span class="sign-name">
                        {{ $instansi->nama_pejabat ?? '................................' }}
                    </span><br>
                    <span style="font-size: 9.5pt; color: #475569;">NIP. {{ $instansi->nip_pejabat ?? '....................' }}</span>
                </td>

                {{-- Center: QR Code Scan Validasi --}}
                <td style="width: 24%; vertical-align: top; padding-top: 5px;">
                    <div class="qr-box">
                        <img src="data:image/svg+xml;base64, {{ base64_encode(QrCode::format('svg')->size(65)->generate(route('certificate.verify', $app->token_verifikasi ?? 'invalid'))) }}" style="display: block; margin: 0 auto; width: 65px; height: 65px;">
                    </div>
                    <span class="qr-label">Scan Validasi</span>
                </td>

                {{-- Right: Pembimbing Lapangan --}}
                <td style="width: 38%;">
                    Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                    <span style="font-weight: bold;">Pembimbing Lapangan</span>
                    
                    @if($app->pembimbing_lapangan && $app->pembimbing_lapangan->signature && file_exists(public_path('storage/' . $app->pembimbing_lapangan->signature)))
                        <div style="margin: 4px 0;">
                            <img src="{{ public_path('storage/' . $app->pembimbing_lapangan->signature) }}" style="height: 52px; width: auto; display: block; margin: 0 auto;">
                        </div>
                    @else
                        <div class="sign-space"></div>
                    @endif

                    <span class="sign-name">
                        {{ $app->pembimbing_lapangan->name ?? '................................' }}
                    </span><br>
                    <span style="font-size: 9.5pt; color: #475569;">NIP/NIK. {{ $app->pembimbing_lapangan->nomor_induk ?? '-' }}</span>
                </td>
            </tr>
        </table>

    </div>

</body>
</html>