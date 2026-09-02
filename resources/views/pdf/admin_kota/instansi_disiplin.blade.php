<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kedisiplinan Instansi</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm 1.5cm 1.5cm;
            size: A4 portrait;
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
        
        .section-title { 
            font-size: 9.5pt;
            font-weight: bold;
            margin: 12px 0 6px 0; 
            padding: 3px 6px;
            background-color: #f1f5f9;
            border-left: 3px solid #000;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .stats-table td {
            border: 1px solid #444;
            padding: 5px 3px;
            text-align: center;
        }
        .stats-table .label {
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #444;
            font-weight: bold;
        }
        .stats-table .value {
            font-size: 11pt;
            font-weight: bold;
            color: #000;
            margin-top: 1px;
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
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        
        .status-sangat { color: #16a34a; font-weight: bold; }
        .status-cukup { color: #2563eb; font-weight: bold; }
        .status-kurang { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>

    @include('pdf.partials.kop_admin_kota')

    <div class="judul-laporan">{{ $title ?? 'LAPORAN KEDISIPLINAN INSTANSI' }}</div>

    <table class="meta-info">
        <tr>
            <td style="width: 50%;">
                <strong>Dicetak Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
                <strong>Pencetak:</strong> {{ Auth::user()->name ?? 'Super Admin' }} (Super Admin Kota)
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <strong>Pencarian Instansi:</strong> {{ $request->q ?: 'Semua' }} &nbsp;|&nbsp;
                <strong>Filter Kategori:</strong> {{ $request->disiplin_range ? strtoupper($request->disiplin_range) : 'Semua Kategori' }}
            </td>
        </tr>
    </table>

    {{-- Ringkasan Statistik --}}
    <div class="section-title">Ringkasan Statistik Kedisiplinan Kota</div>
    <table class="stats-table">
        <tr>
            <td style="width: 16.66%">
                <div class="label">Total Instansi</div>
                <div class="value">{{ $stats['total_instansi'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Rerata Disiplin</div>
                <div class="value" style="color: #0d9488;">{{ $stats['avg_disiplin'] }}%</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Total Absensi</div>
                <div class="value" style="color: #2563eb;">{{ $stats['total_kehadiran'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Total Pelanggaran</div>
                <div class="value" style="color: #dc2626;">{{ $stats['total_pelanggaran'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Total Terlambat</div>
                <div class="value" style="color: #d97706;">{{ $stats['total_terlambat'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Total Alpa</div>
                <div class="value" style="color: #7c3aed;">{{ $stats['total_alpa'] }}</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Utama --}}
    <div class="section-title">Data Peringkat Kedisiplinan &amp; Kepatuhan Absensi</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%">Rank</th>
                <th style="width: 37%">Nama Dinas Instansi / Unit Kerja</th>
                <th style="width: 14%">Total Kehadiran</th>
                <th style="width: 14%">Terlambat</th>
                <th style="width: 14%">Alpa</th>
                <th style="width: 16%">Tingkat Disiplin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($instansis as $index => $data)
                <tr>
                    <td class="text-center text-bold">{{ $index + 1 }}</td>
                    <td>
                        <strong class="text-bold">{{ $data->nama_dinas }}</strong><br>
                        <span style="font-size: 7.5pt; color: #555;">Jam Masuk: {{ $data->jam_mulai_masuk ?: '08:00:00' }}</span>
                    </td>
                    <td class="text-center">{{ $data->total_attendances }} Kali</td>
                    <td class="text-center" style="color: #d97706; font-weight: bold;">{{ $data->total_terlambat }}x</td>
                    <td class="text-center" style="color: #dc2626; font-weight: bold;">{{ $data->total_alpa }}x</td>
                    <td class="text-center text-bold">
                        @php
                            $disClass = '';
                            if ($data->tingkat_disiplin >= 90) {
                                $disClass = 'status-sangat';
                            } elseif ($data->tingkat_disiplin >= 70) {
                                $disClass = 'status-cukup';
                            } else {
                                $disClass = 'status-kurang';
                            }
                        @endphp
                        <span class="{{ $disClass }}">{{ number_format($data->tingkat_disiplin, 1) }}%</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 15px;">Tidak ada data ditemukan.</td>
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
