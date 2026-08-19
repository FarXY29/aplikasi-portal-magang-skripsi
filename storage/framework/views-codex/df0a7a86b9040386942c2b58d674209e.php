

<?php $__env->startSection('title', 'Pemberitahuan Hasil Seleksi Magang'); ?>

<?php $__env->startSection('header_class', 'danger'); ?>
<?php $__env->startSection('header_title', 'Pemberitahuan Hasil Seleksi'); ?>

<?php $__env->startSection('content'); ?>
    <p>Yth. Sdr/i <strong><?php echo e($application->user->name); ?></strong>,</p>
    
    <p>Terima kasih atas partisipasi dan minat Anda untuk melaksanakan program magang di lingkungan Pemerintah Kota Banjarmasin.</p>
    
    <p>Setelah melalui tahapan evaluasi dan pertimbangan terkait ketersediaan kuota pada instansi yang Anda tuju, dengan berat hati kami sampaikan bahwa permohonan magang Anda untuk saat ini <strong>BELUM DAPAT DITERIMA</strong>.</p>
    
    <p>Adapun rincian permohonan Anda adalah sebagai berikut:</p>

    <table class="details-table">
        <tr>
            <td>Instansi</td>
            <td><?php echo e($application->position->instansi->nama_dinas ?? '-'); ?></td>
        </tr>
        <tr>
            <td>Posisi</td>
            <td><?php echo e($application->position->judul_posisi ?? '-'); ?></td>
        </tr>
    </table>

    <p>Kami menyarankan Anda untuk melihat peluang dan mencoba mendaftar kembali pada posisi atau instansi lain yang kuotanya masih tersedia melalui portal kami.</p>
    
    <div class="button-container">
        <a href="<?php echo e(route('login')); ?>" class="button">Lihat Lowongan Lain</a>
    </div>
    
    <p>Demikian pemberitahuan ini kami sampaikan. Kami mengapresiasi antusiasme Anda dan semoga sukses dalam perjalanan akademis Anda.</p>
    
    <p style="margin-top: 30px; margin-bottom: 0;">Hormat kami,</p>
    <p style="font-weight: bold; margin-top: 5px;">Admin SiMagang<br><span style="font-weight: normal; color: #6b7280;">Pemerintah Kota Banjarmasin</span></p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\emails\applications\rejected.blade.php ENDPATH**/ ?>