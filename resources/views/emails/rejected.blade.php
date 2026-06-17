@extends('emails.layouts.master')

@section('title', 'Status Lamaran')

@section('header_class', 'danger')
@section('header_title', 'Status Lamaran Magang')

@section('content')
    <p>Halo <strong>{{ $application->user->name }}</strong>,</p>
    
    <p>Terima kasih telah melamar untuk posisi <strong>{{ $application->position->judul_posisi }}</strong> di <strong>{{ $application->position->instansi->nama_dinas }}</strong>.</p>
    
    <p>Namun, dengan berat hati kami informasikan bahwa lamaran Anda saat ini <strong>BELUM DAPAT DITERIMA</strong> karena keterbatasan kuota atau kualifikasi yang belum sesuai.</p>
    
    <p>Jangan patah semangat! Anda masih bisa mencoba melamar di posisi atau dinas lain yang tersedia.</p>
    
    <div class="button-container">
        <a href="{{ route('login') }}" class="button">Lihat Lowongan Lain</a>
    </div>
    
    <p style="margin-top: 30px; margin-bottom: 0;">Salam hangat,</p>
    <p style="font-weight: bold; margin-top: 5px;">Admin SiMagang<br><span style="font-weight: normal; color: #6b7280;">Pemerintah Kota Banjarmasin</span></p>
@endsection