<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi - <?php echo e($app->user->name); ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        
        /* Kop Surat */
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 11px; }

        /* Info Peserta */
        .info-table { width: 100%; margin-bottom: 15px; }
        .info-table td { padding: 3px 0; vertical-align: top; }
        .label { width: 130px; font-weight: bold; }

        /* Tabel Data */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 6px 8px; text-align: left; }
        .data-table th { background-color: #f0f0f0; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
        
        /* Status Color (Optional for print) */
        .status-hadir { color: #006400; }
        .status-telat { color: #8B0000; font-weight: bold; }
        .status-izin { color: #A0522D; }

        /* Tanda Tangan */
        .signature { margin-top: 50px; width: 100%; }
        .signature-box { float: right; width: 250px; text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <h1><?php echo e($app->position->instansi->nama_dinas); ?></h1>
        <p><?php echo e($app->position->instansi->alamat ?? 'Alamat instansi belum diatur'); ?></p>
        <p>Email: <?php echo e($app->position->instansi->email ?? '-'); ?> | Telp: <?php echo e($app->position->instansi->no_telp ?? '-'); ?></p>
    </div>

    <div style="text-align: center; margin-bottom: 20px;">
        <h3 style="margin: 0; text-decoration: underline;">LAPORAN REKAPITULASI KEHADIRAN</h3>
        <p style="margin: 5px 0;">Periode: <?php echo e($bulan); ?></p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Peserta</td>
            <td>: <?php echo e($app->user->name); ?></td>
            <td class="label">Posisi Magang</td>
            <td>: <?php echo e($app->position->judul_posisi); ?></td>
        </tr>
        <tr>
            <td class="label">NIM/NISN</td>
            <td>: <?php echo e($app->user->nim ?? '-'); ?></td>
            <td class="label">Asal Instansi</td>
            <td>: <?php echo e($app->user->asal_instansi); ?></td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Hari, Tanggal</th>
                <th width="15%">Jam Masuk</th>
                <th width="15%">Jam Pulang</th>
                <th width="15%">Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td class="text-center"><?php echo e($loop->iteration); ?></td>
                <td><?php echo e(\Carbon\Carbon::parse($row->date)->isoFormat('dddd, D MMMM Y')); ?></td>
                
                <td class="text-center">
                    <?php echo e($row->clock_in ? \Carbon\Carbon::parse($row->clock_in)->format('H:i') : '-'); ?>

                </td>
                
                <td class="text-center">
                    <?php echo e($row->clock_out ? \Carbon\Carbon::parse($row->clock_out)->format('H:i') : '-'); ?>

                </td>

                <td class="text-center">
                    <?php if($row->status == 'hadir'): ?>
                        <?php if($row->clock_in > '08:00:00'): ?> 
                            <span class="status-telat">Terlambat</span>
                        <?php else: ?>
                            <span class="status-hadir">Hadir</span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="status-izin"><?php echo e(ucfirst($row->status)); ?></span>
                    <?php endif; ?>
                </td>

                <td><?php echo e($row->description ?? '-'); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px;">Tidak ada data absensi pada periode ini.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="signature">
        <div class="signature-box">
            <p>Banjarmasin, <?php echo e(\Carbon\Carbon::now()->isoFormat('D MMMM Y')); ?></p>
            <p>Mengetahui,<br>Pembimbing Lapangan</p>
            <br><br><br>
            <p style="font-weight: bold; text-decoration: underline;">
                <?php echo e($app->pembimbing_lapangan->name ?? '..........................'); ?>

            </p>
            <p>NIP. <?php echo e($app->pembimbing_lapangan->nik ?? '..........................'); ?></p>
        </div>
    </div>

</body>
</html><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\pdf\admin_instansi\rekap_absensi.blade.php ENDPATH**/ ?>