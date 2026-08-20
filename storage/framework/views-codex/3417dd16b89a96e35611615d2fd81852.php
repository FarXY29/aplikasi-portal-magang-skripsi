

<?php $__env->startSection('title', 'Lamaran Diterima'); ?>

<?php $__env->startSection('header_title', 'Selamat, Lamaran Diterima!'); ?>

<?php $__env->startSection('content'); ?>
    <p>Halo <strong><?php echo e($application->user->name); ?>!</strong>,</p>
    
    <p>Kami membawa kabar gembira. Lamaran magang Anda untuk posisi:</p>
    
    <div class="alert-box" style="background-color: #f0fdf4; border-color: #bbf7d0;">
        <h3 style="color: #166534; font-size: 18px;"><?php echo e($application->position->judul_posisi); ?></h3>
        <p style="color: #15803d; font-style: normal; margin-top: 5px;">di <strong><?php echo e($application->position->instansi->nama_dinas); ?></strong></p>
    </div>
    
    <p>Telah dinyatakan <strong style="color: #0f766e;">DITERIMA</strong>.</p>
    
    <p>Silakan login ke dashboard aplikasi untuk melihat detail dan mulai mengisi logbook kegiatan harian Anda.</p>
    
    <div class="button-container">
        <a href="<?php echo e(route('login')); ?>" class="button">Login Dashboard</a>
    </div>
    
    <p>Selamat bergabung dan semoga sukses!</p>
    
    <p style="margin-top: 30px; margin-bottom: 0;">Hormat kami,</p>
    <p style="font-weight: bold; margin-top: 5px;">Admin SiMagang<br><span style="font-weight: normal; color: #6b7280;">Pemerintah Kota Banjarmasin</span></p>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('emails.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\emails\accepted.blade.php ENDPATH**/ ?>