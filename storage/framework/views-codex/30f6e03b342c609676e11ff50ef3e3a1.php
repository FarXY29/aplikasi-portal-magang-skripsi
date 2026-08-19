<!DOCTYPE html>
<html>
<head>
    <title>Laporan Rekap Peserta Magang</title>
    <style>
        body { font-family: sans-serif; font-size: 9px; color: #333; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 3px double #333; padding-bottom: 12px; }
        .header h2 { margin: 0; text-transform: uppercase; font-size: 14px; letter-spacing: 1px; }
        .header h3 { margin: 3px 0; font-size: 12px; }
        .header p { margin: 2px 0; font-size: 10px; color: #555; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #aaa; padding: 5px 6px; text-align: left; vertical-align: top; }
        th { background-color: #f3f4f6; text-align: center; font-weight: bold; font-size: 8px; text-transform: uppercase; }
        
        .status-aktif { color: #16a34a; font-weight: bold; }
        .status-selesai { color: #2563eb; font-weight: bold; }
        .status-pending { color: #d97706; font-weight: bold; }
        .status-menunggu { color: #d97706; font-weight: bold; }
        .status-ditolak { color: #dc2626; font-weight: bold; }
        
        .meta-info { margin-bottom: 12px; font-size: 10px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        
        .section-title { 
            font-size: 11px; font-weight: bold; margin: 15px 0 8px 0; 
            padding: 5px 8px; background-color: #f3f4f6; border-left: 4px solid #0d9488;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        
        .stats-table { margin-bottom: 15px; }
        .stats-table td { border: 1px solid #ccc; padding: 6px 8px; text-align: center; }
        .stats-table .label { font-size: 8px; text-transform: uppercase; letter-spacing: 0.5px; color: #666; }
        .stats-table .value { font-size: 14px; font-weight: bold; color: #111; }
        
        .footer { margin-top: 20px; font-size: 8px; color: #888; border-top: 1px solid #ccc; padding-top: 8px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>PEMERINTAH KOTA BANJARMASIN</h2>
        <h3><?php echo e($instansi->nama_dinas ?? 'DINAS TERKAIT'); ?></h3>
        <p>Laporan Rekapitulasi Data Pendaftaran Peserta Magang</p>
    </div>

    <div class="meta-info">
        <p><strong>Dicetak Tanggal:</strong> <?php echo e(date('d F Y')); ?> &nbsp;|&nbsp; <em>Oleh: <?php echo e(Auth::user()->name); ?></em></p>
        <p><strong>Filter Terpasang:</strong> 
            Status: <?php echo e($request->status ?: 'Semua Status'); ?> &nbsp;|&nbsp; 
            Asal Instansi: <?php echo e($request->asal_instansi ?: 'Semua Instansi'); ?>

            <?php if($request->filled('start_date') && $request->filled('end_date')): ?>
                 &nbsp;|&nbsp; Periode: <?php echo e(\Carbon\Carbon::parse($request->start_date)->format('d/m/Y')); ?> - <?php echo e(\Carbon\Carbon::parse($request->end_date)->format('d/m/Y')); ?>

            <?php endif; ?>
        </p>
    </div>

    
    <div class="section-title">Ringkasan Statistik Rekapitulasi</div>
    <table class="stats-table">
        <tr>
            <td style="width: 16.66%">
                <div class="label">Total Lamaran</div>
                <div class="value"><?php echo e($stats['total']); ?></div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Status Aktif</div>
                <div class="value status-aktif"><?php echo e($stats['aktif']); ?></div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Status Selesai</div>
                <div class="value status-selesai"><?php echo e($stats['selesai']); ?></div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Status Pending</div>
                <div class="value status-pending"><?php echo e($stats['pending']); ?></div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Status Ditolak</div>
                <div class="value status-ditolak"><?php echo e($stats['ditolak']); ?></div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Kampus Terlibat</div>
                <div class="value" style="color: #4f46e5;"><?php echo e($stats['total_kampus']); ?></div>
            </td>
        </tr>
    </table>

    
    <div class="section-title">Data Rekapitulasi Peserta Magang</div>
    <table>
        <thead>
            <tr>
                <th style="width: 3%">No</th>
                <th style="width: 22%">Nama Peserta &amp; Kontak</th>
                <th style="width: 22%">Asal Sekolah / Kampus</th>
                <th style="width: 22%">Posisi Magang &amp; Pembimbing Lapangan</th>
                <th style="width: 18%">Periode &amp; Durasi Magang</th>
                <th style="width: 13%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-center"><?php echo e($loop->iteration); ?></td>
                    <td>
                        <strong><?php echo e($app->user->name); ?></strong><br>
                        <span style="font-size: 7px; color: #555;"><?php echo e($app->user->email); ?></span>
                        <?php if($app->user->phone): ?>
                            <br><span style="font-size: 7px; color: #555;">Telp: <?php echo e($app->user->phone); ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($app->user->asal_instansi ?? '-'); ?></td>
                    <td>
                        <strong><?php echo e($app->position->judul_posisi); ?></strong>
                        <?php if($app->pembimbing_lapangan): ?>
                            <br><small style="color: #666; font-size: 8px;">PL: <?php echo e($app->pembimbing_lapangan->name); ?></small>
                        <?php else: ?>
                            <br><small style="color: #999; font-size: 8px; font-style: italic;">PL: Belum ditentukan</small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo e(\Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y')); ?> s/d<br>
                        <?php echo e(\Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y')); ?><br>
                        <small style="color: #0d9488; font-weight: bold;">(<?php echo e(\Carbon\Carbon::parse($app->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($app->tanggal_selesai))); ?> Hari)</small>
                    </td>
                    <td class="text-center text-bold">
                        <?php
                            $statusVal = $app->status instanceof \UnitEnum ? $app->status->value : $app->status;
                        ?>
                        <span class="status-<?php echo e($statusVal); ?>">
                            <?php echo e($statusVal === 'diterima' ? 'Aktif' : ($statusVal === 'selesai' ? 'Selesai' : (in_array($statusVal, ['pending', 'menunggu']) ? 'Pending' : ucfirst($statusVal)))); ?>

                        </span>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">Tidak ada data ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh Sistem Portal Magang Pemerintah Kota Banjarmasin. &copy; <?php echo e(date('Y')); ?></p>
    </div>

    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->get_cpdf()->addJS('print(true);');
        }
    </script>
</body>
</html><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\pdf\admin_instansi\rekap_peserta.blade.php ENDPATH**/ ?>