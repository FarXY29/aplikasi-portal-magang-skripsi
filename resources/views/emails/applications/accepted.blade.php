@extends('emails.layouts.master')

@section('title', 'Pemberitahuan Penerimaan Magang')

@section('header_title', 'Selamat, Lamaran Diterima!')

@section('content')
    <p>Halo <strong>{{ $application->user->name }}</strong>,</p>
    
    <p>Berdasarkan hasil seleksi dan evaluasi yang telah dilakukan oleh instansi, kami dengan bangga memberitahukan bahwa permohonan magang Anda telah <strong style="color: #0f766e;">DITERIMA</strong>.</p>
    
    <p>Berikut adalah rincian penempatan magang Anda:</p>

    <table class="details-table">
        <tr>
            <td>Instansi</td>
            <td>{{ $application->position->instansi->nama_dinas ?? '-' }}</td>
        </tr>
        <tr>
            <td>Posisi</td>
            <td>{{ $application->position->judul_posisi ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tanggal Mulai</td>
            <td>{{ \Carbon\Carbon::parse($application->tanggal_mulai)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Tanggal Selesai</td>
            <td>{{ \Carbon\Carbon::parse($application->tanggal_selesai)->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <p>Silakan mengunduh <strong>Surat Balasan</strong> Anda melalui portal magang untuk diserahkan ke pihak kampus sebagai bukti penerimaan resmi.</p>
    
    <div class="button-container">
        <a href="{{ route('login') }}" class="button">Login ke Portal</a>
    </div>
    
    <p>Kami harap Anda dapat memberikan kontribusi terbaik selama masa magang di lingkungan instansi Pemerintah Kota Banjarmasin.</p>
    
    <p style="margin-top: 30px; margin-bottom: 0;">Hormat kami,</p>
    <p style="font-weight: bold; margin-top: 5px;">Admin SiMagang<br><span style="font-weight: normal; color: #6b7280;">Pemerintah Kota Banjarmasin</span></p>
@endsection
