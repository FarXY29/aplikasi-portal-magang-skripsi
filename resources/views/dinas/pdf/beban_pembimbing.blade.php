<!DOCTYPE html>
<html>
<head>
    <title>Laporan Beban dan Kinerja Pembimbing</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid black; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 2px 0; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
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
        <p>Laporan Beban Kerja & Kinerja Pembimbing Lapangan</p>
    </div>

    <div class="meta-info">
        <p><strong>Dicetak Tanggal:</strong> {{ date('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 35%">Nama Pembimbing & NIP</th>
                <th style="width: 15%">Bimbingan Aktif</th>
                <th style="width: 15%">Lulus / Selesai</th>
                <th style="width: 15%">Logbook Tunggakan</th>
                <th style="width: 15%">Rata-Rata Nilai Diberikan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($beban as $pl)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $pl->name }}</strong><br>
                        <small style="color: #555;">NIP: {{ $pl->nik ?? '-' }}</small>
                    </td>
                    <td class="text-center">{{ $pl->total_bimbingan_aktif }} Org</td>
                    <td class="text-center">{{ $pl->total_lulus }} Org</td>
                    <td class="text-center" style="{{ $pl->logbook_tertunda > 0 ? 'color:red; font-weight:bold;' : '' }}">
                        {{ $pl->logbook_tertunda }}
                    </td>
                    <td class="text-center">
                        {{ $pl->rata_nilai_diberikan > 0 ? round($pl->rata_nilai_diberikan, 2) : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">Tidak ada data pembimbing lapangan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->get_cpdf()->addJS('print(true);');
        }
    </script>
</body>
</html>
