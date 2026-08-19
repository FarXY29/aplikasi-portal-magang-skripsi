

<?php $__env->startSection('title', 'Sertifikat Magang Tersedia'); ?>

<?php $__env->startSection('header_title', 'Selamat, Magang Telah Selesai!'); ?>

<?php $__env->startSection('content'); ?>
    <p>Halo <strong><?php echo e($application->user->name); ?></strong>,</p>
    
    <p>Selamat! Program magang Anda di <strong><?php echo e($application->position->instansi->nama_dinas); ?></strong> telah dinyatakan <strong style="color: #0f766e;">Selesai</strong> secara resmi oleh instansi.</p>
    
    <p>Kami mengucapkan terima kasih atas kontribusi, waktu, dan tenaga yang telah Anda berikan selama masa magang. Semoga ilmu dan pengalaman yang Anda dapatkan bermanfaat untuk karir Anda ke depannya.</p>

    <table class="details-table">
        <tr>
            <td>Posisi Magang:</td>
            <td><?php echo e($application->position->judul_posisi); ?></td>
        </tr>
        <tr>
            <td>Periode Magang:</td>
            <td>
                <?php echo e(\Carbon\Carbon::parse($application->tanggal_mulai)->format('d M Y')); ?> - 
                <?php echo e(\Carbon\Carbon::parse($application->tanggal_selesai)->format('d M Y')); ?>

            </td>
        </tr>
        <tr>
            <td>Status Akhir:</td>
            <td><span style="color: #0f766e; font-weight: bold;">Selesai & Lulus</span></td>
        </tr>
    </table>

    <?php if($application->catatan_pembimbing_lapangan): ?>
    <div class="alert-box">
        <h3>💡 Pesan & Saran dari Pembimbing Lapangan:</h3>
        <p>"<?php echo e($application->catatan_pembimbing_lapangan); ?>"</p>
    </div>
    <?php endif; ?>
    
    <p style="margin-top: 25px;">Sertifikat magang Anda kini sudah tersedia dan dapat diunduh langsung melalui dashboard akun Portal Magang Anda. Jangan lupa untuk mengisi kuesioner "Saran & Evaluasi" untuk instansi magang Anda jika Anda belum mengisinya.</p>
    
    <div class="button-container">
        <a href="<?php echo e(url('/peserta/dashboard')); ?>" class="button">Masuk ke Dashboard</a>
    </div>
    
    <p>Jika Anda memiliki pertanyaan, silakan hubungi tim administrasi kami.</p>

    <p style="margin-top: 30px; margin-bottom: 0;">Hormat kami,</p>
    <p style="font-weight: bold; margin-top: 5px;">Admin SiMagang<br><span style="font-weight: normal; color: #6b7280;">Pemerintah Kota Banjarmasin</span></p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\emails\internship_completed.blade.php ENDPATH**/ ?>