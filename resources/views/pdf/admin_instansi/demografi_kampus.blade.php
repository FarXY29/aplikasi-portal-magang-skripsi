<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Demografi Asal Kampus / Sekolah</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm 1.5cm 1.5cm;
            size: A4 landscape;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 9pt;
            color: #000;
            line-height: 1.3;
        }
        
        .judul-laporan {
            text-align: center;
            margin: 8px 0 10px 0;
            font-weight: bold;
            font-size: 11.5pt;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 0.5px;
        }
        
        .meta-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 8.5pt;
            color: #222;
        }
        .meta-info td {
            border: none;
            padding: 1.5px 0;
        }
        
        .section-title { 
            font-size: 9pt;
            font-weight: bold;
            margin: 10px 0 5px 0; 
            padding: 3px 6px;
            background-color: #f1f5f9;
            border-left: 3px solid #000;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .stats-table td {
            border: 1px solid #444;
            padding: 4px 2px;
            text-align: center;
        }
        .stats-table .stat-label {
            font-size: 6.8pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #444;
            font-weight: bold;
        }
        .stats-table .stat-value {
            font-size: 10.5pt;
            font-weight: bold;
            color: #000;
            margin-top: 1px;
        }
        
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
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
            padding: 4.5px 5px;
            text-align: left;
            vertical-align: top;
            font-size: 8.2pt;
        }
        table.data-table th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
            font-size: 7.8pt;
            text-transform: uppercase;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        
        .sub-detail span { display: inline-block; background: #f8fafc; padding: 1px 4px; border: 1px solid #ccc; border-radius: 2px; margin: 1px; font-size: 7pt; }
    </style>
</head>
<body>

    @include('pdf.partials.kop_admin_instansi', ['instansi' => $instansi ?? null])

    <div class="judul-laporan">LAPORAN DEMOGRAFI ASAL KAMPUS / SEKOLAH PENDAFTAR MAGANG</div>

    <table class="meta-info">
        <tr>
            <td style="width: 50%;">
                <strong>Instansi:</strong> {{ $instansi->nama_dinas ?? (Auth::user()->instansi->nama_dinas ?? '-') }}<br>
                <strong>Dicetak Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
                <strong>Pencetak:</strong> {{ Auth::user()->name ?? 'Admin Instansi' }}
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <strong>Total Kampus Terlibat:</strong> {{ $stats['total_kampus'] ?? count($demografi) }} Institusi
            </td>
        </tr>
    </table>

    {{-- Ringkasan Statistik --}}
    <div class="section-title">Ringkasan Statistik Demografi</div>
    <table class="stats-table">
        <tr>
            <td style="width: 20%">
                <div class="stat-label">Asal Kampus / Sekolah</div>
                <div class="stat-value">{{ $stats['total_kampus'] }}</div>
            </td>
            <td style="width: 20%">
                <div class="stat-label">Total Jurusan</div>
                <div class="stat-value">{{ $stats['total_jurusan'] }}</div>
            </td>
            <td style="width: 20%">
                <div class="stat-label">Total Pelamar</div>
                <div class="stat-value">{{ $stats['total_pelamar'] }}</div>
            </td>
            <td style="width: 20%">
                <div class="stat-label">Diterima</div>
                <div class="stat-value" style="color: #15803d;">{{ $stats['total_diterima'] }}</div>
            </td>
            <td style="width: 20%">
                <div class="stat-label">Selesai / Lulus</div>
                <div class="stat-value" style="color: #1d4ed8;">{{ $stats['total_selesai'] }}</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Demografi per Kampus --}}
    <div class="section-title">Distribusi Pendaftar per Kampus / Sekolah</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%">No</th>
                <th style="width: 24%">Asal Kampus / Sekolah</th>
                <th style="width: 8%">Pelamar</th>
                <th style="width: 8%">Diterima</th>
                <th style="width: 8%">Selesai</th>
                <th style="width: 8%">Ditolak</th>
                <th style="width: 8%">Pending</th>
                <th style="width: 8%">Rasio</th>
                <th style="width: 24%">Jurusan Pendaftar</th>
            </tr>
        </thead>
        <tbody>
            @php $t_pelamar=0; $t_diterima=0; $t_selesai=0; $t_ditolak=0; $t_pending=0; @endphp
            @forelse($demografi as $kampus => $data)
                @php 
                    $t_pelamar += $data['total_pelamar'];
                    $t_diterima += $data['diterima'];
                    $t_selesai += $data['selesai'];
                    $t_ditolak += $data['ditolak'];
                    $t_pending += $data['pending'];
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                        <strong class="text-bold">{{ $kampus }}</strong>
                        @if($data['peserta']->count() > 0)
                        <div style="margin-top: 2px; font-size: 7pt; color: #15803d;">
                            Peserta: 
                            @foreach($data['peserta'] as $p)
                                {{ $p['nama'] }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </div>
                        @endif
                    </td>
                    <td class="text-center text-bold">{{ $data['total_pelamar'] }}</td>
                    <td class="text-center text-bold" style="color: #15803d;">{{ $data['diterima'] }}</td>
                    <td class="text-center text-bold" style="color: #1d4ed8;">{{ $data['selesai'] }}</td>
                    <td class="text-center text-bold" style="color: #b91c1c;">{{ $data['ditolak'] }}</td>
                    <td class="text-center text-bold" style="color: #b45309;">{{ $data['pending'] }}</td>
                    <td class="text-center text-bold" style="
                        @if($data['acceptance_rate'] >= 70) color: #15803d;
                        @elseif($data['acceptance_rate'] >= 40) color: #b45309;
                        @else color: #b91c1c;
                        @endif
                    ">{{ $data['acceptance_rate'] }}%</td>
                    <td>
                        <div class="sub-detail">
                            @foreach($data['jurusan'] as $jurusan => $count)
                                <span>{{ $jurusan }} ({{ $count }})</span>
                            @endforeach
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 15px;">Tidak ada data pendaftar.</td>
                </tr>
            @endforelse

            @if($demografi->count() > 0)
                <tr style="background-color: #f1f5f9; font-weight: bold;">
                    <td colspan="2" class="text-right">TOTAL KESELURUHAN</td>
                    <td class="text-center">{{ $t_pelamar }}</td>
                    <td class="text-center" style="color: #15803d;">{{ $t_diterima }}</td>
                    <td class="text-center" style="color: #1d4ed8;">{{ $t_selesai }}</td>
                    <td class="text-center" style="color: #b91c1c;">{{ $t_ditolak }}</td>
                    <td class="text-center" style="color: #b45309;">{{ $t_pending }}</td>
                    <td class="text-center" colspan="2">
                        @php $overall = $t_pelamar > 0 ? round(($t_diterima / $t_pelamar) * 100) : 0; @endphp
                        {{ $overall }}%
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- Tabel Demografi per Jurusan --}}
    @if(isset($demografiJurusan) && $demografiJurusan->count() > 0)
    <div class="section-title">Distribusi Pendaftar per Jurusan / Program Studi</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 45%">Jurusan / Program Studi</th>
                <th style="width: 15%">Total Pelamar</th>
                <th style="width: 15%">Diterima</th>
                <th style="width: 20%">Rasio Penerimaan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($demografiJurusan as $jurusan => $data)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-bold">{{ $jurusan }}</td>
                <td class="text-center text-bold">{{ $data['total'] }}</td>
                <td class="text-center text-bold" style="color: #15803d;">{{ $data['diterima'] }}</td>
                <td class="text-center text-bold" style="
                    @if($data['acceptance_rate'] >= 70) color: #15803d;
                    @elseif($data['acceptance_rate'] >= 40) color: #b45309;
                    @else color: #b91c1c;
                    @endif
                ">{{ $data['acceptance_rate'] }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- Blok Tanda Tangan --}}
    @include('pdf.partials.ttd_admin_instansi', ['instansi' => $instansi ?? null])

    {{-- Penomoran Halaman & Catatan Kaki --}}
    @include('pdf.partials.footer_page_number')

</body>
</html>
