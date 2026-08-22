<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Global Peserta Magang</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm 1.5cm 1.5cm;
            size: A4 portrait;
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
        .status-ditolak { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>

    @include('pdf.partials.kop_admin_kota')

    <div class="judul-laporan">{{ $title ?? 'LAPORAN GLOBAL PESERTA MAGANG' }}</div>

    <table class="meta-info">
        <tr>
            <td style="width: 50%;">
                <strong>Dicetak Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
                <strong>Pencetak:</strong> {{ Auth::user()->name ?? 'Super Admin' }} (Super Admin Kota)
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <strong>Asal Kampus:</strong> {{ $request->instansi ?: 'Semua' }} &nbsp;|&nbsp;
                <strong>Lokasi Dinas:</strong> {{ $request->instansi_id ? 'Filter Terpilih' : 'Semua Dinas' }}
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
                <div class="label">Total Pendaftar</div>
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
                <div class="label">Dinas Terlibat</div>
                <div class="value" style="color: #4f46e5;">{{ $stats['total_dinas'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Kampus Terlibat</div>
                <div class="value" style="color: #db2777;">{{ $stats['total_kampus'] }}</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Utama --}}
    <div class="section-title">Data Rekapitulasi Global Peserta Magang</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%">No</th>
                <th style="width: 22%">Nama Peserta &amp; Kontak</th>
                <th style="width: 22%">Asal Sekolah / Kampus</th>
                <th style="width: 23%">Penempatan Dinas &amp; Posisi Magang</th>
                <th style="width: 18%">Periode &amp; Durasi Magang</th>
                <th style="width: 12%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($allInterns as $data)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $data->user->name }}</strong><br>
                        <span style="font-size: 7.5pt; color: #444;">{{ $data->user->email }}</span>
                        @if($data->user->phone)
                            <br><span style="font-size: 7.5pt; color: #444;">Telp: {{ $data->user->phone }}</span>
                        @endif
                    </td>
                    <td>{{ $data->user->asal_instansi ?? '-' }}</td>
                    <td>
                        <strong>{{ $data->position->instansi->nama_dinas ?? '-' }}</strong><br>
                        <span style="font-size: 7.5pt; color: #444;">Posisi: {{ $data->position->judul_posisi ?? '-' }}</span>
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d M Y') }} s/d<br>
                        {{ \Carbon\Carbon::parse($data->tanggal_selesai)->format('d M Y') }}<br>
                        <small style="color: #0d9488; font-weight: bold;">({{ \Carbon\Carbon::parse($data->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($data->tanggal_selesai)) }} Hari)</small>
                    </td>
                    <td class="text-center text-bold">
                        @php
                            $statusConfig = [
                                'pending' => ['class' => 'status-pending', 'label' => 'Pending'],
                                'menunggu' => ['class' => 'status-pending', 'label' => 'Pending'],
                                'diterima' => ['class' => 'status-aktif', 'label' => 'Aktif'],
                                'selesai' => ['class' => 'status-selesai', 'label' => 'Selesai'],
                                'ditolak' => ['class' => 'status-ditolak', 'label' => 'Ditolak'],
                            ];
                            $statusVal = $data->status instanceof \App\Enums\ApplicationStatus ? $data->status->value : $data->status;
                            $s = $statusConfig[$statusVal] ?? ['class' => '', 'label' => ucfirst($statusVal)];
                        @endphp
                        <span class="{{ $s['class'] }}">{{ $s['label'] }}</span>
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
    @include('pdf.partials.ttd_admin_kota')

    {{-- Penomoran Halaman & Catatan Kaki --}}
    @include('pdf.partials.footer_page_number')

</body>
</html>
