<!DOCTYPE html>
<html>
<head>
    <title>Laporan Jurnal Harian Mahasiswa</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid black; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 2px 0; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        
        .meta-info { margin-bottom: 10px; font-size: 11px; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <h2>PEMERINTAH KOTA BANJARMASIN</h2>
        <h3>{{ Auth::user()->instansi->nama_dinas ?? 'DINAS TERKAIT' }}</h3>
        <p>Laporan Rekapitulasi Jurnal / Aktivitas Harian Mahasiswa Magang</p>
        <p style="font-size: 10px; color: #555; margin-top: 5px;">{{ $label_waktu }}</p>
    </div>

    <div class="meta-info">
        <p><strong>Dicetak Tanggal:</strong> {{ date('d F Y') }}</p>
        <p><em>Dicetak Oleh: {{ Auth::user()->name }}</em></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 12%">Tanggal</th>
                <th style="width: 20%">Nama Mahasiswa</th>
                <th style="width: 15%">Posisi / Divisi</th>
                <th style="width: 38%">Uraian Kegiatan / Aktivitas</th>
                <th style="width: 10%">Validasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jurnal as $log)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($log->tanggal)->format('d-m-Y') }}
                    </td>
                    <td>
                        {{ $log->application->user->name ?? '-' }}
                    </td>
                    <td>
                        {{ $log->application->position->judul_posisi ?? '-' }}
                    </td>
                    <td>
                        {{ $log->kegiatan }}
                    </td>
                    <td class="text-center" style="
                        @if($log->status_validasi == 'valid') color: green; font-weight: bold;
                        @elseif($log->status_validasi == 'revisi') color: red; font-weight: bold;
                        @else color: orange; font-weight: bold; @endif
                    ">
                        {{ ucfirst($log->status_validasi) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">Belum ada data jurnal aktivitas harian.</td>
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
