<?php $__env->startSection('title', 'Reset Password'); ?>

<?php $__env->startSection('header_title', 'Atur Ulang Password Anda'); ?>

<?php $__env->startSection('content'); ?>
    <p>Halo <strong><?php echo e($user->name ?? 'Pengguna'); ?></strong>,</p>
    
    <p>Kami menerima permintaan untuk mengatur ulang (reset) password akun Portal Magang Pemerintah Kota Banjarmasin Anda.</p>
    
    <div class="alert-box" style="background-color: #f0fdf4; border-color: #bbf7d0;">
        <h3 style="color: #166534; font-size: 16px;">Permintaan Reset Password</h3>
        <p style="color: #15803d; font-style: normal; margin-top: 5px;">Klik tombol di bawah ini untuk membuat password baru bagi akun Anda.</p>
    </div>
    
    <div class="button-container">
        <a href="<?php echo e($url); ?>" class="button">Reset Password Sekarang</a>
    </div>
    
    <p style="font-size: 14px; color: #6b7280;">Tautan pengaturan ulang password ini akan kadaluwarsa dalam 60 menit.</p>
    
    <p style="font-size: 14px; color: #6b7280;">Jika Anda tidak merasa melakukan permintaan reset password, Anda tidak perlu melakukan tindakan apa pun dan akun Anda tetap aman.</p>
    
    <p style="margin-top: 30px; margin-bottom: 0;">Hormat kami,</p>
    <p style="font-weight: bold; margin-top: 5px;">Admin SiMagang<br><span style="font-weight: normal; color: #6b7280;">Pemerintah Kota Banjarmasin</span></p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\emails\reset-password.blade.php ENDPATH**/ ?>