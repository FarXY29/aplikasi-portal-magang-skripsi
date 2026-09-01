# 🏛️ Portal Magang Pemerintah Kota Banjarmasin

Sistem Informasi Manajemen Magang (SIMAGANG) Pemerintah Kota Banjarmasin adalah platform terpadu untuk mengelola seluruh siklus program magang dan praktik kerja lapangan (PKL) secara digital, transparan, dan akuntabel di lingkungan Satuan Kerja Perangkat Daerah (SKPD/OPD) Pemerintah Kota Banjarmasin.

---

## 🌟 Fitur Utama & Keunggulan

- **🛡️ Keamanan Berkas Privat (Private Storage Isolation)**: Berkas sensitif (CV, Surat Pengantar, Bukti Absensi, dan Bukti Logbook) disimpan pada direktori disk `private` dan diakses eksklusif melalui `StorageAccessController` dengan validasi otorisasi peran dinamis.
- **📍 Presensi Geolocation & Radius Validasi**: Presensi masuk, pulang, dan izin didukung peta interaktif (Leaflet.js) yang memvalidasi koordinat GPS peserta terhadap titik koordinat dan batas radius instansi.
- **🗄️ Sistem Backup Database Asinkron**: Pembuatan cadangan database SQL terkompresi Gzip via Queue Worker (`CreateDatabaseBackup`), dilengkapi URL unduhan bertanda tangan aman (`signed URL`) dan pembersihan otomatis berkala (`backups:prune`).
- **📜 Tata Kelola & Registri Sertifikat Resmi Kota**: Dilengkapi generator nomor sertifikat otomatis (`GenerateCertificateNumberAction`), QR code verifikasi publik, hash integritas tanda tangan digital, serta fitur *revoke* dan *restore* status sertifikat oleh Admin Kota.
- **⭐ Evaluasi Alur Sertifikat (Gated Feedback)**: Peserta diwajibkan mengisi formulir evaluasi dan saran program magang sebelum modul unduh transkrip nilai, sertifikat, dan LoA diaktifkan.
- **📄 Penerbitan Dokumen Digital Lengkap**: Generator PDF otomatis untuk Surat Penerimaan (*Letter of Acceptance* / LoA), Kartu Tanda Peserta (*ID Card* dengan QR), Transkrip Nilai, Lembar Logbook, Rekap Presensi, dan Sertifikat Kelulusan.
- **📊 Pusat Laporan Eksekutif Multi-Dimensi**: Tersedia pusat laporan eksekutif untuk Admin Kota dan Admin Instansi dengan ekspor PDF cetak siap pakai (Laporan Kinerja, Beban Pembimbing, Demografi Kampus/Jurusan, Penyerapan Kuota, dan Tingkat Kedisiplinan).
- **🔔 Sistem Notifikasi In-App & Email**: Notifikasi waktu nyata di dalam dashboard pengguna (*unread counter badge*) dan notifikasi email otomatis saat pendaftaran disetujui/ditolak, serta pengingat H-7 masa magang berakhir.
- **🎯 Penempatan Otomatis & Cek Ketersediaan**: Fitur rekomendasi/penempatan otomatis berdasarkan jurusan serta pengecekan sisa kuota lowongan instansi secara real-time.
- **🌙 Antarmuka Modern & Dark Mode**: Desain UI responsif dengan Tailwind CSS dan Alpine.js, mendukung preferensi mode terang dan mode gelap di seluruh panel.

---

## 👥 Matriks Peran & Hak Akses

| Peran | Tanggung Jawab & Fitur Utama |
| :--- | :--- |
| **Admin Kota (Super Admin)** | Manajemen OPD/Instansi, manajemen pengguna & RBAC, Data Master Rumpun & Jurusan, Registri & Pencabutan Sertifikat Kota, Audit Trail Aktivitas, Pusat Laporan Kota, serta Pengaturan Sistem & Backup Database. |
| **Admin Instansi (OPD/Dinas)** | Manajemen lowongan/posisi magang, seleksi & validasi pelamar, penugasan Pembimbing Lapangan, monitoring peserta aktif, penerbitan sertifikat dinas, pengaturan pejabat penandatangan, dan laporan instansi. |
| **Pembimbing Lapangan** | Validasi harian absensi (hadir, izin, sakit), validasi logbook (mandiri/batch verifikasi), pemberian catatan perbaikan kegiatan, serta penilaian performa akhir (Teknis, Disiplin, Perilaku). |
| **Pembimbing Akademik (Kampus/Sekolah)** | Monitoring perkembangan mahasiswa/siswa bimbingan, riwayat logbook harian, status kehadiran, serta pencapaian magang dari institusi asal. |
| **Peserta Magang** | Pencarian lowongan, pengajuan magang reguler/otomatis, presensi GPS radius, pengisian logbook harian, pengisian feedback evaluasi, serta unduh ID Card, LoA, Transkrip Nilai, dan Sertifikat. |
| **Publik / Tamu** | Eksplorasi katalog lowongan magang, pelacakan status permohonan via token pendaftaran, pemindai QR Code, dan verifikasi keaslian sertifikat & ID Card. |

---

## 🔑 Kredensial Akun Pengujian (Demo Data)

Semua akun hasil *seeder* menggunakan kata sandi standar: `password`

| Peran | Alamat Email | Username | Keterangan |
| :--- | :--- | :--- | :--- |
| **Admin Kota** | `admin@banjarmasin.go.id` | `superadmin` | Akses penuh tingkat Kota |
| **Admin Instansi** | `admin.instansi1@banjarmasin.go.id` | `admin_instansi_1` | Admin Diskominfotik (tersedia instansi 1 s.d 15) |
| **Pembimbing Lapangan** | `pembimbing.lapangan.1_0@banjarmasin.go.id` | `pembimbing_lapangan_1_0` | Pembimbing di Instansi 1 |
| **Pembimbing Sekolah/Kampus** | `pembimbing1@kampus.ac.id` | `pembimbing_1` | Dosen/Guru Pembimbing Asal Institusi |
| **Peserta Magang** | `peserta1@gmail.com` | `peserta_1` | Mahasiswa/Siswa Magang (tersedia 1 s.d 60) |

---

## 🛠️ Arsitektur & Teknologi

- **Backend Framework**: [Laravel 13](https://laravel.com/) (PHP 8.3+)
- **Role-Based Access Control**: `spatie/laravel-permission` (v8.3+)
- **Database & Cache**: MySQL 8.0 / MariaDB 10.4+ & Redis (via `predis/predis`)
- **Queue & Async Job**: Database Queue Driver / Redis Driver
- **Frontend & Assets**: [Vite](https://vitejs.dev/), [Tailwind CSS](https://tailwindcss.com/), [Alpine.js](https://alpinejs.dev/)
- **Peta & Geolocation**: [Leaflet.js](https://leafletjs.com/) & OpenStreetMap
- **Dokumen & PDF**: `barryvdh/laravel-dompdf` (v3.1+)
- **Rekapitulasi Spreadsheet**: `maatwebsite/excel` (v3.1+)
- **Kode QR & Verifikasi**: `simplesoftwareio/simple-qrcode` (v4.2+) & HTML5 QR Scanner
- **Backup Utility**: `ifsnop/mysqldump-php`

---

## 🚀 Panduan Instalasi & Menjalankan Lokal

### 1. Prasyarat Sistem
- PHP >= 8.3 dengan ekstensi: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `gd`, `intl`, `mbstring`, `openssl`, `pdo_mysql`, `zip`.
- [Composer](https://getcomposer.org/) (v2.x)
- [Node.js](https://nodejs.org/) (>= v18.x) & NPM
- MySQL Server / MariaDB
- Redis Server (opsional untuk lokal, dapat menggunakan driver `database`/`file`)

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

4. **Konfigurasi File `.env`**:
   Buka file `.env` dan sesuaikan koneksi database serta opsi storage/queue:
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

5. **Generate Application Key**:
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

8. **Kompilasi Aset Frontend & Jalankan Server**:
   Buka dua jendela terminal terpisah:
   ```powershell
   # Terminal 1 - Frontend Dev Server
   npm run dev
   ```
   ```powershell
   # Terminal 2 - Laravel HTTP Server
   php artisan serve
   ```
   Aplikasi siap diakses pada: `http://localhost:8000`

---

## ⏱️ Queue Worker & Penjadwalan Tugas (Scheduler)

### Menjalankan Antrean (Queue Worker)
Untuk memproses pekerjaan latar belakang (seperti backup database terkompresi dan pengiriman email):
```powershell
php artisan queue:work
```

### Menjalankan Scheduler Lokal
Untuk menjalankan tugas terjadwal secara berkala di lingkungan lokal:
```powershell
php artisan schedule:work
```

### Ringkasan Tugas Terjadwal (Cron Job di Server Produksi)
Tambahkan entri cron berikut pada server produksi:
```cron
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

| Perintah | Jadwal | Deskripsi |
| :--- | :--- | :--- |
| `internship:complete-expired` | Setiap Hari (00:00) | Menutup status magang yang telah melewati `tanggal_selesai`. |
| `app:send-ending-notifications` | Setiap Hari (08:00) | Mengirimkan email pengingat H-7 berakhirnya masa magang ke peserta. |
| `backups:prune` | Setiap Jam | Menghapus log riwayat dan file fisik arsip database backup yang kedaluwarsa. |

---

## ⚡ Perintah Artisan Khusus

| Perintah | Deskripsi & Penggunaan |
| :--- | :--- |
| `php artisan magang:backfill-roles-master` | Menyinkronkan role pengguna ke Spatie RBAC dan memetakan teks `asal_instansi` ke master `universities` / `schools`. |
| `php artisan documents:migrate-private [--dry-run]` | Memindahkan dokumen sensitif lama (surat pengantar, bukti logbook, bukti absensi) dari storage publik ke storage privat. |
| `php artisan internship:complete-expired` | Memproses penyelesaian magang kadaluwarsa secara manual. |
| `php artisan app:send-ending-notifications` | Memicu pengiriman email pengingat H-7 berakhirnya magang secara manual. |
| `php artisan backups:prune` | Membersihkan berkas backup fisik dan entri data cadangan yang sudah melewati batas waktu retensi. |

---

## 🧪 Pengujian & Kualitas Kode (Testing & QA)

Proyek ini dilengkapi dengan suite pengujian otomatis komprehensif untuk pengujian modul otentikasi, hak akses multi-peran, perizinan storage, pelaporan PDF, dan pengujian alur bisnis.

```powershell
# Menjalankan seluruh test suite
php artisan test

# Menjalankan test dengan format ringkas
php artisan test --compact

# Menjalankan grup pengujian tertentu
php artisan test --filter=CertificateGovernanceTest
php artisan test --filter=SecureStorageAccessTest
php artisan test --filter=AllPdfReportsRenderingTest

# Pemeriksaan cache view & rute
php artisan view:cache
php artisan route:list --except-vendor
```

---

## 🔒 Tata Kelola Berkas Privat & Keamanan

1. **Isolasi Folder Storage**:
   - `storage/app/private/cv/` - Curriculum Vitae pelamar.
   - `storage/app/private/surat_pengantar/` - Surat pengantar institusi.
   - `storage/app/private/logbook/` - Foto/dokumen lampiran logbook harian.
   - `storage/app/private/attendance/` - Foto bukti presensi selfie/kehadiran.
   - `storage/app/private/backups/` - File dump SQL database yang dikompresi gzip.

2. **Akses Berkas Terproteksi**:
   Akses berkas dilakukan via endpoint `/storage-access/{type}/{filename}` yang diverifikasi oleh middleware `auth` dan kebijakan kepemilikan (`Policy` / `Gate`) di `StorageAccessController`.

---

## 📂 Struktur Direktori Utama

```plaintext
aplikasi-magang/
├── app/
│   ├── Actions/                  # Logika bisnis mandiri (cth: GenerateCertificateNumberAction)
│   ├── Console/Commands/         # Perintah CLI Artisan khusus
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminKota/        # Kontroler modul Super Admin Kota
│   │   │   ├── AdminInstansi/    # Kontroler modul Admin OPD / Dinas
│   │   │   ├── Peserta/          # Kontroler modul Mahasiswa/Siswa
│   │   │   └── Public/           # Kontroler pencarian lowongan, pelacakan & verifikasi QR
│   │   └── Middleware/           # Middleware kustom (Security, Role Check)
│   ├── Jobs/                     # Queue Jobs (CreateDatabaseBackup)
│   ├── Mail/                     # Mailable classes notifikasi email
│   ├── Models/                   # Eloquent models & relasi database
│   ├── Notifications/            # Notifikasi in-app
│   └── Policies/                 # Kebijakan otorisasi data (RBAC Security)
├── database/
│   ├── migrations/               # Skema tabel database
│   └── seeders/                  # Seeder data master, instansi, & akun demo
├── resources/
│   ├── views/
│   │   ├── admin/                # Antarmuka Admin Kota & Laporan Eksekutif
│   │   ├── dinas/                # Antarmuka Admin Instansi & Pengaturan
│   │   ├── pembimbing_lapangan/  # Antarmuka validasi logbook & penilaian
│   │   ├── pembimbing/           # Antarmuka monitoring kampus/sekolah
│   │   ├── peserta/              # Antarmuka dashboard, absensi, & logbook
│   │   └── pdf/                  # Template dokumen cetak PDF (LoA, ID Card, Sertifikat)
│   ├── css/                      # Konfigurasi styling Tailwind CSS
│   └── js/                       # Skrip Alpine.js, Leaflet, & QR Scanner
├── routes/                       # Rute web terpisah per modul peran
└── tests/                        # Suite pengujian Feature & Unit
```

---

## 📄 Lisensi

Aplikasi ini dikembangkan di bawah lisensi [MIT License](LICENSE).

