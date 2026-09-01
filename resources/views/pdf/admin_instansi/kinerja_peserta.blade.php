<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Evaluasi Kinerja Peserta Magang</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm 1.5cm 1.5cm;
            size: A4 landscape;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 9pt;
            color: #000;
            line-height: 1.3;
        }
        
        .judul-laporan {
            text-align: center;
            margin: 8px 0 10px 0;
            font-weight: bold;
            font-size: 11.5pt;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 0.5px;
        }
        
        .meta-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 8.5pt;
            color: #222;
        }
        .meta-info td {
            border: none;
            padding: 1.5px 0;
        }
        
        .section-title { 
            font-size: 9pt;
            font-weight: bold;
            margin: 10px 0 5px 0; 
            padding: 3px 6px;
            background-color: #f1f5f9;
            border-left: 3px solid #000;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .stats-table td {
            border: 1px solid #444;
            padding: 4px 2px;
            text-align: center;
        }
        .stats-table .stat-label {
            font-size: 6.8pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #444;
            font-weight: bold;
        }
        .stats-table .stat-value {
            font-size: 10.5pt;
            font-weight: bold;
            color: #000;
            margin-top: 1px;
        }
        
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
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
            padding: 4.5px 5px;
            text-align: left;
            vertical-align: middle;
            font-size: 8.2pt;
        }
        table.data-table th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
            font-size: 7.8pt;
            text-transform: uppercase;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        
        .status-aktif { color: #15803d; font-weight: bold; }
        .status-selesai { color: #1d4ed8; font-weight: bold; }
        
        .detail-table { width: 100%; border-collapse: collapse; margin-top: 2px; }
        .detail-table td { font-size: 7.5pt; padding: 2px 4px; border: 1px solid #777; }
    </style>
</head>
<body>

    @include('pdf.partials.kop_admin_instansi', ['instansi' => $instansi ?? null])

    <div class="judul-laporan">LAPORAN EVALUASI KINERJA &amp; SCORECARD PESERTA MAGANG</div>

    <table class="meta-info">
        <tr>
            <td style="width: 50%;">
                <strong>Instansi:</strong> {{ $instansi->nama_dinas ?? (Auth::user()->instansi->nama_dinas ?? '-') }}<br>
                <strong>Dicetak Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
                <strong>Pencetak:</strong> {{ Auth::user()->name ?? 'Admin Instansi' }}
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <strong>Total Peserta Terdaftar:</strong> {{ $stats['total_peserta'] ?? count($kinerja) }} Orang
            </td>
        </tr>
    </table>

    {{-- Ringkasan Statistik --}}
    <div class="section-title">Ringkasan Statistik Evaluasi Kinerja</div>
    <table class="stats-table">
        <tr>
            <td style="width: 16.66%">
                <div class="stat-label">Total Peserta</div>
                <div class="stat-value">{{ $stats['total_peserta'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="stat-label">Peserta Aktif</div>
                <div class="stat-value status-aktif">{{ $stats['aktif'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="stat-label">Alumni Lulus</div>
                <div class="stat-value status-selesai">{{ $stats['selesai'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="stat-label">Rerata Kehadiran</div>
                <div class="stat-value" style="color: #15803d;">{{ $stats['avg_kehadiran'] }}%</div>
            </td>
            <td style="width: 16.66%">
                <div class="stat-label">Rerata Logbook</div>
                <div class="stat-value" style="color: #1d4ed8;">{{ $stats['avg_logbook'] }}%</div>
            </td>
            <td style="width: 16.66%">
                <div class="stat-label">Rerata Nilai Lulus</div>
                <div class="stat-value" style="color: #b45309;">{{ $stats['avg_nilai'] > 0 ? $stats['avg_nilai'] : '-' }}</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Utama --}}
    <div class="section-title">Scorecard Performa &amp; Evaluasi Peserta Magang</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%">No</th>
                <th style="width: 25%">Nama Peserta &amp; Institusi</th>
                <th style="width: 20%">Posisi Magang</th>
                <th style="width: 13%">Kehadiran (%)</th>
                <th style="width: 13%">Logbook (%)</th>
                <th style="width: 13%">Status</th>
                <th style="width: 13%">Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kinerja as $app)
                {{-- Baris Profil Peserta --}}
                <tr style="background-color: #f8fafc;">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                        <strong class="text-bold">{{ $app->user->name }}</strong><br>
                        <span style="font-size: 7.5pt; color: #444;">{{ $app->user->asal_instansi ?? '-' }}</span>
                    </td>
                    <td>{{ $app->position->judul_posisi ?? '-' }}</td>
                    <td class="text-center text-bold">{{ round($app->attendance_rate, 1) }}%</td>
                    <td class="text-center text-bold">{{ round($app->log_rate, 1) }}%</td>
                    <td class="text-center text-bold">
                        <span class="status-{{ $app->status?->value == 'diterima' ? 'aktif' : 'selesai' }}">
                            {{ $app->status?->value == 'diterima' ? 'Aktif' : 'Selesai' }}
                        </span>
                    </td>
                    <td class="text-center text-bold" style="font-size: 9.5pt; color: #0f766e;">
                        {{ $app->avg_nilai > 0 ? round($app->avg_nilai, 1) : '-' }}
                    </td>
                </tr>
                
                {{-- Baris Detail Peserta --}}
                <tr>
                    <td colspan="7" style="padding: 6px 8px; background-color: #ffffff;">
                        <table style="width: 100%; border: none; border-collapse: collapse;">
                            <tr style="border: none;">
                                {{-- Detail Kehadiran --}}
                                <td style="width: 31%; border: none; padding: 0; vertical-align: top;">
                                    <div style="font-size: 7.5pt; font-weight: bold; color: #047857; margin-bottom: 2px;">
                                        RINCIAN KEHADIRAN
                                    </div>
                                    <table class="detail-table">
                                        <tr>
                                            <td>Hadir:</td>
                                            <td class="text-bold text-center">{{ $app->attendances->where('status', 'hadir')->count() }} hari</td>
                                        </tr>
                                        <tr>
                                            <td>Sakit / Izin:</td>
                                            <td class="text-bold text-center">{{ $app->attendances->whereIn('status', ['sakit', 'izin'])->count() }} hari</td>
                                        </tr>
                                        <tr>
                                            <td>Alpa / Tanpa Ket.:</td>
                                            <td class="text-bold text-center {{ $app->attendances->where('status', 'alpa')->count() > 0 ? 'text-bold' : '' }}" style="{{ $app->attendances->where('status', 'alpa')->count() > 0 ? 'color: #b91c1c;' : '' }}">
                                                {{ $app->attendances->where('status', 'alpa')->count() }} hari
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                
                                <td style="width: 3%; border: none;"></td>

                                {{-- Detail Logbook --}}
                                <td style="width: 31%; border: none; padding: 0; vertical-align: top;">
                                    <div style="font-size: 7.5pt; font-weight: bold; color: #6b21a8; margin-bottom: 2px;">
                                        KEPATUHAN LOGBOOK
                                    </div>
                                    <table class="detail-table">
                                        <tr>
                                            <td>Total Jurnal:</td>
                                            <td class="text-bold text-center">{{ $app->logs->count() }} entri</td>
                                        </tr>
                                        <tr>
                                            <td>Disetujui:</td>
                                            <td class="text-bold text-center" style="color: #15803d;">{{ $app->logs->where('status_validasi', 'disetujui')->count() }} entri</td>
                                        </tr>
                                        <tr>
                                            <td>Pending / Revisi:</td>
                                            <td class="text-bold text-center" style="{{ $app->logs->whereIn('status_validasi', ['pending', 'revisi'])->count() > 0 ? 'color: #b91c1c;' : '' }}">
                                                {{ $app->logs->whereIn('status_validasi', ['pending', 'revisi'])->count() }} entri
                                            </td>
                                        </tr>
                                    </table>
                                </td>

                                <td style="width: 3%; border: none;"></td>

                                {{-- Detail Penilaian --}}
                                <td style="width: 32%; border: none; padding: 0; vertical-align: top;">
                                    <div style="font-size: 7.5pt; font-weight: bold; color: #c2410c; margin-bottom: 2px;">
                                        PENILAIAN &amp; SERTIFIKAT
                                    </div>
                                    @if($app->status?->value === 'selesai')
                                        <div style="font-size: 7pt; background-color: #f8fafc; padding: 3px 4px; border: 1px solid #777; line-height: 1.25;">
                                            <div>Rerata: <strong>{{ round($app->avg_nilai, 1) }} ({{ $app->predikat ?? '-' }})</strong></div>
                                            @if($app->nomor_sertifikat)
                                                <div>No. Sertifikat: <span style="font-family: monospace; font-weight: bold;">{{ $app->nomor_sertifikat }}</span></div>
                                            @endif
                                            <div>PL: {{ $app->pembimbing_lapangan->name ?? '-' }}</div>
                                        </div>
                                    @else
                                        <div style="font-size: 7pt; color: #555; font-style: italic; padding: 3px 0;">
                                            Magang aktif berlangsung.<br>Nilai dan sertifikat diterbitkan saat masa magang berakhir.
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 15px;">Tidak ada data peserta magang aktif/selesai.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Blok Tanda Tangan --}}
    @include('pdf.partials.ttd_admin_instansi', ['instansi' => $instansi ?? null])

    {{-- Penomoran Halaman & Catatan Kaki --}}
    @include('pdf.partials.footer_page_number')

</body>
</html>
