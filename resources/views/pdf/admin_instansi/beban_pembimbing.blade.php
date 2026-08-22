<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kinerja &amp; Beban Pembimbing Lapangan</title>
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
        .text-teal { color: #0d9488; }
        .text-blue { color: #2563eb; }
        
        .detail-table { width: 100%; border-collapse: collapse; margin-top: 2px; }
        .detail-table th { background-color: #f3f4f6; font-size: 7.5pt; color: #333; padding: 2px 4px; border: 1px solid #999; }
        .detail-table td { font-size: 7.5pt; padding: 2px 4px; border: 1px solid #999; }
    </style>
</head>
<body>

    @include('pdf.partials.kop_admin_instansi')

    <div class="judul-laporan">LAPORAN KINERJA &amp; BEBAN KERJA PEMBIMBING LAPANGAN</div>

    <table class="meta-info">
        <tr>
            <td style="width: 50%;">
                <strong>Dicetak Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
                <strong>Pencetak:</strong> {{ Auth::user()->name ?? 'Admin Instansi' }}
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <strong>Total Pembimbing Terdaftar:</strong> {{ $stats['total_pembimbing'] ?? count($beban) }} Orang
            </td>
        </tr>
    </table>

    {{-- Ringkasan Statistik --}}
    <div class="section-title">Ringkasan Statistik Pembimbing</div>
    <table class="stats-table">
        <tr>
            <td style="width: 16.66%">
                <div class="label">Total Pembimbing</div>
                <div class="value">{{ $stats['total_pembimbing'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Bimbingan Aktif</div>
                <div class="value text-blue">{{ $stats['total_bimbingan_aktif'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Alumni Lulus</div>
                <div class="value text-green">{{ $stats['total_lulus'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Rata-rata Nilai</div>
                <div class="value text-teal">{{ $stats['rata_nilai_semua'] > 0 ? round($stats['rata_nilai_semua'], 1) : '-' }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Logbook Pending</div>
                <div class="value text-red">{{ $stats['total_logbook_tertunda'] }}</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Tertib Validasi</div>
                <div class="value text-green">{{ $stats['tertib_validasi'] }} PL</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Utama --}}
    <div class="section-title">Detail Kinerja Pembimbing Lapangan</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%">No</th>
                <th style="width: 25%">Nama Pembimbing Lapangan</th>
                <th style="width: 12%">Bimbingan Aktif</th>
                <th style="width: 12%">Alumni Lulus</th>
                <th style="width: 12%">Logbook Pending</th>
                <th style="width: 12%">Rata-rata Nilai</th>
                <th style="width: 24%">Informasi Kontak</th>
            </tr>
        </thead>
        <tbody>
            @forelse($beban as $pl)
                {{-- Baris Profil Pembimbing --}}
                <tr style="background-color: #f9fafb;">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                        <strong class="text-bold">{{ $pl->name }}</strong><br>
                        <small style="color: #555;">NIP/NIK: {{ $pl->nik ?? '-' }}</small>
                    </td>
                    <td class="text-center text-blue text-bold">{{ $pl->total_bimbingan_aktif }} Orang</td>
                    <td class="text-center text-green text-bold">{{ $pl->total_lulus }} Orang</td>
                    <td class="text-center {{ $pl->logbook_tertunda > 0 ? 'text-red text-bold' : 'text-green' }}">
                        {{ $pl->logbook_tertunda > 0 ? $pl->logbook_tertunda . ' Pending' : 'Tuntas' }}
                    </td>
                    <td class="text-center text-bold">
                        {{ $pl->rata_nilai_diberikan > 0 ? round($pl->rata_nilai_diberikan, 1) : '-' }}
                    </td>
                    <td>
                        {{ $pl->email }}<br>
                        <small style="color: #555;">{{ $pl->phone ?? '-' }}</small>
                    </td>
                </tr>
                
                {{-- Baris Detail Mahasiswa --}}
                <tr>
                    <td colspan="7" style="padding: 6px 8px; background-color: #ffffff;">
                        <table style="width: 100%; border: none; border-collapse: collapse;">
                            <tr style="border: none;">
                                {{-- Kolom Mahasiswa Aktif --}}
                                <td style="width: 48%; border: none; padding: 0; vertical-align: top;">
                                    <div style="font-size: 8pt; font-weight: bold; color: #2563eb; margin-bottom: 2px;">
                                        MAHASISWA AKTIF ({{ count($pl->mahasiswa_aktif) }})
                                    </div>
                                    @if(count($pl->mahasiswa_aktif) > 0)
                                        <table class="detail-table">
                                            <thead>
                                                <tr>
                                                    <th style="width: 6%">No</th>
                                                    <th style="width: 50%">Nama / Asal Kampus</th>
                                                    <th style="width: 26%">Logbook</th>
                                                    <th style="width: 18%">Absen P.</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pl->mahasiswa_aktif as $mhs)
                                                    <tr>
                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                        <td>
                                                            <strong>{{ $mhs['nama'] }}</strong><br>
                                                            <span style="font-size: 6.5pt; color: #555;">{{ $mhs['kampus'] }} ({{ $mhs['posisi'] }})</span>
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $mhs['logbook']['disetujui'] }}/{{ $mhs['logbook']['total'] }} ({{ $mhs['logbook']['rate'] }}%)
                                                            @if($mhs['logbook']['pending'] > 0)
                                                                <br><span style="color: #dc2626; font-size: 6.5pt; font-weight: bold;">{{ $mhs['logbook']['pending'] }} Pending</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if($mhs['absensi']['pending'] > 0)
                                                                <span style="color: #dc2626; font-weight: bold;">{{ $mhs['absensi']['pending'] }}</span>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div style="font-size: 7.5pt; color: #888; font-style: italic;">Tidak ada bimbingan aktif.</div>
                                    @endif
                                </td>
                                
                                <td style="width: 4%; border: none;"></td>

                                {{-- Kolom Mahasiswa Lulus --}}
                                <td style="width: 48%; border: none; padding: 0; vertical-align: top;">
                                    <div style="font-size: 8pt; font-weight: bold; color: #059669; margin-bottom: 2px;">
                                        ALUMNI LULUS ({{ count($pl->mahasiswa_lulus) }})
                                    </div>
                                    @if(count($pl->mahasiswa_lulus) > 0)
                                        <table class="detail-table">
                                            <thead>
                                                <tr>
                                                    <th style="width: 6%">No</th>
                                                    <th style="width: 44%">Nama / Asal Kampus</th>
                                                    <th style="width: 22%">Nilai / Predikat</th>
                                                    <th style="width: 28%">No. Sertifikat</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pl->mahasiswa_lulus as $mhs)
                                                    <tr>
                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                        <td>
                                                            <strong>{{ $mhs['nama'] }}</strong><br>
                                                            <span style="font-size: 6.5pt; color: #555;">{{ $mhs['kampus'] }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <strong>{{ $mhs['nilai'] }}</strong><br>
                                                            <span style="font-size: 6.5pt; color: #059669;">{{ $mhs['predikat'] }}</span>
                                                        </td>
                                                        <td style="font-size: 6.5pt; font-family: monospace;">
                                                            {{ $mhs['nomor_sertifikat'] ?: '-' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div style="font-size: 7.5pt; color: #888; font-style: italic;">Belum ada alumni lulus.</div>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 15px;">Tidak ada data pembimbing lapangan.</td>
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
