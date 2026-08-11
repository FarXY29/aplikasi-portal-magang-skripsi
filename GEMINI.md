# GEMINI.md

Portal Magang Pemerintah Kota Banjarmasin — Laravel 13 internship management portal. Bahasa Indonesia is the primary language for UI strings, comments, validation messages, and seeders. Maintain Indonesian language conventions across views and user-facing messages.

---

## 1. Stack & Specifications

- **PHP**: 8.3+ (CI runs 8.4; `composer.json` platform pinned to `8.4.99`).
- **Framework Architecture**: Laravel 13 with classic Kernel architecture (`app/Http/Kernel.php`, `app/Console/Kernel.php`, manual kernel binding in `bootstrap/app.php`). **Do not modernize to Laravel 11+ Application-based closures in `bootstrap/app.php`.**
- **Database & Cache**: MySQL/MariaDB + Redis (`predis/predis`).
- **Frontend Build**: Vite + Tailwind CSS / Vanilla CSS + Alpine.js + Leaflet.js.
- **Key Packages**:
  - `spatie/laravel-permission` (RBAC)
  - `barryvdh/laravel-dompdf` (PDF document streaming)
  - `simplesoftwareio/simple-qrcode` (QR code verification)
  - `aerni/cloudflared` (Cloudflare Tunnel integration)
  - `ifsnop/mysqldump-php` (Database backup engine)

---

## 2. Dev Environment Requirements

- `FILESYSTEM_DISK=local` — Private documents reside in `storage/app/private` and are auth-gated via `StorageAccessController`. Do not create public symlinks for private documents.
- `QUEUE_CONNECTION=database` — Async queue worker handles database backups and mail notifications.
- `CACHE_STORE=redis`, `SESSION_DRIVER=redis` in `.env`.
- Testing environment overrides cache/session to `array`/`sync` via `phpunit.xml`. Test database is `aplikasi_magang_skripsi_testing` on MySQL.

---

## 3. Local Development Commands

```powershell
composer install
npm ci
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve         # Terminal 1 (Web server)
npm run dev               # Terminal 2 (Vite dev server)
php artisan queue:work    # Terminal 3 (Queue worker)
php artisan schedule:work # Terminal 4 (Scheduler)
```

---

## 4. Quality Verification Pipeline

Before committing or pushing changes, execute the exact verification pipeline (mirrors `.github/workflows/quality.yml`):

```powershell
php artisan view:cache
php artisan route:list --except-vendor
php artisan test --compact
npm run build
```

> [!IMPORTANT]
> **Execution Order Matters**: `view:cache` and `route:list` surface Blade syntax and routing errors before test suite execution.

---

## 5. Testing Guidelines

- **Framework**: PHPUnit 12 (`tests/Unit`, `tests/Feature`).
- **Database**: Feature tests utilize the `DatabaseTransactions` trait for clean per-test rollbacks. Requires a real MySQL testing database (`aplikasi_magang_skripsi_testing`).
- **Single Test Execution**:
  ```powershell
  php artisan test --filter=TestName
  php artisan test tests/Feature/RolePesertaTest.php
  ```
- **Factories**: Only `UserFactory` exists. Other models (`Instansi`, `InternshipPosition`, etc.) must be constructed inline in seeders and feature tests.

---

## 6. Architecture & Subsystem Principles

### Dual Role & Permission System (Spatie + Legacy Fallback)
Portal defines 5 roles (`User::PORTAL_ROLES`): `admin_kota`, `admin_instansi`, `pembimbing_lapangan`, `pembimbing`, `peserta`.
- **Spatie RBAC** is primary. Legacy `users.role` column is kept as fallback.
- `User::hasPortalRole()` checks both Spatie roles and legacy columns.
- `admin_kota` automatically short-circuits to grant all permissions.
- Route protection uses `role:` middleware alias (`CheckRole`).
- Email verification is required only for `peserta` and `pembimbing` roles (`User::hasVerifiedEmail()`).

### Route Structure
Routes are partitioned by role within `routes/`:
- `admin_kota.php` → prefix `admin`, name `admin.*`
- `admin_instansi.php` → prefix `dinas`, name `dinas.*`
- `pembimbing.php` → namespaced groups `pembimbing_lapangan.*` and `pembimbing.*`
- `peserta.php` → prefix `peserta`, name `peserta.*`
- `public.php`, `auth.php`, `profile.php` — shared routes.
- `web.php` dispatches `/dashboard` based on role.

### Private Document Access
All confidential files (surat permohonan, logbooks, attendance proofs) stored in `storage/app/private` are served strictly via `StorageAccessController::serveFile($type, $filename)`. Route `storage.access` enforces Policy authorization (`$this->authorize('view', $model)`).

### Reporting Subsystem (PDF Only)
- Reports in Admin Kota and Admin Instansi export exclusively to **PDF**. Excel and CSV export options have been eliminated.
- PDF generation is handled via `App\Services\PdfExportService`.
- **Super Admin Reports (`admin_kota`)**: Signature sections automatically fetch `pejabat_name`, `pejabat_nip`, `pejabat_jabatan`, and `ttd_image` from the `Setting` model (`/admin/settings`).

### Services Layer Pattern
Complex business logic is isolated in `app/Services/`:
- `ApplicationLifecycleService`
- `AttendanceService`
- `AuditLogService`
- `CertificateService`
- `InternshipApplicationService`
- `PdfExportService`
- `ReportService`

Keep controllers thin by delegating business logic to service classes.

---

## 7. Console Commands & Scheduled Jobs

- `internship:complete-expired` (daily) — Auto-completes internships past their end date.
- `app:send-ending-notifications` (daily at 08:00) — Sends H-7 internship completion reminder emails.
- `backups:prune` (hourly) — Cleans up expired SQL backup files and audit logs.
- `magang:backfill-roles-master` — One-shot Spatie role backfill migration tool.
- `documents:migrate-private [--dry-run]` — One-shot migration tool for moving legacy public documents to private storage.

---

## 8. Development & Language Conventions

- All user-facing strings, Blade views, form labels, comments, and seeders must be written in **Bahasa Indonesia**.
- Preserve exact error message strings in controllers and tests to avoid breaking test assertions.
- Set `VIEW_COMPILED_PATH` in `.env` if fresh Blade compilation is required during PDF template updates.
