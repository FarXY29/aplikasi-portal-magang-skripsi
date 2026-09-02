<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Master Peserta Magang</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm 1.5cm 1.5cm;
            size: A4 landscape;
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
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
    </style>
</head>
<body>

    @include('pdf.partials.kop_admin_kota')

    <div class="judul-laporan">DATA MASTER PESERTA MAGANG KOTA BANJARMASIN</div>

    <table class="meta-info">
        <tr>
            <td style="width: 50%;">
                <strong>Dicetak Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
                <strong>Pencetak:</strong> {{ Auth::user()->name ?? 'Super Admin' }} (Super Admin Kota)
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <strong>Total Peserta Terdaftar:</strong> {{ count($participants) }} Orang
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%">No</th>
                <th style="width: 25%">Nama Peserta &amp; Identitas</th>
                <th style="width: 26%">Asal Sekolah / Kampus</th>
                <th style="width: 22%">Jurusan / Program Studi</th>
                <th style="width: 23%">Kontak (Email / Telepon)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($participants as $index => $user)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong class="text-bold">{{ $user->name }}</strong><br>
                        <span style="font-size: 7.5pt; color: #555;">NIK: {{ $user->nik ?? '-' }}</span>
                        @if($user->nim)
                            <br><span style="font-size: 7.5pt; color: #555;">NIM/NISN: {{ $user->nim }}</span>
                        @endif
                    </td>
                    <td>{{ $user->asal_instansi ?? '-' }}</td>
                    <td>{{ $user->majorDetail?->name ?? ($user->major ?? '-') }}</td>
                    <td>
                        {{ $user->email }}
                        @if($user->phone)
                            <br><span style="font-size: 7.5pt; color: #555;">Telp/WA: {{ $user->phone }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 15px;">Belum ada data peserta terdaftar.</td>
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
