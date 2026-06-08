@component('mail::message')
# PEMBERITAHUAN PENERIMAAN MAGANG
**Pemerintah Kota Banjarmasin**

Yth. Sdr/i **{{ $application->user->name }}**,

Berdasarkan hasil seleksi dan evaluasi yang telah dilakukan oleh instansi terkait, kami dengan bangga memberitahukan bahwa permohonan magang Anda telah **DITERIMA**.

Berikut adalah rincian penempatan magang Anda:

@component('mail::table')
| Keterangan | Detail |
| :--- | :--- |
| **Instansi** | {{ $application->position->instansi->nama_dinas ?? '-' }} |
| **Posisi** | {{ $application->position->judul_posisi ?? '-' }} |
| **Tanggal Mulai** | {{ \Carbon\Carbon::parse($application->tanggal_mulai)->translatedFormat('d F Y') }} |
| **Tanggal Selesai** | {{ \Carbon\Carbon::parse($application->tanggal_selesai)->translatedFormat('d F Y') }} |
@endcomponent

Silakan mengunduh **Letter of Acceptance (LoA)** Anda melalui portal magang untuk diserahkan ke pihak kampus sebagai bukti penerimaan resmi.

@component('mail::button', ['url' => route('login')])
Login ke Portal
@endcomponent

Kami harap Anda dapat memberikan kontribusi terbaik selama masa magang di lingkungan instansi Pemerintah Kota Banjarmasin.

Hormat kami,<br>
**Admin SiMagang**<br>
Pemerintah Kota Banjarmasin
@endcomponent
