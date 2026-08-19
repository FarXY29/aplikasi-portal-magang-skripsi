

<?php $__env->startSection('title', 'Status Lamaran'); ?>

<?php $__env->startSection('header_class', 'danger'); ?>
<?php $__env->startSection('header_title', 'Status Lamaran Magang'); ?>

<?php $__env->startSection('content'); ?>
    <p>Halo <strong><?php echo e($application->user->name); ?></strong>,</p>
    
    <p>Terima kasih telah melamar untuk posisi <strong><?php echo e($application->position->judul_posisi); ?></strong> di <strong><?php echo e($application->position->instansi->nama_dinas); ?></strong>.</p>
    
    <p>Namun, dengan berat hati kami informasikan bahwa lamaran Anda saat ini <strong>BELUM DAPAT DITERIMA</strong> karena keterbatasan kuota atau kualifikasi yang belum sesuai.</p>
    
    <p>Jangan patah semangat! Anda masih bisa mencoba melamar di posisi atau dinas lain yang tersedia.</p>
    
    <div class="button-container">
        <a href="<?php echo e(route('login')); ?>" class="button">Lihat Lowongan Lain</a>
    </div>
    
    <p style="margin-top: 30px; margin-bottom: 0;">Salam hangat,</p>
    <p style="font-weight: bold; margin-top: 5px;">Admin SiMagang<br><span style="font-weight: normal; color: #6b7280;">Pemerintah Kota Banjarmasin</span></p>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('emails.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\emails\rejected.blade.php ENDPATH**/ ?>