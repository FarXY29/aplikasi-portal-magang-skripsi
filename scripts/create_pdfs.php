<?php

$basePath = __DIR__ . '/resources/views/';

$template = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>{{TITLE}}</title>
    <style>
        @page { margin: 2cm; }
        body { font-family: "Times New Roman", Times, serif; font-size: 12pt; line-height: 1.5; }
        .kop-surat { width: 100%; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-logo { width: 80px; height: auto; }
        .kop-pemerintah { font-size: 16pt; font-weight: bold; text-transform: uppercase; }
        .kop-alamat { font-size: 10pt; font-style: italic; }
        .judul-laporan { text-align: center; margin-bottom: 20px; font-weight: bold; text-decoration: underline; font-size: 14pt; text-transform: uppercase; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data th, table.data td { border: 1px solid #000; padding: 6px 8px; vertical-align: middle; font-size: 11pt; }
        table.data th { background-color: #f0f0f0; text-align: center; font-weight: bold; text-transform: uppercase; }
        .ttd-container { width: 100%; margin-top: 40px; display: table; page-break-inside: avoid; }
        .ttd-box-right { display: table-cell; width: 40%; text-align: center; float: right; margin-left: auto; }
    </style>
</head>
<body>
    <table class="kop-surat">
        <tr>
            <td width="15%" align="center" style="border: none;">
                <img src="{{ public_path('images/Banjarmasin_Logo.svg.png') }}" class="kop-logo" alt="Logo">
            </td>
            <td width="85%" align="center" style="border: none;">
                <div class="kop-pemerintah">PEMERINTAH KOTA BANJARMASIN</div>
                <div class="kop-alamat">Jalan RE Martadinata No. 1, Telp (0511) 3352932, Banjarmasin</div>
            </td>
        </tr>
    </table>

    <div class="judul-laporan">{{TITLE_UPPER}}</div>

    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
{{THEAD}}
            </tr>
        </thead>
        <tbody>
{{TBODY}}
        </tbody>
    </table>

    <div class="ttd-container">
        <div class="ttd-box-right">
            <p>Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Kepala ........</p>
            <br><br><br><br>
            <p style="font-weight: bold; text-decoration: underline;">NAMA ........</p>
            <p>NIP. ........................</p>
        </div>
    </div>
</body>
</html>
HTML;

$files = [
    // ADMIN KOTA
    [
        'path' => 'admin/pdf/grading.blade.php',
        'title' => 'Laporan Penilaian & Pemeringkatan Global',
        'thead' => "                <th>Nama Peserta</th>\n                <th>Instansi</th>\n                <th>Nilai Rata-rata</th>\n                <th>Predikat</th>",
        'tbody' => "            @foreach($ranking as $index => $data)\n            <tr>\n                <td style=\"text-align: center;\">{{ $index + 1 }}</td>\n                <td>{{ $data['nama'] }}</td>\n                <td>{{ $data['instansi'] }}</td>\n                <td style=\"text-align: center;\">{{ $data['rata_rata'] }}</td>\n                <td style=\"text-align: center;\">{{ $data['predikat'] }}</td>\n            </tr>\n            @endforeach"
    ],
    [
        'path' => 'admin/pdf/instansi_disiplin.blade.php',
        'title' => 'Laporan Instansi Paling Disiplin',
        'thead' => "                <th>Nama Instansi</th>\n                <th>Total Kehadiran</th>\n                <th>Pelanggaran</th>\n                <th>Tingkat Kedisiplinan</th>",
        'tbody' => "            @foreach($instansis as $index => $data)\n            <tr>\n                <td style=\"text-align: center;\">{{ $index + 1 }}</td>\n                <td>{{ $data->nama_dinas }}</td>\n                <td style=\"text-align: center;\">{{ $data->total_attendances }}</td>\n                <td style=\"text-align: center;\">{{ $data->total_pelanggaran }}</td>\n                <td style=\"text-align: center;\">{{ number_format($data->tingkat_disiplin, 1) }}%</td>\n            </tr>\n            @endforeach"
    ],
    [
        'path' => 'admin/pdf/durasi_magang.blade.php',
        'title' => 'Laporan Rata-rata Durasi Magang',
        'thead' => "                <th>Nama Instansi</th>\n                <th>Rata-rata Durasi (Hari)</th>\n                <th>Estimasi Bulan</th>",
        'tbody' => "            @foreach($instansis as $index => $data)\n            <tr>\n                <td style=\"text-align: center;\">{{ $index + 1 }}</td>\n                <td>{{ $data->nama_dinas }}</td>\n                <td style=\"text-align: center;\">{{ $data->avg_durasi_hari }} Hari</td>\n                <td style=\"text-align: center;\">{{ $data->avg_durasi_bulan }} Bulan</td>\n            </tr>\n            @endforeach"
    ],
    [
        'path' => 'admin/pdf/demografi_jurusan.blade.php',
        'title' => 'Laporan Demografi Jurusan Paling Dicari',
        'thead' => "                <th>Jurusan / Program Studi</th>\n                <th>Total Posisi Dibuka</th>\n                <th>Total Kuota Tersedia</th>",
        'tbody' => "            @foreach($jurusans as $index => $data)\n            <tr>\n                <td style=\"text-align: center;\">{{ $index + 1 }}</td>\n                <td>{{ $data->required_major }}</td>\n                <td style=\"text-align: center;\">{{ $data->total_lowongan }} Posisi</td>\n                <td style=\"text-align: center;\">{{ $data->total_kuota }} Orang</td>\n            </tr>\n            @endforeach"
    ],
    [
        'path' => 'admin/pdf/penyerapan_kuota.blade.php',
        'title' => 'Laporan Kinerja Penyerapan Kuota Magang',
        'thead' => "                <th>Nama Instansi</th>\n                <th>Total Kuota</th>\n                <th>Total Terserap</th>\n                <th>Persentase Penyerapan</th>",
        'tbody' => "            @foreach($penyerapan as $index => $data)\n            <tr>\n                <td style=\"text-align: center;\">{{ $index + 1 }}</td>\n                <td>{{ $data->nama_dinas }}</td>\n                <td style=\"text-align: center;\">{{ $data->total_kuota }}</td>\n                <td style=\"text-align: center;\">{{ $data->total_terserap }}</td>\n                <td style=\"text-align: center;\">{{ number_format($data->persentase_penyerapan, 1) }}%</td>\n            </tr>\n            @endforeach"
    ],
    // DINAS
    [
        'path' => 'dinas/pdf/grading.blade.php',
        'title' => 'Laporan Penilaian Peserta (Internal)',
        'thead' => "                <th>Nama Peserta</th>\n                <th>Posisi Magang</th>\n                <th>Nilai Rata-rata</th>\n                <th>Predikat</th>",
        'tbody' => "            @foreach($ranking as $index => $data)\n            <tr>\n                <td style=\"text-align: center;\">{{ $index + 1 }}</td>\n                <td>{{ $data['nama'] }}</td>\n                <td>{{ $data['posisi'] }}</td>\n                <td style=\"text-align: center;\">{{ $data['rata_rata'] }}</td>\n                <td style=\"text-align: center;\">{{ $data['predikat'] }}</td>\n            </tr>\n            @endforeach"
    ],
    [
        'path' => 'dinas/pdf/evaluasi_pembimbing.blade.php',
        'title' => 'Laporan Evaluasi Kinerja Pembimbing Lapangan',
        'thead' => "                <th>Nama Pembimbing</th>\n                <th>Total Peserta Bimbingan</th>\n                <th>Rata-rata Nilai Diberikan</th>",
        'tbody' => "            @foreach($evaluasi as $index => $data)\n            <tr>\n                <td style=\"text-align: center;\">{{ $index + 1 }}</td>\n                <td>{{ $data->name }}</td>\n                <td style=\"text-align: center;\">{{ $data->total_bimbingan }} Orang</td>\n                <td style=\"text-align: center;\">{{ number_format($data->rata_nilai, 1) }}</td>\n            </tr>\n            @endforeach"
    ],
    [
        'path' => 'dinas/pdf/tren_pendaftaran.blade.php',
        'title' => 'Laporan Tren Musim Pendaftaran',
        'thead' => "                <th>Periode (Bulan - Tahun)</th>\n                <th>Jumlah Pelamar Masuk</th>",
        'tbody' => "            @foreach($tren as $index => $data)\n            <tr>\n                <td style=\"text-align: center;\">{{ $index + 1 }}</td>\n                <td style=\"text-align: center;\">{{ date('F', mktime(0, 0, 0, $data->bulan, 10)) }} {{ $data->tahun }}</td>\n                <td style=\"text-align: center;\">{{ $data->total }} Orang</td>\n            </tr>\n            @endforeach"
    ],
    [
        'path' => 'dinas/pdf/produktivitas_logbook.blade.php',
        'title' => 'Laporan Produktivitas Pengisian Logbook',
        'thead' => "                <th>Nama Peserta</th>\n                <th>Total Logbook Diajukan</th>\n                <th>Logbook Disetujui</th>\n                <th>Tingkat Approval</th>",
        'tbody' => "            @foreach($produktivitas as $index => $data)\n            <tr>\n                <td style=\"text-align: center;\">{{ $index + 1 }}</td>\n                <td>{{ $data->user->name }}</td>\n                <td style=\"text-align: center;\">{{ $data->logs_count }}</td>\n                <td style=\"text-align: center;\">{{ $data->approved_logs }}</td>\n                <td style=\"text-align: center;\">{{ number_format($data->approval_rate, 1) }}%</td>\n            </tr>\n            @endforeach"
    ],
    [
        'path' => 'dinas/pdf/keterisian_posisi.blade.php',
        'title' => 'Laporan Keterisian Posisi (Occupancy Rate)',
        'thead' => "                <th>Posisi Magang</th>\n                <th>Kuota Tersedia</th>\n                <th>Jumlah Diterima</th>\n                <th>Tingkat Keterisian</th>",
        'tbody' => "            @foreach($keterisian as $index => $data)\n            <tr>\n                <td style=\"text-align: center;\">{{ $index + 1 }}</td>\n                <td>{{ $data->judul_posisi }}</td>\n                <td style=\"text-align: center;\">{{ $data->kuota }}</td>\n                <td style=\"text-align: center;\">{{ $data->diterima }}</td>\n                <td style=\"text-align: center;\">{{ number_format($data->occupancy_rate, 1) }}%</td>\n            </tr>\n            @endforeach"
    ],
    [
        'path' => 'dinas/pdf/saran_peserta.blade.php',
        'title' => 'Laporan Kotak Saran Peserta (Anonim)',
        'thead' => "                <th>Tanggal Diterima</th>\n                <th>Posisi Magang (Anonim)</th>\n                <th>Isi Saran / Evaluasi</th>",
        'tbody' => "            @foreach($sarans as $index => $data)\n            <tr>\n                <td style=\"text-align: center;\">{{ $index + 1 }}</td>\n                <td style=\"text-align: center;\">{{ $data->updated_at->format('d/m/Y') }}</td>\n                <td>{{ $data->position->judul_posisi }}</td>\n                <td><i>\"{{ $data->saran_peserta }}\"</i></td>\n            </tr>\n            @endforeach"
    ],
];

foreach ($files as $file) {
    $dir = dirname($basePath . $file['path']);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    
    $content = str_replace(
        ['{{TITLE}}', '{{TITLE_UPPER}}', '{{THEAD}}', '{{TBODY}}'],
        [$file['title'], strtoupper($file['title']), $file['thead'], $file['tbody']],
        $template
    );
    
    file_put_contents($basePath . $file['path'], $content);
    echo "Created: " . $file['path'] . "\n";
}
echo "Done!\n";
