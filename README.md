# 🏛️ Portal Magang Pemerintah Kota Banjarmasin (SIMAGANG)

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 13" />
  <img src="https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3+" />
  <img src="https://img.shields.io/badge/Tests-377%20Passed%20%7C%201467%20Asserts-brightgreen?style=for-the-badge&logo=phpunit&logoColor=white" alt="Automated Tests" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.4+-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
  <img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine.js" />
  <img src="https://img.shields.io/badge/Security-Private%20Storage%20%2B%20RBAC-0ea5e9?style=for-the-badge&logo=shield&logoColor=white" alt="Security" />
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="MIT License" />
</p>

Sistem Informasi Manajemen Magang (**SIMAGANG**) Pemerintah Kota Banjarmasin adalah platform terpadu tingkat pemerintahan kota untuk mengelola seluruh siklus program magang dan Praktik Kerja Lapangan (PKL) secara digital, transparan, aman, dan akuntabel di lingkungan Satuan Kerja Perangkat Daerah (SKPD/OPD) Pemerintah Kota Banjarmasin.

---

## 🌟 Fitur Utama & Keunggulan Sistem

- **🔄 State Machine Alur Magang & Linimasa Tak Terubahkan (*Immutable Timeline*)**:
  Siklus pendaftaran terstandarisasi melalui `ApplicationStateTransitionService` dengan proteksi kuota berbasis *pessimistic lock* (`lockForUpdate`), pencegahan transisi status ilegal, dan pencatatan riwayat peristiwa pendaftaran yang permanen dan transparan (`application_timelines`).
- **⚖️ Alur Sengketa Presensi & Anti-Fraud Decision Engine**:
  Mekanisme formal pengajuan dan penyelesaian sanggahan presensi oleh peserta (`AttendanceDisputeService`), evaluasi otomatis multi-faktor (lokasi GPS, batas radius instansi, validitas tanda tangan QR), serta klasifikasi keputusan operasional terstandarisasi (`AttendanceOperationalDecision`).
- **🎯 Smart Matching Jurusan & Filter Terpersonalisasi**:
  Algoritma pencocokan posisi magang berbasis rumpun keilmuan dan jenjang pendidikan, diproteksi ganda di sisi backend untuk mencegah manipulasi pendaftaran yang tidak memenuhi syarat, serta dilengkapi filter interaktif cerdas (*Lowongan Sesuai Jurusan Saya* vs *Semua Lowongan*).
- **🛡️ Isolasi Berkas Privat (*Private Storage Isolation*)**:
  Semua dokumen sensitif (CV, Surat Pengantar, Bukti Kehadiran/Selfie, Bukti Logbook, dan Berkas Sengketa) tersimpan eksklusif di dalam disk `private` dan diakses secara terkontrol melalui `StorageAccessController` dengan verifikasi otorisasi kepemilikan berbasis `Policy`.
- **📜 Tata Kelola & Registri Sertifikat Resmi Kota**:
  Generator nomor sertifikat kedinasan otomatis (`GenerateCertificateNumberAction`), QR code verifikasi publik, hash integritas digital, serta fitur *revoke* dan *restore* status sertifikat oleh Admin Kota.
- **⭐ Evaluasi Alur Kelulusan (*Gated Feedback*)**:
  Peserta diwajibkan menyelesaikan formulir evaluasi dan umpan balik program magang sebelum modul unduh dokumen akhir (Transkrip Nilai, Sertifikat Kelulusan, dan LoA) dapat diakses.
- **📄 Penerbitan Dokumen Standar Kedinasan**:
  Generator PDF dokumen resmi ber-Kop Surat Pemko Banjarmasin, tata letak Times New Roman kedinasan, grid statistik 6-kotak, blok pengesahan pejabat penandatangan, nomor halaman otomatis, serta QR Code verifikasi publik (Surat Penerimaan/LoA, Kartu Tanda Peserta/ID Card, Transkrip Nilai, Lembar Kegiatan Logbook, Rekap Presensi, dan Laporan Pendaftaran).
- **📊 Pusat Laporan Eksekutif & Pelacakan Real-Time**:
  Pusat laporan komprehensif untuk Admin Kota dan Admin Instansi dengan *dual-view* responsif (tabel desktop & kartu mobile), *interactive status pills*, pencarian instan via Alpine.js, serta modal detail pelacakan alur permohonan.
- **🌙 Antarmuka Adaptif Eye-Comfort Dark Slate & Mobile-First**:
  Palet warna modern Dark Slate yang ramah di mata, desktop *Mini Sidebar* (80px) hemat ruang dengan *floating portal tooltips*, serta navigasi *Bottom Navigation Bar* ergonomis khusus layar mobile (< md) tanpa gangguan drawer samping.
- **🔔 Sistem Notifikasi Asinkron & Ketahanan Antrean**:
  Notifikasi in-app real-time dan notifikasi email mailable dengan antrean otomatis (`ShouldQueue`), mekanisme *auto-retry* ($tries=3, exponential backoff), pengiriman *after-commit*, serta observabilitas pencatatan kegagalan antrean tanpa membocorkan kredensial.
- **🗄️ Sistem Backup Database Asinkron & Pruning Terjadwal**:
  Pembuatan cadangan database SQL terkompresi Gzip via Queue Worker (`CreateDatabaseBackup`), dilengkapi URL unduhan bertanda tangan aman (*signed URL*) dan pembersihan otomatis berkala (*pruning retention*).

---

## 👥 Matriks Peran & Hak Akses

Aplikasi mengimplementasikan *Role-Based Access Control* (RBAC) granular dengan 6 peran pengguna:

| Peran | Tanggung Jawab & Fitur Utama |
| :--- | :--- |
| **Admin Kota (Super Admin)** | Manajemen OPD/Instansi se-Kota, kontrol pengguna & RBAC, Data Master Rumpun & Jurusan, Registri & Pembatalan Sertifikat Kota, Audit Trail Aktivitas, Pusat Laporan Kota Terpadu, serta Konfigurasi Sistem & Backup Database. |
| **Admin Instansi (OPD/SKPD)** | Manajemen lowongan/posisi magang, seleksi & validasi pelamar, penugasan Pembimbing Lapangan, monitoring peserta aktif, penyelesaian sengketa presensi, pengaturan pejabat penandatangan, dan laporan instansi. |
| **Pembimbing Lapangan** | Validasi harian presensi (hadir, izin, sakit), validasi logbook harian (mandiri/batch verifikasi), pemberian catatan perbaikan bimbingan, serta penilaian evaluasi performa akhir (Teknis, Disiplin, Perilaku). |
| **Pembimbing Akademik (Kampus/Sekolah)** | Monitoring berkala perkembangan mahasiswa/siswa bimbingan, riwayat logbook harian, status kehadiran, serta pencapaian magang dari institusi pendidikan asal. |
| **Peserta Magang (Mahasiswa/Siswa)** | Eksplorasi lowongan terpersonalisasi, pengajuan magang, presensi GPS radius instansi, pengisian logbook harian, pengajuan sanggahan presensi, pengisian feedback evaluasi, serta unduh ID Card, LoA, Transkrip, dan Sertifikat. |
| **Publik / Tamu** | Eksplorasi katalog lowongan magang publik, pelacakan status permohonan via kode pendaftaran, pemindai QR Code, dan verifikasi keaslian dokumen sertifikat & ID Card. |

---

## 🔑 Kredensial Akun Pengujian (Demo Data)

Semua akun hasil *seeder* menggunakan kata sandi standar: `password`

| Peran | Alamat Email | Username | Keterangan |
| :--- | :--- | :--- | :--- |
| **Admin Kota** | `admin@banjarmasin.go.id` | `superadmin` | Akses penuh tingkat Pemerintah Kota |
| **Admin Instansi** | `admin.instansi1@banjarmasin.go.id` | `admin_instansi_1` | Admin Diskominfotik (tersedia instansi 1 s.d 15) |
| **Pembimbing Lapangan** | `pembimbing.lapangan.1_0@banjarmasin.go.id` | `pembimbing_lapangan_1_0` | Pembimbing di Instansi 1 |
| **Pembimbing Akademik** | `pembimbing1@kampus.ac.id` | `pembimbing_1` | Dosen/Guru Pembimbing Sekolah/Kampus |
| **Peserta Magang** | `peserta1@gmail.com` | `peserta_1` | Mahasiswa/Siswa Magang (tersedia 1 s.d 60) |

---

## 🛠️ Arsitektur & Teknologi

- **Backend Framework**: [Laravel 13](https://laravel.com/) (PHP 8.3+)
- **Role-Based Access Control**: `spatie/laravel-permission` (v8.3+)
- **Database & Cache**: MySQL 8.0 / MariaDB 10.4+ & Redis (via `predis/predis` v3.5+)
- **Queue & Async Worker**: Database Queue Driver / Redis Driver dengan Retry & Backoff
- **Frontend & Assets**: [Vite](https://vitejs.dev/), [Tailwind CSS](https://tailwindcss.com/) (v3.4+), [Alpine.js](https://alpinejs.dev/) (v3.x)
- **Peta & Geolocation**: [Leaflet.js](https://leafletjs.com/) & OpenStreetMap
- **Dokumen & PDF Kedinasan**: `barryvdh/laravel-dompdf` (v3.1+)
- **Rekapitulasi Spreadsheet**: `maatwebsite/excel` (v3.1+)
- **Kode QR & Verifikasi**: `simplesoftwareio/simple-qrcode` (v4.2+) & HTML5 QR Scanner
- **Backup Engine**: `ifsnop/mysqldump-php`

---

## 🧩 Arsitektur Antarmuka & Sistem Views Terpadu

Antarmuka SIMAGANG mengadopsi struktur kanonikal modern yang modular dan terstandarisasi:

1. **Pustaka Komponen Kanonikal (`resources/views/components/ui/`)**:
   - `<x-ui.badge>`: Lencana status dinamis (status lamaran, kehadiran, peran pengguna).
   - `<x-ui.button>`: Tombol aksi konsisten dengan ragam varian styling (primary, secondary, danger, ghost).
   - `<x-ui.stat-card>`: Kartu ringkasan metrik statistik untuk dashboard eksekutif dan laporan.
   - `<x-ui.alert>`: Komponen pesan informatif, peringatan, dan umpan balik sistem.
   - `<x-ui.modal>`: Modal dialog responsif untuk konfirmasi aksi dan pelacakan linimasa permohonan.
   - `<x-ui.filter-pills>`: Tab filter interaktif terhubung dengan reaktivitas Alpine.js.
   - `<x-ui.card>`: Kontainer konten terpadu dengan palet Dark Slate.
2. **Konvensi Modul & Partials**:
   - Seluruh berkas sub-komponen internal menggunakan awalan garis bawah (`_*.blade.php`) untuk memisahkan secara jelas antara rute halaman mandiri dan komponen parsial.
   - Standardisasi penamaan folder Admin Kota menggunakan Bahasa Indonesia baku (`jurusan/`, `pengguna/`, `pengaturan/`, `sertifikat/`).
3. **Interaktivitas Tanpa Beban Berat**:
   - Penggunaan Alpine.js untuk fitur *live search*, filter status instan, *interactive modal tracking*, dan *floating portal tooltips* pada sidebar navigasi.

---

## 🚀 Panduan Instalasi & Menjalankan Lokal

### 1. Prasyarat Sistem
- PHP >= 8.3 dengan ekstensi: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `gd`, `intl`, `mbstring`, `openssl`, `pdo_mysql`, `zip`.
- [Composer](https://getcomposer.org/) (v2.x)
- [Node.js](https://nodejs.org/) (>= v18.x) & NPM
- MySQL Server / MariaDB
- Redis Server (opsional untuk lokal, dapat menggunakan driver `database` / `file`)

---

### 2. Langkah Instalasi

1. **Clone Repositori & Masuk ke Direktori**:
   ```powershell
   git clone https://github.com/FarXY29/aplikasi-portal-magang-skripsi.git
   cd "aplikasi-portal-magang-skripsi"
   ```

2. **Salin Berkas Konfigurasi Lingkungan**:
   ```powershell
   Copy-Item .env.example .env
   ```

3. **Instal Dependensi PHP & Node.js**:
   ```powershell
   composer install
   npm install
   ```

4. **Konfigurasi Berkas `.env`**:
   Sesuaikan koneksi database serta opsi storage/queue:
   ```env
   APP_NAME="Portal Magang Banjarmasin"
   APP_URL=http://localhost:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=portal_magang_db
   DB_USERNAME=root
   DB_PASSWORD=

   FILESYSTEM_DISK=local
   QUEUE_CONNECTION=database
   CACHE_STORE=redis
   SESSION_DRIVER=redis
   ```

5. **Generate Kunci Aplikasi**:
   ```powershell
   php artisan key:generate
   ```

6. **Jalankan Migrasi & Seeder Database**:
   ```powershell
   php artisan migrate --seed
   ```

7. **Buat Symlink Penyimpanan Publik**:
   ```powershell
   php artisan storage:link
   ```

8. **Kompilasi Aset & Jalankan Server**:
   Buka dua jendela terminal terpisah:
   ```powershell
   # Terminal 1 - Frontend Dev Server
   npm run dev
   ```
   ```powershell
   # Terminal 2 - Laravel HTTP Server
   php artisan serve
   ```
   Aplikasi siap diakses melalui: `http://localhost:8000`

---

## ⏱️ Worker Antrean & Penjadwalan Tugas (Scheduler)

### Menjalankan Antrean (Queue Worker)
Untuk memproses pekerjaan asinkron (seperti pembuatan arsip backup database terkompresi dan pengiriman email mailable):
```powershell
php artisan queue:work --tries=3 --timeout=90
```

### Menjalankan Scheduler Lokal
Untuk menjalankan tugas terjadwal secara periodik di lingkungan lokal:
```powershell
php artisan schedule:work
```

### Penjadwalan Produksi (Cron Job)
Tambahkan entri cron berikut pada server produksi:
```cron
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

| Perintah | Jadwal | Deskripsi |
| :--- | :--- | :--- |
| `internship:complete-expired` | Setiap Hari (00:00) | Menutup status magang yang telah melewati batas `tanggal_selesai`. |
| `app:send-ending-notifications` | Setiap Hari (08:00) | Mengirimkan email pengingat H-7 berakhirnya masa magang ke peserta. |
| `backups:prune` | Setiap Jam | Menghapus log dan berkas arsip database cadangan yang kedaluwarsa. |

---

## ⚡ Perintah Artisan Khusus

| Perintah | Deskripsi & Penggunaan |
| :--- | :--- |
| `php artisan magang:sync-positions` | Menyinkronkan lowongan instansi dan memastikan pemetaan kualifikasi jurusan serta rumpun keilmuan konsisten. |
| `php artisan magang:backfill-roles-master` | Menyinkronkan role pengguna ke RBAC Spatie dan memetakan string `asal_instansi` ke master `universities` / `schools`. |
| `php artisan documents:migrate-private [--dry-run]` | Memindahkan dokumen sensitif lama (surat pengantar, bukti logbook, bukti absensi) dari storage publik ke storage privat. |
| `php artisan internship:complete-expired` | Menjalankan pemrosesan manual penutupan magang yang telah kedaluwarsa. |
| `php artisan app:send-ending-notifications` | Memicu pengiriman manual email pengingat H-7 masa berakhir magang. |
| `php artisan backups:prune` | Membersihkan berkas backup fisik dan rekaman database yang melewati batas retensi. |

---

## 🧪 Pengujian & Jaminan Kualitas (Testing & QA)

SIMAGANG dilengkapi dengan test suite otomatis komprehensif dengan cakupan tinggi:

<p align="center">
  <b>Status Pengujian Terkini: 377 Tests Passed / 1,467 Assertions (100% Passing)</b>
</p>

```powershell
# Menjalankan seluruh test suite
php artisan test

# Menjalankan test dengan format ringkas
php artisan test --compact

# Pengujian modul keamanan & otorisasi RBAC
php artisan test --filter=Security

# Pengujian alur presensi, integritas transaksi & anti-fraud
php artisan test --filter=AttendanceRegressionTest

# Pengujian kualifikasi jurusan & rumpun keilmuan
php artisan test --filter=InternshipPositionQualificationMatchingTest

# Pengujian filter cerdas lowongan terpersonalisasi
php artisan test --filter=PersonalizedLowonganFilterTest

# Pengujian rendering dokumen PDF kedinasan
php artisan test --filter=AllPdfReportsRenderingTest

# Pemeriksaan kompilasi seluruh Blade views & rute
php artisan view:cache
php artisan route:list --except-vendor
```

---

## 🔒 Tata Kelola Berkas Privat & Keamanan

1. **Isolasi Folder Storage**:
   - `storage/app/private/cv/` - Curriculum Vitae pelamar.
   - `storage/app/private/surat_pengantar/` - Surat pengantar resmi institusi.
   - `storage/app/private/logbook/` - Foto/dokumen lampiran kegiatan logbook harian.
   - `storage/app/private/attendance/` - Foto bukti kehadiran selfie peserta.
   - `storage/app/private/disputes/` - Dokumen lampiran pengajuan sengketa presensi.
   - `storage/app/private/backups/` - Berkas dump SQL database terkompresi gzip.

2. **Akses Berkas Terproteksi**:
   Akses terhadap berkas sensitif dilayani secara eksklusif melalui rute `/storage-access/{type}/{filename}` yang divalidasi oleh middleware `auth` dan kebijakan otorisasi kepemilikan data (`Policy` / `Gate`) di `StorageAccessController`.

---

## 📂 Struktur Direktori Repositori

```plaintext
aplikasi-magang/
├── app/
│   ├── Actions/                  # Logika bisnis mandiri (GenerateCertificateNumberAction, dll)
│   ├── Console/Commands/         # Perintah CLI Artisan kustom
│   ├── Enums/                    # Definisi tipe & status terstandarisasi (AttendanceOperationalDecision, dll)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminKota/        # Kontroler modul Super Admin Kota (SKPD, Pengguna, Pengaturan, Sertifikat)
│   │   │   ├── AdminInstansi/    # Kontroler modul Admin OPD / Dinas
│   │   │   ├── Pembimbing/       # Kontroler modul Pembimbing Lapangan & Akademik
│   │   │   ├── Peserta/          # Kontroler modul Mahasiswa/Siswa (Absensi, Logbook, Sengketa, Evaluasi)
│   │   │   └── Public/           # Kontroler pencarian lowongan, pelacakan alur & verifikasi QR publik
│   │   └── Middleware/           # Middleware kustom (Security Headers, Role Checks)
│   ├── Jobs/                     # Queue Jobs (CreateDatabaseBackup, dll)
│   ├── Mail/                     # Mailable notifikasi email terantre (ShouldQueue)
│   ├── Models/                   # Model Eloquent & pemetaan relasi database
│   ├── Notifications/            # Notifikasi in-app
│   ├── Policies/                 # Kebijakan otorisasi data (RBAC Security)
│   └── Services/                 # Layer layanan bisnis (State Transition, Dispute, Quota Lock)
├── database/
│   ├── migrations/               # Skema migrasi tabel database
│   └── seeders/                  # Seeder data master, instansi, rumpun jurusan, & akun demo
├── resources/
│   ├── views/
│   │   ├── admin/                # Antarmuka Admin Kota & Laporan Eksekutif se-Kota
│   │   ├── components/
│   │   │   └── ui/               # Pustaka komponen UI kanonikal (badge, button, modal, stat-card, dll)
│   │   ├── dinas/                # Antarmuka Admin Instansi & Pengaturan Penandatangan
│   │   ├── layouts/              # Master layout adaptif (Desktop Mini Sidebar, Mobile Bottom Nav)
│   │   ├── pembimbing_lapangan/  # Antarmuka validasi logbook harian & evaluasi akhir
│   │   ├── pembimbing/           # Antarmuka monitoring dosen/guru pembimbing institusi
│   │   ├── peserta/              # Antarmuka dashboard, absensi GPS, logbook, & sengketa
│   │   ├── pdf/                  # Template dokumen cetak PDF kedinasan (LoA, ID Card, Sertifikat, Laporan)
│   │   └── public/               # Katalog lowongan publik, pelacakan linimasa, & verifikasi QR
│   ├── css/                      # Konfigurasi Tailwind CSS
│   └── js/                       # Skrip Alpine.js, Leaflet Map, & Scanner QR Code
├── routes/                       # Rute web terpisah per modul peran (auth, admin, instansi, dll)
└── tests/                        # Suite pengujian Feature & Unit (Security, Attendance, PDF, Views)
```

---

## 📄 Lisensi

Aplikasi SIMAGANG dikembangkan di bawah lisensi [MIT License](LICENSE).
