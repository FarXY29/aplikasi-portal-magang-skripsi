<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Register Sertifikat Magang</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm 1.5cm 1.5cm;
            size: A4 landscape;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 9.5pt;
            color: #111;
            line-height: 1.3;
        }
        
        .judul-laporan {
            text-align: center;
            margin: 10px 0 12px 0;
            font-weight: bold;
            font-size: 12pt;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 0.5px;
        }
        
        .meta-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 8.5pt;
            color: #333;
        }
        .meta-info td {
            border: none;
            padding: 1px 0;
        }
        
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            margin-bottom: 10px;
        }
        table.data-table thead {
            display: table-header-group;
        }
        table.data-table tr {
            page-break-inside: avoid;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #333;
            padding: 5px 6px;
            text-align: left;
            vertical-align: middle;
            font-size: 8.5pt;
        }
        table.data-table th {
            background-color: #e5e7eb;
            text-align: center;
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        
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

    @include('pdf.partials.kop_admin_kota')

    <div class="judul-laporan">BUKU REGISTER SERTIFIKAT MAGANG RESMI</div>

    <table class="meta-info">
        <tr>
            <td style="width: 50%;">
                <strong>Dicetak Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
                <strong>Pencetak:</strong> {{ Auth::user()->name ?? 'Super Admin' }} (Super Admin Kota)
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <strong>Total Sertifikat Tercatat:</strong> {{ count($certificates) }} Lembar
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 18%;">Nomor Sertifikat</th>
                <th style="width: 18%;">Nama Peserta &amp; NIK</th>
                <th style="width: 18%;">Institusi / Jurusan</th>
                <th style="width: 18%;">Instansi Penempatan</th>
                <th style="width: 7%;">Nilai</th>
                <th style="width: 8%;">Status</th>
                <th style="width: 9%;">Tgl Terbit</th>
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
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td style="font-family: monospace; font-weight: bold;">{{ $cert->nomor_sertifikat }}</td>
                    <td>
                        <strong class="text-bold">{{ $user?->name ?? '-' }}</strong><br>
                        <span style="font-size: 7.5pt; color: #555;">NIK: {{ $user?->nik ?? '-' }}</span>
                    </td>
                    <td>
                        {{ $user?->asal_instansi ?? '-' }}<br>
                        <span style="font-size: 7.5pt; color: #555;">{{ $user?->majorDetail?->name ?? ($user?->major ?? '-') }}</span>
                    </td>
                    <td>
                        <strong>{{ $app?->position?->instansi?->nama_dinas ?? '-' }}</strong><br>
                        <span style="font-size: 7.5pt; color: #555;">{{ $app?->position?->judul_posisi ?? '-' }}</span>
                    </td>
                    <td class="text-center text-bold">
                        {{ number_format($nilai, 1) }}
                    </td>
                    <td class="text-center">
                        @if($cert->isRevoked())
                            <span class="badge-revoked">DICABUT</span>
                        @else
                            <span class="badge-active">SAH</span>
                        @endif
                    </td>
                    <td class="text-center" style="font-size: 8pt;">
                        {{ $cert->published_at ? $cert->published_at->format('d/m/Y') : ($cert->created_at ? $cert->created_at->format('d/m/Y') : '-') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 15px;">Tidak ada data sertifikat yang tercatat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Blok Tanda Tangan --}}
    @include('pdf.partials.ttd_admin_kota')

    {{-- Penomoran Halaman & Catatan Kaki --}}
    @include('pdf.partials.footer_page_number')

</body>
</html>
