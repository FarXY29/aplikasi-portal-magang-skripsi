<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Aktivitas & Logbook Magang - {{ $user->name }}</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm 1.5cm 1.5cm;
            size: A4 portrait;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 9.5pt;
            color: #000;
            line-height: 1.3;
        }
        
        .judul-laporan {
            text-align: center;
            margin: 8px 0 12px 0;
            font-weight: bold;
            font-size: 11.5pt;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 0.5px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9pt;
        }
        .info-table td {
            padding: 2px 0;
            vertical-align: top;
            border: none;
        }
        .label {
            width: 130px;
            font-weight: bold;
        }

        .section-title {
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 10px 0 5px 0;
            padding: 3px 6px;
            background-color: #f1f5f9;
            border-left: 3px solid #000;
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
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #444;
            font-weight: bold;
        }
        .stats-table .stat-value {
            font-size: 11pt;
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
            padding: 5px 6px;
            text-align: left;
            vertical-align: top;
            font-size: 8.5pt;
        }
        table.data-table th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        
        .status-disetujui { color: #15803d; font-weight: bold; }
        .status-pending { color: #b45309; font-weight: bold; }
        .status-revisi { color: #b91c1c; font-weight: bold; }
    </style>
</head>
<body>

    @include('pdf.partials.kop_admin_instansi', ['instansi' => $app->position->instansi])

    <div class="judul-laporan">LEMBAR REKAPITULASI AKTIVITAS &amp; LOGBOOK HARIAN MAGANG</div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Peserta</td>
            <td style="width: 35%;">: <strong>{{ $user->name }}</strong></td>
            <td class="label">Posisi Magang</td>
            <td style="width: 35%;">: {{ $app->position->judul_posisi ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">NIM / NIK</td>
            <td>: {{ $user->nim ?? ($user->nik ?? ($user->nomor_induk ?? '-')) }}</td>
            <td class="label">Pembimbing Lapangan</td>
            <td>: {{ $app->pembimbing_lapangan->name ?? 'Belum Ditugaskan' }}</td>
        </tr>
        <tr>
            <td class="label">Asal Kampus / Sekolah</td>
            <td>: {{ $user->asal_instansi ?? ($user->university->name ?? ($user->school->name ?? '-')) }}</td>
            <td class="label">Periode Magang</td>
            <td>: 
                @if($app->tanggal_mulai && $app->tanggal_selesai)
                    {{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d M Y') }}
                @else
                    -
                @endif
            </td>
        </tr>
    </table>

    @php
        $cntTotal = $logs->count();
        $cntApproved = $logs->whereIn('status_validasi', ['disetujui', 'approved', 'valid'])->count();
        $cntPending = $logs->whereIn('status_validasi', ['pending', 'menunggu'])->count();
        $cntRevisi = $logs->whereIn('status_validasi', ['revisi', 'rejected'])->count();
        $rateApproved = $cntTotal > 0 ? round(($cntApproved / $cntTotal) * 100) : 0;
    @endphp

    {{-- Ringkasan Statistik Logbook --}}
    <div class="section-title">Ringkasan Statistik Aktivitas &amp; Logbook</div>
    <table class="stats-table">
        <tr>
            <td style="width: 25%">
                <div class="stat-label">Total Entri Jurnal</div>
                <div class="stat-value">{{ $cntTotal }}</div>
            </td>
            <td style="width: 25%">
                <div class="stat-label">Disetujui / Valid</div>
                <div class="stat-value status-disetujui">{{ $cntApproved }}</div>
            </td>
            <td style="width: 25%">
                <div class="stat-label">Pending / Menunggu</div>
                <div class="stat-value status-pending">{{ $cntPending }}</div>
            </td>
            <td style="width: 25%">
                <div class="stat-label">Tingkat Validasi</div>
                <div class="stat-value" style="color: #0f766e;">{{ $rateApproved }}%</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Rincian Logbook --}}
    <div class="section-title">Rincian Jurnal Aktivitas Harian</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%">No</th>
                <th style="width: 18%">Hari, Tanggal</th>
                <th style="width: 58%">Uraian Aktivitas &amp; Output Kegiatan</th>
                <th style="width: 20%">Paraf Pembimbing</th>
            </tr>
        </thead>
        <tbody>
            @php
                $ttdPlPath = null;
                if (!empty($app->pembimbing_lapangan?->signature)) {
                    $rawPl = $app->pembimbing_lapangan->signature;
                    if (\Illuminate\Support\Facades\Storage::disk('private')->exists($rawPl)) {
                        $ttdPlPath = \Illuminate\Support\Facades\Storage::disk('private')->path($rawPl);
                    } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($rawPl)) {
                        $ttdPlPath = \Illuminate\Support\Facades\Storage::disk('public')->path($rawPl);
                    } elseif (file_exists(storage_path('app/public/' . $rawPl))) {
                        $ttdPlPath = storage_path('app/public/' . $rawPl);
                    } elseif (file_exists(public_path('storage/' . $rawPl))) {
                        $ttdPlPath = public_path('storage/' . $rawPl);
                    }
                }
            @endphp
            @forelse($logs as $index => $log)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">
                        <strong>{{ \Carbon\Carbon::parse($log->tanggal)->format('d/m/Y') }}</strong><br>
                        <span style="font-size: 7.5pt; color: #444;">{{ \Carbon\Carbon::parse($log->tanggal)->isoFormat('dddd') }}</span>
                    </td>
                    <td>
                        <div style="white-space: pre-wrap; word-wrap: break-word; line-height: 1.3;">{{ $log->kegiatan }}</div>
                        @if($log->komentar_pembimbing_lapangan)
                            <div style="margin-top: 4px; padding-top: 3px; border-top: 1px dashed #bbb; color: #444; font-size: 7.5pt; font-style: italic;">
                                <strong>Catatan Pembimbing:</strong> "{{ $log->komentar_pembimbing_lapangan }}"
                            </div>
                        @endif
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        @if(in_array($log->status_validasi, ['approved', 'disetujui', 'valid']))
                            @if($ttdPlPath && file_exists($ttdPlPath))
                                <img src="{{ $ttdPlPath }}" style="max-height: 32px; max-width: 80px; display: block; margin: 0 auto;">
                                <span style="font-size: 7pt; color: #15803d; font-weight: bold;">(Disetujui)</span>
                            @else
                                <span class="status-disetujui">&#10003; Disetujui</span>
                            @endif
                        @elseif(in_array($log->status_validasi, ['revisi', 'rejected']))
                            <span class="status-revisi">Perlu Revisi</span>
                        @else
                            <span class="status-pending">Menunggu</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="padding: 15px;">Belum ada catatan aktivitas logbook harian yang tercatat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Blok Tanda Tangan Dual Signature --}}
    @include('pdf.partials.ttd_admin_instansi', [
        'mode' => 'dual',
        'instansi' => $app->position->instansi,
        'pembimbing_lapangan' => $app->pembimbing_lapangan
    ])

    {{-- Penomoran Halaman & Catatan Kaki --}}
    @include('pdf.partials.footer_page_number')

</body>
</html>