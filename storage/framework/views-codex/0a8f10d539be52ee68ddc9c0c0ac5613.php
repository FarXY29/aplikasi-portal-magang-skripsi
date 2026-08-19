<?php $__env->startSection('title', 'Verifikasi Alamat Email'); ?>

<?php $__env->startSection('header_title', 'Verifikasi Akun SiMagang'); ?>

<?php $__env->startSection('content'); ?>
    <p>Halo <strong><?php echo e($user->name ?? 'Calon Peserta/Pembimbing'); ?></strong>,</p>
    
    <p>Terima kasih telah mendaftar di <strong>Portal Magang Pemerintah Kota Banjarmasin (SiMagang)</strong>.</p>
    
    <div class="alert-box" style="background-color: #f0fdf4; border-color: #bbf7d0;">
        <h3 style="color: #166534; font-size: 16px;">Satu Langkah Lagi!</h3>
        <p style="color: #15803d; font-style: normal; margin-top: 5px;">Untuk keamanan akun dan memastikan alamat email Anda aktif, silakan lakukan verifikasi email terlebih dahulu sebelum Anda dapat login dan menggunakan layanan portal.</p>
    </div>
    
    <div class="button-container">
        <a href="<?php echo e($url); ?>" class="button">Verifikasi Email Sekarang</a>
    </div>
    
    <p style="font-size: 14px; color: #6b7280;">Jika Anda tidak merasa mendaftar di Portal Magang Banjarmasin, silakan abaikan pesan ini.</p>
    
    <p style="margin-top: 30px; margin-bottom: 0;">Hormat kami,</p>
    <p style="font-weight: bold; margin-top: 5px;">Admin SiMagang<br><span style="font-weight: normal; color: #6b7280;">Pemerintah Kota Banjarmasin</span></p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views/emails/verify-email.blade.php ENDPATH**/ ?>