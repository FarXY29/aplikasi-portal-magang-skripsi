

<?php $__env->startSection('title', 'Pemberitahuan Penerimaan Magang'); ?>

<?php $__env->startSection('header_title', 'Selamat, Lamaran Diterima!'); ?>

<?php $__env->startSection('content'); ?>
    <p>Halo <strong><?php echo e($application->user->name); ?></strong>,</p>
    
    <p>Berdasarkan hasil seleksi dan evaluasi yang telah dilakukan oleh instansi, kami dengan bangga memberitahukan bahwa permohonan magang Anda telah <strong style="color: #0f766e;">DITERIMA</strong>.</p>
    
    <p>Berikut adalah rincian penempatan magang Anda:</p>

    <table class="details-table">
        <tr>
            <td>Instansi</td>
            <td><?php echo e($application->position->instansi->nama_dinas ?? '-'); ?></td>
        </tr>
        <tr>
            <td>Posisi</td>
            <td><?php echo e($application->position->judul_posisi ?? '-'); ?></td>
        </tr>
        <tr>
            <td>Tanggal Mulai</td>
            <td><?php echo e(\Carbon\Carbon::parse($application->tanggal_mulai)->translatedFormat('d F Y')); ?></td>
        </tr>
        <tr>
            <td>Tanggal Selesai</td>
            <td><?php echo e(\Carbon\Carbon::parse($application->tanggal_selesai)->translatedFormat('d F Y')); ?></td>
        </tr>
    </table>

    <p>Silakan mengunduh <strong>Surat Balasan</strong> Anda melalui portal magang untuk diserahkan ke pihak kampus sebagai bukti penerimaan resmi.</p>
    
    <div class="button-container">
        <a href="<?php echo e(route('login')); ?>" class="button">Login ke Portal</a>
    </div>
    
    <p>Kami harap Anda dapat memberikan kontribusi terbaik selama masa magang di lingkungan instansi Pemerintah Kota Banjarmasin.</p>
    
    <p style="margin-top: 30px; margin-bottom: 0;">Hormat kami,</p>
    <p style="font-weight: bold; margin-top: 5px;">Admin SiMagang<br><span style="font-weight: normal; color: #6b7280;">Pemerintah Kota Banjarmasin</span></p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\emails\applications\accepted.blade.php ENDPATH**/ ?>