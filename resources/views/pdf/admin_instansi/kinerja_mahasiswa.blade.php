<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kinerja Mahasiswa</title>
    <style>
        body { font-family: sans-serif; font-size: 9px; color: #333; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 3px double #333; padding-bottom: 12px; }
        .header h2 { margin: 0; text-transform: uppercase; font-size: 14px; letter-spacing: 1px; }
        .header h3 { margin: 3px 0; font-size: 12px; }
        .header p { margin: 2px 0; font-size: 10px; color: #555; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #aaa; padding: 4px 5px; text-align: left; vertical-align: top; }
        th { background-color: #f3f4f6; text-align: center; font-weight: bold; font-size: 8px; text-transform: uppercase; }
        
        .meta-info { margin-bottom: 12px; font-size: 10px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .text-green { color: #16a34a; }
        .text-red { color: #dc2626; }
        .text-orange { color: #ea580c; }
        .text-blue { color: #2563eb; }
        
        .section-title { 
            font-size: 11px; font-weight: bold; margin: 15px 0 8px 0; 
            padding: 5px 8px; background-color: #f3f4f6; border-left: 4px solid #2563eb;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        
        .stats-table { margin-bottom: 15px; }
        .stats-table td { border: 1px solid #ccc; padding: 6px 8px; text-align: center; }
        .stats-table .label { font-size: 8px; text-transform: uppercase; letter-spacing: 0.5px; color: #666; }
        .stats-table .value { font-size: 14px; font-weight: bold; color: #111; }
        
        .mahasiswa-row { background-color: #f9fafb; font-weight: bold; font-size: 9px; }
        
        .detail-title { font-weight: bold; font-size: 8px; margin-bottom: 3px; text-transform: uppercase; }
        .detail-table { width: 100%; border-collapse: collapse; margin-top: 2px; }
        .detail-table th { background-color: #f9fafb; font-size: 7px; color: #4b5563; padding: 2px; }
        .detail-table td { font-size: 8px; padding: 3px; border: 1px solid #e5e7eb; }
        
        .footer { margin-top: 20px; font-size: 8px; color: #888; border-top: 1px solid #ccc; padding-top: 8px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>PEMERINTAH KOTA BANJARMASIN</h2>
        <h3>{{ Auth::user()->instansi->nama_dinas ?? 'DINAS TERKAIT' }}</h3>
        <p>Laporan Kinerja Peserta Magang (Absensi &amp; Logbook)</p>
    </div>

    <div class="meta-info">
        <p><strong>Dicetak Tanggal:</strong> {{ date('d F Y') }} &nbsp;|&nbsp; <em>Oleh: {{ Auth::user()->name }}</em></p>
    </div>

    {{-- Ringkasan Statistik --}}
    <div class="section-title">Ringkasan Statistik Instansi</div>
    <table class="stats-table">
        <tr>
            <td style="width: 16.66%">
                <div class="label">Total Peserta</div>
                <div class="value">{{ $stats['total_peserta'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Peserta Aktif</div>
                <div class="value text-green">{{ $stats['aktif'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Alumni Lulus</div>
                <div class="value text-blue">{{ $stats['selesai'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Avg Kehadiran</div>
                <div class="value text-green">{{ $stats['avg_kehadiran'] }}%</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Avg Logbook</div>
                <div class="value text-blue">{{ $stats['avg_logbook'] }}%</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Avg Nilai Lulus</div>
                <div class="value text-orange">{{ $stats['avg_nilai'] > 0 ? $stats['avg_nilai'] : '-' }}</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Utama --}}
    <div class="section-title">Scorecard Performa Mahasiswa</div>
    <table>
        <thead>
            <tr>
                <th style="width: 3%">No</th>
                <th style="width: 27%">Nama Peserta &amp; Kampus</th>
                <th style="width: 20%">Posisi Magang</th>
                <th style="width: 13%">Kehadiran (%)</th>
                <th style="width: 13%">Logbook (%)</th>
                <th style="width: 12%">Status</th>
                <th style="width: 12%">Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kinerja as $app)
                {{-- Baris Profil Mahasiswa --}}
                <tr class="mahasiswa-row" style="background-color: #f3f4f6;">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $app->user->name }}</strong><br>
                        <small style="font-weight: normal; color: #555;">{{ $app->user->asal_instansi ?? '-' }}</small>
                    </td>
                    <td>{{ $app->position->judul_posisi }}</td>
                    <td class="text-center">{{ round($app->attendance_rate, 1) }}%</td>
                    <td class="text-center">{{ round($app->log_rate, 1) }}%</td>
                    <td class="text-center">
                        {{ $app->status?->value == 'diterima' ? 'Aktif' : 'Selesai' }}
                    </td>
                    <td class="text-center font-bold">
                        {{ $app->avg_nilai > 0 ? round($app->avg_nilai, 1) : '-' }}
                    </td>
                </tr>
                
                {{-- Baris Detail Mahasiswa --}}
                <tr>
                    <td colspan="7" style="padding: 8px 10px; background-color: #ffffff;">
                        <div style="width: 100%;">
                            
                            {{-- Tabel Anak: Detail Kehadiran --}}
                            <div style="width: 30%; float: left; margin-right: 4%;">
                                <div class="detail-title" style="color: #0d9488; border-bottom: 1px solid #ccfbf1; padding-bottom: 2px;">
                                    Rincian Absensi
                                </div>
                                <table class="detail-table">
                                    <tr>
                                        <td>Hadir:</td>
                                        <td class="text-bold text-center">{{ $app->attendances->where('status', 'hadir')->count() }} hari</td>
                                    </tr>
                                    <tr>
                                        <td>Sakit:</td>
                                        <td class="text-bold text-center">{{ $app->attendances->where('status', 'sakit')->count() }} hari</td>
                                    </tr>
                                    <tr>
                                        <td>Izin:</td>
                                        <td class="text-bold text-center">{{ $app->attendances->where('status', 'izin')->count() }} hari</td>
                                    </tr>
                                    <tr>
                                        <td>Alfa:</td>
                                        <td class="text-bold text-center">{{ $app->attendances->where('status', 'alfa')->count() }} hari</td>
                                    </tr>
                                    <tr>
                                        <td>Pending:</td>
                                        <td class="text-bold text-center" style="{{ $app->attendances->where('validation_status', 'pending')->count() > 0 ? 'color: red;' : '' }}">
                                            {{ $app->attendances->where('validation_status', 'pending')->count() }} hari
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            {{-- Tabel Anak: Kepatuhan Logbook --}}
                            <div style="width: 30%; float: left; margin-right: 4%;">
                                <div class="detail-title" style="color: #7c3aed; border-bottom: 1px solid #f3e8ff; padding-bottom: 2px;">
                                    Kepatuhan Logbook
                                </div>
                                <table class="detail-table">
                                    <tr>
                                        <td>Total Jurnal:</td>
                                        <td class="text-bold text-center">{{ $app->logs->count() }} entri</td>
                                    </tr>
                                    <tr>
                                        <td>Disetujui:</td>
                                        <td class="text-bold text-center text-green">{{ $app->logs->where('status_validasi', 'disetujui')->count() }} entri</td>
                                    </tr>
                                    <tr>
                                        <td>Pending:</td>
                                        <td class="text-bold text-center" style="{{ $app->logs->where('status_validasi', 'pending')->count() > 0 ? 'color: red;' : '' }}">
                                            {{ $app->logs->where('status_validasi', 'pending')->count() }} entri
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Revisi:</td>
                                        <td class="text-bold text-center" style="{{ $app->logs->where('status_validasi', 'revisi')->count() > 0 ? 'color: orange;' : '' }}">
                                            {{ $app->logs->where('status_validasi', 'revisi')->count() }} entri
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            {{-- Tabel Anak: Penilaian Akhir --}}
                            <div style="width: 32%; float: left;">
                                <div class="detail-title" style="color: #ea580c; border-bottom: 1px solid #ffedd5; padding-bottom: 2px;">
                                    Penilaian &amp; Sertifikat
                                </div>
                                @if($app->status?->value === 'selesai')
                                    <div style="font-size: 7px; background-color: #f9fafb; padding: 4px; border: 1px solid #e5e7eb; border-radius: 3px; line-height: 1.2;">
                                        <div style="margin-bottom: 2px;">
                                            Kerajinan: <strong>{{ $app->nilai_kerajinan ?? '-' }}</strong> &nbsp;|&nbsp; 
                                            Disiplin: <strong>{{ $app->nilai_disiplin ?? '-' }}</strong> &nbsp;|&nbsp; 
                                            Adaptasi: <strong>{{ $app->nilai_adaptasi ?? '-' }}</strong>
                                        </div>
                                        <div style="margin-bottom: 3px;">
                                            Kreatifitas: <strong>{{ $app->nilai_kreatifitas ?? '-' }}</strong> &nbsp;|&nbsp; 
                                            Skill: <strong>{{ $app->nilai_skill_pengetahuan ?? '-' }}</strong>
                                        </div>
                                        <div style="border-top: 1px solid #ddd; padding-top: 3px; font-size: 8px;">
                                            Rerata: <strong>{{ round($app->avg_nilai, 1) }} ({{ $app->predikat ?? '-' }})</strong><br>
                                            @if($app->nomor_sertifikat)
                                                Sertifikat: <span style="font-family: monospace; font-weight: bold; background: #fff; border: 1px solid #ccc; padding: 0px 2px;">{{ $app->nomor_sertifikat }}</span><br>
                                            @endif
                                            Pembimbing: <strong>{{ $app->pembimbing_lapangan->name ?? '-' }}</strong>
                                        </div>
                                        @if($app->catatan_pembimbing_lapangan)
                                            <div style="margin-top: 3px; border-top: 1px dashed #ccc; padding-top: 2px; font-size: 7px; color: #555; font-style: italic;">
                                                Catatan: "{{ $app->catatan_pembimbing_lapangan }}"
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <p style="font-size: 8px; color: #666; font-style: italic; margin-top: 5px; line-height: 1.3;">
                                        Magang sedang berjalan.<br>Nilai akan diinput oleh Pembimbing Lapangan saat peserta menyelesaikan masa magang.
                                    </p>
                                @endif
                            </div>
                            
                            <div style="clear: both;"></div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">Tidak ada data peserta magang aktif/selesai.</td>
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
