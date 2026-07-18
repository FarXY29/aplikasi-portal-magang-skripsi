<!DOCTYPE html>
<html>
<head>
    <title>Sertifikat Magang</title>
    <style>
        /* 1. Atur Margin Halaman di sini (Area Cetak Aman) */
        @page {
            size: A4 landscape;
            margin: 0; /* Menghapus margin agar background bisa penuh edge-to-edge */
        }

        /* 2. Reset Body agar tidak ada spasi tambahan */
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: "Times New Roman", serif;
            color: #333;
            background-image: url('{{ public_path("images/certificate_frame.png") }}');
            background-size: 100% 100%;
            background-repeat: no-repeat;
            background-position: center;
        }

        /* 3. Container Utama (Isi Konten) */
        .container {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            box-sizing: border-box; 
            padding: 55px 120px; /* Memberikan ruang ekstra dari border */
            text-align: center;
        }

        /* 4. Typography &amp; Spacing (Diperpadat &amp; Lebih Premium) */
        .header-text {
            font-size: 18pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 2px;
            color: #0F766E; /* Dark Teal matching the frame */
            line-height: 1.2;
            margin-top: 15px;
        }

        .sub-header-text {
            font-size: 13pt;
            font-weight: normal;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            color: #475569;
        }

        .title {
            font-size: 32pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #0F766E; /* Dark Teal */
            margin: 10px 0 2px 0;
            font-family: "Helvetica", sans-serif;
            letter-spacing: 2px;
        }

        .nomor-surat {
            font-size: 10pt;
            margin-bottom: 15px;
            color: #475569;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .content-text {
            font-size: 12pt;
            margin: 4px 0;
            color: #1e293b;
        }

        .candidate-name {
            font-size: 26pt;
            font-weight: bold;
            margin: 12px 0;
            color: #1a202c;
            text-transform: uppercase;
            font-family: "Times New Roman", serif;
            letter-spacing: 1px;
        }

        .predikat-label {
            font-weight: bold;
            color: #0F766E;
            text-transform: uppercase;
        }

        .duration-text {
            font-size: 11.5pt;
            font-style: italic;
            margin-bottom: 20px;
            color: #475569;
        }

        /* 5. Layout Tanda Tangan */
        .signature-section {
            width: 100%;
            margin-top: 5px;
            border-collapse: collapse; /* Menggunakan tabel agar lebih stabil */
        }
        .signature-section td {
            vertical-align: top;
            text-align: center;
            font-size: 10.5pt;
            color: #1e293b;
        }
        .sign-space {
            height: 60px; /* Ruang tanda tangan pas */
        }
    </style>
</head>
<body>

    <div class="container">
        
        <div class="header-text">PEMERINTAH KOTA BANJARMASIN</div>
        <div class="sub-header-text">{{ $app->position->instansi->nama_dinas }}</div>
        
        <div class="title">SERTIFIKAT MAGANG</div>
        <div class="nomor-surat">
            Nomor: {{ $app->nomor_sertifikat ?? 'Draft' }}
        </div>

        <p class="content-text">Diberikan apresiasi setinggi-tingginya kepada:</p>

        <div class="candidate-name"><u>{{ $app->user->name }}</u></div>
        
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

        <table class="signature-section">
            <tr>
                {{-- Kolom Kiri: Kepala Dinas / Pejabat --}}
                <td style="width: 40%;">
                    Mengetahui,<br>
                    <span style="font-weight: bold;">{{ $instansi->jabatan_pejabat ?? 'Kepala Dinas' }}</span>
                    
                    <br><br>
                    
                    {{-- Tanda Tangan Kepala Dinas --}}
                    @if($instansi->ttd_kepala && file_exists(public_path('storage/' . $instansi->ttd_kepala)))
                        <img src="{{ public_path('storage/' . $instansi->ttd_kepala) }}" style="height: 55px; width: auto; display: block; margin: 0 auto;">
                    @else
                        <div class="sign-space"></div>
                    @endif

                    <span style="font-weight: bold; text-decoration: underline;">
                        {{ $instansi->nama_pejabat ?? '................................' }}
                    </span><br>
                    NIP. {{ $instansi->nip_pejabat ?? '....................' }}
                </td>

                {{-- Kolom Tengah: Scan Validasi QR Code --}}
                <td style="width: 20%; vertical-align: middle; text-align: center; padding-top: 15px;">
                    <div style="display: inline-block; padding: 6px; background: white; border: 1px solid #e2e8f0; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                        <img src="data:image/svg+xml;base64, {{ base64_encode(QrCode::format('svg')->size(70)->generate(route('certificate.verify', $app->token_verifikasi ?? 'invalid'))) }}" style="display: block; margin: 0 auto;">
                    </div>
                    <span style="font-size: 7.5pt; font-weight: bold; color: #64748b; display: block; margin-top: 6px; letter-spacing: 0.5px;">Scan Validasi</span>
                </td>

                {{-- Kolom Kanan: Pembimbing Lapangan --}}
                <td style="width: 40%;">
                    Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                    Pembimbing Lapangan
                    
                    <br><br>

                    {{-- Tanda Tangan Pembimbing Lapangan --}}
                    @if($app->pembimbing_lapangan && $app->pembimbing_lapangan->signature && file_exists(public_path('storage/' . $app->pembimbing_lapangan->signature)))
                        <img src="{{ public_path('storage/' . $app->pembimbing_lapangan->signature) }}" style="height: 55px; width: auto; display: block; margin: 0 auto;">
                    @else
                        <div class="sign-space"></div>
                    @endif

                    <span style="font-weight: bold; text-decoration: underline;">
                        {{ $app->pembimbing_lapangan->name ?? '................................' }}
                    </span><br>
                    NIP/NIK. {{ $app->pembimbing_lapangan->nomor_induk ?? '-' }}
                </td>
            </tr>
        </table>

    </div>

</body>
</html>