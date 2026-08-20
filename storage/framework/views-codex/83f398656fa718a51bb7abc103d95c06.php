<!DOCTYPE html>
<html>
<head>
    <title>Laporan Demografi Kampus</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 3px double #333; padding-bottom: 12px; }
        .header h2 { margin: 0; text-transform: uppercase; font-size: 14px; letter-spacing: 1px; }
        .header h3 { margin: 3px 0; font-size: 12px; }
        .header p { margin: 2px 0; font-size: 10px; color: #555; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #999; padding: 5px 6px; text-align: left; vertical-align: top; }
        th { background-color: #e8e8e8; text-align: center; font-weight: bold; font-size: 9px; text-transform: uppercase; }
        
        .meta-info { margin-bottom: 12px; font-size: 10px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .text-green { color: #16a34a; }
        .text-red { color: #dc2626; }
        .text-orange { color: #ea580c; }
        .text-blue { color: #2563eb; }
        
        .section-title { 
            font-size: 11px; font-weight: bold; margin: 20px 0 8px 0; 
            padding: 6px 10px; background-color: #f3f4f6; border-left: 4px solid #ea580c;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        
        .stats-table { margin-bottom: 15px; }
        .stats-table td { border: 1px solid #ccc; padding: 8px 10px; text-align: center; }
        .stats-table .label { font-size: 8px; text-transform: uppercase; letter-spacing: 0.5px; color: #666; }
        .stats-table .value { font-size: 16px; font-weight: bold; color: #111; }
        
        .sub-detail { font-size: 9px; color: #555; padding-left: 10px; }
        .sub-detail span { display: inline-block; background: #f0f0f0; padding: 1px 5px; border-radius: 3px; margin: 1px 2px; font-size: 8px; }
        
        .bar-container { display: inline-block; width: 60px; height: 8px; background: #e5e7eb; border-radius: 4px; vertical-align: middle; }
        .bar-fill { display: inline-block; height: 8px; background: #ea580c; border-radius: 4px; }
        
        .footer { margin-top: 20px; font-size: 9px; color: #888; border-top: 1px solid #ccc; padding-top: 8px; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>

    <div class="header">
        <h2>PEMERINTAH KOTA BANJARMASIN</h2>
        <h3><?php echo e(Auth::user()->instansi->nama_dinas ?? 'DINAS TERKAIT'); ?></h3>
        <p>Laporan Demografi Asal Kampus / Sekolah Pendaftar Magang</p>
    </div>

    <div class="meta-info">
        <p><strong>Dicetak Tanggal:</strong> <?php echo e(date('d F Y')); ?> &nbsp;|&nbsp; <em>Oleh: <?php echo e(Auth::user()->name); ?></em></p>
    </div>

    
    <div class="section-title">Ringkasan Statistik</div>
    <table class="stats-table">
        <tr>
            <td style="width: 20%">
                <div class="label">Asal Kampus</div>
                <div class="value"><?php echo e($stats['total_kampus']); ?></div>
            </td>
            <td style="width: 20%">
                <div class="label">Jurusan</div>
                <div class="value"><?php echo e($stats['total_jurusan']); ?></div>
            </td>
            <td style="width: 20%">
                <div class="label">Total Pelamar</div>
                <div class="value"><?php echo e($stats['total_pelamar']); ?></div>
            </td>
            <td style="width: 20%">
                <div class="label">Diterima</div>
                <div class="value text-green"><?php echo e($stats['total_diterima']); ?></div>
            </td>
            <td style="width: 20%">
                <div class="label">Selesai / Lulus</div>
                <div class="value" style="color: #059669;"><?php echo e($stats['total_selesai']); ?></div>
            </td>
        </tr>
    </table>

    
    <div class="section-title">Distribusi Pendaftar per Kampus / Sekolah</div>
    <table>
        <thead>
            <tr>
                <th style="width: 4%">No</th>
                <th style="width: 24%">Asal Kampus / Sekolah</th>
                <th style="width: 8%">Pelamar</th>
                <th style="width: 8%">Diterima</th>
                <th style="width: 8%">Selesai</th>
                <th style="width: 8%">Ditolak</th>
                <th style="width: 8%">Pending</th>
                <th style="width: 7%">Rasio</th>
                <th style="width: 25%">Jurusan Pendaftar</th>
            </tr>
        </thead>
        <tbody>
            <?php $t_pelamar=0; $t_diterima=0; $t_selesai=0; $t_ditolak=0; $t_pending=0; ?>
            <?php $__empty_1 = true; $__currentLoopData = $demografi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kampus => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php 
                    $t_pelamar += $data['total_pelamar'];
                    $t_diterima += $data['diterima'];
                    $t_selesai += $data['selesai'];
                    $t_ditolak += $data['ditolak'];
                    $t_pending += $data['pending'];
                ?>
                <tr>
                    <td class="text-center"><?php echo e($loop->iteration); ?></td>
                    <td>
                        <strong><?php echo e($kampus); ?></strong>
                        <?php if($data['peserta']->count() > 0): ?>
                        <div class="sub-detail" style="margin-top: 3px; font-size: 8px; color: #16a34a;">
                            &#10003; Peserta: 
                            <?php $__currentLoopData = $data['peserta']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e($p['nama']); ?><?php echo e(!$loop->last ? ', ' : ''); ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td class="text-center text-bold"><?php echo e($data['total_pelamar']); ?></td>
                    <td class="text-center text-bold text-green"><?php echo e($data['diterima']); ?></td>
                    <td class="text-center text-bold" style="color: #059669;"><?php echo e($data['selesai']); ?></td>
                    <td class="text-center text-bold text-red"><?php echo e($data['ditolak']); ?></td>
                    <td class="text-center text-bold text-orange"><?php echo e($data['pending']); ?></td>
                    <td class="text-center text-bold" style="
                        <?php if($data['acceptance_rate'] >= 70): ?> color: #16a34a;
                        <?php elseif($data['acceptance_rate'] >= 40): ?> color: #ca8a04;
                        <?php else: ?> color: #dc2626;
                        <?php endif; ?>
                    "><?php echo e($data['acceptance_rate']); ?>%</td>
                    <td>
                        <div class="sub-detail" style="padding-left: 0;">
                            <?php $__currentLoopData = $data['jurusan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jurusan => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span><?php echo e($jurusan); ?> (<?php echo e($count); ?>)</span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" class="text-center" style="padding: 20px;">Tidak ada data pendaftar.</td>
                </tr>
            <?php endif; ?>

            <?php if($demografi->count() > 0): ?>
                <tr style="background-color: #e5e7eb; font-weight: bold;">
                    <td colspan="2" class="text-right"><strong>TOTAL KESELURUHAN</strong></td>
                    <td class="text-center"><strong><?php echo e($t_pelamar); ?></strong></td>
                    <td class="text-center text-green"><strong><?php echo e($t_diterima); ?></strong></td>
                    <td class="text-center" style="color: #059669;"><strong><?php echo e($t_selesai); ?></strong></td>
                    <td class="text-center text-red"><strong><?php echo e($t_ditolak); ?></strong></td>
                    <td class="text-center text-orange"><strong><?php echo e($t_pending); ?></strong></td>
                    <td class="text-center" colspan="2">
                        <?php $overall = $t_pelamar > 0 ? round(($t_diterima / $t_pelamar) * 100) : 0; ?>
                        <strong><?php echo e($overall); ?>%</strong>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    
    <?php if($demografiJurusan->count() > 0): ?>
    <div class="section-title" style="margin-top: 25px;">Distribusi Pendaftar per Jurusan / Program Studi</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 45%">Jurusan / Program Studi</th>
                <th style="width: 15%">Total Pelamar</th>
                <th style="width: 15%">Diterima</th>
                <th style="width: 20%">Rasio Penerimaan</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $demografiJurusan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jurusan => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="text-center"><?php echo e($loop->iteration); ?></td>
                <td><strong><?php echo e($jurusan); ?></strong></td>
                <td class="text-center text-bold"><?php echo e($data['total']); ?></td>
                <td class="text-center text-bold text-green"><?php echo e($data['diterima']); ?></td>
                <td class="text-center text-bold" style="
                    <?php if($data['acceptance_rate'] >= 70): ?> color: #16a34a;
                    <?php elseif($data['acceptance_rate'] >= 40): ?> color: #ca8a04;
                    <?php else: ?> color: #dc2626;
                    <?php endif; ?>
                "><?php echo e($data['acceptance_rate']); ?>%</td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php endif; ?>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh Sistem Portal Magang Pemerintah Kota Banjarmasin. &copy; <?php echo e(date('Y')); ?></p>
    </div>

</body>
</html>
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\pdf\admin_instansi\demografi_kampus.blade.php ENDPATH**/ ?>