<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Absensi - {{ $app->user->name ?? 'Peserta' }}</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm 1.5cm 1.5cm;
            size: A4 portrait;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 10pt;
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
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 9pt;
        }
        .info-table td {
            padding: 2px 0;
            vertical-align: top;
            border: none;
        }
        .label {
            width: 130px;
            font-weight: bold;
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
            vertical-align: middle;
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
        
        .status-hadir { color: #16a34a; font-weight: bold; }
        .status-telat { color: #d97706; font-weight: bold; }
        .status-izin { color: #2563eb; }
        .status-sakit { color: #9333ea; }
        .status-alpa { color: #dc2626; font-weight: bold; }
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
            <td>: {{ $app->user->nik ?? ($app->user->nim ?? '-') }}</td>
            <td class="label">Pembimbing Lapangan</td>
            <td>: {{ $app->pembimbing_lapangan->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Asal Kampus / Sekolah</td>
            <td>: {{ $app->user->asal_instansi ?? '-' }}</td>
            <td class="label">Periode Rekap</td>
            <td>: <strong>{{ $bulan }}</strong></td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 25%">Hari, Tanggal</th>
                <th style="width: 15%">Jam Masuk</th>
                <th style="width: 15%">Jam Pulang</th>
                <th style="width: 15%">Status</th>
                <th style="width: 25%">Keterangan</th>
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
                            @if($row->clock_in > ($app->position->instansi->jam_mulai_masuk ?? '08:00:00')) 
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