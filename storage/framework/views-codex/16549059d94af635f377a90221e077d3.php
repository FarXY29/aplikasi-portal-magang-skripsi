<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Instansi</title>
    <style>
        /* Setup Dasar untuk Kertas A4 */
        @page { margin: 2cm; }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.5;
        }

        /* Styling Kop Surat */
        .kop-surat {
            width: 100%;
            border-bottom: 3px double #000; /* Garis ganda tebal tipis khas surat dinas */
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-logo {
            width: 80px;
            height: auto;
        }
        .kop-text {
            text-align: center;
        }
        .kop-instansi {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-pemerintah {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-alamat {
            font-size: 10pt;
            font-style: italic;
        }

        /* Styling Tabel Data */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data th, table.data td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
            font-size: 11pt;
        }
        table.data th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }

        /* Tanda Tangan */
        .ttd-container {
            width: 100%;
            margin-top: 50px;
            display: table; /* Hack untuk layout kolom di PDF */
        }
        .ttd-box {
            display: table-cell;
            width: 60%;
        }
        .ttd-box-right {
            display: table-cell;
            width: 40%;
            text-align: center;
        }
    </style>
</head>
<body>

    <table class="kop-surat">
        <tr>
            <td width="15%" align="center">
                <img src="<?php echo e(public_path('images/Banjarmasin_Logo.svg.png')); ?>" class="kop-logo" alt="Logo">
            </td>
            <td width="85%" align="center">
                <div class="kop-pemerintah">PEMERINTAH KOTA BANJARMASIN</div>
                <div class="kop-instansi">BADAN KESATUAN BANGSA DAN POLITIK</div>
                <div class="kop-alamat">Jalan RE Martadinata No. 1, Telp (0511) 3352932, Banjarmasin</div>
            </td>
        </tr>
    </table>

    <div style="text-align: center; margin-bottom: 20px;">
        <span style="font-weight: bold; text-decoration: underline; font-size: 14pt;">LAPORAN DATA Instansi</span>
    </div>

    <table class="data">
    <thead>
        <tr>
            <th width="5%">No.</th>
            <th width="25%">Nama Dinas / Instansi</th>
            <th width="15%">Kode Unit</th>
            <th width="10%">Jml Lowongan</th>
            <th width="10%">Jml Peserta</th>
            <th width="25%">Alamat Kantor</th>
            <th width="10%">Koordinat</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $instansis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $instansi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td style="text-align: center;"><?php echo e($index + 1); ?></td>
            <td><?php echo e($instansi->nama_dinas); ?></td>
            <td style="text-align: center;"><?php echo e($instansi->kode_unit_kerja); ?></td>
            
            <td style="text-align: center;">
                <?php echo e($instansi->positions->count()); ?>

            </td>
            <td style="text-align: center;">
                <?php echo e($instansi->positions->flatMap->applications->whereIn('status', ['diterima', 'selesai'])->count()); ?>

            </td>

            <td><?php echo e($instansi->alamat); ?></td>
            <td style="font-size: 9pt;">
                <?php echo e($instansi->latitude); ?>, <?php echo e($instansi->longitude); ?>

            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>

    <?php
        $pejabatNama = $pejabat_nama ?? \App\Models\Setting::value('pejabat_name') ?? 'H. Lukman Fadlun, SH';
        $pejabatNip = $pejabat_nip ?? \App\Models\Setting::value('pejabat_nip') ?? '-';
        $pejabatJabatan = $pejabat_jabatan ?? \App\Models\Setting::value('pejabat_jabatan') ?? 'Kepala Bakesbangpol Kota Banjarmasin';
        $ttdImg = \App\Models\Setting::value('ttd_image');
        $ttdFile = $ttd_image_path ?? ($ttdImg && \Illuminate\Support\Facades\Storage::disk('public')->exists($ttdImg) ? storage_path('app/public/' . $ttdImg) : null);
    ?>

    <div class="ttd-container">
        <div class="ttd-box-right">
            <p>Banjarmasin, <?php echo e(\Carbon\Carbon::now()->translatedFormat('d F Y')); ?></p>
            <p style="margin-top: 2px;"><?php echo e($pejabatJabatan); ?></p>
            <?php if($ttdFile && file_exists($ttdFile)): ?>
                <div style="margin: 5px 0;">
                    <img src="<?php echo e($ttdFile); ?>" style="max-height: 60px; max-width: 150px;">
                </div>
            <?php else: ?>
                <br><br><br><br>
            <?php endif; ?>
            <p style="font-weight: bold; text-decoration: underline; margin-bottom: 2px;"><?php echo e($pejabatNama); ?></p>
            <p style="font-size: 8px; color: #555;">NIP. <?php echo e($pejabatNip); ?></p>
        </div>
    </div>

</body>
</html>
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\pdf\admin_kota\instansi.blade.php ENDPATH**/ ?>