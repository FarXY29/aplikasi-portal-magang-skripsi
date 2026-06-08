@component('mail::message')
# PEMBERITAHUAN BERAKHIRNYA MASA MAGANG
**Pemerintah Kota Banjarmasin**

Yth. Sdr/i **{{ $application->user->name }}**,

Melalui surat elektronik ini, kami memberitahukan bahwa program magang yang sedang Anda jalani akan segera berakhir dalam waktu **7 hari ke depan**.

Berikut adalah rincian magang Anda:

@component('mail::table')
| Keterangan | Detail |
| :--- | :--- |
| **Instansi** | {{ $application->position->instansi->nama_dinas ?? '-' }} |
| **Tanggal Selesai** | {{ \Carbon\Carbon::parse($application->tanggal_selesai)->translatedFormat('d F Y') }} |
@endcomponent

Mengingat waktu yang tersisa, kami mengingatkan Anda untuk segera menyelesaikan kewajiban administratif berikut:
1. Memastikan seluruh **Logbook / Laporan Harian** telah diisi dan diverifikasi.
2. Memeriksa kembali kelengkapan **Absensi Harian**.
3. Meminta **Penilaian Akhir** dari Pembimbing Lapangan Anda di instansi.

Penyelesaian kewajiban di atas adalah syarat mutlak untuk penerbitan **Sertifikat dan Transkrip Nilai** magang Anda.

@component('mail::button', ['url' => route('login')])
Buka Dashboard Magang
@endcomponent

Terima kasih atas dedikasi Anda sejauh ini. Tetap semangat menyelesaikan program ini dengan tuntas!

Hormat kami,<br>
**Admin SiMagang**<br>
Pemerintah Kota Banjarmasin
@endcomponent
