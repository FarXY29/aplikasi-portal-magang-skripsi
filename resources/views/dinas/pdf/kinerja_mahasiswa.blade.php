<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kinerja Mahasiswa</title>
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
        <p>Laporan Kinerja Peserta Magang (Absensi & Logbook)</p>
    </div>

    <div class="meta-info">
        <p><strong>Dicetak Tanggal:</strong> {{ date('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 25%">Nama Peserta & Kampus</th>
                <th style="width: 20%">Posisi Magang</th>
                <th style="width: 15%">Kehadiran (%)</th>
                <th style="width: 15%">Logbook (%)</th>
                <th style="width: 10%">Status</th>
                <th style="width: 10%">Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kinerja as $app)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $app->user->name }}</strong><br>
                        <small style="color: #555;">{{ $app->user->asal_instansi ?? '-' }}</small>
                    </td>
                    <td>{{ $app->position->judul_posisi }}</td>
                    <td class="text-center">
                        {{ round($app->attendance_rate, 1) }}%
                    </td>
                    <td class="text-center">
                        {{ round($app->log_rate, 1) }}%
                    </td>
                    <td class="text-center">
                        {{ ucfirst($app->status) }}
                    </td>
                    <td class="text-center">
                        @if($app->status == 'selesai' && $app->avg_nilai > 0)
                            <strong>{{ round($app->avg_nilai, 2) }}</strong>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">Tidak ada data peserta magang aktif/selesai.</td>
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
