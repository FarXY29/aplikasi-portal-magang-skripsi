<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Peserta Magang</title>
    <style>
        /* Setup Kertas A4 Landscape agar kolom muat banyak */
        @page { margin: 2cm; }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.5;
        }

        /* Kop Surat */
        .kop-surat {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-logo {
            width: 80px;
            height: auto;
        }
        .kop-pemerintah {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
        }
        .kop-instansi {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
        }
        .kop-alamat {
            font-size: 10pt;
            font-style: italic;
            text-align: center;
        }

        /* Judul Laporan */
        .judul-laporan {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            text-decoration: underline;
            font-size: 14pt;
            text-transform: uppercase;
        }

        /* Tabel Data */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data th, table.data td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
            font-size: 11pt;
        }
        table.data th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }
        table.data td {
            vertical-align: top;
        }

        /* Tanda Tangan */
        .ttd-container {
            width: 100%;
            margin-top: 40px;
            display: table;
            page-break-inside: avoid;
        }
        .ttd-box-right {
            display: table-cell;
            width: 40%;
            text-align: center;
            margin-left: auto; /* Hack alignment */
            float: right;
        }
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

    <div class="judul-laporan">DATA MASTER PESERTA MAGANG</div>

    <table class="data">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="20%">Nama Peserta</th>
                <th width="25%">Asal Instansi / Kampus</th>
                <th width="20%">Jurusan</th>
                <th width="30%">Kontak (Email / HP)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($participants as $index => $user)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $user->name }}</strong><br>
                    <span style="font-size: 9pt;">NIK: {{ $user->nik ?? '-' }}</span>
                </td>
                <td>{{ $user->asal_instansi ?? '-' }}</td>
                <td>{{ $user->major ?? '-' }}</td>
                <td>
                    {{ $user->email }}<br>
                    <span style="font-size: 9pt;">HP: {{ $user->phone ?? '-' }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 20px;">Belum ada data peserta terdaftar.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="ttd-container">
        <div class="ttd-box-right">
            <p>Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Kepala Bakesbangpol</p>
            <br><br><br><br>
            <p style="font-weight: bold; text-decoration: underline;">NAMA KEPALA BADAN</p>
            <p>NIP. 19700101 200001 1 001</p>
        </div>
    </div>

</body>
</html>