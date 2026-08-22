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
            margin: 14px 0 6px 0; 
            padding: 3px 6px;
            background-color: #f3f4f6;
            border-left: 3px solid #ea580c;
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
        .text-green { color: #16a34a; }
        .text-red { color: #dc2626; }
        .text-orange { color: #ea580c; }
        .text-blue { color: #2563eb; }
        
        .sub-detail span { display: inline-block; background: #f3f4f6; padding: 1px 4px; border: 1px solid #ddd; border-radius: 2px; margin: 1px; font-size: 7pt; }
    </style>
</head>
<body>

    @include('pdf.partials.kop_admin_instansi')

    <div class="judul-laporan">LAPORAN DEMOGRAFI ASAL KAMPUS / SEKOLAH PENDAFTAR MAGANG</div>

    <table class="meta-info">
        <tr>
            <td style="width: 50%;">
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
                <div class="label">Asal Kampus</div>
                <div class="value">{{ $stats['total_kampus'] }}</div>
            </td>
            <td style="width: 20%">
                <div class="label">Total Jurusan</div>
                <div class="value">{{ $stats['total_jurusan'] }}</div>
            </td>
            <td style="width: 20%">
                <div class="label">Total Pelamar</div>
                <div class="value">{{ $stats['total_pelamar'] }}</div>
            </td>
            <td style="width: 20%">
                <div class="label">Diterima</div>
                <div class="value text-green">{{ $stats['total_diterima'] }}</div>
            </td>
            <td style="width: 20%">
                <div class="label">Selesai / Lulus</div>
                <div class="value" style="color: #059669;">{{ $stats['total_selesai'] }}</div>
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
                    <td class="text-center text-bold text-green">{{ $data['diterima'] }}</td>
                    <td class="text-center text-bold" style="color: #059669;">{{ $data['selesai'] }}</td>
                    <td class="text-center text-bold text-red">{{ $data['ditolak'] }}</td>
                    <td class="text-center text-bold text-orange">{{ $data['pending'] }}</td>
                    <td class="text-center text-bold" style="
                        @if($data['acceptance_rate'] >= 70) color: #16a34a;
                        @elseif($data['acceptance_rate'] >= 40) color: #ca8a04;
                        @else color: #dc2626;
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
                <tr style="background-color: #f3f4f6; font-weight: bold;">
                    <td colspan="2" class="text-right">TOTAL KESELURUHAN</td>
                    <td class="text-center">{{ $t_pelamar }}</td>
                    <td class="text-center text-green">{{ $t_diterima }}</td>
                    <td class="text-center" style="color: #059669;">{{ $t_selesai }}</td>
                    <td class="text-center text-red">{{ $t_ditolak }}</td>
                    <td class="text-center text-orange">{{ $t_pending }}</td>
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
                <td class="text-center text-bold text-green">{{ $data['diterima'] }}</td>
                <td class="text-center text-bold" style="
                    @if($data['acceptance_rate'] >= 70) color: #16a34a;
                    @elseif($data['acceptance_rate'] >= 40) color: #ca8a04;
                    @else color: #dc2626;
                    @endif
                ">{{ $data['acceptance_rate'] }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- Blok Tanda Tangan --}}
    @include('pdf.partials.ttd_admin_instansi')

    {{-- Penomoran Halaman & Catatan Kaki --}}
    @include('pdf.partials.footer_page_number')

</body>
</html>
