@component('mail::message')
# PEMBERITAHUAN HASIL SELEKSI
**Pemerintah Kota Banjarmasin**

Yth. Sdr/i **{{ $application->user->name }}**,

Terima kasih atas partisipasi dan minat Anda untuk melaksanakan program magang di lingkungan Pemerintah Kota Banjarmasin.

Setelah melalui tahapan evaluasi dan pertimbangan terkait ketersediaan kuota pada instansi yang Anda tuju, dengan berat hati kami sampaikan bahwa permohonan magang Anda untuk saat ini **BELUM DAPAT DITERIMA**.

Adapun rincian permohonan Anda adalah sebagai berikut:

@component('mail::table')
| Keterangan | Detail |
| :--- | :--- |
| **Instansi** | {{ $application->position->instansi->nama_dinas ?? '-' }} |
| **Posisi** | {{ $application->position->judul_posisi ?? '-' }} |
@endcomponent

Kami menyarankan Anda untuk melihat peluang dan mencoba mendaftar kembali pada posisi atau instansi lain yang kuotanya masih tersedia melalui portal kami.

@component('mail::button', ['url' => route('login')])
Lihat Lowongan Lain
@endcomponent

Demikian pemberitahuan ini kami sampaikan. Kami mengapresiasi antusiasme Anda dan semoga sukses dalam perjalanan akademis Anda.

Hormat kami,<br>
**Admin SiMagang**<br>
Pemerintah Kota Banjarmasin
@endcomponent
