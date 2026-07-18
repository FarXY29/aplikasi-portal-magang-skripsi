<!DOCTYPE html>
<html>
<head>
    <title>Laporan Analisis Kompetensi &amp; Performa</title>
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
        
        .predikat-sangat-baik { color: #16a34a; font-weight: bold; }
        .predikat-baik { color: #2563eb; font-weight: bold; }
        .predikat-cukup { color: #d97706; font-weight: bold; }
        .predikat-kurang { color: #dc2626; font-weight: bold; }
        
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
                    <strong>Lokasi Dinas:</strong> {{ $request->instansi_id ? 'Filter Dinas Aktif' : 'Semua' }} &nbsp;|&nbsp;
                    <strong>Predikat:</strong> {{ $request->predikat ?: 'Semua' }}
                </td>
            </tr>
        </table>
    </div>

    {{-- Ringkasan Statistik --}}
    <div class="section-title">Ringkasan Statistik Kompetensi</div>
    <table class="stats-table">
        <tr>
            <td style="width: 16.66%">
                <div class="label">Total Dinilai</div>
                <div class="value">{{ $stats['total'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Sangat Baik</div>
                <div class="value predikat-sangat-baik">{{ $stats['sangat_baik'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Baik</div>
                <div class="value predikat-baik">{{ $stats['baik'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Cukup</div>
                <div class="value predikat-cukup">{{ $stats['cukup'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Kurang</div>
                <div class="value predikat-kurang">{{ $stats['kurang'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Rerata Kelulusan</div>
                <div class="value" style="color: #0d9488;">{{ $stats['avg_nilai'] }}</div>
            </td>
        </tr>
    </table>

    <table class="stats-table" style="margin-top: -8px;">
        <tr>
            <td style="width: 33.33%">
                <span class="label">Rerata Kompetensi Teknis:</span> <strong>{{ $statsGlobal['avg_teknis'] }}/100</strong>
            </td>
            <td style="width: 33.33%">
                <span class="label">Rerata Kedisiplinan:</span> <strong>{{ $statsGlobal['avg_disiplin'] }}/100</strong>
            </td>
            <td style="width: 33.33%">
                <span class="label">Rerata Perilaku / Soft Skill:</span> <strong>{{ $statsGlobal['avg_perilaku'] }}/100</strong>
            </td>
        </tr>
    </table>

    {{-- Tabel Utama --}}
    <div class="section-title">Data Pemeringkatan &amp; Analisis Performa</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%">Rank</th>
                <th style="width: 25%">Nama Peserta &amp; Asal Kampus</th>
                <th style="width: 25%">Penempatan Dinas &amp; Posisi</th>
                <th style="width: 20%">Aspek Nilai (Teknis / Disiplin / Perilaku)</th>
                <th style="width: 13%">Nilai Akhir</th>
                <th style="width: 13%">Predikat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($gradedList as $index => $data)
                <tr>
                    <td class="text-center text-bold">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $data['nama'] }}</strong><br>
                        <span style="font-size: 7px; color: #555;">{{ $data['asal_instansi'] }}</span>
                    </td>
                    <td>
                        <strong>{{ $data['instansi'] }}</strong><br>
                        <span style="font-size: 7px; color: #555;">{{ $data['posisi'] }}</span>
                    </td>
                    <td class="text-center">
                        <span style="color: #2563eb;">{{ $data['teknis'] }}</span> /
                        <span style="color: #7c3aed;">{{ $data['disiplin'] }}</span> /
                        <span style="color: #059669;">{{ $data['perilaku'] }}</span>
                    </td>
                    <td class="text-center text-bold" style="font-size: 10px; color: #0d9488;">
                        {{ $data['rata_rata'] }}
                    </td>
                    <td class="text-center text-bold">
                        @php
                            $pClass = match($data['predikat']) {
                                'Sangat Baik' => 'predikat-sangat-baik',
                                'Baik' => 'predikat-baik',
                                'Cukup' => 'predikat-cukup',
                                'Kurang' => 'predikat-kurang',
                                default => ''
                            };
                        @endphp
                        <span class="{{ $pClass }}">{{ $data['predikat'] }}</span>
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
        <p>Laporan Kompetensi &amp; Performa ini merupakan hasil kumulatif evaluasi program magang Kota Banjarmasin. &copy; {{ date('Y') }}</p>
    </div>

    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->get_cpdf()->addJS('print(true);');
        }
    </script>
</body>
</html>