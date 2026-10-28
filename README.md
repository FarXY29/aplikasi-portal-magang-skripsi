# SIMAGANG — Portal Magang Kota Banjarmasin

Sistem Informasi Manajemen Magang (**SIMAGANG**) adalah portal berbasis web untuk mengelola siklus magang dan Praktik Kerja Lapangan (PKL) di lingkungan Pemerintah Kota Banjarmasin. Aplikasi mencakup pendaftaran, seleksi, presensi, logbook, penilaian, hingga penerbitan dokumen dan sertifikat.

Dibangun dengan **Laravel 13**, **PHP 8.3+**, **MySQL**, **Tailwind CSS**, **Alpine.js**, dan **Vite**.

## Fitur utama

- **Pendaftaran dan seleksi** — katalog lowongan, pencocokan jurusan, pengelolaan kuota, dan pelacakan status permohonan.
- **Presensi dan logbook** — presensi berbasis lokasi/QR, validasi pembimbing, serta pengajuan dan penyelesaian sanggahan.
- **Dokumen resmi** — LoA, ID card, transkrip, sertifikat, dan laporan PDF dengan verifikasi QR.
- **Akses berbasis peran** — pemisahan hak akses Admin Kota, Admin Instansi, pembimbing, dan peserta.
- **Dokumen privat** — CV, surat pengantar, bukti presensi, dan lampiran lain disimpan di storage privat dengan pemeriksaan otorisasi.
- **Notifikasi dan pekerjaan latar** — email/notifikasi dalam aplikasi, backup database, dan tugas terjadwal melalui queue dan scheduler Laravel.
- **Antarmuka responsif** — navigasi desktop dan mobile, filter interaktif, peta, serta pemindaian QR.

## Peran pengguna

| Peran | Tanggung jawab utama |
| --- | --- |
| Admin Kota | Mengelola instansi, pengguna, data master, sertifikat, laporan kota, pengaturan, dan backup. |
| Admin Instansi | Mengelola lowongan, seleksi, pembimbing, peserta, sanggahan presensi, dan laporan instansi. |
| Pembimbing Lapangan | Memvalidasi presensi dan logbook serta memberikan penilaian akhir. |
| Pembimbing Akademik | Memantau perkembangan, logbook, dan kehadiran peserta bimbingan. |
| Peserta | Mendaftar magang, melakukan presensi, mengisi logbook dan evaluasi, serta mengunduh dokumen. |
| Publik | Melihat lowongan, melacak permohonan, dan memverifikasi dokumen. |

## Prasyarat

- PHP 8.3 atau lebih baru, dengan ekstensi yang tercantum di `composer.json` (`bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `gd`, `intl`, `mbstring`, `openssl`, `pdo_mysql`, dan `zip`).
- Composer 2.x.
- Node.js 18+ dan npm; Node.js 22 digunakan oleh CI.
- MySQL 8 atau MariaDB 10.4+.
- Redis, karena konfigurasi bawaan memakai Redis untuk cache dan session. Alternatifnya, ubah `CACHE_STORE` dan `SESSION_DRIVER` di `.env`.

## Instalasi lokal

Contoh perintah berikut menggunakan PowerShell. Di macOS/Linux, gunakan `cp .env.example .env` sebagai pengganti `Copy-Item`.

1. Clone repositori dan masuk ke direktorinya:

   ```powershell
   git clone https://github.com/FarXY29/aplikasi-portal-magang-skripsi.git
   Set-Location aplikasi-portal-magang-skripsi
   ```

2. Instal dependensi dan siapkan konfigurasi:

   ```powershell
   composer install
   npm ci
   Copy-Item .env.example .env
   php artisan key:generate
   ```

3. Buat database MySQL/MariaDB kosong, lalu sesuaikan konfigurasi berikut di `.env`:

   ```dotenv
   APP_NAME="Portal Magang Banjarmasin"
   APP_URL=http://localhost:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=portal_magang
   DB_USERNAME=root
   DB_PASSWORD=

   QUEUE_CONNECTION=database
   CACHE_STORE=redis
   SESSION_DRIVER=redis
   FILESYSTEM_DISK=local
   ```

   Jika Redis tidak tersedia untuk pengembangan lokal, gunakan `CACHE_STORE=file` dan `SESSION_DRIVER=file`. Alternatif ini tidak memerlukan tabel cache atau session tambahan.

4. Jalankan migrasi, isi data contoh, dan buat symbolic link untuk aset publik:

   ```powershell
   php artisan migrate --seed
   php artisan storage:link
   ```

   > `migrate --seed` ditujukan untuk instalasi pengembangan baru. Jangan menjalankan seeder data contoh pada database produksi.

5. Jalankan Vite dan server Laravel di dua terminal terpisah:

   ```powershell
   npm run dev
   ```

   ```powershell
   php artisan serve
   ```

   Buka **http://localhost:8000**. Vite dikonfigurasi dapat diakses dari perangkat lain di jaringan lokal; pastikan `APP_URL`, `APP_PUBLIC_URL`, dan pengaturan proxy sesuai jika aplikasi diakses melalui perangkat atau tunnel lain.

### Akun data contoh

Akun yang dibuat oleh `DatabaseSeeder` menggunakan kata sandi `password`.

| Peran | Email | Username |
| --- | --- | --- |
| Admin Kota | `admin@banjarmasin.go.id` | `superadmin` |
| Admin Instansi | `admin.instansi1@banjarmasin.go.id` | `admin_instansi_1` |
| Pembimbing Lapangan | `pembimbing.lapangan.1_0@banjarmasin.go.id` | `pembimbing_lapangan_1_0` |
| Pembimbing Akademik | `pembimbing1@kampus.ac.id` | `pembimbing_1` |
| Peserta | `peserta1@gmail.com` | `peserta_1` |

Seeder juga membuat akun Admin Instansi 1–15 dan Peserta 1–60 dengan pola yang sama. Kredensial ini hanya untuk lingkungan pengembangan.

## Menjalankan aplikasi

Untuk pengembangan, proses yang umumnya dibutuhkan adalah:

```powershell
php artisan serve
npm run dev
php artisan queue:work --tries=3 --timeout=90
php artisan schedule:work
```

Queue worker diperlukan untuk pekerjaan asinkron seperti email dan backup. Scheduler menjalankan penutupan magang kedaluwarsa, notifikasi masa berakhir, pembersihan backup, dan pemeliharaan queue.

Untuk membangun aset produksi:

```powershell
npm run build
```

Hasil build ditulis ke `public/build`. Jangan menjalankan Vite dev server sebagai pengganti build aset produksi.

## Konfigurasi penting

Semua konfigurasi lingkungan berada di `.env`; template yang aman untuk disalin tersedia di `.env.example`. Jangan commit `.env` atau kredensial produksi.

| Variabel | Kegunaan |
| --- | --- |
| `APP_URL` | URL utama aplikasi. |
| `APP_PUBLIC_URL` | URL publik opsional untuk QR atau tautan yang dibuka dari perangkat lain. |
| `TRUSTED_PROXIES` | IP/CIDR reverse proxy tepercaya, dipisahkan koma. |
| `DB_*` | Koneksi MySQL/MariaDB. |
| `CACHE_STORE`, `SESSION_DRIVER` | Backend cache dan session; bawaan menggunakan Redis. |
| `QUEUE_CONNECTION` | Driver queue; bawaan `database`, sehingga worker harus dijalankan. |
| `MAIL_*` | SMTP untuk email. Port `1025` pada contoh cocok untuk Mailpit/Mailhog lokal. |
| `ATTENDANCE_FRAUD_*` | Deteksi kecurangan presensi, rate limit, nonce, dan retensi percobaan. |

Mode deteksi kecurangan presensi:

- `shadow`: mencatat deteksi tanpa memengaruhi keputusan presensi.
- `soft`: menandai aktivitas mencurigakan.
- `enforce`: memblokir kondisi kritikal sesuai aturan aplikasi.

Gunakan `shadow` saat penerapan awal dan evaluasi hasilnya sebelum mengaktifkan mode yang lebih ketat.

### Storage privat

Berkas sensitif disimpan pada disk `private` di `storage/app/private`, antara lain dokumen pelamar, bukti presensi/logbook, lampiran sengketa, dan backup. Berkas diakses melalui `/storage-access/{type}/{filename}` dengan autentikasi dan pemeriksaan policy.

Jangan memindahkan dokumen sensitif ke `public/storage` atau membuat tautan publik langsung ke direktori privat. Untuk migrasi dokumen lama, lihat perintah `documents:migrate-private` di bawah.

## Pengujian dan pemeriksaan kualitas

Pengujian menggunakan PHPUnit dan **database MySQL terpisah**, sesuai `phpunit.xml`. Buat database `aplikasi_magang_skripsi_testing`, atau ubah nama database tersebut pada konfigurasi pengujian lokal. Jangan mengarahkan pengujian ke database pengembangan atau produksi.

Salin template pengujian bila perlu, lalu sesuaikan kredensial MySQL:

```powershell
Copy-Item .env.testing.example .env.testing
```

Jalankan pemeriksaan berikut sebelum mengirim perubahan:

```powershell
# Seluruh test suite
php artisan test

# Satu kelas atau kelompok pengujian
php artisan test --filter=AttendanceRegressionTest
php artisan test --filter=Security

# Format kode PHP
./vendor/bin/pint

# Kompilasi Blade dan validasi pendaftaran rute
php artisan view:cache
php artisan route:list --except-vendor

# Build frontend
npm run build
```

Setelah `view:cache` selama pengembangan, jalankan `php artisan view:clear` jika perubahan Blade tidak langsung terlihat.

CI di `.github/workflows/quality.yml` menjalankan instalasi dependensi, migrasi database, pemeriksaan view dan rute, test suite, serta build frontend.

## Perintah Artisan khusus

| Perintah | Kegunaan |
| --- | --- |
| `php artisan positions:sync-categories` | Menyinkronkan kategori dan kualifikasi posisi magang. |
| `php artisan magang:backfill-roles-master` | Menyinkronkan role RBAC dan data master institusi. |
| `php artisan documents:migrate-private --dry-run` | Meninjau migrasi dokumen lama ke storage privat tanpa mengubah berkas. |
| `php artisan documents:migrate-private` | Menjalankan migrasi dokumen lama ke storage privat. |
| `php artisan internship:complete-expired` | Menutup magang yang telah melewati tanggal selesai. |
| `php artisan app:send-ending-notifications` | Mengirim pengingat masa berakhir magang. |
| `php artisan backups:prune` | Menghapus backup yang melewati masa retensi. |

Buat backup sebelum menjalankan perintah yang mengubah data atau memindahkan berkas pada lingkungan produksi.

## Struktur proyek

```text
app/
├── Actions/             # Operasi domain dengan satu tanggung jawab
├── Console/Commands/    # Perintah Artisan khusus
├── Enums/               # Status dan tipe domain
├── Http/                # Controller, Form Request, dan middleware
├── Jobs/                # Pekerjaan queue
├── Models/              # Model Eloquent
├── Policies/            # Otorisasi tingkat data
└── Services/            # Logika bisnis, presensi, dan dokumen
config/                  # Konfigurasi aplikasi dan integrasi
database/
├── migrations/          # Skema database
└── seeders/             # Data master dan data contoh
resources/
├── js/                  # JavaScript dan loader dependensi browser
├── css/                 # Sumber Tailwind CSS
└── views/               # Blade, komponen UI, dan template PDF
routes/                  # Rute yang dipisahkan berdasarkan modul/peran
tests/                   # Pengujian Feature dan Unit
```

Alur utama kode mengikuti pola: **route → Form Request → Controller → Service/Action → Model**. Otorisasi data ditangani melalui Policy dan role permission. Aset frontend dibundel melalui Vite; dependensi browser dimuat melalui `resources/js/vendor-loader.js`, bukan CDN.

## Catatan deployment

Sebelum deployment produksi:

1. Gunakan PHP, Node.js, dan database yang memenuhi prasyarat; jalankan `composer install --no-dev --optimize-autoloader` dan `npm ci && npm run build` pada proses build.
2. Set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` HTTPS yang benar, kredensial database/SMTP, serta konfigurasi proxy dan cookie yang sesuai.
3. Jalankan `php artisan migrate --force` setelah memastikan backup tersedia.
4. Jalankan queue worker yang diawasi process manager, misalnya Supervisor.
5. Tambahkan scheduler ke cron server:

   ```cron
   * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
   ```

6. Pastikan direktori `storage` dan `bootstrap/cache` dapat ditulis oleh user web, tanpa membuka storage privat ke publik.
7. Sesuaikan konfigurasi web server agar document root mengarah ke direktori `public`.

Deployment memerlukan evaluasi keamanan dan pencadangan tersendiri; jangan menggunakan akun data contoh atau kata sandi bawaan di produksi.

## Panduan kontribusi

- Pertahankan istilah domain Bahasa Indonesia yang sudah digunakan oleh kode.
- Gunakan Form Request untuk validasi dan Service/Action untuk logika bisnis.
- Gunakan komponen Blade yang tersedia dan prefix `_` untuk partial.
- Format PHP dengan Laravel Pint dan gunakan npm untuk dependensi JavaScript.
- Tambahkan atau perbarui pengujian untuk perubahan perilaku.
- Jangan commit `.env`, kredensial, backup database, atau dokumen privat.

Pedoman teknis tambahan tersedia di `AGENTS.md`.
