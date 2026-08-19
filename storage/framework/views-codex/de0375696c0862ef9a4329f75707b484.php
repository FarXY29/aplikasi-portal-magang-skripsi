<!DOCTYPE html>
<html>
<head>
    <title>Laporan Statistik Magang Instansi</title>
    <style>
        body { font-family: sans-serif; font-size: 9px; color: #333; line-height: 1.4; }
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
        th, td { border: 1px solid #aaa; padding: 5px 6px; text-align: left; vertical-align: middle; }
        th { background-color: #f3f4f6; text-align: center; font-weight: bold; font-size: 8px; text-transform: uppercase; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        
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
        .ttd-col-left { display: table-cell; width: 60%; }
        .ttd-col-right { display: table-cell; width: 40%; text-align: center; }
        .ttd-space { height: 50px; }
    </style>
</head>
<body>

    <table class="kop-surat" style="border: none;">
        <tr style="border: none;">
            <td width="12%" align="center" style="border: none; padding: 0;">
                <img src="<?php echo e(public_path('images/Banjarmasin_Logo.svg.png')); ?>" class="kop-logo" alt="Logo">
            </td>
            <td width="88%" class="kop-text" style="border: none; padding: 0;">
                <div class="kop-pemerintah">PEMERINTAH KOTA BANJARMASIN</div>
                <div class="kop-dinas">BADAN KESATUAN BANGSA DAN POLITIK</div>
                <div class="kop-alamat">Jalan RE Martadinata No. 1, Telp (0511) 3352932, Banjarmasin 70111</div>
            </td>
        </tr>
    </table>

    <div class="judul-laporan">LAPORAN REKAPITULASI PROGRAM MAGANG KOTA BANJARMASIN</div>

    <div class="meta-info">
        <table style="width: 100%; border: none; margin: 0;">
            <tr style="border: none;">
                <td style="border: none; width: 50%; font-size: 8px; color: #555;">
                    <strong>Dicetak Tanggal:</strong> <?php echo e(\Carbon\Carbon::now()->translatedFormat('d F Y')); ?> <br>
                    <strong>Pencetak:</strong> <?php echo e(Auth::user()->name); ?> (Super Admin)
                </td>
                <td style="border: none; width: 50%; font-size: 8px; color: #555; text-align: right; vertical-align: top;">
                    <?php if(isset($request) && $request->filled('search')): ?>
                        <strong>Filter Pencarian:</strong> "<?php echo e($request->search); ?>" &nbsp;|&nbsp;
                    <?php endif; ?>
                    <strong>Urutan:</strong> 
                    <?php
                        $sortLabel = 'Peminat Terbanyak';
                        if(isset($request)) {
                            $sort = $request->sort;
                            if($sort == 'pelamar_asc') $sortLabel = 'Peminat Tersedikit';
                            elseif($sort == 'name_asc') $sortLabel = 'Nama Instansi (A - Z)';
                            elseif($sort == 'name_desc') $sortLabel = 'Nama Instansi (Z - A)';
                            elseif($sort == 'lowongan_desc') $sortLabel = 'Lowongan Terbanyak';
                            elseif($sort == 'lowongan_asc') $sortLabel = 'Lowongan Tersedikit';
                            elseif($sort == 'seleksi_desc') $sortLabel = 'Rasio Kelulusan Tertinggi';
                            elseif($sort == 'seleksi_asc') $sortLabel = 'Rasio Kelulusan Terendah';
                        }
                    ?>
                    <?php echo e($sortLabel); ?>

                </td>
            </tr>
        </table>
    </div>

    
    <div class="section-title">Ringkasan Statistik Kota</div>
    <table class="stats-table">
        <tr>
            <td style="width: 16.66%">
                <div class="label">Total Instansi</div>
                <div class="value" style="color: #0f766e;"><?php echo e($stats['total_instansi']); ?></div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Lowongan Aktif</div>
                <div class="value" style="color: #1d4ed8;"><?php echo e($stats['total_lowongan']); ?></div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Total Pelamar</div>
                <div class="value" style="color: #4f46e5;"><?php echo e($stats['total_pelamar']); ?></div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Diterima/Selesai</div>
                <div class="value" style="color: #15803d;"><?php echo e($stats['total_diterima']); ?></div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Rasio Kelulusan</div>
                <div class="value" style="color: #b45309;"><?php echo e($stats['avg_seleksi_rate']); ?>%</div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Instansi Terfavorit</div>
                <div class="value" style="color: #be123c; font-size: 8px; line-height: 1.1; font-weight: 800;" title="<?php echo e($stats['fav_dinas']); ?>"><?php echo e($stats['fav_dinas']); ?></div>
            </td>
        </tr>
    </table>

    
    <div class="section-title">Data Statistik Instansi</div>
    <table>
        <thead>
            <tr>
                <th style="width: 4%">No</th>
                <th style="width: 38%">Nama Instansi / Dinas</th>
                <th style="width: 13%">Lowongan Aktif</th>
                <th style="width: 13%">Total Pelamar</th>
                <th style="width: 13%">Diterima / Selesai</th>
                <th style="width: 11%">Tingkat Seleksi</th>
                <th style="width: 8%">Rasio Peminat</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $laporan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-center"><?php echo e($index + 1); ?></td>
                    <td class="text-bold"><?php echo e($data['nama_dinas']); ?></td>
                    <td class="text-center"><?php echo e($data['lowongan_aktif']); ?> Posisi</td>
                    <td class="text-center"><?php echo e($data['total_pelamar']); ?> Orang</td>
                    <td class="text-center"><?php echo e($data['total_magang']); ?> Orang</td>
                    <td class="text-center text-bold" style="color: #0f766e;"><?php echo e($data['seleksi_rate']); ?>%</td>
                    <td class="text-center" style="font-style: italic; color: #555;"><?php echo e($data['avg_peminat']); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">Tidak ada data statistik ditemukan.</td>
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
        <p>Dokumen ini merupakan laporan statistik resmi program magang Kota Banjarmasin. Dicetak otomatis dari sistem. &copy; <?php echo e(date('Y')); ?></p>
    </div>

    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->get_cpdf()->addJS('print(true);');
        }
    </script>
</body>
</html>
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\pdf\admin_kota\laporan.blade.php ENDPATH**/ ?>