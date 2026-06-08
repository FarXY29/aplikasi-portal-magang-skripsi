<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sertifikat Magang Tersedia</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            max-w-2xl: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
            margin-bottom: 40px;
        }
        .header {
            background-color: #0f766e; /* Teal-700 */
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
            color: #374151; /* Gray-700 */
        }
        .content p {
            margin-bottom: 15px;
            font-size: 16px;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            background-color: #0d9488; /* Teal-600 */
            color: #ffffff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
        }
        .footer {
            background-color: #f9fafb; /* Gray-50 */
            padding: 20px;
            text-align: center;
            color: #6b7280; /* Gray-500 */
            font-size: 14px;
            border-top: 1px solid #e5e7eb;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .details-table td {
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .details-table td:first-child {
            font-weight: bold;
            width: 40%;
            color: #4b5563;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Selamat, Magang Telah Selesai!</h1>
        </div>
        
        <div class="content">
            <p>Halo <strong>{{ $application->user->name }}</strong>,</p>
            
            <p>Selamat! Program magang Anda di <strong>{{ $application->position->instansi->nama_dinas }}</strong> telah dinyatakan <strong>selesai</strong> secara resmi oleh instansi.</p>
            
            <p>Kami mengucapkan terima kasih atas kontribusi, waktu, dan tenaga yang telah Anda berikan selama masa magang. Semoga ilmu dan pengalaman yang Anda dapatkan bermanfaat untuk karir Anda ke depannya.</p>

            <table class="details-table">
                <tr>
                    <td>Posisi Magang:</td>
                    <td>{{ $application->position->judul_posisi }}</td>
                </tr>
                <tr>
                    <td>Periode Magang:</td>
                    <td>
                        {{ \Carbon\Carbon::parse($application->tanggal_mulai)->format('d M Y') }} - 
                        {{ \Carbon\Carbon::parse($application->tanggal_selesai)->format('d M Y') }}
                    </td>
                </tr>
                <tr>
                    <td>Status Akhir:</td>
                    <td><span style="color: #0f766e; font-weight: bold;">Selesai & Lulus</span></td>
                </tr>
            </table>

            @if($application->catatan_pembimbing_lapangan)
            <div style="background-color: #fffbeb; border: 1px solid #fef3c7; padding: 20px; border-radius: 8px; margin-top: 25px;">
                <h3 style="margin-top: 0; color: #b45309; font-size: 16px;">💡 Pesan & Saran dari Pembimbing Lapangan:</h3>
                <p style="margin-bottom: 0; color: #78350f; font-style: italic;">"{{ $application->catatan_pembimbing_lapangan }}"</p>
            </div>
            @endif
            
            <p style="margin-top: 25px;">Sertifikat magang Anda kini sudah tersedia dan dapat diunduh langsung melalui dashboard akun Portal Magang Anda. Jangan lupa untuk mengisi kuesioner "Saran & Evaluasi" untuk instansi magang Anda jika Anda belum mengisinya.</p>
            
            <div class="button-container">
                <a href="{{ url('/peserta/dashboard') }}" class="button">Masuk ke Dashboard</a>
            </div>
            
            <p>Jika Anda memiliki pertanyaan, silakan hubungi tim administrasi kami.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ env('APP_NAME', 'Portal Magang') }}. All rights reserved.</p>
            <p>Pemerintah Kota Banjarmasin</p>
        </div>
    </div>
</body>
</html>
