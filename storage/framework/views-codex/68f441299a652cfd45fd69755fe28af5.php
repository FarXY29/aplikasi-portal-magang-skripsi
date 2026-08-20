<!DOCTYPE html>
<html>
<head>
    <title>Laporan Analisis Kompetensi &amp; Performa</title>
    <style>
        body { font-family: sans-serif; font-size: 8px; color: #333; line-height: 1.3; }
        .kop-surat { width: 100%; border-bottom: 3px double #333; padding-bottom: 10px; margin-bottom: 15px; }
        .kop-logo { width: 60px; height: auto; }
        .kop-text { text-align: center; }
        .kop-pemerintah { font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
        .kop-dinas { font-size: 15px; font-weight: 800; text-transform: uppercase; margin-top: 2px; }
        .kop-alamat { font-size: 8px; color: #555; margin-top: 3px; font-style: italic; }
        
        .judul-laporan { text-align: center; margin: 15px 0 10px 0; font-weight: bold; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        
        .meta-info { margin-bottom: 12px; font-size: 9px; }
        .meta-info td { border: none; padding: 2px 0; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #aaa; padding: 4px 5px; text-align: left; vertical-align: top; }
        th { background-color: #f3f4f6; text-align: center; font-weight: bold; font-size: 8px; text-transform: uppercase; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        
        .predikat-sangat-baik { color: #16a34a; font-weight: bold; }
        .predikat-baik { color: #2563eb; font-weight: bold; }
        .predikat-cukup { color: #d97706; font-weight: bold; }
        .predikat-kurang { color: #dc2626; font-weight: bold; }
        
        .section-title { 
            font-size: 10px; font-weight: bold; margin: 15px 0 8px 0; 
            padding: 4px 8px; background-color: #f3f4f6; border-left: 4px solid #0d9488;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        
        .stats-table { margin-bottom: 15px; }
        .stats-table td { border: 1px solid #ccc; padding: 6px 4px; text-align: center; }
        .stats-table .label { font-size: 7px; text-transform: uppercase; letter-spacing: 0.5px; color: #666; font-weight: bold; }
        .stats-table .value { font-size: 12px; font-weight: bold; color: #111; margin-top: 2px; }
        
        .footer { margin-top: 20px; font-size: 8px; color: #888; border-top: 1px solid #ccc; padding-top: 8px; }
        
        .ttd-container { width: 100%; margin-top: 30px; display: table; page-break-inside: avoid; }
        .ttd-row { display: table-row; }
        .ttd-col-left { display: table-cell; width: 65%; }
        .ttd-col-right { display: table-cell; width: 35%; text-align: center; }
        .ttd-space { height: 50px; }
    </style>
</head>
<body>

    <table class="kop-surat" style="border: none;">
        <tr style="border: none;">
            <td width="10%" align="center" style="border: none; padding: 0;">
                <img src="<?php echo e(public_path('images/Banjarmasin_Logo.svg.png')); ?>" class="kop-logo" alt="Logo">
            </td>
            <td width="90%" class="kop-text" style="border: none; padding: 0;">
                <div class="kop-pemerintah">PEMERINTAH KOTA BANJARMASIN</div>
                <div class="kop-dinas">BADAN KESATUAN BANGSA DAN POLITIK</div>
                <div class="kop-alamat">Jalan RE Martadinata No. 1, Telp (0511) 3352932, Banjarmasin 70111</div>
            </td>
        </tr>
    </table>

    <div class="judul-laporan"><?php echo nl2br(e($title)); ?></div>

    <div class="meta-info">
        <table style="width: 100%; border: none; margin: 0;">
            <tr style="border: none;">
                <td style="border: none; width: 50%; font-size: 8px; color: #555; padding: 0;">
                    <strong>Dicetak Tanggal:</strong> <?php echo e(\Carbon\Carbon::now()->translatedFormat('d F Y')); ?> <br>
                    <strong>Pencetak:</strong> <?php echo e(Auth::user()->name); ?> (Super Admin)
                </td>
                <td style="border: none; width: 50%; font-size: 8px; color: #555; text-align: right; vertical-align: top; padding: 0;">
                    <strong>Asal Kampus:</strong> <?php echo e($request->instansi ?: 'Semua'); ?> &nbsp;|&nbsp;
                    <strong>Lokasi Dinas:</strong> <?php echo e($request->instansi_id ? 'Filter Dinas Aktif' : 'Semua'); ?> &nbsp;|&nbsp;
                    <strong>Predikat:</strong> <?php echo e($request->predikat ?: 'Semua'); ?>

                </td>
            </tr>
        </table>
    </div>

    
    <div class="section-title">Ringkasan Statistik Kompetensi</div>
    <table class="stats-table">
        <tr>
            <td style="width: 16.66%">
                <div class="label">Total Dinilai</div>
                <div class="value"><?php echo e($stats['total']); ?></div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Sangat Baik</div>
                <div class="value predikat-sangat-baik"><?php echo e($stats['sangat_baik']); ?></div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Baik</div>
                <div class="value predikat-baik"><?php echo e($stats['baik']); ?></div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Cukup</div>
                <div class="value predikat-cukup"><?php echo e($stats['cukup']); ?></div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Kurang</div>
                <div class="value predikat-kurang"><?php echo e($stats['kurang']); ?></div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Rerata Kelulusan</div>
                <div class="value" style="color: #0d9488;"><?php echo e($stats['avg_nilai']); ?></div>
            </td>
        </tr>
    </table>

    <table class="stats-table" style="margin-top: -8px;">
        <tr>
            <td style="width: 33.33%">
                <span class="label">Rerata Kompetensi Teknis:</span> <strong><?php echo e($statsGlobal['avg_teknis']); ?>/100</strong>
            </td>
            <td style="width: 33.33%">
                <span class="label">Rerata Kedisiplinan:</span> <strong><?php echo e($statsGlobal['avg_disiplin']); ?>/100</strong>
            </td>
            <td style="width: 33.33%">
                <span class="label">Rerata Perilaku / Soft Skill:</span> <strong><?php echo e($statsGlobal['avg_perilaku']); ?>/100</strong>
            </td>
        </tr>
    </table>

    
    <div class="section-title">Data Pemeringkatan &amp; Analisis Performa</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%">Rank</th>
                <th style="width: 25%">Nama Peserta &amp; Asal Kampus</th>
                <th style="width: 25%">Penempatan Dinas &amp; Posisi</th>
                <th style="width: 20%">Aspek Nilai (Teknis / Disiplin / Perilaku)</th>
                <th style="width: 13%">Nilai Akhir</th>
                <th style="width: 13%">Predikat</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $gradedList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-center text-bold"><?php echo e($index + 1); ?></td>
                    <td>
                        <strong><?php echo e($data['nama']); ?></strong><br>
                        <span style="font-size: 7px; color: #555;"><?php echo e($data['asal_instansi']); ?></span>
                    </td>
                    <td>
                        <strong><?php echo e($data['instansi']); ?></strong><br>
                        <span style="font-size: 7px; color: #555;"><?php echo e($data['posisi']); ?></span>
                    </td>
                    <td class="text-center">
                        <span style="color: #2563eb;"><?php echo e($data['teknis']); ?></span> /
                        <span style="color: #7c3aed;"><?php echo e($data['disiplin']); ?></span> /
                        <span style="color: #059669;"><?php echo e($data['perilaku']); ?></span>
                    </td>
                    <td class="text-center text-bold" style="font-size: 10px; color: #0d9488;">
                        <?php echo e($data['rata_rata']); ?>

                    </td>
                    <td class="text-center text-bold">
                        <?php
                            $pClass = match($data['predikat']) {
                                'Sangat Baik' => 'predikat-sangat-baik',
                                'Baik' => 'predikat-baik',
                                'Cukup' => 'predikat-cukup',
                                'Kurang' => 'predikat-kurang',
                                default => ''
                            };
                        ?>
                        <span class="<?php echo e($pClass); ?>"><?php echo e($data['predikat']); ?></span>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">Tidak ada data ditemukan.</td>
                </tr>
            <?php endif; ?>
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
        <div class="ttd-row">
            <div class="ttd-col-left"></div>
            <div class="ttd-col-right">
                <p>Banjarmasin, <?php echo e(\Carbon\Carbon::now()->translatedFormat('d F Y')); ?></p>
                <p style="margin-top: 2px;"><?php echo e($pejabatJabatan); ?></p>
                <?php if($ttdFile && file_exists($ttdFile)): ?>
                    <div style="margin: 5px 0;">
                        <img src="<?php echo e($ttdFile); ?>" style="max-height: 60px; max-width: 150px;">
                    </div>
                <?php else: ?>
                    <div class="ttd-space"></div>
                <?php endif; ?>
                <p style="font-weight: bold; text-decoration: underline; margin-bottom: 2px;"><?php echo e($pejabatNama); ?></p>
                <p style="font-size: 8px; color: #555;">NIP. <?php echo e($pejabatNip); ?></p>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Laporan Kompetensi &amp; Performa ini merupakan hasil kumulatif evaluasi program magang Kota Banjarmasin. &copy; <?php echo e(date('Y')); ?></p>
    </div>

    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->get_cpdf()->addJS('print(true);');
        }
    </script>
</body>
</html>
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\pdf\admin_kota\grading.blade.php ENDPATH**/ ?>