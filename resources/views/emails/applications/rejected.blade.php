@extends('emails.layouts.master')

@section('title', 'Pemberitahuan Hasil Seleksi Magang')

@section('header_class', 'danger')
@section('header_title', 'Pemberitahuan Hasil Seleksi')

@section('content')
    <p>Yth. Sdr/i <strong>{{ $application->user->name }}</strong>,</p>
    
    <p>Terima kasih atas partisipasi dan minat Anda untuk melaksanakan program magang di lingkungan Pemerintah Kota Banjarmasin.</p>
    
    <p>Setelah melalui tahapan evaluasi dan pertimbangan terkait ketersediaan kuota pada instansi yang Anda tuju, dengan berat hati kami sampaikan bahwa permohonan magang Anda untuk saat ini <strong>BELUM DAPAT DITERIMA</strong>.</p>
    
    <p>Adapun rincian permohonan Anda adalah sebagai berikut:</p>

    <table class="details-table">
        <tr>
            <td>Instansi</td>
            <td>{{ $application->position->instansi->nama_dinas ?? '-' }}</td>
        </tr>
        <tr>
            <td>Posisi</td>
            <td>{{ $application->position->judul_posisi ?? '-' }}</td>
        </tr>
    </table>

    <p>Kami menyarankan Anda untuk melihat peluang dan mencoba mendaftar kembali pada posisi atau instansi lain yang kuotanya masih tersedia melalui portal kami.</p>
    
    <div class="button-container">
        <a href="{{ route('login') }}" class="button">Lihat Lowongan Lain</a>
    </div>
    
    <p>Demikian pemberitahuan ini kami sampaikan. Kami mengapresiasi antusiasme Anda dan semoga sukses dalam perjalanan akademis Anda.</p>
    
    <p style="margin-top: 30px; margin-bottom: 0;">Hormat kami,</p>
    <p style="font-weight: bold; margin-top: 5px;">Admin SiMagang<br><span style="font-weight: normal; color: #6b7280;">Pemerintah Kota Banjarmasin</span></p>
@endsection
