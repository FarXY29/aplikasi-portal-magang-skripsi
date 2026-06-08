<!DOCTYPE html>
<html>
<head>
    <title>Laporan Rekap Peserta Magang</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid black; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 2px 0; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        
        .status-aktif { color: green; font-weight: bold; }
        .status-selesai { color: blue; }
        .status-pending { color: orange; }
        .status-ditolak { color: red; }
        
        .meta-info { margin-bottom: 10px; font-size: 11px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>PEMERINTAH KOTA BANJARMASIN</h2>
        <h3>{{ $instansi->nama_dinas ?? 'DINAS TERKAIT' }}</h3>
        <p>Laporan Rekapitulasi Peserta Magang</p>
    </div>

    <div class="meta-info">
        <p><strong>Dicetak Tanggal:</strong> {{ date('d F Y') }}</p>
        <p><strong>Filter Terpasang:</strong> 
            Status: {{ $request->status ?: 'Semua' }} | 
            Instansi: {{ $request->asal_instansi ?: 'Semua' }}
        </p>
    </div>
<div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 20%">Nama Peserta</th>
                <th style="width: 20%">Asal Sekolah/Kampus</th>
                <th style="width: 20%">Posisi Magang</th>
                <th style="width: 20%">Periode</th>
                <th style="width: 15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $app)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>
                        {{ $app->user->name }}<br>
                        <small style="color: #555;">{{ $app->user->email }}</small>
                    </td>
                    <td>{{ $app->user->asal_instansi ?? '-' }}</td>
                    <td>{{ $app->position->judul_posisi }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }} s/d<br>
                        {{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}
                    </td>
                    <td style="text-align: center;">
                        <span class="status-{{ $app->status }}">
                            {{ ucfirst($app->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">Tidak ada data ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
{{-- Script untuk memunculkan dialog print otomatis saat PDF dibuka --}}
    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->get_cpdf()->addJS('print(true);');
        }
    </script>
</body>
</html>