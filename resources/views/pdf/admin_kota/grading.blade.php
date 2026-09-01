<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Evaluasi &amp; Penilaian Peserta</title>
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
            vertical-align: top;
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
        
        .predikat-sangat-baik { color: #16a34a; font-weight: bold; }
        .predikat-baik { color: #2563eb; font-weight: bold; }
        .predikat-cukup { color: #d97706; font-weight: bold; }
        .predikat-kurang { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>

    @include('pdf.partials.kop_admin_kota')

    <div class="judul-laporan">{{ $title ?? 'LAPORAN EVALUASI & PENILAIAN PESERTA MAGANG' }}</div>

    <table class="meta-info">
        <tr>
            <td style="width: 50%;">
                <strong>Dicetak Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
                <strong>Pencetak:</strong> {{ Auth::user()->name ?? 'Super Admin' }} (Super Admin Kota)
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <strong>Asal Kampus:</strong> {{ $request->instansi ?: 'Semua' }} &nbsp;|&nbsp;
                <strong>Lokasi Dinas:</strong> {{ $request->instansi_id ? 'Filter Terpilih' : 'Semua Dinas' }} &nbsp;|&nbsp;
                <strong>Predikat:</strong> {{ $request->predikat ?: 'Semua' }}
            </td>
        </tr>
    </table>

    {{-- Ringkasan Statistik --}}
    <div class="section-title">Ringkasan Statistik Kompetensi</div>
    <table class="stats-table">
        <tr>
            <td style="width: 16.66%">
                <div class="label">Total Dinilai</div>
                <div class="value">{{ $stats['total'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Sangat Baik</div>
                <div class="value predikat-sangat-baik">{{ $stats['sangat_baik'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Baik</div>
                <div class="value predikat-baik">{{ $stats['baik'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Cukup</div>
                <div class="value predikat-cukup">{{ $stats['cukup'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Kurang</div>
                <div class="value predikat-kurang">{{ $stats['kurang'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Rerata Nilai</div>
                <div class="value" style="color: #0d9488;">{{ $stats['avg_nilai'] }}</div>
            </td>
        </tr>
    </table>

    <table class="stats-table" style="margin-top: -6px;">
        <tr>
            <td style="width: 33.33%">
                <span class="label">Rerata Kompetensi Teknis:</span> <strong>{{ $statsGlobal['avg_teknis'] ?? '-' }}/100</strong>
            </td>
            <td style="width: 33.33%">
                <span class="label">Rerata Kedisiplinan:</span> <strong>{{ $statsGlobal['avg_disiplin'] ?? '-' }}/100</strong>
            </td>
            <td style="width: 33.33%">
                <span class="label">Rerata Perilaku / Soft Skill:</span> <strong>{{ $statsGlobal['avg_perilaku'] ?? '-' }}/100</strong>
            </td>
        </tr>
    </table>

    {{-- Tabel Utama --}}
    <div class="section-title">Data Pemeringkatan &amp; Analisis Performa</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%">Rank</th>
                <th style="width: 25%">Nama Peserta &amp; Asal Kampus</th>
                <th style="width: 25%">Penempatan Dinas &amp; Posisi</th>
                <th style="width: 21%">Aspek (Teknis / Disiplin / Perilaku)</th>
                <th style="width: 12%">Nilai Akhir</th>
                <th style="width: 12%">Predikat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($gradedList as $index => $data)
                <tr>
                    <td class="text-center text-bold">{{ $index + 1 }}</td>
                    <td>
                        <strong class="text-bold">{{ $data['nama'] }}</strong><br>
                        <span style="font-size: 7.5pt; color: #555;">{{ $data['asal_instansi'] }}</span>
                    </td>
                    <td>
                        <strong class="text-bold">{{ $data['instansi'] }}</strong><br>
                        <span style="font-size: 7.5pt; color: #555;">{{ $data['posisi'] }}</span>
                    </td>
                    <td class="text-center">
                        <span style="color: #2563eb;">{{ $data['teknis'] }}</span> /
                        <span style="color: #7c3aed;">{{ $data['disiplin'] }}</span> /
                        <span style="color: #059669;">{{ $data['perilaku'] }}</span>
                    </td>
                    <td class="text-center text-bold" style="font-size: 9.5pt; color: #0d9488;">
                        {{ $data['rata_rata'] }}
                    </td>
                    <td class="text-center text-bold">
                        @php
                            $pClass = match($data['predikat']) {
                                'Sangat Baik' => 'predikat-sangat-baik',
                                'Baik' => 'predikat-baik',
                                'Cukup' => 'predikat-cukup',
                                'Kurang' => 'predikat-kurang',
                                default => ''
                            };
                        @endphp
                        <span class="{{ $pClass }}">{{ $data['predikat'] }}</span>
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
