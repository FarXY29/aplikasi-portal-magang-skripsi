<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendaftaran &amp; Pelacakan Permohonan Magang Real-Time</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm 1.5cm 1.5cm;
            size: A4 landscape;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 8.8pt;
            color: #000;
            line-height: 1.25;
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
            padding: 2px 0;
            vertical-align: top;
        }
        
        .section-title { 
            font-size: 9pt;
            font-weight: bold;
            margin: 10px 0 5px 0; 
            padding: 3px 6px;
            background-color: #f1f5f9;
            border-left: 3px solid #0f766e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }
        
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .stats-table td {
            border: 1px solid #475569;
            padding: 0;
            text-align: center;
            vertical-align: middle;
        }
        .stats-table .stat-header {
            font-size: 6.8pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #334155;
            font-weight: bold;
            background-color: #f8fafc;
            padding: 3px 2px;
            border-bottom: 1px solid #cbd5e1;
        }
        .stats-table .stat-value {
            font-size: 11pt;
            font-weight: bold;
            color: #0f172a;
            padding: 4px 2px;
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
            border: 1px solid #334155;
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
            color: #0f172a;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .font-mono { font-family: monospace; font-size: 8pt; }
        
        .stat-total { color: #0f766e; }
        .stat-pending { color: #d97706; }
        .stat-menunggu { color: #ca8a04; }
        .stat-diterima { color: #16a34a; }
        .stat-ditolak { color: #dc2626; }
        .stat-selesai { color: #2563eb; }

        .status-diterima { color: #16a34a; font-weight: bold; }
        .status-selesai { color: #2563eb; font-weight: bold; }
        .status-pending { color: #d97706; font-weight: bold; }
        .status-menunggu { color: #ca8a04; font-weight: bold; }
        .status-ditolak { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>

    @include('pdf.partials.kop_admin_kota')

    <div class="judul-laporan">{{ $title ?? 'LAPORAN PENDAFTARAN & PELACAKAN PERMOHONAN MAGANG REAL-TIME' }}</div>

    <table class="meta-info">
        <tr>
            <td style="width: 50%;">
                <strong>Dicetak Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WITA<br>
                <strong>Pencetak:</strong> {{ Auth::user()->name ?? 'Super Admin' }} (Super Admin Kota Banjarmasin)<br>
                <strong>Dinas / SKPD:</strong> {{ request('instansi_id') ? (\App\Models\Instansi::find(request('instansi_id'))?->nama_dinas ?? 'Filter Terpilih') : 'Semua Dinas / SKPD Pemko Banjarmasin' }}
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                @if(isset($request) && $request->filled('status') && $request->status !== 'semua')
                    <strong>Filter Status:</strong> {{ ucfirst($request->status) }}<br>
                @else
                    <strong>Filter Status:</strong> Semua Status Permohonan<br>
                @endif
                @if(isset($request) && $request->filled('start_date') && $request->filled('end_date'))
                    <strong>Periode Pengajuan:</strong> {{ \Carbon\Carbon::parse($request->start_date)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($request->end_date)->format('d/m/Y') }}<br>
                @elseif(isset($request) && $request->filled('start_date'))
                    <strong>Periode Pengajuan:</strong> Sejak {{ \Carbon\Carbon::parse($request->start_date)->format('d/m/Y') }}<br>
                @elseif(isset($request) && $request->filled('end_date'))
                    <strong>Periode Pengajuan:</strong> Hingga {{ \Carbon\Carbon::parse($request->end_date)->format('d/m/Y') }}<br>
                @else
                    <strong>Periode Pengajuan:</strong> Seluruh Waktu Pendaftaran<br>
                @endif
                @if(isset($request) && $request->filled('search'))
                    <strong>Kata Kunci:</strong> &ldquo;{{ $request->search }}&rdquo;
                @endif
            </td>
        </tr>
    </table>

    {{-- Ringkasan Statistik 6 Status (Formal Government Box Grid) --}}
    <div class="section-title">Ringkasan Statistik Pendaftaran &amp; Status Pelacakan Real-Time</div>
    <table class="stats-table">
        <tr>
            <td style="width: 16.66%;">
                <div class="stat-header">Total Pendaftar</div>
                <div class="stat-value stat-total">{{ number_format($stats['total']) }}</div>
            </td>
            <td style="width: 16.66%;">
                <div class="stat-header">Pending (Baru)</div>
                <div class="stat-value stat-pending">{{ number_format($stats['pending']) }}</div>
            </td>
            <td style="width: 16.66%;">
                <div class="stat-header">Daftar Tunggu</div>
                <div class="stat-value stat-menunggu">{{ number_format($stats['menunggu']) }}</div>
            </td>
            <td style="width: 16.66%;">
                <div class="stat-header">Diterima / Aktif</div>
                <div class="stat-value stat-diterima">{{ number_format($stats['diterima']) }}</div>
            </td>
            <td style="width: 16.66%;">
                <div class="stat-header">Ditolak</div>
                <div class="stat-value stat-ditolak">{{ number_format($stats['ditolak']) }}</div>
            </td>
            <td style="width: 16.66%;">
                <div class="stat-header">Selesai Magang</div>
                <div class="stat-value stat-selesai">{{ number_format($stats['selesai']) }}</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Utama Pelacakan Pendaftaran (7 Kolom A4 Landscape) --}}
    <div class="section-title">Daftar Rincian Permohonan Pendaftaran Magang</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 14%;">No. Registrasi &amp; Tanggal</th>
                <th style="width: 19%;">Nama Pemohon &amp; Kontak</th>
                <th style="width: 19%;">Asal Sekolah / Kampus</th>
                <th style="width: 23%;">Dinas &amp; Formasi Magang</th>
                <th style="width: 11%;">Periode Magang</th>
                <th style="width: 11%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $app)
                @php
                    $appStatus = $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->value : (string) $app->status;
                    $statusLabel = match($appStatus) {
                        'diterima' => 'Diterima / Aktif',
                        'selesai' => 'Selesai Magang',
                        'pending' => 'Pending (Baru)',
                        'menunggu' => 'Daftar Tunggu',
                        'ditolak' => 'Ditolak',
                        default => ucfirst($appStatus),
                    };
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                        <strong class="font-mono">{{ $app->nomor_registrasi ?? ('REG-' . $app->id) }}</strong><br>
                        <span style="font-size: 7.2pt; color: #475569;">{{ \Carbon\Carbon::parse($app->created_at)->translatedFormat('d M Y, H:i') }}</span>
                        @if($app->is_automatic_placement)
                            <br><span style="font-size: 6.8pt; color: #0f766e; font-weight: bold;">[Penempatan Otomatis]</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $app->user->name ?? '-' }}</strong><br>
                        <span style="font-size: 7.2pt; color: #475569;">{{ $app->user->email ?? '-' }}</span>
                        @if($app->user->phone || $app->user->nomor_telepon)
                            <br><span style="font-size: 7.2pt; color: #475569;">Telp: {{ $app->user->phone ?? $app->user->nomor_telepon }}</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $app->user->asal_instansi ?? ($app->user->university->name ?? ($app->user->school->name ?? '-')) }}</strong>
                        @if($app->user->nim)
                            <br><span style="font-size: 7.2pt; color: #475569;">NIM/NISN: {{ $app->user->nim }}</span>
                        @endif
                        @if($app->user->major || $app->user->jurusan)
                            <br><span style="font-size: 7.2pt; color: #475569;">Jurusan: {{ $app->user->major ?? $app->user->jurusan }}</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $app->position->instansi->nama_dinas ?? '-' }}</strong><br>
                        <span style="font-size: 7.5pt; color: #0f766e; font-weight: bold;">Posisi: {{ $app->position->judul_posisi ?? '-' }}</span>
                        @if($app->pembimbing_lapangan)
                            <br><span style="font-size: 7.2pt; color: #475569;">PL: {{ $app->pembimbing_lapangan->name }}</span>
                        @else
                            <br><span style="font-size: 7.2pt; color: #94a3b8; font-style: italic;">PL: Belum ditugaskan</span>
                        @endif
                    </td>
                    <td>
                        @if($app->tanggal_mulai && $app->tanggal_selesai)
                            {{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d/m/Y') }} s/d<br>
                            {{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d/m/Y') }}<br>
                            <span style="color: #0f766e; font-weight: bold; font-size: 7.2pt;">({{ \Carbon\Carbon::parse($app->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($app->tanggal_selesai)) }} Hari)</span>
                        @else
                            <span style="color: #94a3b8; font-style: italic;">Belum ditentukan</span>
                        @endif
                    </td>
                    <td class="text-center text-bold">
                        <span class="status-{{ $appStatus }}">{{ $statusLabel }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 15px;">Tidak ada data permohonan pendaftaran yang ditemukan sesuai kriteria filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Blok Tanda Tangan Pejabat Kota Banjarmasin --}}
    @include('pdf.partials.ttd_admin_kota')

    {{-- Penomoran Halaman & Catatan Kaki Resmi --}}
    @include('pdf.partials.footer_page_number')

</body>
</html>
