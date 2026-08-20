

<?php $__env->startSection('title', 'Pemberitahuan Berakhirnya Masa Magang'); ?>

<?php $__env->startSection('header_class', 'warning'); ?>
<?php $__env->startSection('header_title', 'Masa Magang Segera Berakhir'); ?>

<?php $__env->startSection('content'); ?>
    <p>Yth. Sdr/i <strong><?php echo e($application->user->name); ?></strong>,</p>
    
    <p>Melalui surat elektronik ini, kami memberitahukan bahwa program magang yang sedang Anda jalani akan segera berakhir dalam waktu <strong>7 hari ke depan</strong>.</p>
    
    <p>Berikut adalah rincian magang Anda:</p>

    <table class="details-table">
        <tr>
            <td>Instansi</td>
            <td><?php echo e($application->position->instansi->nama_dinas ?? '-'); ?></td>
        </tr>
        <tr>
            <td>Tanggal Selesai</td>
            <td><?php echo e(\Carbon\Carbon::parse($application->tanggal_selesai)->translatedFormat('d F Y')); ?></td>
        </tr>
    </table>

    <div class="alert-box">
        <h3>Peringatan Kewajiban Administratif</h3>
        <p style="font-style: normal; color: #374151; margin-top: 10px;">Mengingat waktu yang tersisa, kami mengingatkan Anda untuk segera menyelesaikan kewajiban berikut:</p>
        <ul style="margin-top: 10px; color: #4b5563;">
            <li>Memastikan seluruh <strong>Logbook / Laporan Harian</strong> telah diisi dan diverifikasi.</li>
            <li>Memeriksa kembali kelengkapan <strong>Absensi Harian</strong>.</li>
            <li>Meminta <strong>Penilaian Akhir</strong> dari Pembimbing Lapangan Anda di instansi.</li>
        </ul>
        <p style="font-style: italic; color: #b45309; margin-top: 15px; font-size: 14px;">Penyelesaian kewajiban di atas adalah syarat mutlak untuk penerbitan Sertifikat dan Transkrip Nilai magang Anda.</p>
    </div>
    
    <div class="button-container">
        <a href="<?php echo e(route('login')); ?>" class="button">Buka Dashboard Magang</a>
    </div>
    
    <p>Terima kasih atas dedikasi Anda sejauh ini. Tetap semangat menyelesaikan program ini dengan tuntas!</p>
    
    <p style="margin-top: 30px; margin-bottom: 0;">Hormat kami,</p>
    <p style="font-weight: bold; margin-top: 5px;">Admin SiMagang<br><span style="font-weight: normal; color: #6b7280;">Pemerintah Kota Banjarmasin</span></p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\emails\internship\ending.blade.php ENDPATH**/ ?>