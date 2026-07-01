<!DOCTYPE html>
<html>
<head>
    <title>Laporan Global Peserta Magang</title>
    <style>
        body { font-family: sans-serif; font-size: 8px; color: #333; line-height: 1.3; }
        .kop-surat { width: 100%; border-bottom: 3px double #333; padding-bottom: 10px; margin-bottom: 15px; }
        .kop-logo { width: 60px; height: auto; }
        .kop-text { text-align: center; }
        .kop-pemerintah { font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
        .kop-dinas { font-size: 15px; font-weight: 800; text-transform: uppercase; margin-top: 2px; }
        .kop-alamat { font-size: 8px; color: #555; margin-top: 3px; font-style: italic; }
        
        .judul-laporan { text-align: center; margin: 15px 0 10px 0; font-weight: bold; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        
        .meta-info { margin-bottom: 12px; font-size: 9px; }
        .meta-info td { border: none; padding: 2px 0; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #aaa; padding: 4px 5px; text-align: left; vertical-align: top; }
        th { background-color: #f3f4f6; text-align: center; font-weight: bold; font-size: 8px; text-transform: uppercase; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        
        .status-aktif { color: #16a34a; font-weight: bold; }
        .status-selesai { color: #2563eb; font-weight: bold; }
        .status-pending { color: #d97706; font-weight: bold; }
        .status-ditolak { color: #dc2626; font-weight: bold; }
        
        .section-title { 
            font-size: 10px; font-weight: bold; margin: 15px 0 8px 0; 
            padding: 4px 8px; background-color: #f3f4f6; border-left: 4px solid #0d9488;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        
        .stats-table { margin-bottom: 15px; }
        .stats-table td { border: 1px solid #ccc; padding: 6px 4px; text-align: center; }
        .stats-table .label { font-size: 7px; text-transform: uppercase; letter-spacing: 0.5px; color: #666; font-weight: bold; }
        .stats-table .value { font-size: 12px; font-weight: bold; color: #111; margin-top: 2px; }
        
        .footer { margin-top: 20px; font-size: 8px; color: #888; border-top: 1px solid #ccc; padding-top: 8px; }
        
        .ttd-container { width: 100%; margin-top: 30px; display: table; page-break-inside: avoid; }
        .ttd-row { display: table-row; }
        .ttd-col-left { display: table-cell; width: 65%; }
        .ttd-col-right { display: table-cell; width: 35%; text-align: center; }
        .ttd-space { height: 50px; }
    </style>
</head>
<body>

    <table class="kop-surat" style="border: none;">
        <tr style="border: none;">
            <td width="10%" align="center" style="border: none; padding: 0;">
                <img src="{{ public_path('images/Banjarmasin_Logo.svg.png') }}" class="kop-logo" alt="Logo">
            </td>
            <td width="90%" class="kop-text" style="border: none; padding: 0;">
                <div class="kop-pemerintah">PEMERINTAH KOTA BANJARMASIN</div>
                <div class="kop-dinas">BADAN KESATUAN BANGSA DAN POLITIK</div>
                <div class="kop-alamat">Jalan RE Martadinata No. 1, Telp (0511) 3352932, Banjarmasin 70111</div>
            </td>
        </tr>
    </table>

    <div class="judul-laporan">{!! nl2br(e($title)) !!}</div>

    <div class="meta-info">
        <table style="width: 100%; border: none; margin: 0;">
            <tr style="border: none;">
                <td style="border: none; width: 50%; font-size: 8px; color: #555; padding: 0;">
                    <strong>Dicetak Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
                    <strong>Pencetak:</strong> {{ Auth::user()->name }} (Super Admin)
                </td>
                <td style="border: none; width: 50%; font-size: 8px; color: #555; text-align: right; vertical-align: top; padding: 0;">
                    <strong>Asal Kampus:</strong> {{ $request->instansi ?: 'Semua' }} &nbsp;|&nbsp;
                    <strong>Lokasi Dinas:</strong> {{ $request->instansi_id ? 'Filter Aktif' : 'Semua' }} &nbsp;|&nbsp;
                    @if($request->filled('start_date') && $request->filled('end_date'))
                        <strong>Periode:</strong> {{ \Carbon\Carbon::parse($request->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('d/m/Y') }}
                    @endif
                </td>
            </tr>
        </table>
    </div>

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
    <table>
        <thead>
            <tr>
                <th style="width: 3%">No</th>
                <th style="width: 22%">Nama Peserta & Kontak</th>
                <th style="width: 22%">Asal Sekolah / Kampus</th>
                <th style="width: 23%">Penempatan Dinas & Posisi Magang</th>
                <th style="width: 18%">Periode & Durasi Magang</th>
                <th style="width: 12%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($allInterns as $data)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $data->user->name }}</strong><br>
                        <span style="font-size: 7px; color: #555;">{{ $data->user->email }}</span>
                        @if($data->user->phone)
                            <br><span style="font-size: 7px; color: #555;">Telp: {{ $data->user->phone }}</span>
                        @endif
                    </td>
                    <td>{{ $data->user->asal_instansi ?? '-' }}</td>
                    <td>
                        <strong>{{ $data->position->instansi->nama_dinas }}</strong><br>
                        <span style="font-size: 7px; color: #555;">Posisi: {{ $data->position->judul_posisi }}</span>
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
                            $s = $statusConfig[$data->status] ?? ['class' => '', 'label' => ucfirst($data->status)];
                        @endphp
                        <span class="{{ $s['class'] }}">{{ $s['label'] }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">Tidak ada data ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Tanda Tangan --}}
    <div class="ttd-container">
        <div class="ttd-row">
            <div class="ttd-col-left"></div>
            <div class="ttd-col-right">
                <p>Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p style="margin-top: 2px;">Kepala Bakesbangpol Kota Banjarmasin</p>
                <div class="ttd-space"></div>
                <p style="font-weight: bold; text-decoration: underline; margin-bottom: 2px;">H. Lukman Fadlun, SH</p>
                <p style="font-size: 8px; color: #555;">NIP. ........................................</p>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Laporan Global ini merupakan dokumen rekapitulasi program magang terpusat Kota Banjarmasin. &copy; {{ date('Y') }}</p>
    </div>

    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->get_cpdf()->addJS('print(true);');
        }
    </script>
</body>
</html>