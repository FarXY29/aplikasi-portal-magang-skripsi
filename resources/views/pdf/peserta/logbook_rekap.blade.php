<!DOCTYPE html>
<html>
<head>
    <title>Rekap Kegiatan Magang</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; text-transform: uppercase; }
        .header h1 { margin: 0; font-size: 16px; }
        .header h2 { margin: 5px 0; font-size: 14px; }
        
        /* Menggunakan float:left untuk meta agar layout stabil */
        .meta-container { width: 100%; margin-bottom: 20px; overflow: hidden; }
        .meta-left { float: left; width: 50%; }
        .meta-right { float: right; width: 45%; }
        
        table { border-collapse: collapse; width: 100%; }
        .table-data th, .table-data td { border: 1px solid black; padding: 6px; text-align: left; vertical-align: top; }
        .table-data th { background-color: #f0f0f0; text-align: center; }
        
        .footer { margin-top: 30px; page-break-inside: avoid; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Daftar Hadir dan Agenda Kegiatan</h1>
        <h2>{{ $app->position->instansi->nama_dinas }}</h2>
        <p>Pemerintah Kota Banjarmasin</p>
    </div>

    {{-- Informasi Peserta --}}
    <div class="meta-container">
        <div class="meta-left">
            <table>
                <tr>
                    <td width="30%">Nama Peserta</td>
                    <td width="5%">:</td>
                    <td><strong>{{ $user->name }}</strong></td>
                </tr>
                <tr>
                    <td>NIM / NPM / NISN</td>
                    <td>:</td>
                    <td>{{ $user->nik ?? $user->nomor_induk ?? '-' }}</td>
                </tr>
            </table>
        </div>
        <div class="meta-right">
            <table>
                <tr>
                    <td width="30%">Posisi</td>
                    <td width="5%">:</td>
                    <td>{{ $app->position->judul_posisi }}</td>
                </tr>
                <tr>
                    <td>Asal Instansi</td>
                    <td>:</td>
                    <td>{{ $user->asal_instansi }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Tabel Logbook --}}
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 60%">Uraian Kegiatan</th>
                <th style="width: 20%">Paraf Pembimbing</th>
            </tr>
        </thead>
        <tbody>
            @php
                $ttdPlPath = null;
                if (!empty($app->pembimbing_lapangan?->signature)) {
                    $rawPl = $app->pembimbing_lapangan->signature;
                    if (file_exists(storage_path('app/public/' . $rawPl))) {
                        $ttdPlPath = storage_path('app/public/' . $rawPl);
                    } elseif (file_exists(public_path('storage/' . $rawPl))) {
                        $ttdPlPath = public_path('storage/' . $rawPl);
                    }
                }
            @endphp
            @foreach($logs as $index => $log)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($log->tanggal)->format('d-m-Y') }}</td>
                <td>
                    {{ $log->kegiatan }}
                    @if($log->komentar_pembimbing_lapangan)
                        <br><br>
                        <i style="color: #555; font-size: 10px;">Catatan Pembimbing Lapangan: {{ $log->komentar_pembimbing_lapangan }}</i>
                    @endif
                </td>
                <td style="text-align: center; vertical-align: middle;">
                    {{-- MENAMPILKAN PARAF --}}
                    @if(in_array($log->status_validasi, ['approved', 'disetujui', 'valid']))
                        @if($ttdPlPath)
                            <img src="{{ $ttdPlPath }}" style="max-height: 35px; max-width: 90px; display: block; margin: 0 auto;">
                        @else
                            <span style="font-size: 10px; font-weight: bold; color: #0d9488;">(Disetujui)</span>
                        @endif
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Footer Tanda Tangan --}}
    <div class="footer" style="page-break-inside: avoid; margin-top: 20px;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 60%; border: none;"></td>
                <td style="width: 40%; border: none; text-align: center; vertical-align: top;">
                    <p style="margin-bottom: 2px;">Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <p style="font-weight: bold; margin-top: 0;">Pembimbing Lapangan,</p>
                    
                    <div style="height: 60px; margin: 4px 0; text-align: center;">
                        @if($ttdPlPath)
                            <img src="{{ $ttdPlPath }}" style="max-height: 58px; max-width: 150px; display: block; margin: 0 auto;">
                        @else
                            <div style="height: 60px;"></div>
                        @endif
                    </div>

                    <p style="font-weight: bold; text-decoration: underline; margin-bottom: 2px;">{{ $app->pembimbing_lapangan->name ?? '.........................' }}</p>
                    <p style="margin-top: 0;">NIP/NIK. {{ $app->pembimbing_lapangan->nik ?? $app->pembimbing_lapangan->nomor_induk ?? '-' }}</p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>