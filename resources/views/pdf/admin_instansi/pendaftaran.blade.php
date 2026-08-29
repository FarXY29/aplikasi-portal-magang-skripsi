<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendaftaran & Pelacakan Permohonan Magang</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm 1.5cm 1.5cm;
            size: A4 landscape;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 9pt;
            color: #111;
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
            color: #333;
        }
        .meta-info td {
            border: none;
            padding: 1px 0;
        }
        
        .section-title { 
            font-size: 9pt;
            font-weight: bold;
            margin: 10px 0 5px 0; 
            padding: 3px 6px;
            background-color: #f3f4f6;
            border-left: 3px solid #059669;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .stats-table td {
            border: 1px solid #666;
            padding: 4px 2px;
            text-align: center;
        }
        .stats-table .label {
            font-size: 6.8pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #555;
            font-weight: bold;
        }
        .stats-table .value {
            font-size: 10.5pt;
            font-weight: bold;
            color: #111;
            margin-top: 1px;
        }
        
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
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
            background-color: #e5e7eb;
            text-align: center;
            font-weight: bold;
            font-size: 7.8pt;
            text-transform: uppercase;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .font-mono { font-family: monospace; font-size: 8pt; }
        
        .status-diterima { color: #16a34a; font-weight: bold; }
        .status-selesai { color: #2563eb; font-weight: bold; }
        .status-pending { color: #d97706; font-weight: bold; }
        .status-menunggu { color: #ca8a04; font-weight: bold; }
        .status-ditolak { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>

    @include('pdf.partials.kop_admin_instansi')

    <div class="judul-laporan">LAPORAN PENDAFTARAN &amp; PELACAKAN STATUS PERMOHONAN MAGANG</div>

    <table class="meta-info">
        <tr>
            <td style="width: 50%;">
                <strong>Instansi:</strong> {{ $instansi->nama_dinas ?? '-' }}<br>
                <strong>Dicetak Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB<br>
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
                    <br><strong>Kata Kunci:</strong> "{{ $request->search }}"
                @endif
            </td>
        </tr>
    </table>

    {{-- Ringkasan Statistik 6 Status --}}
    <div class="section-title">Ringkasan Statistik Status Pendaftaran</div>
    <table class="stats-table">
        <tr>
            <td style="width: 16.66%">
                <div class="label">Total Pendaftar</div>
                <div class="value" style="color: #047857;">{{ $stats['total'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Pending (Review)</div>
                <div class="value status-pending">{{ $stats['pending'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Daftar Tunggu</div>
                <div class="value status-menunggu">{{ $stats['menunggu'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Diterima / Aktif</div>
                <div class="value status-diterima">{{ $stats['diterima'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Ditolak</div>
                <div class="value status-ditolak">{{ $stats['ditolak'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Selesai Magang</div>
                <div class="value status-selesai">{{ $stats['selesai'] }}</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Utama Data Pelacakan --}}
    <div class="section-title">Data Pelacakan Permohonan Pendaftaran Magang</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%">No</th>
                <th style="width: 16%">No. Registrasi &amp; Tgl Lamar</th>
                <th style="width: 25%">Nama Pemohon &amp; Institusi Asal</th>
                <th style="width: 22%">Posisi Magang &amp; Pembimbing</th>
                <th style="width: 18%">Periode Pelaksanaan</th>
                <th style="width: 16%">Status Terkini</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $app)
                @php
                    $statusVal = $app->status instanceof \UnitEnum ? $app->status->value : $app->status;
                    $statusLabels = [
                        'pending' => 'Pending (Review)',
                        'menunggu' => 'Daftar Tunggu',
                        'diterima' => 'Diterima / Aktif',
                        'ditolak' => 'Ditolak',
                        'selesai' => 'Selesai',
                    ];
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    
                    {{-- No. Registrasi & Tanggal --}}
                    <td>
                        <strong class="font-mono">{{ $app->nomor_registrasi ?? ('REG-' . $app->id) }}</strong><br>
                        <span style="font-size: 7.5pt; color: #555;">
                            {{ \Carbon\Carbon::parse($app->created_at)->translatedFormat('d M Y, H:i') }}
                        </span>
                        @if($app->is_automatic_placement)
                            <br><small style="color: #0d9488; font-weight: bold; font-size: 7pt;">[Penempatan Otomatis]</small>
                        @endif
                    </td>

                    {{-- Pemohon & Institusi --}}
                    <td>
                        <strong class="text-bold">{{ $app->user->name ?? '-' }}</strong><br>
                        <span style="font-size: 7.8pt; color: #047857; font-weight: bold;">
                            {{ $app->user->asal_instansi ?? ($app->user->university->name ?? ($app->user->school->name ?? '-')) }}
                        </span><br>
                        <span style="font-size: 7.2pt; color: #555;">
                            @if(!empty($app->user->nim))
                                NIM/NISN: {{ $app->user->nim }} &bull;
                            @endif
                            {{ $app->user->major ?? ($app->user->jurusan ?? '-') }}
                        </span>
                    </td>

                    {{-- Posisi & Pembimbing --}}
                    <td>
                        <strong class="text-bold">{{ $app->position->judul_posisi ?? '-' }}</strong>
                        @if($app->pembimbing_lapangan)
                            <br><small style="color: #333; font-size: 7.5pt;">PL: {{ $app->pembimbing_lapangan->name }}</small>
                        @else
                            <br><small style="color: #888; font-size: 7.2pt; font-style: italic;">PL: Belum ditugaskan</small>
                        @endif
                    </td>

                    {{-- Periode Magang --}}
                    <td>
                        @if($app->tanggal_mulai && $app->tanggal_selesai)
                            {{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }} s/d<br>
                            {{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}<br>
                            <small style="color: #059669; font-weight: bold;">
                                ({{ \Carbon\Carbon::parse($app->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($app->tanggal_selesai)) }} Hari)
                            </small>
                        @else
                            <span style="color: #888; font-style: italic;">-</span>
                        @endif
                    </td>

                    {{-- Status Terkini --}}
                    <td class="text-center">
                        <span class="status-{{ $statusVal }}">
                            {{ $statusLabels[$statusVal] ?? ucfirst($statusVal) }}
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

    {{-- Blok Tanda Tangan Pejabat --}}
    @include('pdf.partials.ttd_admin_instansi')

    {{-- Footer Penomoran Halaman --}}
    @include('pdf.partials.footer_page_number')

</body>
</html>

