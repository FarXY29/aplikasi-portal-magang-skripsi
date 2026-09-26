# CLAUDE.md

This file provides guidance to Tau when working with code in this repository.

## Development & Operational Commands

### Environment & Server
- **PHP CLI Server**: `php artisan serve` (default: `http://localhost:8000`)
- **Vite Dev Server**: `npm run dev` (runs `vite --host`)
- **Vite Build**: `npm run build`
- **Queue Worker**: `php artisan queue:work` (processes email notifications and database backups)
- **Task Scheduler**: `php artisan schedule:work` (local cron runner)

### Testing & Verification
- **Run all tests**: `php artisan test` (or `php artisan test --compact`)
- **Run a single test file/class**: `php artisan test tests/Feature/ExampleTest.php`
- **Run a test by method/class filter**: `php artisan test --filter=CertificateGovernanceTest`
- **Common test suites**:
  - `php artisan test --filter=SecureStorageAccessTest`
  - `php artisan test --filter=AllPdfReportsRenderingTest`
  - `php artisan test --filter=AttendanceDynamicQrTest`
  - `php artisan test --filter=QueuedNotificationReliabilityTest`
  - `php artisan test --filter=FullSystemRoleAndPageVerificationTest`
- **Blade view compilation check**: `php artisan view:cache`
- **Route registration check**: `php artisan route:list --except-vendor`

### Linting & Code Style
- **Check code style (Pint dry-run)**: `./vendor/bin/pint --test`
- **Auto-fix code style**: `./vendor/bin/pint`

### Artisan Domain Commands
- `php artisan internship:complete-expired` - Mark expired internships as completed (scheduled daily at 00:00)
- `php artisan app:send-ending-notifications` - Send H-7 completion notice emails (scheduled daily at 08:00)
- `php artisan backups:prune` - Delete expired database backup files and entries (scheduled hourly)
- `php artisan documents:migrate-private [--dry-run]` - Migrate legacy documents to private storage
- `php artisan magang:backfill-roles-master` - Synchronize Spatie RBAC roles and master schools/universities

### Knowledge Graph (`graphify`)
- Query codebase: `graphify query "<question>"` (reads `graphify-out/graph.json`)
- Relationship check: `graphify path "<A>" "<B>"`
- Concept explanation: `graphify explain "<concept>"`
- Update graph after code modifications: `graphify update .` (AST-only)

---

## High-Level Architecture & Domain Model

### 1. Role-Based Access Control & Multi-Tenancy
The system implements five primary portal roles via `spatie/laravel-permission`:
- **`admin_kota` (Super Admin)**: Manages municipal-wide settings, OPD instansi master records, major categories (`majors`, `major_categories`), user accounts, official certificate registry/revocation (`CertificateController`), audit trails (`audit_logs`), and executive PDF report generation.
- **`admin_instansi` (OPD / Dinas Admin)**: Tenant-isolated by `instansi_id`. Manages vacancy positions (`internship_positions`), reviews/accepts applicants, assigns field mentors (`pembimbing_lapangan`), and monitors attendance fraud telemetry.
- **`pembimbing_lapangan` (Field Mentor)**: Scoped to an instansi. Handles daily attendance validations, single/batch logbook approvals (`daily_logs`), and final rubric performance grading (technical, discipline, conduct).
- **`pembimbing` (School / University Mentor)**: Academic supervisor tracking student logbooks, attendance, and evaluation status for their registered institution.
- **`peserta` (Intern Participant)**: Vacancy browsing with qualification matching, GPS-radius dynamic QR attendance, daily logbook entry with attachments, gated exit feedback evaluation, and PDF document generation (LoA, ID Card with QR, transcript, certificate).
- **Guest / Public**: Vacancy catalog, registration token tracking, certificate authenticity verification, and attendance kiosk screen (`/kiosk/presensi/{kiosk_token}`).

> **Tenancy Rule**: When querying or updating resources for `admin_instansi` or `pembimbing_lapangan`, always enforce the tenant scope (`where('instansi_id', $user->instansi_id)`).

### 2. Presensi & Anti-Fraud Verification Pipeline
Attendance clock-in/out involves a multi-layer verification pipeline:
- **Geolocation & Radius**: Coordinates are verified against the OPD office location via `GeoDistanceService` (Leaflet on frontend).
- **Dynamic Rotating QR**: Kiosk displays rotate QR codes every 30 seconds with a 1-step grace window via `DynamicQrService`.
- **Anti-Replay & Idempotency**: Protected with challenge nonces (`AttendanceChallengeService`) and atomic locks (`AttendanceLockService`, `AttendanceIdempotencyService`).
- **Fraud Telemetry & Scoring**: `AttendanceFraudDetector` and `AttendanceRiskScorer` evaluate signals (GPS spoofing, signature tampering, rapid duplicate attempts) and persist an `AttendanceOperationalDecision` via `AttendanceAttemptService`.
- **Database Integrity**: Attendance submissions execute inside `DB::transaction(...)` with database unique constraint on `(application_id, date)`.

### 3. Storage Security & File Isolation
- **Private Disk Isolation**: Sensitive documents (CV, institutional cover letters, logbook attachments, attendance selfie evidence, compressed SQL backups) reside exclusively on the `private` disk under `storage/app/private/`.
- **Gated Access**: Direct web access to private storage is prohibited. All downloads and previews pass through `StorageAccessController` (`/storage-access/{type}/{filename}`) which checks authentication and model authorization policies.

### 4. Application Lifecycle & State Machine
- **Atomic Transitions**: Managed by `ApplicationStateTransitionService` (`app/Services/ApplicationStateTransitionService.php`). Vacancy quotas use pessimistic database locking (`lockForUpdate`) during acceptance.
- **Audit & Timelines**: State changes record immutable history entries in `application_timelines` and system audit entries in `audit_logs`.
- **Asynchronous Notifications**: Emails and in-app notifications implement `ShouldQueue` with `$afterCommit = true` to avoid dispatching before database transactions commit.

### 5. View Conventions & Frontend Structure
- **Route & Controller Mapping**: Routes are partitioned by role under `routes/` (`admin_kota.php`, `admin_instansi.php`, `peserta.php`, `pembimbing.php`, `public.php`, `auth.php`).
- **Canonical Components**: Reusable UI components live in `resources/views/components/ui/` (`<x-ui.stat-card>`, `<x-ui.badge>`, `<x-ui.empty-state>`, `<x-ui.page-header>`, `<x-ui.pill>`, `<x-ui.alert>`, etc.).
- **Partials Convention**: Extracted view partials within role folders use an underscore prefix (e.g., `resources/views/admin_kota/laporan/partials/_peserta-table.blade.php`).
- **Zero In-View Queries**: Blade templates must not query Eloquent models directly (`::where`, `::count`, `::first`); controllers and services must pass all required data and aggregates to views.
