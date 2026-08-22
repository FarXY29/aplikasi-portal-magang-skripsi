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
            font-size: 9.5pt;
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
        
        .section-title { 
            font-size: 9.5pt;
            font-weight: bold;
            margin: 12px 0 6px 0; 
            padding: 3px 6px;
            background-color: #f3f4f6;
            border-left: 3px solid #0d9488;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .stats-table td {
            border: 1px solid #555;
            padding: 5px 3px;
            text-align: center;
        }
        .stats-table .label {
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #555;
            font-weight: bold;
        }
        .stats-table .value {
            font-size: 11pt;
            font-weight: bold;
            color: #111;
            margin-top: 1px;
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
            padding: 5px 6px;
            text-align: left;
            vertical-align: middle;
            font-size: 8.5pt;
        }
        table.data-table th {
            background-color: #e5e7eb;
            text-align: center;
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .text-green { color: #16a34a; }
        .text-red { color: #dc2626; }
        .text-orange { color: #ea580c; }
        .text-blue { color: #2563eb; }
        
        .detail-table { width: 100%; border-collapse: collapse; margin-top: 2px; }
        .detail-table td { font-size: 7.5pt; padding: 2px 4px; border: 1px solid #999; }
    </style>
</head>
<body>

    @include('pdf.partials.kop_admin_instansi')

    <div class="judul-laporan">LAPORAN EVALUASI KINERJA PESERTA MAGANG</div>

    <table class="meta-info">
        <tr>
            <td style="width: 50%;">
                <strong>Dicetak Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
                <strong>Pencetak:</strong> {{ Auth::user()->name ?? 'Admin Instansi' }}
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <strong>Total Peserta Terdaftar:</strong> {{ $stats['total_peserta'] ?? count($kinerja) }} Orang
            </td>
        </tr>
    </table>

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
    <div class="section-title">Scorecard Performa &amp; Evaluasi Peserta</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%">No</th>
                <th style="width: 25%">Nama Peserta &amp; Kampus</th>
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
                <tr style="background-color: #f9fafb;">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                        <strong class="text-bold">{{ $app->user->name }}</strong><br>
                        <small style="color: #555;">{{ $app->user->asal_instansi ?? '-' }}</small>
                    </td>
                    <td>{{ $app->position->judul_posisi ?? '-' }}</td>
                    <td class="text-center text-bold">{{ round($app->attendance_rate, 1) }}%</td>
                    <td class="text-center text-bold">{{ round($app->log_rate, 1) }}%</td>
                    <td class="text-center text-bold">
                        <span style="color: {{ $app->status?->value == 'diterima' ? '#16a34a' : '#2563eb' }};">
                            {{ $app->status?->value == 'diterima' ? 'Aktif' : 'Selesai' }}
                        </span>
                    </td>
                    <td class="text-center text-bold" style="font-size: 9.5pt; color: #0d9488;">
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
                                    <div style="font-size: 7.5pt; font-weight: bold; color: #0d9488; margin-bottom: 2px;">
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
                                            <td>Alpa:</td>
                                            <td class="text-bold text-center {{ $app->attendances->where('status', 'alpa')->count() > 0 ? 'text-red' : '' }}">
                                                {{ $app->attendances->where('status', 'alpa')->count() }} hari
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                
                                <td style="width: 3%; border: none;"></td>

                                {{-- Detail Logbook --}}
                                <td style="width: 31%; border: none; padding: 0; vertical-align: top;">
                                    <div style="font-size: 7.5pt; font-weight: bold; color: #7c3aed; margin-bottom: 2px;">
                                        KEPATUHAN LOGBOOK
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
                                            <td>Pending / Revisi:</td>
                                            <td class="text-bold text-center {{ $app->logs->whereIn('status_validasi', ['pending', 'revisi'])->count() > 0 ? 'text-red' : '' }}">
                                                {{ $app->logs->whereIn('status_validasi', ['pending', 'revisi'])->count() }} entri
                                            </td>
                                        </tr>
                                    </table>
                                </td>

                                <td style="width: 3%; border: none;"></td>

                                {{-- Detail Penilaian --}}
                                <td style="width: 32%; border: none; padding: 0; vertical-align: top;">
                                    <div style="font-size: 7.5pt; font-weight: bold; color: #ea580c; margin-bottom: 2px;">
                                        PENILAIAN &amp; SERTIFIKAT
                                    </div>
                                    @if($app->status?->value === 'selesai')
                                        <div style="font-size: 7pt; background-color: #f9fafb; padding: 3px 4px; border: 1px solid #999; line-height: 1.2;">
                                            <div>Rerata: <strong>{{ round($app->avg_nilai, 1) }} ({{ $app->predikat ?? '-' }})</strong></div>
                                            @if($app->nomor_sertifikat)
                                                <div>No. Sertifikat: <span style="font-family: monospace; font-weight: bold;">{{ $app->nomor_sertifikat }}</span></div>
                                            @endif
                                            <div>PL: {{ $app->pembimbing_lapangan->name ?? '-' }}</div>
                                        </div>
                                    @else
                                        <div style="font-size: 7pt; color: #777; font-style: italic; padding: 3px 0;">
                                            Magang aktif berlangsung.<br>Nilai diinput saat masa magang berakhir.
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
    @include('pdf.partials.ttd_admin_instansi')

    {{-- Penomoran Halaman & Catatan Kaki --}}
    @include('pdf.partials.footer_page_number')

</body>
</html>
