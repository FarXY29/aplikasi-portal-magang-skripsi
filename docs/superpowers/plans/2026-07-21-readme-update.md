# README.md Update Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Update README.md to accurately document Portal Magang v2.0+ architecture, environment variables, queue schedules, and quality assurance processes.

**Architecture:** We will replace the entire contents of [README.md](file:///c:/EnvKit/aplikasi-magang-skripsi%20-%20Copy/README.md) with a clean, modern, and structured documentation highlighting all core features (Spatie permission, Socialite Google OAuth, Secure Storage, Database Backups, Gated Feedback, and scheduler configs).

**Tech Stack:** Laravel 13, Markdown

---

### Task 1: Update README.md Content

**Files:**
- Modify: `README.md`

**Interfaces:**
- Produces: Updated documentation in `README.md`

- [ ] **Step 1: Write updated content into README.md**

Write the following content into [README.md](file:///c:/EnvKit/aplikasi-magang-skripsi%20-%20Copy/README.md) (overwriting existing content):

```markdown
# Portal Magang Pemerintah Kota Banjarmasin

Portal Magang mengelola seluruh siklus magang: publikasi lowongan, pengajuan peserta, seleksi, penempatan, absensi berbasis lokasi dengan validasi radius, logbook harian, penilaian kinerja, sertifikat QR, laporan eksekutif, audit aktivitas, dan cadangan database.

## 🌟 Fitur Utama (v2.0+)

- **Keamanan Penyimpanan Dokumen**: Surat pengantar, bukti logbook, dan bukti absensi disimpan di penyimpanan pribadi (`private` disk) dengan otorisasi akses dinamis via `StorageAccessController`.
- **Sistem Backup Database**: Pembuatan berkas cadangan SQL yang dikompresi melalui antrean queue (`CreateDatabaseBackup`), dilengkapi tautan unduh kedaluwarsa bertanda tangan, serta pembersihan berkala otomatis.
- **Google OAuth Login**: Pendaftaran dan login cepat bagi peran **Peserta** dan **Pembimbing Akademik/Sekolah** menggunakan Laravel Socialite.
- **Evaluasi Alur Sertifikat (Gated Feedback)**: Peserta wajib mengisi formulir saran dan evaluasi terlebih dahulu sebelum tombol unduh transkrip nilai dan sertifikat diaktifkan.
- **Absensi Validasi Koordinat & Radius**: Fitur presensi masuk/pulang berbasis Leaflet Map yang divalidasi berdasarkan titik koordinat dan batas radius yang dapat dikonfigurasi per instansi.
- **Dukungan Mode Gelap (Dark Mode)**: Antarmuka modern yang sepenuhnya responsif dan mendukung mode gelap pada seluruh panel dashboard peran.

## 👥 Peran Pengguna

- **Admin Kota**: Mengelola instansi, pengguna, pengaturan sistem global, log audit aktivitas, laporan kota, serta manajemen pencadangan database.
- **Admin Instansi**: Mengelola lowongan/posisi magang, validasi pelamar, monitoring peserta aktif, menugaskan pembimbing lapangan, tanda tangan digital instansi, penerbitan sertifikat QR, dan rekapitulasi laporan dinas.
- **Pembimbing Lapangan**: Melakukan validasi absensi (hadir, izin, sakit) dan logbook harian peserta, serta mengisi penilaian kinerja akhir.
- **Pembimbing Akademik/Sekolah**: Memantau kemajuan, logbook harian, dan absensi peserta bimbingan asal sekolah/universitas terkait.
- **Peserta**: Mengajukan magang ke instansi, mengisi presensi harian dengan lokasi GPS, mencatat logbook kegiatan, mengisi umpan balik akhir, serta mengunduh transkrip nilai dan sertifikat hasil magang.

## 🛠️ Teknologi & Dependensi

- **Core & Backend**: PHP 8.3+ dan Laravel 13
- **Database & Cache**: MySQL/MariaDB dan Redis (Predis)
- **Frontend**: Vite, Tailwind CSS, Alpine.js, dan Leaflet.js (Peta)
- **Paket Tambahan Utama**:
  - `spatie/laravel-permission` (RBAC)
  - `laravel/socialite` (Google OAuth)
  - `barryvdh/laravel-dompdf` (Dokumen PDF)
  - `maatwebsite/excel` (Dokumen Excel)
  - `simplesoftwareio/simple-qrcode` (Kode QR Sertifikat)
  - `aerni/cloudflared` (Integrasi Cloudflare Tunnel)

## 🚀 Menjalankan Secara Lokal

1. **Salin Konfigurasi**:
   ```powershell
   Copy-Item .env.example .env
   ```
2. **Konfigurasi File `.env`**:
   Lengkapi setelan dasar database, pengirim email, dan beberapa kunci baru berikut:
   - `FILESYSTEM_DISK=local` (Mengaktifkan penyimpanan berkas lokal, di mana berkas privat disimpan di `storage/app/private`).
   - `QUEUE_CONNECTION=database` (Untuk menjalankan proses backup database dan email notifikasi secara asynchronous).
   - `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URL` (Kredensial API dari Google Cloud Console untuk fitur login Google).
3. **Instal Dependensi**:
   ```powershell
   composer install
   npm ci
   ```
4. **Buat Application Key**:
   ```powershell
   php artisan key:generate
   ```
5. **Jalankan Migrasi & Seed Awal**:
   ```powershell
   php artisan migrate --seed
   ```
6. **Buat Tautan Storage**:
   ```powershell
   php artisan storage:link
   ```
7. **Jalankan Aplikasi**:
   Mulai server web dan kompilasi aset frontend di terminal terpisah:
   ```powershell
   php artisan serve
   npm run dev
   ```

## ⏱️ Penjadwalan & Queue Worker

Untuk memproses pekerjaan latar belakang (seperti backup database dan pengiriman email), jalankan antrean worker:
```powershell
php artisan queue:work
```

Untuk menjamin tugas terjadwal berjalan otomatis di lokal, jalankan scheduler:
```powershell
php artisan schedule:work
```

Tugas-tugas terjadwal meliputi:
- `internship:complete-expired` (Harian) - Menyelesaikan magang peserta secara otomatis jika melewati tanggal selesai.
- `app:send-ending-notifications` (Harian pada pukul 08:00) - Mengirim email notifikasi pengingat H-7 berakhirnya masa magang.
- `backups:prune` (Per Jam) - Menghapus log cadangan database dan file fisik sql di disk private yang telah kedaluwarsa.

## 🛡️ Pemeriksaan Kualitas & CI/CD

Untuk melakukan verifikasi kualitas kode secara mandiri sebelum push:
```powershell
php artisan view:cache
php artisan route:list --except-vendor
php artisan test --compact
npm run build
```

Setiap push dan pull request ke cabang `main` akan divalidasi secara otomatis melalui alur kerja GitHub Actions `.github/workflows/quality.yml`.

## ⚡ Prosedur Operasional & Migrasi

Jika melakukan deployment pembaruan dari versi lama (upgrade fungsionalitas), jalankan serangkaian perintah migrasi data sekali saja:
```powershell
php artisan db:seed --class=Database\Seeders\RoleAndPermissionSeeder --force
php artisan magang:backfill-roles-master
php artisan documents:migrate-private --dry-run
php artisan documents:migrate-private
```

Perintah `documents:migrate-private` akan memindahkan dokumen lama (surat pengantar, bukti logbook, bukti absensi) dari public storage ke private storage. Lakukan review terhadap keluaran opsi `--dry-run` sebelum melakukan migrasi fisik.
```

- [ ] **Step 2: Run verification checks**

Run the quality check commands locally to make sure there are no errors:
Run: `php artisan test --compact`
Expected: PASS

- [ ] **Step 3: Commit updates**

```bash
git add README.md
git commit -m "docs: update README.md to reflect v2.0+ architecture"
```
