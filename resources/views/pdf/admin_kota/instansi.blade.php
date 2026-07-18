<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Instansi</title>
    <style>
        /* Setup Dasar untuk Kertas A4 */
        @page { margin: 2cm; }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.5;
        }

        /* Styling Kop Surat */
        .kop-surat {
            width: 100%;
            border-bottom: 3px double #000; /* Garis ganda tebal tipis khas surat dinas */
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-logo {
            width: 80px;
            height: auto;
        }
        .kop-text {
            text-align: center;
        }
        .kop-instansi {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-pemerintah {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-alamat {
            font-size: 10pt;
            font-style: italic;
        }

        /* Styling Tabel Data */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data th, table.data td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
            font-size: 11pt;
        }
        table.data th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }

        /* Tanda Tangan */
        .ttd-container {
            width: 100%;
            margin-top: 50px;
            display: table; /* Hack untuk layout kolom di PDF */
        }
        .ttd-box {
            display: table-cell;
            width: 60%;
        }
        .ttd-box-right {
            display: table-cell;
            width: 40%;
            text-align: center;
        }
    </style>
</head>
<body>

    <table class="kop-surat">
        <tr>
            <td width="15%" align="center">
                <img src="{{ public_path('images/Banjarmasin_Logo.svg.png') }}" class="kop-logo" alt="Logo">
            </td>
            <td width="85%" align="center">
                <div class="kop-pemerintah">PEMERINTAH KOTA BANJARMASIN</div>
                <div class="kop-instansi">BADAN KESATUAN BANGSA DAN POLITIK</div>
                <div class="kop-alamat">Jalan RE Martadinata No. 1, Telp (0511) 3352932, Banjarmasin</div>
            </td>
        </tr>
    </table>

    <div style="text-align: center; margin-bottom: 20px;">
        <span style="font-weight: bold; text-decoration: underline; font-size: 14pt;">LAPORAN DATA Instansi</span>
    </div>

    <table class="data">
    <thead>
        <tr>
            <th width="5%">No.</th>
            <th width="25%">Nama Dinas / Instansi</th>
            <th width="15%">Kode Unit</th>
            <th width="10%">Jml Lowongan</th>
            <th width="10%">Jml Peserta</th>
            <th width="25%">Alamat Kantor</th>
            <th width="10%">Koordinat</th>
        </tr>
    </thead>
    <tbody>
        @foreach($instansis as $index => $instansi)
        <tr>
            <td style="text-align: center;">{{ $index + 1 }}</td>
            <td>{{ $instansi->nama_dinas }}</td>
            <td style="text-align: center;">{{ $instansi->kode_unit_kerja }}</td>
            
            <td style="text-align: center;">
                {{ $instansi->positions->count() }}
            </td>
            <td style="text-align: center;">
                {{ $instansi->positions->flatMap->applications->whereIn('status', ['diterima', 'selesai'])->count() }}
            </td>

            <td>{{ $instansi->alamat }}</td>
            <td style="font-size: 9pt;">
                {{ $instansi->latitude }}, {{ $instansi->longitude }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

    <div class="ttd-container">
        <div class="ttd-box">
            </div>
        <div class="ttd-box-right">
            <p>Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Kepala Badan,</p>
            <br><br><br><br>
            <p style="font-weight: bold; text-decoration: underline;">NAMA KEPALA BADAN</p>
            <p>NIP. 19700101 200001 1 001</p>
        </div>
    </div>

</body>
</html>