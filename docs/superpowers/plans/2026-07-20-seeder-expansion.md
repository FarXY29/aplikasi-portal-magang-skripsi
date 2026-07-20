# Seeder Expansion Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Expand the database seeder to generate 30 instansi, ~60-90 field supervisors, 30 academic supervisors, and 250 participants for scale testing.

**Architecture:** Use Eloquent loops in `DatabaseSeeder.php` to generate related structures in database while maintaining role assignments via Spatie roles.

**Tech Stack:** Laravel, Eloquent, Spatie Permission, Faker.

## Global Constraints
- Do not bypass model events or role constraints.
- Seeded passwords must be hashed using `Hash::make('password')`.

---

### Task 1: Expand Instansi, Pembimbing, and Peserta Counts in DatabaseSeeder

**Files:**
- Modify: `database/seeders/DatabaseSeeder.php:40-190`

**Interfaces:**
- Consumes: Models `Instansi`, `User`, `InternshipPosition`, `Application`
- Produces: Expanded dummy data matching target scale

- [ ] **Step 1: Write expanded arrays and loop limits in DatabaseSeeder**

In [DatabaseSeeder.php](file:///c:/EnvKit/aplikasi-magang-skripsi%20-%20Copy/database/seeders/DatabaseSeeder.php), modify the dinasList to have 30 entries, increase pembimbing sekolah count to 30, and increase peserta count to 250.

```php
        // List Nama Dinas Realistis
        $dinasList = [
            'Dinas Komunikasi, Informatika dan Statistik',
            'Dinas Pendidikan',
            'Dinas Kesehatan',
            'Dinas Pekerjaan Umum dan Penataan Ruang',
            'Dinas Perumahan Rakyat dan Kawasan Permukiman',
            'Dinas Sosial',
            'Dinas Kependudukan dan Pencatatan Sipil',
            'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu',
            'Dinas Koperasi, Usaha Mikro dan Tenaga Kerja',
            'Dinas Perdagangan dan Perindustrian',
            'Dinas Perhubungan',
            'Dinas Kebudayaan, Kepemudaan, Olahraga dan Pariwisata',
            'Badan Perencanaan Pembangunan Daerah, Penelitian dan Pengembangan',
            'Badan Keuangan Daerah',
            'Badan Kepegawaian Daerah, Pendidikan dan Pelatihan',
            'Dinas Ketahanan Pangan, Pertanian dan Perikanan',
            'Dinas Lingkungan Hidup',
            'Dinas Perpustakaan dan Kearsipan',
            'Dinas Pemadam Kebakaran dan Penyelamatan',
            'Dinas Pemberdayaan Perempuan dan Perlindungan Anak',
            'Dinas Pengendalian Penduduk dan Keluarga Berencana',
            'Satuan Polisi Pamong Praja',
            'Badan Penanggulangan Bencana Daerah',
            'Badan Kesatuan Bangsa dan Politik',
            'Rumah Sakit Umum Daerah Sultan Suriansyah',
            'Inspektorat Kota Banjarmasin',
            'Badan Pengelola Keuangan, Pendapatan dan Aset Daerah',
            'Sekretariat Daerah Kota Banjarmasin',
            'Sekretariat DPRD Kota Banjarmasin',
            'Dinas Kearsipan dan Perpustakaan Daerah'
        ];
```

Increase Pembimbing Akademik / Sekolah loop to `30`:
```php
        // Buat Pembimbing Akademik / Sekolah (30 orang)
        $pembimbingSekolahList = [];
        for ($k = 1; $k <= 30; $k++) {
```

Increase Peserta loop to `250`:
```php
        // Buat 250 Peserta
        for ($i = 1; $i <= 250; $i++) {
```

- [ ] **Step 2: Run db:wipe and db:seed to verify seeder success**

Run: `php artisan db:wipe && php artisan migrate && php artisan db:seed`
Expected: Seeding completes successfully.

- [ ] **Step 3: Commit**

Run:
```bash
git add database/seeders/DatabaseSeeder.php
git commit -m "feat: expand seeder to generate 30 instansi, 30 academic supervisors, and 250 candidates"
```
