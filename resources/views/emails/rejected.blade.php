<!DOCTYPE html>
<html>
<head>
    <title>Status Lamaran</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <h2>Halo, {{ $application->user->name }}</h2>
    
    <p>Terima kasih telah melamar untuk posisi <strong>{{ $application->position->judul_posisi }}</strong> di {{ $application->position->instansi->nama_dinas }}.</p>
    
    <p>Namun, dengan berat hati kami informasikan bahwa lamaran Anda saat ini <strong>BELUM DAPAT DITERIMA</strong> karena keterbatasan kuota atau kualifikasi yang belum sesuai.</p>
    
    <p>Jangan patah semangat! Anda masih bisa mencoba melamar di posisi atau dinas lain yang tersedia.</p>
    
    <br>
    <p>Salam hangat,</p>
    <p>--<br>Admin Portal Magang Banjarmasin</p>
</body>
</html>