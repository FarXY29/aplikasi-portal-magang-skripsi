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
        
        .text-green { color: #16a34a; }
        .text-red { color: #dc2626; }
        .text-orange { color: #ea580c; }
        .text-purple { color: #9333ea; }
        .text-blue { color: #2563eb; }
    </style>
</head>
<body>

    @include('pdf.partials.kop_admin_instansi')

    <div class="judul-laporan">LAPORAN REKAPITULASI JURNAL &amp; AKTIVITAS HARIAN</div>

    <table class="meta-info">
        <tr>
            <td style="width: 50%;">
                <strong>Dicetak Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
                <strong>Pencetak:</strong> {{ Auth::user()->name ?? 'Admin Instansi' }}
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <strong>Filter Waktu:</strong> {{ $label_waktu ?? 'Semua Waktu' }} &nbsp;|&nbsp;
                <strong>Total Peserta Aktif:</strong> {{ $stats['total_peserta_aktif'] ?? '-' }} Orang
            </td>
        </tr>
    </table>

    {{-- Ringkasan Statistik --}}
    <div class="section-title">Ringkasan Statistik Jurnal</div>
    <table class="stats-table">
        <tr>
            <td style="width: 16.66%">
                <div class="label">Total Jurnal</div>
                <div class="value">{{ $stats['total_jurnal'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Disetujui</div>
                <div class="value text-green">{{ $stats['disetujui'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Pending</div>
                <div class="value text-orange">{{ $stats['pending'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Revisi</div>
                <div class="value text-red">{{ $stats['revisi'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Peserta Aktif</div>
                <div class="value text-blue">{{ $stats['total_peserta_aktif'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Rasio Validasi</div>
                <div class="value text-purple">{{ $stats['rasio_validasi'] }}%</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Rekapitulasi Jurnal --}}
    <div class="section-title">Daftar Aktivitas Logbook Harian</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%">No</th>
                <th style="width: 10%">Tanggal</th>
                <th style="width: 18%">Nama Mahasiswa &amp; Kampus</th>
                <th style="width: 15%">Posisi / Divisi</th>
                <th style="width: 32%">Uraian Kegiatan / Aktivitas</th>
                <th style="width: 8%">Status</th>
                <th style="width: 14%">Pembimbing Lapangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jurnal as $log)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($log->tanggal)->format('d/m/Y') }}<br>
                        <small style="color: #555;">{{ \Carbon\Carbon::parse($log->tanggal)->isoFormat('dddd') }}</small>
                    </td>
                    <td>
                        <strong class="text-bold">{{ $log->application->user->name ?? '-' }}</strong><br>
                        <small style="color: #555;">{{ $log->application->user->asal_instansi ?? '-' }}</small>
                    </td>
                    <td>
                        {{ $log->application->position->judul_posisi ?? '-' }}
                    </td>
                    <td>
                        <div style="white-space: pre-wrap; word-wrap: break-word;">{{ $log->kegiatan }}</div>
                    </td>
                    <td class="text-center text-bold" style="
                        @if($log->status_validasi == 'disetujui') color: #16a34a;
                        @elseif($log->status_validasi == 'revisi') color: #dc2626;
                        @else color: #d97706; @endif
                    ">
                        {{ ucfirst($log->status_validasi) }}
                    </td>
                    <td>
                        @if($log->application->pembimbing_lapangan)
                            <strong class="text-bold">{{ $log->application->pembimbing_lapangan->name }}</strong>
                            @if($log->komentar_pembimbing_lapangan)
                                <br><small style="color: #555; font-style: italic;">"{{ $log->komentar_pembimbing_lapangan }}"</small>
                            @endif
                        @else
                            <small style="color: #888; font-style: italic;">Belum ditentukan</small>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 15px;">Belum ada data jurnal aktivitas harian.</td>
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
