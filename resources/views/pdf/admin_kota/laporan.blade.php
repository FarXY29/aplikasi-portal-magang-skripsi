<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Statistik Rekapitulasi Magang Kota</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm 1.5cm 1.5cm;
            size: A4 portrait;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 10pt;
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
            background-color: #f3f4f6;
            border-left: 3px solid #0d9488;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .stats-table td {
            border: 1px solid #555;
            padding: 5px 3px;
            text-align: center;
        }
        .stats-table .label {
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #555;
            font-weight: bold;
        }
        .stats-table .value {
            font-size: 11pt;
            font-weight: bold;
            color: #111;
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
            background-color: #e5e7eb;
            text-align: center;
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
    </style>
</head>
<body>

    @include('pdf.partials.kop_admin_kota')

    <div class="judul-laporan">LAPORAN REKAPITULASI PROGRAM MAGANG KOTA BANJARMASIN</div>

    <table class="meta-info">
        <tr>
            <td style="width: 55%;">
                <strong>Dicetak Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
                <strong>Pencetak:</strong> {{ Auth::user()->name ?? 'Super Admin' }} (Super Admin Kota)
            </td>
            <td style="width: 45%; text-align: right; vertical-align: top;">
                @if(isset($request) && $request->filled('search'))
                    <strong>Filter Pencarian:</strong> "{{ $request->search }}" &nbsp;|&nbsp;
                @endif
                <strong>Urutan:</strong> 
                @php
                    $sortLabel = 'Peminat Terbanyak';
                    if(isset($request)) {
                        $sort = $request->sort;
                        if($sort == 'pelamar_asc') $sortLabel = 'Peminat Tersedikit';
                        elseif($sort == 'name_asc') $sortLabel = 'Nama Instansi (A - Z)';
                        elseif($sort == 'name_desc') $sortLabel = 'Nama Instansi (Z - A)';
                        elseif($sort == 'lowongan_desc') $sortLabel = 'Lowongan Terbanyak';
                        elseif($sort == 'lowongan_asc') $sortLabel = 'Lowongan Tersedikit';
                        elseif($sort == 'seleksi_desc') $sortLabel = 'Rasio Kelulusan Tertinggi';
                        elseif($sort == 'seleksi_asc') $sortLabel = 'Rasio Kelulusan Terendah';
                    }
                @endphp
                {{ $sortLabel }}
            </td>
        </tr>
    </table>

    {{-- Ringkasan Statistik --}}
    <div class="section-title">Ringkasan Statistik Kota</div>
    <table class="stats-table">
        <tr>
            <td style="width: 16.66%">
                <div class="label">Total Instansi</div>
                <div class="value" style="color: #0f766e;">{{ $stats['total_instansi'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Lowongan Aktif</div>
                <div class="value" style="color: #1d4ed8;">{{ $stats['total_lowongan'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Total Pelamar</div>
                <div class="value" style="color: #4f46e5;">{{ $stats['total_pelamar'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Diterima/Selesai</div>
                <div class="value" style="color: #15803d;">{{ $stats['total_diterima'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Rasio Kelulusan</div>
                <div class="value" style="color: #b45309;">{{ $stats['avg_seleksi_rate'] }}%</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Instansi Terfavorit</div>
                <div class="value" style="color: #be123c; font-size: 7.5pt; line-height: 1.1; font-weight: bold;" title="{{ $stats['fav_dinas'] }}">{{ Str::limit($stats['fav_dinas'], 20) }}</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Utama --}}
    <div class="section-title">Data Statistik Instansi</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%">No</th>
                <th style="width: 38%">Nama Instansi / Dinas</th>
                <th style="width: 13%">Lowongan Aktif</th>
                <th style="width: 13%">Total Pelamar</th>
                <th style="width: 13%">Diterima / Selesai</th>
                <th style="width: 11%">Tingkat Seleksi</th>
                <th style="width: 8%">Rasio Peminat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $index => $data)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-bold">{{ $data['nama_dinas'] }}</td>
                    <td class="text-center">{{ $data['lowongan_aktif'] }} Posisi</td>
                    <td class="text-center">{{ $data['total_pelamar'] }} Orang</td>
                    <td class="text-center">{{ $data['total_magang'] }} Orang</td>
                    <td class="text-center text-bold" style="color: #0f766e;">{{ $data['seleksi_rate'] }}%</td>
                    <td class="text-center" style="font-style: italic; color: #555;">{{ $data['avg_peminat'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 15px;">Tidak ada data statistik ditemukan.</td>
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
