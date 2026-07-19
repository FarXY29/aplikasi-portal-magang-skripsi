# Portal Magang Pemerintah Kota Banjarmasin

Portal Magang mengelola seluruh siklus magang: publikasi lowongan, pengajuan peserta, seleksi, penempatan, absensi berbasis lokasi, logbook, penilaian, sertifikat QR, laporan, dan audit aktivitas.

## Peran pengguna

- **Admin Kota**: mengelola instansi, pengguna, pengaturan, audit trail, dan laporan kota.
- **Admin Instansi**: mengelola lowongan, pelamar, peserta aktif, pembimbing lapangan, sertifikat, dan laporan instansi.
- **Pembimbing Lapangan**: memvalidasi absensi dan logbook serta mengisi penilaian.
- **Pembimbing Akademik/Sekolah**: memantau logbook dan absensi peserta bimbingan.
- **Peserta**: mengajukan magang, melihat status, mengisi absensi/logbook, dan mengunduh dokumen hasil magang.

## Teknologi

- PHP 8.3+ dan Laravel 13
- MySQL/MariaDB
- Vite, Tailwind CSS, dan Alpine.js
- Laravel Sanctum, Spatie Permission, DomPDF, Laravel Excel, serta queue database

## Menjalankan secara lokal

1. Salin konfigurasi: `Copy-Item .env.example .env`.
2. Isi koneksi database, mailer, dan URL aplikasi pada `.env`.
3. Instal dependensi: `composer install` dan `npm ci`.
4. Buat application key: `php artisan key:generate`.
5. Jalankan migrasi dan data awal: `php artisan migrate --seed`.
6. Buat tautan storage: `php artisan storage:link`.
7. Jalankan aplikasi: `php artisan serve` dan `npm run dev`.

Untuk proses asynchronous, jalankan worker queue di terminal terpisah:

```powershell
php artisan queue:work
```

Scheduler harus dijalankan di lingkungan produksi setiap menit:

```powershell
php artisan schedule:work
```

## Pemeriksaan kualitas

```powershell
php artisan view:cache
php artisan route:list --except-vendor
php artisan test --compact
npm run build
```

Pipeline GitHub Actions menjalankan pemeriksaan yang sama pada setiap push dan pull request ke `main`.

## Catatan operasional

- Jangan menyimpan `.env`, database dump, atau dokumen peserta di Git.
- Konfigurasikan `APP_ENV=production`, `APP_DEBUG=false`, mailer, queue, dan storage sebelum rilis.
- Jalankan backup dan perubahan data melalui prosedur administrator yang terdokumentasi; jangan menghapus data produksi secara langsung.

## Upgrade keamanan Fase 1

Pada upgrade aplikasi yang sudah memiliki pengguna, jalankan sekali setelah deployment:

```powershell
php artisan db:seed --class=Database\Seeders\RoleAndPermissionSeeder --force
php artisan magang:backfill-roles-master
php artisan documents:migrate-private --dry-run
php artisan documents:migrate-private
```

Perintah terakhir memindahkan surat pengantar, bukti logbook, dan bukti izin/sakit lama dari storage public ke storage private. Pastikan hasil `--dry-run` sudah ditinjau dan backup database tersedia sebelum menjalankannya tanpa opsi tersebut.
