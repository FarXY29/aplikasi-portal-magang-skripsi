# Prompt

Anda adalah seorang **Senior Business Analyst, System Analyst, UI/UX Architect, dan Software Solution Architect** yang berpengalaman dalam melakukan **analisis, evaluasi, dan pengembangan lanjutan (application enhancement)** untuk aplikasi e-Government di Indonesia.

## Latar Belakang

Pemerintah Kota Banjarmasin **telah memiliki aplikasi Portal Magang** yang saat ini sudah digunakan. Namun, aplikasi tersebut memerlukan pembaruan agar memiliki fitur yang lebih lengkap, proses bisnis yang lebih baik, tampilan yang lebih modern, serta mampu memenuhi kebutuhan pengguna saat ini.

Tugas Anda **bukan merancang aplikasi baru dari awal**, tetapi **menyusun rencana pengembangan (enhancement plan)** terhadap aplikasi yang sudah ada dengan mempertahankan tujuan utama sistem, sekaligus memberikan rekomendasi perbaikan berdasarkan praktik terbaik (best practices).

Aplikasi yang diperbarui harus tetap memiliki:

* Landing Page yang dapat diakses tanpa login.
* Dashboard untuk setiap role.
* Fitur manajemen proses magang yang lebih lengkap.
* Minimal 8 laporan (report) yang tersebar di seluruh aplikasi.

Role yang digunakan tetap terdiri dari:

1. Super Admin
2. Admin Instansi
3. Pembimbing Lapangan
4. Pembimbing Akademik
5. Peserta Magang

---

# Tujuan

Lakukan analisis terhadap aplikasi yang telah ada, kemudian susun **dokumen rencana pembaruan aplikasi (Application Enhancement Plan)** yang berisi evaluasi, usulan pengembangan, penyempurnaan fitur, serta rekomendasi implementasi tanpa menghilangkan fungsi utama aplikasi.

---

# Output yang Harus Dihasilkan

## 1. Analisis Aplikasi Eksisting

Jelaskan asumsi mengenai kondisi aplikasi saat ini, meliputi:

* Fungsi utama yang sudah tersedia.
* Kelebihan aplikasi saat ini.
* Kekurangan aplikasi.
* Kendala yang sering terjadi.
* Peluang peningkatan sistem.

Apabila informasi aplikasi eksisting belum tersedia, buat asumsi yang realistis berdasarkan karakteristik portal magang pemerintah.

---

## 2. Analisis GAP (Gap Analysis)

Buat tabel yang membandingkan:

* Kondisi saat ini.
* Permasalahan.
* Dampak.
* Solusi yang diusulkan.
* Prioritas pengembangan (Tinggi/Sedang/Rendah).

---

## 3. Rekomendasi Penyempurnaan Landing Page

Evaluasi landing page yang ada, kemudian berikan usulan peningkatan seperti:

* Struktur menu.
* Tampilan UI/UX.
* Responsivitas.
* Optimasi aksesibilitas.
* SEO.
* Informasi publik.
* Berita dan pengumuman.
* FAQ.
* Kontak.
* Daftar instansi.
* Alur magang.
* Halaman login.

Jelaskan manfaat dari setiap perubahan.

---

## 4. Evaluasi dan Penyempurnaan Dashboard

Untuk masing-masing role (Super Admin, Admin Instansi, Pembimbing Lapangan, Pembimbing Akademik, dan Peserta Magang), jelaskan:

* Fitur dashboard yang sebaiknya dipertahankan.
* Fitur yang perlu diperbaiki.
* Widget baru yang direkomendasikan.
* Informasi statistik yang perlu ditampilkan.
* Notifikasi yang perlu ditambahkan.
* KPI yang dapat ditampilkan pada dashboard.

---

## 5. Rekomendasi Pengembangan Modul

Evaluasi modul yang ada, kemudian berikan rekomendasi pengembangan pada modul seperti:

* Manajemen User
* Role & Permission
* Instansi
* Universitas
* Sekolah
* Peserta
* Pengajuan Magang
* Seleksi
* Approval
* Penempatan
* Jadwal
* Logbook
* Kehadiran
* Monitoring
* Penilaian
* Surat
* Sertifikat
* Pengumuman
* Notifikasi
* Audit Log
* Pengaturan Sistem

Untuk setiap modul jelaskan:

* Kondisi saat ini (asumsi bila tidak diketahui).
* Kekurangan.
* Rekomendasi peningkatan.
* Manfaat.
* Prioritas implementasi.

---

## 6. Penyempurnaan Proses Bisnis

Evaluasi alur bisnis yang ada dan usulkan workflow yang lebih efisien mulai dari:

Pendaftaran
↓
Pengajuan
↓
Verifikasi
↓
Persetujuan
↓
Penempatan
↓
Monitoring
↓
Logbook
↓
Penilaian
↓
Sertifikat

Gunakan diagram ASCII dan jelaskan perubahan dibanding proses sebelumnya.

---

## 7. Evaluasi Struktur Database

Berikan rekomendasi perubahan struktur database apabila diperlukan, termasuk:

* Penambahan tabel.
* Penambahan field.
* Optimasi relasi.
* Normalisasi data.
* Strategi migrasi agar data lama tetap aman.

---

## 8. Evaluasi Diagram Sistem

Buat versi usulan:

* ERD (ASCII).
* Use Case Diagram (PlantUML).
* Activity Diagram (PlantUML).
* Sequence Diagram (PlantUML).

Tunjukkan bagian yang mengalami perubahan dibandingkan sistem sebelumnya.

---

## 9. Penyempurnaan Laporan

Rancang minimal 8 laporan yang lebih informatif, seperti:

1. Laporan Pengajuan Magang
2. Laporan Peserta Aktif
3. Laporan Penempatan Peserta
4. Laporan Kehadiran
5. Laporan Logbook
6. Laporan Monitoring Pembimbing
7. Laporan Penilaian Akhir
8. Laporan Statistik Magang Tahunan

Untuk setiap laporan jelaskan:

* Tujuan.
* Pengguna.
* Sumber data.
* Filter.
* Format ekspor (PDF, Excel, CSV).
* Dashboard yang mengakses laporan.

---

## 10. Penyempurnaan Notifikasi

Jelaskan notifikasi baru yang direkomendasikan untuk setiap role, termasuk notifikasi melalui email dan WhatsApp apabila memungkinkan.

---

## 11. Rekomendasi Keamanan

Evaluasi keamanan aplikasi dan usulkan peningkatan seperti:

* RBAC.
* Multi-Factor Authentication (opsional).
* Audit Log.
* Validasi Input.
* Backup.
* Logging.
* Upload file aman.
* Session Management.
* Keamanan API.

---

## 12. Roadmap Pengembangan

Buat roadmap implementasi pembaruan dalam beberapa fase:

* Analisis aplikasi eksisting.
* Identifikasi kebutuhan pengguna.
* Perancangan UI/UX baru.
* Pengembangan fitur baru.
* Integrasi dengan sistem lama.
* Migrasi data.
* Pengujian.
* Deployment.
* Pelatihan pengguna.
* Maintenance.

---

## 13. Risiko dan Mitigasi

Identifikasi risiko selama proses pembaruan aplikasi, seperti:

* Kehilangan data.
* Gangguan layanan.
* Ketidaksesuaian kebutuhan pengguna.
* Kendala migrasi.
* Perubahan proses bisnis.

Sertakan strategi mitigasi untuk setiap risiko.

---

## 14. Rekomendasi Fitur Baru

Berikan rekomendasi fitur yang dapat meningkatkan kualitas aplikasi, misalnya:

* Dashboard analitik.
* Single Sign-On (SSO).
* Tanda tangan elektronik.
* Integrasi API dengan sistem pemerintah.
* Notifikasi email dan WhatsApp.
* Kalender kegiatan.
* Riwayat aktivitas pengguna.
* Manajemen dokumen digital.
* Fitur pencarian lanjutan.
* Monitoring KPI magang.
* Sistem umpan balik (feedback) peserta dan pembimbing.

---

## Ketentuan Output

* Gunakan bahasa Indonesia yang formal.
* Sajikan dalam format Markdown dengan heading yang jelas.
* Gunakan tabel untuk analisis, gap analysis, hak akses, modul, database, roadmap, dan laporan.
* Sertakan diagram dalam format PlantUML dan ASCII.
* Fokus pada peningkatan aplikasi yang sudah ada, bukan pembangunan aplikasi baru.
* Setiap rekomendasi harus disertai alasan, manfaat, dampak terhadap pengguna, serta prioritas implementasinya.
