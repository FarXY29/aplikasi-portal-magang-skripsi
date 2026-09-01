<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Kehadiran - {{ $app->user->name ?? 'Peserta' }}</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm 1.5cm 1.5cm;
            size: A4 portrait;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 9.5pt;
            color: #000;
            line-height: 1.3;
        }
        
        .judul-laporan {
            text-align: center;
            margin: 8px 0 12px 0;
            font-weight: bold;
            font-size: 12pt;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 0.5px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9pt;
        }
        .info-table td {
            padding: 2.5px 0;
            vertical-align: top;
            border: none;
        }
        .label {
            width: 135px;
            font-weight: bold;
        }

        .section-title {
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 10px 0 5px 0;
            padding: 3px 6px;
            background-color: #f1f5f9;
            border-left: 3px solid #000;
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
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #444;
            font-weight: bold;
        }
        .stats-table .stat-value {
            font-size: 11pt;
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
            padding: 5px 6px;
            text-align: left;
            vertical-align: middle;
            font-size: 8.5pt;
        }
        table.data-table th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        
        .status-hadir { color: #15803d; font-weight: bold; }
        .status-telat { color: #b45309; font-weight: bold; }
        .status-izin { color: #1d4ed8; font-weight: bold; }
        .status-sakit { color: #7e22ce; font-weight: bold; }
        .status-alpa { color: #b91c1c; font-weight: bold; }
    </style>
</head>
<body>

    @include('pdf.partials.kop_admin_instansi', ['instansi' => $app->position->instansi])

    <div class="judul-laporan">LAPORAN REKAPITULASI KEHADIRAN PESERTA MAGANG</div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Peserta</td>
            <td style="width: 35%;">: <strong>{{ $app->user->name ?? '-' }}</strong></td>
            <td class="label">Posisi Magang</td>
            <td style="width: 35%;">: {{ $app->position->judul_posisi ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">NIM / NIK</td>
            <td>: {{ $app->user->nim ?? ($app->user->nik ?? '-') }}</td>
            <td class="label">Pembimbing Lapangan</td>
            <td>: {{ $app->pembimbing_lapangan->name ?? 'Belum Ditugaskan' }}</td>
        </tr>
        <tr>
            <td class="label">Asal Kampus / Sekolah</td>
            <td>: {{ $app->user->asal_instansi ?? '-' }}</td>
            <td class="label">Periode Rekap</td>
            <td>: <strong>{{ $bulan }}</strong></td>
        </tr>
    </table>

    @php
        $jamMasukInstansi = $app->position->instansi->jam_mulai_masuk ?? '08:00:00';
        $cntHadirTepat = $data->where('status', 'hadir')->filter(fn($r) => $r->clock_in && $r->clock_in <= $jamMasukInstansi)->count();
        $cntHadirTelat = $data->where('status', 'hadir')->filter(fn($r) => $r->clock_in && $r->clock_in > $jamMasukInstansi)->count();
        $cntIzin = $data->where('status', 'izin')->count();
        $cntSakit = $data->where('status', 'sakit')->count();
        $cntAlpa = $data->where('status', 'alpa')->count();
        $totalRecord = $data->count();
    @endphp

    {{-- Ringkasan Statistik Kehadiran --}}
    <div class="section-title">Ringkasan Statistik Kehadiran</div>
    <table class="stats-table">
        <tr>
            <td style="width: 16.66%">
                <div class="stat-label">Total Hari</div>
                <div class="stat-value">{{ $totalRecord }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="stat-label">Tepat Waktu</div>
                <div class="stat-value status-hadir">{{ $cntHadirTepat }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="stat-label">Terlambat</div>
                <div class="stat-value status-telat">{{ $cntHadirTelat }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="stat-label">Izin</div>
                <div class="stat-value status-izin">{{ $cntIzin }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="stat-label">Sakit</div>
                <div class="stat-value status-sakit">{{ $cntSakit }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="stat-label">Alpa / Tanpa Ket.</div>
                <div class="stat-value status-alpa">{{ $cntAlpa }}</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Rincian Data Absensi --}}
    <div class="section-title">Rincian Riwayat Absensi Harian</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 25%">Hari, Tanggal</th>
                <th style="width: 14%">Jam Masuk</th>
                <th style="width: 14%">Jam Pulang</th>
                <th style="width: 16%">Status</th>
                <th style="width: 26%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $row)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->date)->isoFormat('dddd, D MMMM Y') }}</td>
                    
                    <td class="text-center">
                        {{ $row->clock_in ? \Carbon\Carbon::parse($row->clock_in)->format('H:i') : '-' }}
                    </td>
                    
                    <td class="text-center">
                        {{ $row->clock_out ? \Carbon\Carbon::parse($row->clock_out)->format('H:i') : '-' }}
                    </td>

                    <td class="text-center text-bold">
                        @if($row->status == 'hadir')
                            @if($row->clock_in && $row->clock_in > $jamMasukInstansi) 
                                <span class="status-telat">Terlambat</span>
                            @else
                                <span class="status-hadir">Hadir</span>
                            @endif
                        @elseif($row->status == 'izin')
                            <span class="status-izin">Izin</span>
                        @elseif($row->status == 'sakit')
                            <span class="status-sakit">Sakit</span>
                        @else
                            <span class="status-alpa">{{ ucfirst($row->status) }}</span>
                        @endif
                    </td>

                    <td>{{ $row->description ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 15px;">Tidak ada data absensi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Blok Tanda Tangan Dual Signature --}}
    @include('pdf.partials.ttd_admin_instansi', [
        'mode' => 'dual',
        'instansi' => $app->position->instansi,
        'pembimbing_lapangan' => $app->pembimbing_lapangan
    ])

    {{-- Penomoran Halaman & Catatan Kaki --}}
    @include('pdf.partials.footer_page_number')

</body>
</html>