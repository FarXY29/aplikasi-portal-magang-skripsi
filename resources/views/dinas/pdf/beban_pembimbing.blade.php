<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kinerja dan Beban Pembimbing Lapangan</title>
    <style>
        body { font-family: sans-serif; font-size: 9px; color: #333; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 3px double #333; padding-bottom: 12px; }
        .header h2 { margin: 0; text-transform: uppercase; font-size: 14px; letter-spacing: 1px; }
        .header h3 { margin: 3px 0; font-size: 12px; }
        .header p { margin: 2px 0; font-size: 10px; color: #555; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #aaa; padding: 4px 5px; text-align: left; vertical-align: middle; }
        th { background-color: #f3f4f6; text-align: center; font-weight: bold; font-size: 8px; text-transform: uppercase; }
        
        .meta-info { margin-bottom: 12px; font-size: 10px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .text-green { color: #16a34a; }
        .text-red { color: #dc2626; }
        .text-teal { color: #0d9488; }
        .text-blue { color: #2563eb; }
        
        .section-title { 
            font-size: 11px; font-weight: bold; margin: 15px 0 8px 0; 
            padding: 5px 8px; background-color: #f3f4f6; border-left: 4px solid #0d9488;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        
        .stats-table { margin-bottom: 15px; }
        .stats-table td { border: 1px solid #ccc; padding: 6px 8px; text-align: center; }
        .stats-table .label { font-size: 8px; text-transform: uppercase; letter-spacing: 0.5px; color: #666; }
        .stats-table .value { font-size: 14px; font-weight: bold; color: #111; }
        
        .pembimbing-row { background-color: #f9fafb; font-weight: bold; font-size: 9px; }
        
        .detail-card { background-color: #ffffff; padding: 5px; margin-top: 3px; border: 1px solid #e5e7eb; border-radius: 4px; }
        .detail-title { font-weight: bold; color: #374151; font-size: 8px; margin-bottom: 3px; text-transform: uppercase; }
        .detail-table { width: 100%; border-collapse: collapse; margin-top: 2px; }
        .detail-table th { background-color: #f9fafb; font-size: 7px; color: #4b5563; padding: 3px; }
        .detail-table td { font-size: 8px; padding: 3px; border: 1px solid #e5e7eb; }
        
        .footer { margin-top: 20px; font-size: 8px; color: #888; border-top: 1px solid #ccc; padding-top: 8px; }
        .badge { display: inline-block; padding: 1px 3px; border-radius: 2px; font-size: 7px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h2>PEMERINTAH KOTA BANJARMASIN</h2>
        <h3>{{ Auth::user()->instansi->nama_dinas ?? 'DINAS TERKAIT' }}</h3>
        <p>Laporan Kinerja & Beban Kerja Pembimbing Lapangan</p>
    </div>

    <div class="meta-info">
        <p><strong>Dicetak Tanggal:</strong> {{ date('d F Y') }} &nbsp;|&nbsp; <em>Oleh: {{ Auth::user()->name }}</em></p>
    </div>

    {{-- Ringkasan Statistik --}}
    <div class="section-title">Ringkasan Statistik</div>
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
    <table>
        <thead>
            <tr>
                <th style="width: 3%">No</th>
                <th style="width: 27%">Nama Pembimbing Lapangan</th>
                <th style="width: 12%">Bimbingan Aktif</th>
                <th style="width: 12%">Alumni Lulus</th>
                <th style="width: 12%">Logbook Pending</th>
                <th style="width: 12%">Rata-rata Nilai</th>
                <th style="width: 22%">Informasi Kontak</th>
            </tr>
        </thead>
        <tbody>
            @forelse($beban as $pl)
                {{-- Baris Profil Pembimbing --}}
                <tr class="pembimbing-row" style="background-color: #f3f4f6;">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $pl->name }}</strong><br>
                        <small style="font-weight: normal; color: #555;">NIP/NIK: {{ $pl->nik ?? '-' }}</small>
                    </td>
                    <td class="text-center text-blue">{{ $pl->total_bimbingan_aktif }} Org</td>
                    <td class="text-center text-green">{{ $pl->total_lulus }} Org</td>
                    <td class="text-center {{ $pl->logbook_tertunda > 0 ? 'text-red text-bold' : 'text-green' }}">
                        {{ $pl->logbook_tertunda > 0 ? $pl->logbook_tertunda . ' Pending' : 'Tuntas' }}
                    </td>
                    <td class="text-center">
                        {{ $pl->rata_nilai_diberikan > 0 ? round($pl->rata_nilai_diberikan, 1) : '-' }}
                    </td>
                    <td style="font-weight: normal; color: #444;">
                        {{ $pl->email }}<br>
                        <small>{{ $pl->phone ?? '-' }}</small>
                    </td>
                </tr>
                
                {{-- Baris Detail Mahasiswa --}}
                <tr>
                    <td colspan="7" style="padding: 8px 10px; background-color: #ffffff;">
                        <div style="width: 100%;">
                            
                            {{-- Tabel Anak: Mahasiswa Aktif --}}
                            <div style="width: 48%; float: left; margin-right: 4%;">
                                <div class="detail-title" style="color: #2563eb; border-bottom: 1px solid #dbeafe; padding-bottom: 2px;">
                                    Mahasiswa Aktif ({{ count($pl->mahasiswa_aktif) }})
                                </div>
                                @if(count($pl->mahasiswa_aktif) > 0)
                                    <table class="detail-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">No</th>
                                                <th style="width: 45%">Nama / Asal Kampus</th>
                                                <th style="width: 30%">Logbook</th>
                                                <th style="width: 20%">Absen Pend.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pl->mahasiswa_aktif as $mhs)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>
                                                        <strong>{{ $mhs['nama'] }}</strong><br>
                                                        <span style="font-size: 7px; color: #666;">{{ $mhs['kampus'] }} ({{ $mhs['posisi'] }})</span>
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $mhs['logbook']['disetujui'] }}/{{ $mhs['logbook']['total'] }} ({{ $mhs['logbook']['rate'] }}%)
                                                        @if($mhs['logbook']['pending'] > 0)
                                                            <br><span style="color: #dc2626; font-size: 7px; font-weight: bold;">{{ $mhs['logbook']['pending'] }} Pending</span>
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
                                    <p style="font-size: 8px; color: #888; font-style: italic; margin-top: 5px;">Tidak ada bimbingan aktif.</p>
                                @endif
                            </div>
                            
                            {{-- Tabel Anak: Mahasiswa Lulus --}}
                            <div style="width: 48%; float: left;">
                                <div class="detail-title" style="color: #059669; border-bottom: 1px solid #dcfce7; padding-bottom: 2px;">
                                    Alumni Lulus ({{ count($pl->mahasiswa_lulus) }})
                                </div>
                                @if(count($pl->mahasiswa_lulus) > 0)
                                    <table class="detail-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">No</th>
                                                <th style="width: 45%">Nama / Asal Kampus</th>
                                                <th style="width: 20%">Nilai / Pred.</th>
                                                <th style="width: 30%">Sertifikat</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pl->mahasiswa_lulus as $mhs)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>
                                                        <strong>{{ $mhs['nama'] }}</strong><br>
                                                        <span style="font-size: 7px; color: #666;">{{ $mhs['kampus'] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <strong>{{ $mhs['nilai'] }}</strong><br>
                                                        <span style="font-size: 6px; color: #059669;">{{ $mhs['predikat'] }}</span>
                                                    </td>
                                                    <td>
                                                        <span style="font-size: 7px; font-family: monospace;">{{ $mhs['nomor_sertifikat'] ?: '-' }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p style="font-size: 8px; color: #888; font-style: italic; margin-top: 5px;">Belum ada alumni lulus.</p>
                                @endif
                            </div>
                            
                            <div style="clear: both;"></div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">Tidak ada data pembimbing lapangan.</td>
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
