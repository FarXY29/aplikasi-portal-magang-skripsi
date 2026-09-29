# AGENTS.md

Shared guidance for AI coding agents (Claude Code, Gemini CLI, Codex, etc.) working in this repo.
`CLAUDE.md` and `GEMINI.md` import this file, so edit it here, not there.

## Project

**SIMAGANG** is an internship (magang/PKL) management system for the Banjarmasin City Government, built as a thesis (skripsi) project. It covers the full cycle across regional agencies (OPD/SKPD): vacancies -> applications -> attendance -> logbooks -> evaluation -> certificates.

- Stack: Laravel 13, PHP ^8.3, MySQL/MariaDB, Redis (optional locally), Blade + Alpine.js + Tailwind 3 via Vite, Leaflet, dompdf, maatwebsite/excel, simple-qrcode, spatie/laravel-permission.
- Language: UI text, validation messages (`lang/id`), and domain terms are Indonesian. Timezone is `Asia/Makassar`.
- Dev machine is Windows (PowerShell); production is Linux (nginx). Keep file and view paths case-correct and avoid hard-coded backslashes.

## Commands

```bash
php artisan serve            # app on http://localhost:8000
npm run dev                  # Vite dev server
php artisan queue:work       # emails + DB backups (needed to see queued mail)
php artisan schedule:work    # local cron
```

Tests need a **MySQL** database named `aplikasi_magang_skripsi_testing` (see `phpunit.xml`, `.env.testing`). SQLite is not supported.

```bash
php artisan test --compact
php artisan test --compact --filter=AttendanceDynamicQrTest
```

**Before saying a task is done, run what CI runs** (`.github/workflows/quality.yml`):

```bash
./vendor/bin/pint --test     # fix with ./vendor/bin/pint (Laravel preset, no pint.json)
php artisan view:cache       # required after any Blade change
php artisan route:list --except-vendor
php artisan test --compact
npm run build                # after any JS/CSS change
```

Domain commands: `internship:complete-expired`, `app:send-ending-notifications`, `backups:prune`, `documents:migrate-private [--dry-run]`, `magang:backfill-roles-master`, `positions:sync-categories`.

## Domain vocabulary

| Term | Meaning |
| :-- | :-- |
| OPD / SKPD / instansi | Government agency (`Instansi` model, table `instansis`) |
| lowongan / posisi | Vacancy (`InternshipPosition`) |
| peserta | Intern (student) |
| pembimbing_lapangan | Field mentor at the agency |
| pembimbing | **Academic** supervisor from the school/university (not the field mentor) |
| presensi | Attendance. `kiosk` = office screen that shows the rotating QR |
| logbook | Daily activity log (`DailyLog`) |
| sengketa | Attendance dispute (`AttendanceDispute`) |
| rumpun / jurusan | Field-of-study category / major (`MajorCategory`, `Major`) |

Statuses are backed enums in `app/Enums/`: `ApplicationStatus` (pending, menunggu, diterima, ditolak, selesai, dikeluarkan, dibatalkan) and `AttendanceStatus` (hadir, sakit, izin, alpa). Use enum cases in new code, not raw strings.

## Architecture rules

1. **Roles.** Spatie is the source of truth: `admin_kota`, `admin_instansi`, `pembimbing_lapangan`, `pembimbing`, `peserta`, plus public guests. The `role:` route middleware is the custom `CheckRole` (registered in `app/Http/Kernel.php`), which calls `User::hasPortalRole()`. The legacy `users.role` column is only a fallback during backfill; don't build on it.
2. **Tenancy is manual.** There is no global scope. For `admin_instansi` and `pembimbing_lapangan`, every query and endpoint must use both a policy (`app/Policies`) and an explicit `where('instansi_id', $user->instansi_id)` (through the position for applications). Add a cross-tenant test for each new endpoint. Quirk: `attendance_attempts` uses `instance_id`, not `instansi_id`.
3. **State machine.** All application status changes go through `ApplicationStateTransitionService` (quota via `lockForUpdate`, history in `application_timelines`, audit via `AuditLogService`). Never `update(['status' => ...])` directly.
4. **Attendance pipeline** (`app/Services/Attendance/`): GPS radius (`GeoDistanceService`), rotating QR every 30 s (`DynamicQrService::ROTATION_INTERVAL`), challenge nonce, locks and idempotency, fraud scoring, then a stored `AttendanceOperationalDecision`. Writes run in `DB::transaction` and rely on the unique `(application_id, date)` constraint. Never loosen fraud checks to make a test pass.
5. **Files.** Sensitive uploads (CV, cover letters, logbook and attendance evidence, dispute files, backups) live on the `private` disk (`storage/app/private`) and are served only through `StorageAccessController` (`/storage-access/{type}/{filename}`). Never put user uploads on the `public` disk.
6. **Async.** Mailables and Notifications implement `ShouldQueue` with `afterCommit`. Tests run with `QUEUE_CONNECTION=sync`.
7. **Scheduler** lives in `app/Console/Kernel.php`, not `routes/console.php`.
8. **Thin controllers.** Business logic goes in `app/Services` or `app/Actions`. Validate with Form Requests in `app/Http/Requests/<Area>/`; avoid new inline `$request->validate()`.
9. **Views.** No Eloquent queries in Blade; controllers and services pass all data. Reuse `resources/views/components/ui/` (`<x-ui.*>`). Sub-views inside a role folder use an underscore prefix (`_peserta-table.blade.php`). View folders match roles: `admin_kota`, `admin_instansi`, `pembimbing_lapangan`, `pembimbing`, `peserta`, `public`, `kiosk`, `pdf`, `shared`.
10. **Routes** are split per role in `routes/*.php`. Put new routes in the matching file with the right `auth` and `role:` middleware.
11. **User HTML.** Rich text must pass through `HtmlSanitizer::clean()` before storage or render. Avoid `{!! !!}` on anything user-supplied.

## Working rules

- Every behavior change gets a test in `tests/Feature/` (follow neighbouring tests). For access changes, test each role that should and should not get through.
- Never edit a committed migration; add a new one.
- Never commit `.env`, SQL dumps, or real personal data. Seeded demo accounts use the password `password`, which is local-only. Don't add new default credentials.
- Prefer npm packages bundled by Vite over CDN scripts. If you must change the CSP, edit `app/Http/Middleware/SecurityHeaders.php`.
- Follow the naming of the surrounding module: domain columns are Indonesian (`tanggal_selesai`), technical names are English.
- Don't hand-edit generated or tool-managed paths: `storage/`, `graphify-out/`, `app/EnvKit/` (local debug tool).
- Ask before adding dependencies, changing auth/RBAC, changing fraud thresholds, or doing destructive DB changes.
- Keep diffs small and focused; state assumptions when the request is ambiguous.

## Codebase navigation (optional)

If the `graphify` CLI is installed and `graphify-out/graph.json` exists, prefer it over broad grep or reading `GRAPH_REPORT.md`:

- `graphify query "<question>"` for codebase questions
- `graphify path "<A>" "<B>"` for relationships
- `graphify explain "<concept>"` for a focused concept
- `graphify update .` after code changes (AST-only, no API cost)