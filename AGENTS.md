# AGENTS.md

Portal Magang Pemerintah Kota Banjarmasin — Laravel internship management portal (vacancies, applications, GPS attendance, logbooks, grading, QR certificates, backups). Bahasa Indonesia primary; UI strings, comments, validation messages, and seeders are Indonesian. Keep that when editing views/messages.

## Project
- PHP 8.3+ (CI runs 8.4; `composer.json` platform pinned `8.4.99`). Laravel 13 declared, **but app uses classic Kernel architecture** (`app/Http/Kernel.php`, `app/Console/Kernel.php`, `bootstrap/app.php` binds kernels manually) — NOT Laravel 11+ Application-based bootstrap. Do not "modernize" to `bootstrap/app.php` closures; it will break.
- MySQL/MariaDB + Redis (Predis). Vite + Tailwind + Alpine.js + Leaflet.js. `maatwebsite/excel` is in composer.json but **unused** — reports are PDF-only (`PdfExportService`, dompdf).
- Key packages: `spatie/laravel-permission` (RBAC), `barryvdh/laravel-dompdf`, `simplesoftwareio/simple-qrcode`, `aerni/cloudflared`, `ifsnop/mysqldump-php` (DB backups).
- Entry points: `public/index.php`; web routes in `routes/*.php` loaded by `app/Providers/RouteServiceProvider`.

## Commands
- Setup (PowerShell): `composer install`, `npm ci`, `php artisan key:generate`, `php artisan migrate --seed`, `php artisan storage:link`.
- Run: `php artisan serve` (web) + `npm run dev` (vite) + `php artisan queue:work` (backups/mail) + `php artisan schedule:work` (scheduler).
- Verify before push (mirrors `.github/workflows/quality.yml`, ORDER MATTERS):
  ```powershell
  php artisan view:cache
  php artisan route:list --except-vendor
  php artisan test --compact
  npm run build
  ```
- Single test: `php artisan test --filter=TestName` or `php artisan test tests/Feature/RolePesertaTest.php`.
- Lint: no Pint config; `laravel/pint` dev-only, run ad-hoc `vendor/bin/pint`.
- One-shot migrations (order matters): `php artisan db:seed --class=Database\Seeders\RoleAndPermissionSeeder --force`, `php artisan magang:backfill-roles-master`, `php artisan documents:migrate-private --dry-run` then `documents:migrate-private`.

## Environment (required for dev)
- `FILESYSTEM_DISK=local` — private docs live in `storage/app/private`, served via `StorageAccessController` (auth-gated, never symlink public).
- `QUEUE_CONNECTION=database` — backups + mail run async; without a queue worker these stall.
- `CACHE_STORE=redis`, `SESSION_DRIVER=redis` in `.env.example` (Redis expected locally).
- Tests override to `array`/`sync` via `phpunit.xml`; test DB is `aplikasi_magang_skripsi_testing` on MySQL. CI uses `portal_magang_testing`. MySQL required (not SQLite).

## Architecture
### Roles (dual system — important)
Five portal roles in `User::PORTAL_ROLES`: `admin_kota`, `admin_instansi`, `pembimbing_lapangan`, `pembimbing`, `peserta`.
- **Spatie RBAC is primary**; legacy `users.role` column kept as fallback during backfill migration.
- `User::hasPortalRole()` checks both. `User::hasPortalPermission()` falls back to hardcoded `LEGACY_PERMISSIONS` only when user has no Spatie roles yet.
- `admin_kota` short-circuits to all permissions granted.
- Route guard: `role:` middleware alias (`CheckRole`) — uses `hasPortalRole()`, redirects to login if guest, 403 otherwise.
- Email verification: only `peserta` + `pembimbing` must verify; other roles treated verified automatically (`User::hasVerifiedEmail()`).
- Model policies (`app/Policies/`) add a second auth layer; `admin_kota` `before()` short-circuits in all of them.

### Route layout
Routes split by role in `routes/`:
- `admin_kota.php` → prefix `admin`, name `admin.*`
- `admin_instansi.php` → prefix `dinas`, name `dinas.*`
- `pembimbing.php` → two groups: `pembimbing_lapangan.*` and `pembimbing.*`
- `peserta.php` → prefix `peserta`, name `peserta.*`
- `public.php`, `auth.php`, `profile.php` — shared.
- `web.php` dispatches `/dashboard` by role. `StorageAccessController` route `storage.access` serves private files.
- Throttle aliases defined in `RouteServiceProvider`: `api`, `auth-sensitive`, `public-search`, `availability-check`.

### Domain model
`Application` is the hub: `Instansi 1—* InternshipPosition 1—* Application 1—* DailyLog` and `1—* Attendance`, `1—0..1 Certificate`. `User` links to `Instansi`, `University`/`School`, and self-referential `pembimbing_sekolah_id` (academic mentor). Application status enum: `pending → menunggu → diterima → selesai` / `dikeluarkan` / `dibatalkan` / `ditolak`. Deleting a model cascades via model boot events (User/Instansi/Position/Application).

### Services layer
`app/Services/` holds business logic — controllers stay thin and delegate here: `AdminDashboardService` (superadmin dashboard stats + trends), `ApplicationLifecycleService` (auto-finish, waiting-list promotion, transactions), `AttendanceService` (time window + Haversine GPS radius + history filtering), `AuditLogService`, `CertificateService` (QR + token + unique number), `InternshipApplicationService` (quota via `lockForUpdate`, accept/reject/cancel + mails), `PdfExportService` (stream/download + optional signature injection), `PembimbingLogbookService` (logbook filtering + dropdown data), `ReportService` (SQL-side aggregation for reports). Small single-purpose actions live in `app/Actions/` (`GenerateCertificateNumberAction`).

### Private document access
`StorageAccessController::serveFile($type, $filename)` resolves `surat`/`logbook`/`attendance` to owning model, calls `$this->authorize('view', $model)`, falls back to public disk if file not in private. Documents never served by raw path. Use this pattern for new document types.

### Console commands (custom)
- `internship:complete-expired` (daily) — auto-finish internships past end date. Runs via scheduler only (legacy `UpdateExpiredInternships` web middleware removed for performance; class still exists).
- `app:send-ending-notifications` (daily 08:00) — H-7 ending email.
- `backups:prune` (hourly) — prune expired backup logs + SQL files.
- `magang:backfill-roles-master` — one-shot backfill of Spatie roles + master-data mapping.
- `documents:migrate-private [--dry-run]` — one-shot migrate legacy public docs to private disk. Always run `--dry-run` first.
- Scheduled in `app/Console/Kernel.php::schedule()`. Locally requires `php artisan schedule:work`; production uses cron.

### Jobs & Mail
`app/Jobs/CreateDatabaseBackup` (queue `maintenance`, mysqldump to private disk, writes AuditLog). Mails in use: `ApplicationAcceptedMail` (`emails.applications.accepted`), `ApplicationRejectedMail` (`emails.applications.rejected`), `InternshipEndingMail` (`emails.internship.ending`), and `InternshipCompleted` (`emails.internship_completed`, sent from `AdminInstansi\ActiveInternController::finishIntern`).

### EnvKit debug (`app/EnvKit/`)
EnvKit-managed debug collector (`Client.php`, `EnvKitDebugServiceProvider`), registered in `config/app.php`. Inert unless `ENVKIT_DEBUG_INGEST` env set + state file enables it. **Self-contained — do not add dependencies or edit casually; EnvKit overwrites this dir.**

## Conventions
- User-facing strings, comments, Blade, seeders in Indonesian; keep verbatim error strings when referenced in tests.
- `DatabaseSeeder` generates realistic Banjarmasin data (15 instansi, 60 peserta, pembimbing). Use for local dev only; `MassDummySeeder` / `PenilaianDummySeeder` for extra fixtures.
- Factories: only `UserFactory` exists. Other models are built inline in tests/seeders — do not assume `Model::factory()` for Instansi/Position/etc.
- Role tests (`Role*Test.php`) gate each of the 5 portal roles; consult before changing role middleware or permissions. Security tests in `tests/Feature/Security/`.
- `docs/plan.md` is a full audit remediation plan (sanitizer, attendance uniqueness, cert sequencing, status cleanup). As of now the sanitizer/`wawancara` migration is NOT yet implemented in `app/` — check before assuming it landed.
- `GEMINI.md` mirrors `AGENTS.md` (older copy) — update both when changing architecture facts.
- `VIEW_COMPILED_PATH` env (empty by default) — set to fresh dir when PDF templates change to avoid opcode cache serving stale PDFs.

## Notes
- **Auth enumeration hardening (2026)**: Login (`LoginRequest`) and guest verification-resend (`EmailVerificationNotificationController@storeGuest`) intentionally return the same generic response for unknown/unverified/format-invalid emails to avoid acting as a user-enumeration oracle. Do NOT reintroduce distinct error messages that reveal whether an email exists — keep any new auth message constant across states.
- **V2 documented scope**: Changes accepted during V2 release beyond the original spec — treat as intentional:
  - PDF-only reports (Excel/CSV exports removed; see §7).
  - Waiting-list opt-in (`is_waiting_list` flag on applications).
  - Signature images for PDF (`ttd_kepala`, `ttd_image` on instansi/users).
  - Auth views UI redesign (ambient orbs, Outfit/Plus Jakarta Sans fonts).
  - Public lowongan UI overhaul (gradient badges, initials avatar).
(Add quick notes here as they come up.)
