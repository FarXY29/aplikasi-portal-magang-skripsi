<?php

function mk($path, $content) {
    $dir = dirname($path);
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    file_put_contents($path, $content);
    echo "Created: $path\n";
}

$base = __DIR__ . '/resources/views/';

$template = <<<'HTML'
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

function makeFile($path, $title, $thead, $tbody) {
    global $base, $template;
    $content = str_replace(
        ['{{TITLE}}', '{{TITLE_UPPER}}', '{{THEAD}}', '{{TBODY}}'],
        [$title, strtoupper($title), $thead, $tbody],
        $template
    );
    mk($base . $path, $content);
}

// 1. Grading
makeFile('admin/pdf/grading.blade.php', 'Laporan Penilaian & Pemeringkatan Global', <<<'THEAD'
                <th>Nama Peserta</th>
                <th>Instansi</th>
                <th>Nilai Rata-rata</th>
                <th>Predikat</th>
THEAD
, <<<'TBODY'
            @foreach($ranking as $index => $data)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $data['nama'] }}</td>
                <td>{{ $data['instansi'] }}</td>
                <td style="text-align: center;">{{ $data['rata_rata'] }}</td>
                <td style="text-align: center;">{{ $data['predikat'] }}</td>
            </tr>
            @endforeach
TBODY);

// 2. Disiplin
makeFile('admin/pdf/instansi_disiplin.blade.php', 'Laporan Instansi Paling Disiplin', <<<'THEAD'
                <th>Nama Instansi</th>
                <th>Total Kehadiran</th>
                <th>Pelanggaran</th>
                <th>Tingkat Kedisiplinan</th>
THEAD
, <<<'TBODY'
            @foreach($instansis as $index => $data)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $data->nama_dinas }}</td>
                <td style="text-align: center;">{{ $data->total_attendances }}</td>
                <td style="text-align: center;">{{ $data->total_pelanggaran }}</td>
                <td style="text-align: center;">{{ number_format($data->tingkat_disiplin, 1) }}%</td>
            </tr>
            @endforeach
TBODY);

// 3. Durasi Magang
makeFile('admin/pdf/durasi_magang.blade.php', 'Laporan Rata-rata Durasi Magang', <<<'THEAD'
                <th>Nama Instansi</th>
                <th>Rata-rata Durasi (Hari)</th>
                <th>Estimasi Bulan</th>
THEAD
, <<<'TBODY'
            @foreach($instansis as $index => $data)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $data->nama_dinas }}</td>
                <td style="text-align: center;">{{ $data->avg_durasi_hari }} Hari</td>
                <td style="text-align: center;">{{ $data->avg_durasi_bulan }} Bulan</td>
            </tr>
            @endforeach
TBODY);

// 4. Demografi Jurusan
makeFile('admin/pdf/demografi_jurusan.blade.php', 'Laporan Demografi Jurusan Paling Dicari', <<<'THEAD'
                <th>Jurusan / Program Studi</th>
                <th>Total Posisi Dibuka</th>
                <th>Total Kuota Tersedia</th>
THEAD
, <<<'TBODY'
            @foreach($jurusans as $index => $data)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $data->required_major }}</td>
                <td style="text-align: center;">{{ $data->total_lowongan }} Posisi</td>
                <td style="text-align: center;">{{ $data->total_kuota }} Orang</td>
            </tr>
            @endforeach
TBODY);

// 5. Penyerapan Kuota
makeFile('admin/pdf/penyerapan_kuota.blade.php', 'Laporan Kinerja Penyerapan Kuota Magang', <<<'THEAD'
                <th>Nama Instansi</th>
                <th>Total Kuota</th>
                <th>Total Terserap</th>
                <th>Persentase Penyerapan</th>
THEAD
, <<<'TBODY'
            @foreach($penyerapan as $index => $data)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $data->nama_dinas }}</td>
                <td style="text-align: center;">{{ $data->total_kuota }}</td>
                <td style="text-align: center;">{{ $data->total_terserap }}</td>
                <td style="text-align: center;">{{ number_format($data->persentase_penyerapan, 1) }}%</td>
            </tr>
            @endforeach
TBODY);

// 6. Dinas Grading
makeFile('dinas/pdf/grading.blade.php', 'Laporan Penilaian Peserta (Internal)', <<<'THEAD'
                <th>Nama Peserta</th>
                <th>Posisi Magang</th>
                <th>Nilai Rata-rata</th>
                <th>Predikat</th>
THEAD
, <<<'TBODY'
            @foreach($ranking as $index => $data)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $data['nama'] }}</td>
                <td>{{ $data['posisi'] }}</td>
                <td style="text-align: center;">{{ $data['rata_rata'] }}</td>
                <td style="text-align: center;">{{ $data['predikat'] }}</td>
            </tr>
            @endforeach
TBODY);

// 7. Dinas Evaluasi
makeFile('dinas/pdf/evaluasi_pembimbing.blade.php', 'Laporan Evaluasi Kinerja Pembimbing Lapangan', <<<'THEAD'
                <th>Nama Pembimbing</th>
                <th>Total Peserta Bimbingan</th>
                <th>Rata-rata Nilai Diberikan</th>
THEAD
, <<<'TBODY'
            @foreach($evaluasi as $index => $data)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $data->name }}</td>
                <td style="text-align: center;">{{ $data->total_bimbingan }} Orang</td>
                <td style="text-align: center;">{{ number_format($data->rata_nilai, 1) }}</td>
            </tr>
            @endforeach
TBODY);

// 8. Dinas Tren
makeFile('dinas/pdf/tren_pendaftaran.blade.php', 'Laporan Tren Musim Pendaftaran', <<<'THEAD'
                <th>Periode (Bulan - Tahun)</th>
                <th>Jumlah Pelamar Masuk</th>
THEAD
, <<<'TBODY'
            @foreach($tren as $index => $data)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="text-align: center;">{{ date('F', mktime(0, 0, 0, $data->bulan, 10)) }} {{ $data->tahun }}</td>
                <td style="text-align: center;">{{ $data->total }} Orang</td>
            </tr>
            @endforeach
TBODY);

// 9. Dinas Produktivitas
makeFile('dinas/pdf/produktivitas_logbook.blade.php', 'Laporan Produktivitas Pengisian Logbook', <<<'THEAD'
                <th>Nama Peserta</th>
                <th>Total Logbook Diajukan</th>
                <th>Logbook Disetujui</th>
                <th>Tingkat Approval</th>
THEAD
, <<<'TBODY'
            @foreach($produktivitas as $index => $data)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $data->user->name }}</td>
                <td style="text-align: center;">{{ $data->logs_count }}</td>
                <td style="text-align: center;">{{ $data->approved_logs }}</td>
                <td style="text-align: center;">{{ number_format($data->approval_rate, 1) }}%</td>
            </tr>
            @endforeach
TBODY);

// 10. Dinas Keterisian Posisi
makeFile('dinas/pdf/keterisian_posisi.blade.php', 'Laporan Keterisian Posisi (Occupancy Rate)', <<<'THEAD'
                <th>Posisi Magang</th>
                <th>Kuota Tersedia</th>
                <th>Jumlah Diterima</th>
                <th>Tingkat Keterisian</th>
THEAD
, <<<'TBODY'
            @foreach($keterisian as $index => $data)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $data->judul_posisi }}</td>
                <td style="text-align: center;">{{ $data->kuota }}</td>
                <td style="text-align: center;">{{ $data->diterima }}</td>
                <td style="text-align: center;">{{ number_format($data->occupancy_rate, 1) }}%</td>
            </tr>
            @endforeach
TBODY);

// 11. Dinas Saran Peserta
makeFile('dinas/pdf/saran_peserta.blade.php', 'Laporan Kotak Saran Peserta (Anonim)', <<<'THEAD'
                <th>Tanggal Diterima</th>
                <th>Posisi Magang (Anonim)</th>
                <th>Isi Saran / Evaluasi</th>
THEAD
, <<<'TBODY'
            @foreach($sarans as $index => $data)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="text-align: center;">{{ $data->updated_at->format('d/m/Y') }}</td>
                <td>{{ $data->position->judul_posisi }}</td>
                <td><i>"{{ $data->saran_peserta }}"</i></td>
            </tr>
            @endforeach
TBODY);

echo "All Done.\n";
