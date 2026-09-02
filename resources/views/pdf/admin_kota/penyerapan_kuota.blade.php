<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kinerja Penyerapan Kuota Magang</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm 1.5cm 1.5cm;
            size: A4 portrait;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 10pt;
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
            padding: 6px 8px;
            text-align: left;
            vertical-align: middle;
            font-size: 9pt;
        }
        table.data-table th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
            font-size: 8.5pt;
            text-transform: uppercase;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
    </style>
</head>
<body>

    @include('pdf.partials.kop_admin_kota')

    <div class="judul-laporan">LAPORAN KINERJA PENYERAPAN KUOTA MAGANG</div>

    <table class="meta-info">
        <tr>
            <td style="width: 50%;">
                <strong>Dicetak Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
                <strong>Pencetak:</strong> {{ Auth::user()->name ?? 'Super Admin' }} (Super Admin Kota)
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <strong>Total Instansi:</strong> {{ count($penyerapan) }} Instansi
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 45%">Nama Instansi / Perangkat Daerah</th>
                <th style="width: 16%">Total Kuota</th>
                <th style="width: 16%">Terserap</th>
                <th style="width: 18%">Penyerapan (%)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalK = 0;
                $totalT = 0;
            @endphp
            @forelse($penyerapan as $index => $data)
                @php
                    $totalK += (int) $data->total_kuota;
                    $totalT += (int) $data->total_terserap;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-bold">{{ $data->nama_dinas }}</td>
                    <td class="text-center">{{ $data->total_kuota }}</td>
                    <td class="text-center">{{ $data->total_terserap }}</td>
                    <td class="text-center text-bold" style="color: {{ $data->persentase_penyerapan >= 80 ? '#15803d' : ($data->persentase_penyerapan >= 50 ? '#0f766e' : '#b45309') }};">
                        {{ number_format($data->persentase_penyerapan, 1) }}%
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 15px;">Belum ada data penyerapan kuota.</td>
                </tr>
            @endforelse
            @if(count($penyerapan) > 0)
                <tr style="background-color: #f9fafb; font-weight: bold;">
                    <td colspan="2" class="text-center">TOTAL KESELURUHAN</td>
                    <td class="text-center">{{ $totalK }}</td>
                    <td class="text-center">{{ $totalT }}</td>
                    <td class="text-center" style="color: #0f766e;">
                        {{ $totalK > 0 ? number_format(($totalT / $totalK) * 100, 1) : 0 }}%
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- Blok Tanda Tangan --}}
    @include('pdf.partials.ttd_admin_kota')

    {{-- Penomoran Halaman & Catatan Kaki --}}
    @include('pdf.partials.footer_page_number')

</body>
</html>
