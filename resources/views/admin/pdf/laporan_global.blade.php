<!DOCTYPE html>
<html>
<head>
    <title>Laporan Global Peserta Magang</title>
    <style>
        @page { margin: 2cm; }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.5;
        }

        /* KOP SURAT */
        .kop-surat {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-logo { width: 80px; height: auto; }
        .kop-text { text-align: center; }
        .kop-pemerintah { font-size: 16pt; font-weight: bold; text-transform: uppercase; }
        .kop-instansi { font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .kop-alamat { font-size: 10pt; font-style: italic; }

        /* JUDUL */
        .judul-laporan {
            text-align: center; margin-bottom: 20px;
            font-weight: bold; text-decoration: underline;
            font-size: 14pt; text-transform: uppercase;
        }

        /* TABEL */
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data th, table.data td {
            border: 1px solid #000; padding: 6px 8px;
            vertical-align: middle; font-size: 11pt;
        }
        table.data th { background-color: #f0f0f0; text-align: center; font-weight: bold; text-transform: uppercase; }
        
        /* TANDA TANGAN */
        .ttd-container { width: 100%; margin-top: 40px; display: table; page-break-inside: avoid; }
        .ttd-box-right { display: table-cell; width: 40%; text-align: center; float: right; margin-left: auto; }
        
        .status-badge {
            font-size: 10pt; font-weight: bold; text-transform: uppercase;
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

    <div class="judul-laporan">{!! nl2br(e($title)) !!}</div>

    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Nama Peserta / Asal</th>
                <th width="25%">Lokasi Magang (Instansi)</th>
                <th width="10%">Mulai</th>
                <th width="10%">Selesai</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($allInterns as $index => $data)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $data->user->name }}</strong><br>
                    <span style="font-size: 10pt; font-style: italic;">{{ $data->user->asal_instansi }}</span>
                </td>
                <td>{{ $data->position->instansi->nama_dinas }}</td>
                <td style="text-align: center;">
                    {{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d/m/Y') }}
                </td>
                <td style="text-align: center;">
                    {{ \Carbon\Carbon::parse($data->tanggal_selesai)->format('d/m/Y') }}
                </td>
                <td style="text-align: center;">
                    @if($data->status == 'selesai')
                        <span class="status-badge" style="color: blue;">Selesai</span>
                    @else
                        <span class="status-badge" style="color: green;">Aktif</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px;">Tidak ada data magang.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="ttd-container">
        <div class="ttd-box-right">
            <p>Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Kepala ........</p>
            <br><br><br><br>
            <p style="font-weight: bold; text-decoration: underline;">NAMA ........</p>
            <p>NIP. ........................</p>
        </div>
    </div>

</body>
</html>