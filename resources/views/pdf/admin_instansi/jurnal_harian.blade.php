<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Jurnal Aktivitas Harian Magang</title>
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
        
        .status-disetujui { color: #15803d; font-weight: bold; }
        .status-revisi { color: #b91c1c; font-weight: bold; }
        .status-pending { color: #b45309; font-weight: bold; }
    </style>
</head>
<body>

    @include('pdf.partials.kop_admin_instansi', ['instansi' => $instansi ?? null])

    <div class="judul-laporan">LAPORAN REKAPITULASI JURNAL &amp; AKTIVITAS HARIAN MAGANG</div>

    <table class="meta-info">
        <tr>
            <td style="width: 50%;">
                <strong>Instansi:</strong> {{ $instansi->nama_dinas ?? (Auth::user()->instansi->nama_dinas ?? '-') }}<br>
                <strong>Dicetak Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
                <strong>Pencetak:</strong> {{ Auth::user()->name ?? 'Admin Instansi' }}
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <strong>Filter Waktu:</strong> {{ $label_waktu ?? 'Semua Waktu' }}<br>
                <strong>Total Peserta Aktif:</strong> {{ $stats['total_peserta_aktif'] ?? '-' }} Orang
            </td>
        </tr>
    </table>

    {{-- Ringkasan Statistik Jurnal --}}
    <div class="section-title">Ringkasan Statistik Jurnal Aktivitas</div>
    <table class="stats-table">
        <tr>
            <td style="width: 16.66%">
                <div class="stat-label">Total Jurnal</div>
                <div class="stat-value">{{ $stats['total_jurnal'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="stat-label">Disetujui</div>
                <div class="stat-value status-disetujui">{{ $stats['disetujui'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="stat-label">Pending (Menunggu)</div>
                <div class="stat-value status-pending">{{ $stats['pending'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="stat-label">Perlu Revisi</div>
                <div class="stat-value status-revisi">{{ $stats['revisi'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="stat-label">Peserta Aktif</div>
                <div class="stat-value" style="color: #1d4ed8;">{{ $stats['total_peserta_aktif'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="stat-label">Rasio Validasi</div>
                <div class="stat-value" style="color: #7e22ce;">{{ $stats['rasio_validasi'] }}%</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Rekapitulasi Jurnal --}}
    <div class="section-title">Daftar Aktivitas Logbook Harian Peserta</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%">No</th>
                <th style="width: 11%">Tanggal</th>
                <th style="width: 18%">Nama Peserta &amp; Institusi</th>
                <th style="width: 15%">Posisi Magang</th>
                <th style="width: 31%">Uraian Kegiatan / Aktivitas</th>
                <th style="width: 8%">Status</th>
                <th style="width: 14%">Pembimbing Lapangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jurnal as $log)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">
                        <strong>{{ \Carbon\Carbon::parse($log->tanggal)->format('d/m/Y') }}</strong><br>
                        <span style="font-size: 7.2pt; color: #444;">{{ \Carbon\Carbon::parse($log->tanggal)->isoFormat('dddd') }}</span>
                    </td>
                    <td>
                        <strong class="text-bold">{{ $log->application->user->name ?? '-' }}</strong><br>
                        <span style="font-size: 7.5pt; color: #444;">{{ $log->application->user->asal_instansi ?? '-' }}</span>
                    </td>
                    <td>
                        {{ $log->application->position->judul_posisi ?? '-' }}
                    </td>
                    <td>
                        <div style="white-space: pre-wrap; word-wrap: break-word; line-height: 1.25;">{{ $log->kegiatan }}</div>
                    </td>
                    <td class="text-center text-bold">
                        @if($log->status_validasi == 'disetujui')
                            <span class="status-disetujui">Disetujui</span>
                        @elseif($log->status_validasi == 'revisi')
                            <span class="status-revisi">Revisi</span>
                        @else
                            <span class="status-pending">{{ ucfirst($log->status_validasi ?? 'Pending') }}</span>
                        @endif
                    </td>
                    <td>
                        @if($log->application->pembimbing_lapangan)
                            <strong class="text-bold">{{ $log->application->pembimbing_lapangan->name }}</strong>
                            @if($log->komentar_pembimbing_lapangan)
                                <br><span style="color: #444; font-size: 7.2pt; font-style: italic;">"{{ $log->komentar_pembimbing_lapangan }}"</span>
                            @endif
                        @else
                            <span style="color: #666; font-size: 7.2pt; font-style: italic;">Belum ditentukan</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 15px;">Belum ada data jurnal aktivitas harian pada periode ini.</td>
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
