# AGENTS.md

Portal Magang Pemerintah Kota Banjarmasin — Laravel internship management portal. Bahasa Indonesia primary; UI strings, comments, and seeders are Indonesian. Keep that when editing views/messages.

## Stack & versions
- PHP 8.3+ (CI runs 8.4; `composer.json` platform pinned `8.4.99`). Laravel 13 declared, **but app uses classic Kernel architecture** (`app/Http/Kernel.php`, `app/Console/Kernel.php`, `bootstrap/app.php` binds kernels manually) — NOT Laravel 11+ Application-based bootstrap. Do not "modernize" to `bootstrap/app.php` closures; it will break.
- MySQL/MariaDB + Redis (Predis). Vite + Tailwind + Alpine.js + Leaflet.js.
- Key packages: `spatie/laravel-permission` (RBAC), `barryvdh/laravel-dompdf`, `maatwebsite/excel`, `simplesoftwareio/simple-qrcode`, `aerni/cloudflared`, `ifsnop/mysqldump-php` (DB backups).

## Required env for dev
- `FILESYSTEM_DISK=local` — private docs live in `storage/app/private`, served via `StorageAccessController` (auth-gated, never symlink public).
- `QUEUE_CONNECTION=database` — backups + mail run async; without a queue worker these stall.
- `CACHE_STORE=redis`, `SESSION_DRIVER=redis` in `.env.example` (Redis expected locally).
- Tests override to `array`/`sync` via `phpunit.xml`; test DB is `aplikasi_magang_skripsi_testing` on MySQL. CI uses `portal_magang_testing`.

## Dev commands
```powershell
composer install
npm ci
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve        # terminal 1
npm run dev              # terminal 2 (vite --host, exposes LAN IP for mobile)
php artisan queue:work   # terminal 3 (backups, mail)
php artisan schedule:work # terminal 4 (scheduler)
```

## Quality verification (run before push — mirrors `.github/workflows/quality.yml`)
```powershell
php artisan view:cache
php artisan route:list --except-vendor
php artisan test --compact
npm run build
```
Order matters: `view:cache` + `route:list` surface wiring errors before tests. No Pint config file exists; `laravel/pint` is dev-only, run ad-hoc via `vendor/bin/pint` if needed.

## Testing
- PHPUnit (PHPUnit 12). Two suites: `tests/Unit`, `tests/Feature`. Feature tests use `DatabaseTransactions` trait — full refresh per test.
- Run single test: `php artisan test --filter=TestName` or `php artisan test tests/Feature/RolePesertaTest.php`.
- Role tests (`Role*Test.php`) gate each of the 5 portal roles; consult them before changing role middleware or permissions.
- Factories: only `UserFactory` exists. Other models are built inline in tests/seeders — do not assume `Model::factory()` exists for Instansi/Position/etc.
- MySQL required for tests (not SQLite). Ensure testing DB exists.

## Architecture notes
### Roles (dual system — important)
Five portal roles in `User::PORTAL_ROLES`: `admin_kota`, `admin_instansi`, `pembimbing_lapangan`, `pembimbing`, `peserta`.
- **Spatie RBAC is primary**; legacy `users.role` column kept as fallback during backfill migration.
- `User::hasPortalRole()` checks both. `User::hasPortalPermission()` falls back to hardcoded `LEGACY_PERMISSIONS` only when user has no Spatie roles yet.
- `admin_kota` short-circuits to all permissions granted.
- Route guard: `role:` middleware alias (`CheckRole`) — uses `hasPortalRole()`, redirects to login if guest, 403 otherwise.
- Email verification: only `peserta` + `pembimbing` must verify; other roles treated verified automatically (`User::hasVerifiedEmail()`).

### Route layout
Routes split by role in `routes/`:
- `admin_kota.php` → prefix `admin`, name `admin.*`
- `admin_instansi.php` → prefix `dinas`, name `dinas.*`
- `pembimbing.php` → two groups: `pembimbing_lapangan.*` and `pembimbing.*`
- `peserta.php` → prefix `peserta`, name `peserta.*`
- `public.php`, `auth.php`, `profile.php` — shared.
- `web.php` dispatches `/dashboard` by role. `StorageAccessController` route `storage.access` serves private files.
- Throttle defined `availability-check` used by position-availability check.

### Private document access
`StorageAccessController::serveFile($type, $filename)` resolves `surat`/`logbook`/`attendance` to owning model, calls `$this->authorize('view', $model)`, falls back to public disk if file not in private. Documents never served by raw path. Use this pattern for new document types.

### Console commands (custom)
- `internship:complete-expired` (daily) — auto-finish internships past end date. Also triggered inline by `UpdateExpiredInternships` middleware (cached 1h) on every web request.
- `app:send-ending-notifications` (daily 08:00) — H-7 ending email.
- `backups:prune` (hourly) — prune expired backup logs + SQL files.
- `magang:backfill-roles-master` — one-shot backfill of Spatie roles from legacy column.
- `documents:migrate-private [--dry-run]` — one-shot migrate legacy public docs to private disk. Always run `--dry-run` first.

### Scheduled jobs
Defined in `app/Console/Kernel.php::schedule()`. Locally requires `php artisan schedule:work`; production uses cron.

### Services layer
`app/Services/` holds business logic: `ApplicationLifecycleService`, `AttendanceService`, `AuditLogService`, `CertificateService`, `InternshipApplicationService`, `PdfExportService`, `ReportService`. Controllers stay thin — delegate here.

### EnvKit debug (`app/EnvKit/`)
EnvKit-managed debug collector (`Client.php`, `EnvKitDebugServiceProvider`), registered in `config/app.php`. Inert unless `ENVKIT_DEBUG_INGEST` env set + state file enables it. **Self-contained — do not add dependencies or edit casually; EnvKit overwrites this dir.**

## Migration / upgrade procedure
When upgrading from legacy versions, run once (order matters):
```powershell
php artisan db:seed --class=Database\Seeders\RoleAndPermissionSeeder --force
php artisan magang:backfill-roles-master
php artisan documents:migrate-private --dry-run
php artisan documents:migrate-private
```

## Conventions
- Comments and user-facing strings in Indonesian; keep verbatim error strings when referenced in tests.
- `DatabaseSeeder` generates realistic Banjarmasin city data (15 instansi, 60 peserta, pembimbing). Use for local dev only; `MassDummySeeder` / `PenilaianDummySeeder` for additional fixtures.
- `VIEW_COMPILED_PATH` env (empty by default) — set to fresh dir when PDF templates change to avoid opcode cache serving stale PDFs.
