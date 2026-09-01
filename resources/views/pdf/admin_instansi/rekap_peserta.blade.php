<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Peserta Magang</title>
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
        
        .status-aktif { color: #15803d; font-weight: bold; }
        .status-selesai { color: #1d4ed8; font-weight: bold; }
        .status-pending { color: #b45309; font-weight: bold; }
        .status-ditolak { color: #b91c1c; font-weight: bold; }
    </style>
</head>
<body>

    @include('pdf.partials.kop_admin_instansi', ['instansi' => $instansi ?? null])

    <div class="judul-laporan">LAPORAN REKAPITULASI PESERTA MAGANG</div>

    <table class="meta-info">
        <tr>
            <td style="width: 50%;">
                <strong>Instansi:</strong> {{ $instansi->nama_dinas ?? (Auth::user()->instansi->nama_dinas ?? '-') }}<br>
                <strong>Dicetak Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
                <strong>Pencetak:</strong> {{ Auth::user()->name ?? 'Admin Instansi' }}
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <strong>Filter Status:</strong> {{ !empty($request->status) ? ucfirst($request->status) : 'Semua Status' }} &nbsp;|&nbsp; 
                <strong>Asal Instansi:</strong> {{ !empty($request->asal_instansi) ? $request->asal_instansi : 'Semua' }}
                @if(isset($request) && $request->filled('start_date') && $request->filled('end_date'))
                    <br><strong>Periode:</strong> {{ \Carbon\Carbon::parse($request->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('d/m/Y') }}
                @endif
            </td>
        </tr>
    </table>

    {{-- Ringkasan Statistik --}}
    <div class="section-title">Ringkasan Statistik Rekapitulasi Peserta</div>
    <table class="stats-table">
        <tr>
            <td style="width: 16.66%">
                <div class="stat-label">Total Lamaran</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="stat-label">Status Aktif</div>
                <div class="stat-value status-aktif">{{ $stats['aktif'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="stat-label">Status Selesai</div>
                <div class="stat-value status-selesai">{{ $stats['selesai'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="stat-label">Status Pending</div>
                <div class="stat-value status-pending">{{ $stats['pending'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="stat-label">Status Ditolak</div>
                <div class="stat-value status-ditolak">{{ $stats['ditolak'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="stat-label">Kampus Terlibat</div>
                <div class="stat-value" style="color: #4338ca;">{{ $stats['total_kampus'] }}</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Utama --}}
    <div class="section-title">Data Rekapitulasi Peserta Magang</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%">No</th>
                <th style="width: 22%">Nama Peserta &amp; Kontak</th>
                <th style="width: 22%">Asal Sekolah / Perguruan Tinggi</th>
                <th style="width: 23%">Posisi Magang &amp; Pembimbing</th>
                <th style="width: 18%">Periode &amp; Durasi Magang</th>
                <th style="width: 12%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $app)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                        <strong class="text-bold">{{ $app->user->name ?? '-' }}</strong><br>
                        <span style="font-size: 7.5pt; color: #444;">{{ $app->user->email ?? '-' }}</span>
                        @if(!empty($app->user->phone))
                            <br><span style="font-size: 7.5pt; color: #444;">Telp: {{ $app->user->phone }}</span>
                        @endif
                    </td>
                    <td>
                        <strong style="color: #047857;">{{ $app->user->asal_instansi ?? '-' }}</strong><br>
                        <span style="font-size: 7.5pt; color: #444;">{{ $app->user->majorDetail?->name ?? ($app->user->major ?? '-') }}</span>
                    </td>
                    <td>
                        <strong class="text-bold">{{ $app->position->judul_posisi ?? '-' }}</strong>
                        @if($app->pembimbing_lapangan)
                            <br><span style="color: #333; font-size: 7.5pt;">PL: {{ $app->pembimbing_lapangan->name }}</span>
                        @else
                            <br><span style="color: #666; font-size: 7.2pt; font-style: italic;">PL: Belum ditentukan</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }}</strong> s/d<br>
                        <strong>{{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}</strong><br>
                        <span style="color: #0d9488; font-weight: bold; font-size: 7.2pt;">({{ \Carbon\Carbon::parse($app->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($app->tanggal_selesai)) }} Hari)</span>
                    </td>
                    <td class="text-center text-bold">
                        @php
                            $statusVal = $app->status instanceof \UnitEnum ? $app->status->value : $app->status;
                        @endphp
                        <span class="status-{{ $statusVal === 'diterima' ? 'aktif' : ($statusVal === 'selesai' ? 'selesai' : (in_array($statusVal, ['pending', 'menunggu']) ? 'pending' : 'ditolak')) }}">
                            {{ $statusVal === 'diterima' ? 'Aktif' : ($statusVal === 'selesai' ? 'Selesai' : (in_array($statusVal, ['pending', 'menunggu']) ? 'Pending' : ucfirst($statusVal))) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 15px;">Tidak ada data peserta magang yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Blok Tanda Tangan --}}
    @include('pdf.partials.ttd_admin_instansi', ['instansi' => $instansi ?? null])

    {{-- Penomoran Halaman & Catatan Kaki --}}
    @include('pdf.partials.footer_page_number')

</body>
</html>