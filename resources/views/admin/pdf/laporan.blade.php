<!DOCTYPE html>
<html>
<head>
    <title>Laporan Statistik Magang</title>
    <style>
        @page { margin: 2cm; }
        body { font-family: "Times New Roman", Times, serif; font-size: 12pt; line-height: 1.5; }
        .kop-surat { width: 100%; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-logo { width: 80px; height: auto; }
        .kop-pemerintah { font-size: 16pt; font-weight: bold; text-transform: uppercase; }
        .kop-alamat { font-size: 10pt; font-style: italic; }
        .judul-laporan { text-align: center; margin-bottom: 20px; font-weight: bold; text-decoration: underline; font-size: 14pt; text-transform: uppercase; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data th, table.data td { border: 1px solid #000; padding: 6px 8px; vertical-align: middle; font-size: 11pt; }
        table.data th { background-color: #f0f0f0; text-align: center; font-weight: bold; text-transform: uppercase; }
        .ttd-container { width: 100%; margin-top: 40px; display: table; page-break-inside: avoid; }
        .ttd-box-right { display: table-cell; width: 40%; text-align: center; float: right; margin-left: auto; }
    </style>
</head>
<body>
    <table class="kop-surat">
        <tr>
            <td width="15%" align="center" style="border: none;">
                <img src="{{ public_path('images/Banjarmasin_Logo.svg.png') }}" class="kop-logo" alt="Logo">
            </td>
            <td width="85%" align="center" style="border: none;">
                <div class="kop-pemerintah">PEMERINTAH KOTA BANJARMASIN</div>
                <div class="kop-alamat">Jalan RE Martadinata No. 1, Telp (0511) 3352932, Banjarmasin</div>
            </td>
        </tr>
    </table>

    <div class="judul-laporan">LAPORAN REKAPITULASI PROGRAM MAGANG</div>

    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Instansi</th>
                <th>Lowongan Aktif</th>
                <th>Total Pelamar</th>
                <th>Diterima / Selesai</th>
                <th>Tingkat Seleksi</th>
                <th>Rasio Peminat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $index => $data)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $data['nama_dinas'] }}</td>
                <td style="text-align: center;">{{ $data['lowongan_aktif'] }}</td>
                <td style="text-align: center;">{{ $data['total_pelamar'] }}</td>
                <td style="text-align: center;">{{ $data['total_magang'] }}</td>
                <td style="text-align: center;">{{ $data['seleksi_rate'] }}</td>
                <td style="text-align: center;">{{ $data['avg_peminat'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="ttd-container">
        <div class="ttd-box-right">
            <p>Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Kepala Bakesbangpol</p>
            <br><br><br><br>
            <p style="font-weight: bold; text-decoration: underline;">H. Lukman Fadlun, SH</p>
            <p>NIP. ........................</p>
        </div>
    </div>
</body>
</html>
