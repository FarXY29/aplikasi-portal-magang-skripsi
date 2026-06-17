<!DOCTYPE html>
<html>
<head>
    <title>Laporan Demografi Kampus</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid black; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 2px 0; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; vertical-align: middle; }
        th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        
        .meta-info { margin-bottom: 10px; font-size: 11px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>

    <div class="header">
        <h2>PEMERINTAH KOTA BANJARMASIN</h2>
        <h3>{{ Auth::user()->instansi->nama_dinas ?? 'DINAS TERKAIT' }}</h3>
        <p>Laporan Demografi Asal Kampus / Sekolah Pendaftar Magang</p>
    </div>

    <div class="meta-info">
        <p><strong>Dicetak Tanggal:</strong> {{ date('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 35%">Asal Kampus / Sekolah</th>
                <th style="width: 15%">Total Pelamar</th>
                <th style="width: 15%">Diterima</th>
                <th style="width: 15%">Ditolak</th>
                <th style="width: 15%">Pending</th>
            </tr>
        </thead>
        <tbody>
            @php $total_pelamar = 0; $total_diterima = 0; $total_ditolak = 0; $total_pending = 0; @endphp
            @forelse($demografi as $kampus => $data)
                @php 
                    $total_pelamar += $data['total_pelamar'];
                    $total_diterima += $data['diterima'];
                    $total_ditolak += $data['ditolak'];
                    $total_pending += $data['pending'];
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $kampus }}</strong>
                    </td>
                    <td class="text-center">
                        {{ $data['total_pelamar'] }}
                    </td>
                    <td class="text-center" style="color: green; font-weight: bold;">
                        {{ $data['diterima'] }}
                    </td>
                    <td class="text-center" style="color: red; font-weight: bold;">
                        {{ $data['ditolak'] }}
                    </td>
                    <td class="text-center" style="color: orange; font-weight: bold;">
                        {{ $data['pending'] }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">Tidak ada data pendaftar.</td>
                </tr>
            @endforelse
            
            @if($demografi->count() > 0)
                <tr style="background-color: #eaeaea;">
                    <td colspan="2" class="text-right"><strong>TOTAL KESELURUHAN</strong></td>
                    <td class="text-center"><strong>{{ $total_pelamar }}</strong></td>
                    <td class="text-center"><strong>{{ $total_diterima }}</strong></td>
                    <td class="text-center"><strong>{{ $total_ditolak }}</strong></td>
                    <td class="text-center"><strong>{{ $total_pending }}</strong></td>
                </tr>
            @endif
        </tbody>
    </table>

    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->get_cpdf()->addJS('print(true);');
        }
    </script>
</body>
</html>
