<!DOCTYPE html>
<html>
<head>
    <title>Laporan Jurnal Harian Mahasiswa</title>
    <style>
        body { font-family: sans-serif; font-size: 9px; color: #333; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 3px double #333; padding-bottom: 12px; }
        .header h2 { margin: 0; text-transform: uppercase; font-size: 14px; letter-spacing: 1px; }
        .header h3 { margin: 3px 0; font-size: 12px; }
        .header p { margin: 2px 0; font-size: 10px; color: #555; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #aaa; padding: 5px 6px; text-align: left; vertical-align: top; }
        th { background-color: #f3f4f6; text-align: center; font-weight: bold; font-size: 8px; text-transform: uppercase; }
        
        .meta-info { margin-bottom: 12px; font-size: 10px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .text-green { color: #16a34a; }
        .text-red { color: #dc2626; }
        .text-orange { color: #ea580c; }
        .text-purple { color: #9333ea; }
        .text-blue { color: #2563eb; }
        
        .section-title { 
            font-size: 11px; font-weight: bold; margin: 15px 0 8px 0; 
            padding: 5px 8px; background-color: #f3f4f6; border-left: 4px solid #9333ea;
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
        <h3><?php echo e(Auth::user()->instansi->nama_dinas ?? 'DINAS TERKAIT'); ?></h3>
        <p>Laporan Rekapitulasi Jurnal / Aktivitas Harian Mahasiswa Magang</p>
        <p style="font-size: 9px; color: #666; margin-top: 4px; font-weight: bold;">Filter Waktu: <?php echo e($label_waktu); ?></p>
    </div>

    <div class="meta-info">
        <p><strong>Dicetak Tanggal:</strong> <?php echo e(date('d F Y')); ?> &nbsp;|&nbsp; <em>Oleh: <?php echo e(Auth::user()->name); ?></em></p>
    </div>

    
    <div class="section-title">Ringkasan Statistik Jurnal</div>
    <table class="stats-table">
        <tr>
            <td style="width: 16.66%">
                <div class="label">Total Jurnal</div>
                <div class="value"><?php echo e($stats['total_jurnal']); ?></div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Disetujui</div>
                <div class="value text-green"><?php echo e($stats['disetujui']); ?></div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Pending</div>
                <div class="value text-orange"><?php echo e($stats['pending']); ?></div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Revisi</div>
                <div class="value text-red"><?php echo e($stats['revisi']); ?></div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Peserta Aktif</div>
                <div class="value text-blue"><?php echo e($stats['total_peserta_aktif']); ?></div>
            </td>
            <td style="width: 16.66%">
                <div class="label">Rasio Validasi</div>
                <div class="value text-purple"><?php echo e($stats['rasio_validasi']); ?>%</div>
            </td>
        </tr>
    </table>

    
    <div class="section-title">Daftar Aktivitas Logbook Harian</div>
    <table>
        <thead>
            <tr>
                <th style="width: 3%">No</th>
                <th style="width: 10%">Tanggal</th>
                <th style="width: 18%">Nama Mahasiswa &amp; Kampus</th>
                <th style="width: 14%">Posisi / Divisi</th>
                <th style="width: 32%">Uraian Kegiatan / Aktivitas</th>
                <th style="width: 8%">Status</th>
                <th style="width: 15%">Pembimbing &amp; Catatan</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $jurnal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-center"><?php echo e($loop->iteration); ?></td>
                    <td class="text-center">
                        <?php echo e(\Carbon\Carbon::parse($log->tanggal)->format('d-m-Y')); ?><br>
                        <small style="color: #666;"><?php echo e(\Carbon\Carbon::parse($log->tanggal)->isoFormat('dddd')); ?></small>
                    </td>
                    <td>
                        <strong><?php echo e($log->application->user->name ?? '-'); ?></strong><br>
                        <small style="color: #555;"><?php echo e($log->application->user->asal_instansi ?? '-'); ?></small>
                    </td>
                    <td>
                        <?php echo e($log->application->position->judul_posisi ?? '-'); ?>

                    </td>
                    <td>
                        <div style="white-space: pre-wrap; word-wrap: break-word;"><?php echo e($log->kegiatan); ?></div>
                    </td>
                    <td class="text-center text-bold" style="
                        <?php if($log->status_validasi == 'disetujui'): ?> color: #16a34a;
                        <?php elseif($log->status_validasi == 'revisi'): ?> color: #dc2626;
                        <?php else: ?> color: #d97706; <?php endif; ?>
                    ">
                        <?php echo e(ucfirst($log->status_validasi)); ?>

                    </td>
                    <td>
                        <?php if($log->application->pembimbing_lapangan): ?>
                            <strong><?php echo e($log->application->pembimbing_lapangan->name); ?></strong>
                            <?php if($log->komentar_pembimbing_lapangan): ?>
                                <br><small style="color: #555; font-style: italic;">"<?php echo e($log->komentar_pembimbing_lapangan); ?>"</small>
                            <?php endif; ?>
                        <?php else: ?>
                            <small style="color: #999; font-style: italic;">Belum ditentukan</small>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">Belum ada data jurnal aktivitas harian.</td>
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
</html>
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\pdf\admin_instansi\jurnal_harian.blade.php ENDPATH**/ ?>