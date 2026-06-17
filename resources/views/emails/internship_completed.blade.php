@extends('emails.layouts.master')

@section('title', 'Sertifikat Magang Tersedia')

@section('header_title', 'Selamat, Magang Telah Selesai!')

@section('content')
    <p>Halo <strong>{{ $application->user->name }}</strong>,</p>
    
    <p>Selamat! Program magang Anda di <strong>{{ $application->position->instansi->nama_dinas }}</strong> telah dinyatakan <strong style="color: #0f766e;">Selesai</strong> secara resmi oleh instansi.</p>
    
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
    <div class="alert-box">
        <h3>💡 Pesan & Saran dari Pembimbing Lapangan:</h3>
        <p>"{{ $application->catatan_pembimbing_lapangan }}"</p>
    </div>
    @endif
    
    <p style="margin-top: 25px;">Sertifikat magang Anda kini sudah tersedia dan dapat diunduh langsung melalui dashboard akun Portal Magang Anda. Jangan lupa untuk mengisi kuesioner "Saran & Evaluasi" untuk instansi magang Anda jika Anda belum mengisinya.</p>
    
    <div class="button-container">
        <a href="{{ url('/peserta/dashboard') }}" class="button">Masuk ke Dashboard</a>
    </div>
    
    <p>Jika Anda memiliki pertanyaan, silakan hubungi tim administrasi kami.</p>

    <p style="margin-top: 30px; margin-bottom: 0;">Hormat kami,</p>
    <p style="font-weight: bold; margin-top: 5px;">Admin SiMagang<br><span style="font-weight: normal; color: #6b7280;">Pemerintah Kota Banjarmasin</span></p>
@endsection
