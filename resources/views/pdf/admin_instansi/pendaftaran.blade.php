<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendaftaran &amp; Pelacakan Permohonan Magang</title>
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

    @include('pdf.partials.kop_admin_instansi', ['instansi' => $instansi ?? null])

    <div class="judul-laporan">LAPORAN PENDAFTARAN &amp; PELACAKAN STATUS PERMOHONAN MAGANG</div>

    <table class="meta-info">
        <tr>
            <td style="width: 50%;">
                <strong>Instansi:</strong> {{ $instansi->nama_dinas ?? (Auth::user()->instansi->nama_dinas ?? '-') }}<br>
                <strong>Dicetak Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WITA<br>
                <strong>Pencetak:</strong> {{ Auth::user()->name ?? 'Admin Instansi' }}
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <strong>Filter Status:</strong> {{ !empty($request->status) && $request->status !== 'semua' ? ucfirst($request->status) : 'Semua Status' }}<br>
                <strong>Posisi Magang:</strong> {{ !empty($request->posisi_id) ? ($applications->first()?->position->judul_posisi ?? 'Dipilih') : 'Semua Posisi' }}
                @if(!empty($request->start_date) || !empty($request->end_date))
                    <br><strong>Rentang Pendaftaran:</strong> 
                    @if(!empty($request->start_date) && !empty($request->end_date))
                        {{ \Carbon\Carbon::parse($request->start_date)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($request->end_date)->format('d/m/Y') }}
                    @elseif(!empty($request->start_date))
                        Mulai {{ \Carbon\Carbon::parse($request->start_date)->format('d/m/Y') }}
                    @elseif(!empty($request->end_date))
                        Sampai {{ \Carbon\Carbon::parse($request->end_date)->format('d/m/Y') }}
                    @endif
                @endif
                @if(!empty($request->search))
                    <br><strong>Kata Kunci:</strong> &ldquo;{{ $request->search }}&rdquo;
                @endif
            </td>
        </tr>
    </table>

    {{-- Ringkasan Statistik 6 Status (Formal Government Box Grid) --}}
    <div class="section-title">Ringkasan Statistik Status Pendaftaran</div>
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

    {{-- Tabel Utama Data Pelacakan (6 Kolom A4 Landscape) --}}
    <div class="section-title">Data Pelacakan Permohonan Pendaftaran Magang</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 15%;">No. Registrasi &amp; Tgl Lamar</th>
                <th style="width: 29%;">Identitas Pemohon &amp; Institusi Asal</th>
                <th style="width: 23%;">Posisi Magang &amp; Pembimbing</th>
                <th style="width: 16%;">Periode Pelaksanaan</th>
                <th style="width: 14%;">Status Terkini</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $app)
                @php
                    $statusVal = $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->value : (string) $app->status;
                    $statusLabel = match($statusVal) {
                        'diterima' => 'Diterima / Aktif',
                        'selesai' => 'Selesai Magang',
                        'pending' => 'Pending (Baru)',
                        'menunggu' => 'Daftar Tunggu',
                        'ditolak' => 'Ditolak',
                        default => ucfirst($statusVal),
                    };
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    
                    {{-- No. Registrasi & Tanggal Lamar --}}
                    <td>
                        <strong class="font-mono">{{ $app->nomor_registrasi ?? ('REG-' . $app->id) }}</strong><br>
                        <span style="font-size: 7.2pt; color: #475569;">
                            {{ \Carbon\Carbon::parse($app->created_at)->translatedFormat('d M Y, H:i') }}
                        </span>
                        @if($app->is_automatic_placement)
                            <br><span style="font-size: 6.8pt; color: #0f766e; font-weight: bold;">[Penempatan Otomatis]</span>
                        @endif
                    </td>

                    {{-- Identitas Pemohon & Institusi Asal --}}
                    <td>
                        <strong class="text-bold">{{ $app->user->name ?? '-' }}</strong><br>
                        <span style="font-size: 7.8pt; color: #0f766e; font-weight: bold;">
                            {{ $app->user->asal_instansi ?? ($app->user->university->name ?? ($app->user->school->name ?? '-')) }}
                        </span><br>
                        <span style="font-size: 7.2pt; color: #475569;">
                            @if(!empty($app->user->nim))
                                NIM/NISN: {{ $app->user->nim }} &bull;
                            @endif
                            {{ $app->user->major ?? ($app->user->jurusan ?? '-') }}
                        </span>
                        @if(!empty($app->user->email) || !empty($app->user->phone) || !empty($app->user->nomor_telepon))
                            <br><span style="font-size: 7.2pt; color: #64748b;">
                                {{ $app->user->email ?? '' }}{{ (!empty($app->user->email) && (!empty($app->user->phone) || !empty($app->user->nomor_telepon))) ? ' • ' : '' }}{{ $app->user->phone ?? ($app->user->nomor_telepon ?? '') }}
                            </span>
                        @endif
                    </td>

                    {{-- Posisi Magang & Pembimbing --}}
                    <td>
                        <strong class="text-bold">{{ $app->position->judul_posisi ?? '-' }}</strong>
                        @if($app->pembimbing_lapangan)
                            <br><span style="color: #475569; font-size: 7.2pt;">PL: {{ $app->pembimbing_lapangan->name }}</span>
                        @else
                            <br><span style="color: #94a3b8; font-size: 7.2pt; font-style: italic;">PL: Belum ditugaskan</span>
                        @endif
                    </td>

                    {{-- Periode Magang & Durasi --}}
                    <td>
                        @if($app->tanggal_mulai && $app->tanggal_selesai)
                            <strong>{{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }}</strong> s/d<br>
                            <strong>{{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}</strong><br>
                            <span style="color: #0f766e; font-weight: bold; font-size: 7.2pt;">
                                ({{ \Carbon\Carbon::parse($app->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($app->tanggal_selesai)) }} Hari)
                            </span>
                        @else
                            <span style="color: #94a3b8; font-style: italic;">Belum ditentukan</span>
                        @endif
                    </td>

                    {{-- Status Terkini --}}
                    <td class="text-center text-bold">
                        <span class="status-{{ $statusVal }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 15px;">Tidak ada data permohonan pendaftaran yang ditemukan sesuai parameter filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Blok Tanda Tangan Pejabat Instansi --}}
    @include('pdf.partials.ttd_admin_instansi', ['instansi' => $instansi ?? null])

    {{-- Footer Penomoran Halaman Resmi --}}
    @include('pdf.partials.footer_page_number')

</body>
</html>
