<!DOCTYPE html>
<html>
<head>
    <title>Rekap Kegiatan Magang</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; text-transform: uppercase; }
        .header h1 { margin: 0; font-size: 16px; }
        .header h2 { margin: 5px 0; font-size: 14px; }
        
        /* Menggunakan float:left untuk meta agar layout stabil */
        .meta-container { width: 100%; margin-bottom: 20px; overflow: hidden; }
        .meta-left { float: left; width: 50%; }
        .meta-right { float: right; width: 45%; }
        
        table { border-collapse: collapse; width: 100%; }
        .table-data th, .table-data td { border: 1px solid black; padding: 6px; text-align: left; vertical-align: top; }
        .table-data th { background-color: #f0f0f0; text-align: center; }
        
        .footer { margin-top: 30px; page-break-inside: avoid; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Daftar Hadir dan Agenda Kegiatan</h1>
        <h2><?php echo e($app->position->instansi->nama_dinas); ?></h2>
        <p>Pemerintah Kota Banjarmasin</p>
    </div>

    
    <div class="meta-container">
        <div class="meta-left">
            <table>
                <tr>
                    <td width="30%">Nama Peserta</td>
                    <td width="5%">:</td>
                    <td><strong><?php echo e($user->name); ?></strong></td>
                </tr>
                <tr>
                    <td>NIM/NISN</td>
                    <td>:</td>
                    <td><?php echo e($user->nomor_induk ?? '-'); ?></td>
                </tr>
            </table>
        </div>
        <div class="meta-right">
            <table>
                <tr>
                    <td width="30%">Posisi</td>
                    <td width="5%">:</td>
                    <td><?php echo e($app->position->judul_posisi); ?></td>
                </tr>
                <tr>
                    <td>Asal Instansi</td>
                    <td>:</td>
                    <td><?php echo e($user->asal_instansi); ?></td>
                </tr>
            </table>
        </div>
    </div>

    
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 60%">Uraian Kegiatan</th>
                <th style="width: 20%">Paraf Pembimbing</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td style="text-align: center;"><?php echo e($index + 1); ?></td>
                <td><?php echo e(\Carbon\Carbon::parse($log->tanggal)->format('d-m-Y')); ?></td>
                <td>
                    <?php echo e($log->kegiatan); ?>

                    <?php if($log->komentar_pembimbing_lapangan): ?>
                        <br><br>
                        <i style="color: #555; font-size: 10px;">Catatan Pembimbing Lapangan: <?php echo e($log->komentar_pembimbing_lapangan); ?></i>
                    <?php endif; ?>
                </td>
                <td style="text-align: center; vertical-align: middle;">
                    
                    <?php if(in_array($log->status_validasi, ['approved', 'disetujui', 'valid'])): ?>
                        <?php if($app->pembimbing_lapangan && $app->pembimbing_lapangan->signature): ?>
                            
                            <img src="<?php echo e(public_path('storage/' . $app->pembimbing_lapangan->signature)); ?>" style="height: 35px; width: auto;">
                        <?php else: ?>
                            
                            <span style="font-size: 10px; font-weight: bold; color: green;">(Valid)</span>
                        <?php endif; ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    
    <div class="footer">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 60%; border: none;"></td>
                <td style="width: 40%; border: none; text-align: center;">
                    <p>Banjarmasin, <?php echo e(date('d F Y')); ?></p>
                    <p>Pembimbing Lapangan,</p>
                    
                    <?php if($app->pembimbing_lapangan && $app->pembimbing_lapangan->signature): ?>
                        <div style="height: 60px; display: flex; justify-content: center; align-items: center;">
                            <img src="<?php echo e(public_path('storage/' . $app->pembimbing_lapangan->signature)); ?>" style="height: 60px; width: auto;">
                        </div>
                    <?php else: ?>
                        <br><br><br>
                    <?php endif; ?>

                    <p style="font-weight: bold; text-decoration: underline;"><?php echo e($app->pembimbing_lapangan->name ?? '.........................'); ?></p>
                    <p>NIP. <?php echo e($app->pembimbing_lapangan->nomor_induk ?? '-'); ?></p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\pdf\peserta\logbook_rekap.blade.php ENDPATH**/ ?>