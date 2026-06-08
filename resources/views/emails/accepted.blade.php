<!DOCTYPE html>
<html>
<head>
    <title>Lamaran Diterima</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <h2>Halo, {{ $application->user->name }}!</h2>
    
    <p>Kami membawa kabar gembira. Lamaran magang Anda untuk posisi:</p>
    
    <h3>{{ $application->position->judul_posisi }}</h3>
    <p>di <strong>{{ $application->position->instansi->nama_dinas }}</strong></p>
    
    <p style="color: green; font-weight: bold;">Telah DITERIMA.</p>
    
    <p>Silakan login ke dashboard aplikasi untuk melihat detail dan mulai mengisi logbook kegiatan harian Anda.</p>
    
    <p>
        <a href="{{ route('login') }}" style="background-color: #0F766E; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Login Dashboard</a>
    </p>
    
    <br>
    <p>Selamat bergabung dan semoga sukses!</p>
    <p>--<br>Admin Portal Magang Banjarmasin</p>
</body>
</html>