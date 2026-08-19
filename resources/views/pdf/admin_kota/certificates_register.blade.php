<!DOCTYPE html>
<html>
<head>
    <title>Buku Register Sertifikat Magang</title>
    <style>
        @page { margin: 1.5cm 2cm; }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 10pt;
            line-height: 1.3;
        }

        .kop-surat {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .kop-logo {
            width: 70px;
            height: auto;
        }
        .kop-text {
            text-align: center;
        }
        .kop-pemerintah {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-instansi {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-alamat {
            font-size: 9pt;
            font-style: italic;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data th, table.data td {
            border: 1px solid #000;
            padding: 5px 6px;
            vertical-align: middle;
            font-size: 9pt;
        }
        table.data th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }

        .ttd-container {
            width: 100%;
            margin-top: 15px;
        }
        .ttd-box-right {
            float: right;
            width: 250px;
            text-align: center;
        }

        .badge-active {
            font-weight: bold;
            color: #065f46;
        }
        .badge-revoked {
            font-weight: bold;
            color: #991b1b;
        }
    </style>
</head>
<body>

    <table class="kop-surat">
        <tr>
            <td style="width: 15%; text-align: center;">
                @if(file_exists(public_path('images/Banjarmasin_Logo.svg.png')))
                    <img src="{{ public_path('images/Banjarmasin_Logo.svg.png') }}" class="kop-logo">
                @endif
            </td>
            <td class="kop-text" style="width: 85%;">
                <div class="kop-pemerintah">PEMERINTAH KOTA BANJARMASIN</div>
                <div class="kop-instansi">BADAN KESATUAN BANGSA DAN POLITIK</div>
                <div class="kop-alamat">Jl. RE Martadinata No. 1, Telp. (0511) 3354444, Banjarmasin, Kalimantan Selatan</div>
            </td>
        </tr>
    </table>

    <div style="text-align: center; margin-bottom: 15px;">
        <h3 style="margin: 0; text-transform: uppercase; font-size: 12pt; text-decoration: underline;">BUKU REGISTER SERTIFIKAT MAGANG RESMI</h3>
        <p style="margin: 3px 0 0 0; font-size: 9pt;">Pemerintah Kota Banjarmasin Tahun {{ date('Y') }}</p>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 18%;">Nomor Sertifikat</th>
                <th style="width: 18%;">Nama Peserta & NIK</th>
                <th style="width: 18%;">Institusi / Jurusan</th>
                <th style="width: 18%;">Instansi Penempatan</th>
                <th style="width: 8%;">Nilai</th>
                <th style="width: 8%;">Status</th>
                <th style="width: 8%;">Tgl Terbit</th>
            </tr>
        </thead>
        <tbody>
            @forelse($certificates as $index => $cert)
                @php
                    $app = $cert->application;
                    $user = $app?->user;
                    $nilai = (float) ($app?->nilai_angka ?? 0);
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="font-family: monospace; font-weight: bold;">{{ $cert->nomor_sertifikat }}</td>
                    <td>
                        <strong>{{ $user?->name ?? '-' }}</strong><br>
                        <span style="font-size: 8pt; color: #555;">NIK: {{ $user?->nik ?? '-' }}</span>
                    </td>
                    <td>
                        {{ $user?->asal_instansi ?? '-' }}<br>
                        <span style="font-size: 8pt; color: #555;">{{ $user?->majorDetail?->name ?? ($user?->major ?? '-') }}</span>
                    </td>
                    <td>
                        {{ $app?->position?->instansi?->nama_dinas ?? '-' }}<br>
                        <span style="font-size: 8pt; color: #555;">{{ $app?->position?->judul_posisi ?? '-' }}</span>
                    </td>
                    <td style="text-align: center;">
                        <strong>{{ number_format($nilai, 1) }}</strong>
                    </td>
                    <td style="text-align: center;">
                        @if($cert->isRevoked())
                            <span class="badge-revoked">DICABUT</span>
                        @else
                            <span class="badge-active">SAH</span>
                        @endif
                    </td>
                    <td style="text-align: center; font-size: 8pt;">
                        {{ $cert->published_at ? $cert->published_at->format('d/m/Y') : ($cert->created_at ? $cert->created_at->format('d/m/Y') : '-') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">Tidak ada data sertifikat yang tercatat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="ttd-container">
        <div class="ttd-box-right">
            <p>Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p style="margin-top: 2px;">{{ $pejabat_jabatan }}</p>
            @if(!empty($ttd_image_path) && file_exists($ttd_image_path))
                <div style="margin: 5px 0;">
                    <img src="{{ $ttd_image_path }}" style="max-height: 60px; max-width: 150px;">
                </div>
            @else
                <br><br><br><br>
            @endif
            <p style="font-weight: bold; text-decoration: underline; margin-bottom: 2px;">{{ $pejabat_nama }}</p>
            <p style="font-size: 8pt; color: #555;">NIP. {{ $pejabat_nip }}</p>
        </div>
    </div>

</body>
</html>
