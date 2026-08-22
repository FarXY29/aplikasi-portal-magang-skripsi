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
        
        .status-aktif { color: #16a34a; font-weight: bold; }
        .status-selesai { color: #2563eb; font-weight: bold; }
        .status-pending { color: #d97706; font-weight: bold; }
        .status-menunggu { color: #d97706; font-weight: bold; }
        .status-ditolak { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>

    @include('pdf.partials.kop_admin_instansi')

    <div class="judul-laporan">LAPORAN REKAPITULASI PENDAFTARAN PESERTA MAGANG</div>

    <table class="meta-info">
        <tr>
            <td style="width: 50%;">
                <strong>Dicetak Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
                <strong>Pencetak:</strong> {{ Auth::user()->name ?? 'Admin Instansi' }}
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <strong>Filter Status:</strong> {{ $request->status ? ucfirst($request->status) : 'Semua Status' }} &nbsp;|&nbsp; 
                <strong>Asal Instansi:</strong> {{ $request->asal_instansi ?: 'Semua' }}
                @if(isset($request) && $request->filled('start_date') && $request->filled('end_date'))
                    <br><strong>Periode:</strong> {{ \Carbon\Carbon::parse($request->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('d/m/Y') }}
                @endif
            </td>
        </tr>
    </table>

    {{-- Ringkasan Statistik --}}
    <div class="section-title">Ringkasan Statistik Rekapitulasi</div>
    <table class="stats-table">
        <tr>
            <td style="width: 16.66%">
                <div class="label">Total Lamaran</div>
                <div class="value">{{ $stats['total'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Status Aktif</div>
                <div class="value status-aktif">{{ $stats['aktif'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Status Selesai</div>
                <div class="value status-selesai">{{ $stats['selesai'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Status Pending</div>
                <div class="value status-pending">{{ $stats['pending'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Status Ditolak</div>
                <div class="value status-ditolak">{{ $stats['ditolak'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Kampus Terlibat</div>
                <div class="value" style="color: #4f46e5;">{{ $stats['total_kampus'] }}</div>
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
                <th style="width: 22%">Asal Sekolah / Kampus</th>
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
                        {{ $app->user->asal_instansi ?? '-' }}<br>
                        <span style="font-size: 7.5pt; color: #555;">{{ $app->user->majorDetail?->name ?? ($app->user->major ?? '-') }}</span>
                    </td>
                    <td>
                        <strong class="text-bold">{{ $app->position->judul_posisi ?? '-' }}</strong>
                        @if($app->pembimbing_lapangan)
                            <br><small style="color: #444; font-size: 7.5pt;">PL: {{ $app->pembimbing_lapangan->name }}</small>
                        @else
                            <br><small style="color: #888; font-size: 7.5pt; font-style: italic;">PL: Belum ditentukan</small>
                        @endif
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }} s/d<br>
                        {{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}<br>
                        <small style="color: #0d9488; font-weight: bold;">({{ \Carbon\Carbon::parse($app->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($app->tanggal_selesai)) }} Hari)</small>
                    </td>
                    <td class="text-center text-bold">
                        @php
                            $statusVal = $app->status instanceof \UnitEnum ? $app->status->value : $app->status;
                        @endphp
                        <span class="status-{{ $statusVal }}">
                            {{ $statusVal === 'diterima' ? 'Aktif' : ($statusVal === 'selesai' ? 'Selesai' : (in_array($statusVal, ['pending', 'menunggu']) ? 'Pending' : ucfirst($statusVal))) }}
                        </span>
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
    @include('pdf.partials.ttd_admin_instansi')

    {{-- Penomoran Halaman & Catatan Kaki --}}
    @include('pdf.partials.footer_page_number')

</body>
</html>