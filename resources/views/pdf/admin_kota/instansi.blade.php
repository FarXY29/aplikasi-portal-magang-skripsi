<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Master Instansi</title>
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
            background-color: #e5e7eb;
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

    <div class="judul-laporan">LAPORAN DATA MASTER INSTANSI / SKPD</div>

    <table class="meta-info">
        <tr>
            <td style="width: 50%;">
                <strong>Dicetak Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
                <strong>Pencetak:</strong> {{ Auth::user()->name ?? 'Super Admin' }} (Super Admin Kota)
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <strong>Total Instansi Terdaftar:</strong> {{ count($instansis) }} Instansi
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%">No</th>
                <th style="width: 25%">Nama Dinas / Instansi</th>
                <th style="width: 12%">Kode Unit Kerja</th>
                <th style="width: 10%">Jml Lowongan</th>
                <th style="width: 10%">Jml Peserta</th>
                <th style="width: 24%">Alamat Kantor</th>
                <th style="width: 15%">Kontak / Titik Lokasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($instansis as $index => $ins)
                @php
                    $lowonganCount = $ins->positions ? $ins->positions->count() : 0;
                    $pesertaCount = $ins->positions ? $ins->positions->flatMap->applications->whereIn('status', ['diterima', 'selesai'])->count() : 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-bold">{{ $ins->nama_dinas }}</td>
                    <td class="text-center" style="font-family: monospace;">{{ $ins->kode_unit_kerja ?? '-' }}</td>
                    <td class="text-center">{{ $lowonganCount }} Posisi</td>
                    <td class="text-center">{{ $pesertaCount }} Orang</td>
                    <td>{{ $ins->alamat ?? '-' }}</td>
                    <td style="font-size: 8pt;">
                        @if($ins->contact_whatsapp)
                            WA: {{ $ins->contact_whatsapp }}<br>
                        @endif
                        @if($ins->latitude && $ins->longitude)
                            <span style="color: #555; font-family: monospace;">{{ round($ins->latitude, 4) }}, {{ round($ins->longitude, 4) }}</span>
                        @else
                            <span style="color: #888; font-style: italic;">Lokasi belum diatur</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 15px;">Belum ada data instansi.</td>
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
