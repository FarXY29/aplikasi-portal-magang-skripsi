<!DOCTYPE html>
<html>
<head>
    <title>Laporan Jurnal Harian Mahasiswa</title>
    <style>
        body { font-family: sans-serif; font-size: 9px; color: #333; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 3px double #333; padding-bottom: 12px; }
        .header h2 { margin: 0; text-transform: uppercase; font-size: 14px; letter-spacing: 1px; }
        .header h3 { margin: 3px 0; font-size: 12px; }
        .header p { margin: 2px 0; font-size: 10px; color: #555; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #aaa; padding: 5px 6px; text-align: left; vertical-align: top; }
        th { background-color: #f3f4f6; text-align: center; font-weight: bold; font-size: 8px; text-transform: uppercase; }
        
        .meta-info { margin-bottom: 12px; font-size: 10px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .text-green { color: #16a34a; }
        .text-red { color: #dc2626; }
        .text-orange { color: #ea580c; }
        .text-purple { color: #9333ea; }
        .text-blue { color: #2563eb; }
        
        .section-title { 
            font-size: 11px; font-weight: bold; margin: 15px 0 8px 0; 
            padding: 5px 8px; background-color: #f3f4f6; border-left: 4px solid #9333ea;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        
        .stats-table { margin-bottom: 15px; }
        .stats-table td { border: 1px solid #ccc; padding: 6px 8px; text-align: center; }
        .stats-table .label { font-size: 8px; text-transform: uppercase; letter-spacing: 0.5px; color: #666; }
        .stats-table .value { font-size: 14px; font-weight: bold; color: #111; }
        
        .footer { margin-top: 20px; font-size: 8px; color: #888; border-top: 1px solid #ccc; padding-top: 8px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>PEMERINTAH KOTA BANJARMASIN</h2>
        <h3>{{ Auth::user()->instansi->nama_dinas ?? 'DINAS TERKAIT' }}</h3>
        <p>Laporan Rekapitulasi Jurnal / Aktivitas Harian Mahasiswa Magang</p>
        <p style="font-size: 9px; color: #666; margin-top: 4px; font-weight: bold;">Filter Waktu: {{ $label_waktu }}</p>
    </div>

    <div class="meta-info">
        <p><strong>Dicetak Tanggal:</strong> {{ date('d F Y') }} &nbsp;|&nbsp; <em>Oleh: {{ Auth::user()->name }}</em></p>
    </div>

    {{-- Ringkasan Statistik --}}
    <div class="section-title">Ringkasan Statistik Jurnal</div>
    <table class="stats-table">
        <tr>
            <td style="width: 16.66%">
                <div class="label">Total Jurnal</div>
                <div class="value">{{ $stats['total_jurnal'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Disetujui</div>
                <div class="value text-green">{{ $stats['disetujui'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Pending</div>
                <div class="value text-orange">{{ $stats['pending'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Revisi</div>
                <div class="value text-red">{{ $stats['revisi'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Peserta Aktif</div>
                <div class="value text-blue">{{ $stats['total_peserta_aktif'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Rasio Validasi</div>
                <div class="value text-purple">{{ $stats['rasio_validasi'] }}%</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Rekapitulasi Jurnal --}}
    <div class="section-title">Daftar Aktivitas Logbook Harian</div>
    <table>
        <thead>
            <tr>
                <th style="width: 3%">No</th>
                <th style="width: 10%">Tanggal</th>
                <th style="width: 18%">Nama Mahasiswa & Kampus</th>
                <th style="width: 14%">Posisi / Divisi</th>
                <th style="width: 32%">Uraian Kegiatan / Aktivitas</th>
                <th style="width: 8%">Status</th>
                <th style="width: 15%">Pembimbing & Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jurnal as $log)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($log->tanggal)->format('d-m-Y') }}<br>
                        <small style="color: #666;">{{ \Carbon\Carbon::parse($log->tanggal)->isoFormat('dddd') }}</small>
                    </td>
                    <td>
                        <strong>{{ $log->application->user->name ?? '-' }}</strong><br>
                        <small style="color: #555;">{{ $log->application->user->asal_instansi ?? '-' }}</small>
                    </td>
                    <td>
                        {{ $log->application->position->judul_posisi ?? '-' }}
                    </td>
                    <td>
                        <div style="white-space: pre-wrap; word-wrap: break-word;">{{ $log->kegiatan }}</div>
                    </td>
                    <td class="text-center text-bold" style="
                        @if($log->status_validasi == 'disetujui') color: #16a34a;
                        @elseif($log->status_validasi == 'revisi') color: #dc2626;
                        @else color: #d97706; @endif
                    ">
                        {{ ucfirst($log->status_validasi) }}
                    </td>
                    <td>
                        @if($log->application->pembimbing_lapangan)
                            <strong>{{ $log->application->pembimbing_lapangan->name }}</strong>
                            @if($log->komentar_pembimbing_lapangan)
                                <br><small style="color: #555; font-style: italic;">"{{ $log->komentar_pembimbing_lapangan }}"</small>
                            @endif
                        @else
                            <small style="color: #999; font-style: italic;">Belum ditentukan</small>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">Belum ada data jurnal aktivitas harian.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh Sistem Portal Magang Pemerintah Kota Banjarmasin. &copy; {{ date('Y') }}</p>
    </div>

    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->get_cpdf()->addJS('print(true);');
        }
    </script>
</body>
</html>
