# Graph Report - aplikasi-magang - Copy  (2026-08-29)

## Corpus Check
- Large corpus: 795 files · ~476,263 words. Semantic extraction will be expensive (many Claude tokens). Consider running on a subfolder.

## Summary
- 2329 nodes · 3959 edges · 646 communities (581 shown, 65 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 18 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- User Identity & Profile Management
- Internship Vacancy & Quota Management
- Authentication & Testing Infrastructure
- Supervisor & School Dashboard
- Participant Application Lifecycle
- Session & Password Authentication
- Anti-Fraud & Attendance Rules Engine
- EnvKit Telemetry & Debug Service
- Certificate & LoA Downloads
- Attendance Logs & Fraud Detection
- Fraud Monitoring & Demographics Reporting
- Certificate Governance & Storage Facades
- Notification & Email Delivery
- OpenDesign UI Mockups & CI Workflow
- Agency & Category Administration
- Database Seeders & Permission Config
- Certificate Generation & Background Tasks
- Attendance Clock-In & Verification
- Composer Package Dependencies
- PDF Export & Report Generation
- ActiveInternController Module
- AttendanceFraudDetector Module
- alpinejs Module
- RequestDatabaseBackupRequest Module
- down() Module
- AdminSettingController Module
- LogbookController Module
- Kernel Module
- HasFactory Module
- @alpinejs/collapse Module
- badgeClass() Module
- ReportController Module
- CertificateController Module
- ImpossibleTravelRule Module
- down() Module
- down() Module
- CheckRole Module
- CreateDatabaseBackup Module
- LowonganRequest Module
- CertificateGovernanceController Module
- MajorController Module
- AttendanceChallengeService Module
- PembimbingLapanganController Module
- composer.json Module
- LoginRequest Module
- TestCase Module
- PesertaViewRegressionTest Module
- BackfillRolesAndMasterData Module
- PembimbingLapanganController Module
- Mail Module
- AttendanceChallengeTest Module
- AuditLogController Module
- ProfileController Module
- Setting Module
- AttendanceIdempotencyService Module
- GeoDistanceService Module
- require-dev Module
- scripts Module
- manifest.json Module
- StoreDailyLogRequest Module
- pestphp/pest-plugin Module
- LaporanRedesignSmokeTest Module
- EventServiceProvider Module
- public.welcome._alur-magang Module
- InstansiController Module
- StorageAccessController Module
- app.js Module
- CertificateGovernanceTest Module
- HighPriorityReviewTest Module
- Kernel Module
- ValidateDailyLogRequest Module
- TrackingController Module
- package.json Module
- peserta.dashboard._absensi-card Module
- Handler Module
- AssignMentorRequest Module
- RejectApplicationRequest Module
- DashboardController Module
- autoload Module
- NullHandler Module
- layouts.navigation Module
- Graphify Query and Navigation Guidelines Module
- TrustHosts Module
- TrustProxies Module
- Kernel Module
- profile.partials.delete-user-form Module
- pdf.partials.footer_page_number Module
- pdf.partials.footer_page_number Module
- pdf.partials.footer_page_number Module
- pdf.partials.footer_page_number Module
- pdf.partials.footer_page_number Module
- pdf.partials.footer_page_number Module
- pdf.partials.footer_page_number Module
- pdf.partials.footer_page_number Module
- pdf.partials.footer_page_number Module
- pdf.partials.footer_page_number Module
- pdf.partials.footer_page_number Module
- pdf.partials.footer_page_number Module
- pdf.partials.footer_page_number Module
- pdf.partials.footer_page_number Module
- pdf.partials.footer_page_number Module
- pdf.partials.footer_page_number Module
- pdf.partials.footer_page_number Module
- admin_kota.laporan.partials.application-status-badge Module
- admin_kota.users.partials.role-badge Module
- EncryptCookies Module
- PreventRequestsDuringMaintenance Module
- TrimStrings Module
- ValidateSignature Module
- VerifyCsrfToken Module
- AuthServiceProvider Module
- keywords Module
- GdImage Module
- Inspiring Module
- manifest.json Module
- Banjar Cultural Elements (Rumah Bubungan Tinggi & Jukung) Module
- processDir() Module
- makeFile() Module
- admin_instansi.partials._fraud-detail-modal Module
- admin_kota.dashboard._stats-grid Module
- admin_kota.partials.audit-action-badge Module
- @alpinejs/intersect Module
- autoprefixer Module
- class-variance-authority Module
- clsx Module
- Sanctum Module
- Renderer Module
- leaflet Module
- lucide-react Module
- @radix-ui/react-icons Module
- @radix-ui/react-select Module
- react-day-picker Module
- Internship Certificate Design & Generation Module
- sw.js Module
- admin_kota.laporan.partials.chart-loader Module
- admin_kota.laporan.partials.chart-loader Module
- admin_kota.laporan.partials.chart-loader Module
- admin_kota.laporan.partials.chart-loader Module
- admin_kota.laporan.partials.chart-loader Module
- admin_kota.laporan.partials.chart-loader Module
- Banjarmasin PWA Icon (192x192) Module
- PWA Icon 512x512 (Banjarmasin Seal) Module
- ID Card Frame Template Module

## God Nodes (most connected - your core abstractions)
1. `User` - 281 edges
2. `Application` - 197 edges
3. `Instansi` - 120 edges
4. `InternshipPosition` - 104 edges
5. `TestCase` - 85 edges
6. `Attendance` - 73 edges
7. `Controller` - 70 edges
8. `DailyLog` - 47 edges
9. `MajorCategory` - 42 edges
10. `AttendanceFraudContext` - 38 edges

## Surprising Connections (you probably didn't know these)
- `Quality Checks Verify Job` --references--> `Portal Magang Banjarmasin System Overview`  [INFERRED]
  .github/workflows/quality.yml → README.md
- `Robots Exclusion Protocol Configuration` --conceptually_related_to--> `Portal Magang Banjarmasin System Overview`  [INFERRED]
  public/robots.txt → README.md
- `Katalog Lowongan Magang Pemko Banjarmasin Mockup` --conceptually_related_to--> `Automatic Placement and Real-time Quota Management`  [INFERRED]
  opendesign/mockups/katalog-magang-banjarmasin/index.html → README.md
- `AttendanceFraudMonitoringTest` --references--> `Instansi`  [EXTRACTED]
  tests/Feature/AdminInstansi/AttendanceFraudMonitoringTest.php → app/Models/Instansi.php
- `AttendanceFraudMonitoringTest` --references--> `User`  [EXTRACTED]
  tests/Feature/AdminInstansi/AttendanceFraudMonitoringTest.php → app/Models/User.php

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Internship Application Design System & UI Mockups** — opendesign_mockups_alur_magang_brutalism_index_mockup, opendesign_mockups_alur_magang_minimalism_index_mockup, opendesign_mockups_katalog_magang_banjarmasin_index_catalog, opendesign_mockups_kartu_metriks_stats_index_mockup [INFERRED 0.85]
- **Graphify Knowledge Graph Rules and AI Agent Instructions** — agents_rules_graphify_rule, agents_workflows_graphify_workflow, gemini_graphify_instructions [EXTRACTED 1.00]
- **Core SIMAGANG Security and Governance Architecture** — readme_private_storage_isolation, readme_certificate_governance, readme_gated_feedback_evaluation, readme_rbac_matrix [EXTRACTED 1.00]

## Communities (646 total, 65 thin omitted)

### Community 0 - "User Identity & Profile Management"
Cohesion: 0.04
Nodes (10): User, InstansiPolicy, InternshipPositionPolicy, Illuminate\Foundation\Auth\User, MajorManagementTest, EmailVerificationExemptionTest, ProfileTest, RoleAdminKotaTest (+2 more)

### Community 1 - "Internship Vacancy & Quota Management"
Cohesion: 0.05
Nodes (12): LowonganController, LowonganController, Instansi, InternshipPosition, MajorCategory, DatabaseSeeder, FullSystemRoleAndPageVerificationTest, IdCardVerificationTest (+4 more)

### Community 2 - "Authentication & Testing Infrastructure"
Cohesion: 0.04
Nodes (22): CreatesApplication, Illuminate\Auth\Events\Verified, Illuminate\Auth\Notifications\ResetPassword, Illuminate\Auth\Notifications\VerifyEmail, Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, Illuminate\Foundation\Testing\WithFaker, Illuminate\Notifications\Messages\MailMessage (+14 more)

### Community 3 - "Supervisor & School Dashboard"
Cohesion: 0.06
Nodes (12): DashboardController, PembimbingSekolahController, Carbon, Attendance, AttendancePolicy, AttendanceService, Carbon, PembimbingLogbookService (+4 more)

### Community 4 - "Participant Application Lifecycle"
Cohesion: 0.05
Nodes (9): ApplicationController, Application, ApplicationPolicy, Illuminate\Auth\Access\Response, Illuminate\Pagination\LengthAwarePaginator, Illuminate\Support\Facades\Cache, Illuminate\Support\Facades\DB, PembimbingSekolahViewsTest (+1 more)

### Community 5 - "Session & Password Authentication"
Cohesion: 0.08
Nodes (24): AuthenticatedSessionController, ConfirmablePasswordController, EmailVerificationNotificationController, EmailVerificationPromptController, NewPasswordController, PasswordController, PasswordResetLinkController, RegisteredUserController (+16 more)

### Community 6 - "Anti-Fraud & Attendance Rules Engine"
Cohesion: 0.08
Nodes (10): AttendanceFraudContext, FraudSignal, AccuracyRule, AttendanceFraudRule, BoundaryConfidenceRule, NetworkAnomalyRule, RequestFrequencyRule, SessionConsistencyRule (+2 more)

### Community 7 - "EnvKit Telemetry & Debug Service"
Cohesion: 0.08
Nodes (11): Client, EnvKitDebugServiceProvider, AppServiceProvider, BroadcastServiceProvider, Illuminate\Support\Facades\Broadcast, Illuminate\Support\Facades\Facade, Illuminate\Support\Facades\View, Illuminate\Support\ServiceProvider (+3 more)

### Community 8 - "Certificate & LoA Downloads"
Cohesion: 0.07
Nodes (7): DashboardController, InternshipApplicationService, Barryvdh\DomPDF\Facade\Pdf, UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Support\Str, static

### Community 9 - "Attendance Logs & Fraud Detection"
Cohesion: 0.09
Nodes (5): AttendanceAttempt, AttendanceFraudEvent, AttendanceAttemptService, Illuminate\Support\Facades\Log, AttendanceFraudMonitoringTest

### Community 10 - "Fraud Monitoring & Demographics Reporting"
Cohesion: 0.12
Nodes (6): FraudMonitoringController, ReportController, Authenticate, Illuminate\Auth\Middleware\Authenticate, Illuminate\Contracts\Http\Kernel, Illuminate\Http\Request

### Community 11 - "Certificate Governance & Storage Facades"
Cohesion: 0.11
Nodes (9): RoleAndPermissionSeeder, Illuminate\Foundation\Testing\DatabaseTransactions, Illuminate\Http\UploadedFile, Illuminate\Support\Facades\Storage, Illuminate\Support\Facades\URL, SimpleSoftwareIO\QrCode\Facades\QrCode, RoleAdminInstansiTest, RolePembimbingSekolahTest (+1 more)

### Community 12 - "Notification & Email Delivery"
Cohesion: 0.12
Nodes (8): ApplicationAcceptedMail, ApplicationRejectedMail, InternshipCompleted, InternshipEndingMail, Illuminate\Mail\Mailable, Illuminate\Mail\Mailables\Attachment, Illuminate\Mail\Mailables\Content, Illuminate\Mail\Mailables\Envelope

### Community 13 - "OpenDesign UI Mockups & CI Workflow"
Cohesion: 0.09
Nodes (27): Quality Checks Verify Job, GitHub Actions Quality Checks Workflow, OpenDesign Manifest Loader & Navigation, OpenDesign UI Prototype Viewer, Neo-Brutalist 4-Step Registration Flow, Alur Pendaftaran Magang Neo-Brutalism Mockup, Minimalist 4-Step Registration Flow, Alur Pendaftaran Magang Minimalism Mockup (+19 more)

### Community 14 - "Agency & Category Administration"
Cohesion: 0.09
Nodes (5): MajorCategoryController, AdminUserController, Illuminate\Support\Facades\Hash, Illuminate\Validation\Rule, Illuminate\Validation\Rules\Password

### Community 15 - "Database Seeders & Permission Config"
Cohesion: 0.09
Nodes (16): Carbon\CarbonPeriod, AttendanceSeeder, MajorSeeder, MassDummySeeder, PenilaianDummySeeder, Factory, Illuminate\Contracts\Auth\MustVerifyEmail, Illuminate\Database\Seeder (+8 more)

### Community 16 - "Certificate Generation & Background Tasks"
Cohesion: 0.10
Nodes (8): GenerateCertificateNumberAction, CompleteExpiredInternships, MigratePublicDocumentsToPrivate, PruneExpiredBackups, SendEndingNotifications, ApplicationLifecycleService, CertificateService, Illuminate\Console\Command

### Community 17 - "Attendance Clock-In & Verification"
Cohesion: 0.13
Nodes (5): AttendanceController, Carbon, ClockInRequest, PermissionRequest, Illuminate\Database\QueryException

### Community 18 - "Composer Package Dependencies"
Cohesion: 0.08
Nodes (25): require, barryvdh/laravel-dompdf, ext-bcmath, ext-ctype, ext-curl, ext-dom, ext-fileinfo, ext-gd (+17 more)

### Community 19 - "PDF Export & Report Generation"
Cohesion: 0.13
Nodes (3): PdfExportService, ReportService, Illuminate\Http\Response

### Community 20 - "ActiveInternController Module"
Cohesion: 0.10
Nodes (4): ActiveInternController, ApplicantController, SettingController, Illuminate\Support\Facades\Auth

### Community 21 - "AttendanceFraudDetector Module"
Cohesion: 0.11
Nodes (4): AttendanceFraudDetector, self, AttendanceRiskScorer, AttendanceRiskScorerTest

### Community 22 - "alpinejs Module"
Cohesion: 0.10
Nodes (21): alpinejs, laravel-vite-plugin, devDependencies, alpinejs, laravel-vite-plugin, postcss, tailwindcss, @tailwindcss/forms (+13 more)

### Community 23 - "RequestDatabaseBackupRequest Module"
Cohesion: 0.13
Nodes (5): RequestDatabaseBackupRequest, UpdateSystemSettingsRequest, StoreCertificateRequest, StoreApplicationRequest, Illuminate\Foundation\Http\FormRequest

### Community 25 - "AdminSettingController Module"
Cohesion: 0.16
Nodes (3): AdminSettingController, DatabaseBackup, DatabaseBackupTest

### Community 26 - "LogbookController Module"
Cohesion: 0.18
Nodes (3): LogbookController, DailyLog, DailyLogPolicy

### Community 27 - "Kernel Module"
Cohesion: 0.11
Nodes (17): Kernel, Illuminate\Auth\Middleware\AuthenticateWithBasicAuth, Illuminate\Auth\Middleware\Authorize, Illuminate\Auth\Middleware\EnsureEmailIsVerified, Illuminate\Auth\Middleware\RequirePassword, Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse, Illuminate\Foundation\Http\Kernel, Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull (+9 more)

### Community 28 - "HasFactory Module"
Cohesion: 0.19
Nodes (3): Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model, Illuminate\Database\Eloquent\Relations\HasMany

### Community 29 - "@alpinejs/collapse Module"
Cohesion: 0.12
Nodes (17): @alpinejs/collapse, @fortawesome/fontawesome-free, @hotwired/turbo, dependencies, @alpinejs/collapse, @fortawesome/fontawesome-free, @hotwired/turbo, @radix-ui/react-slot (+9 more)

### Community 30 - "badgeClass() Module"
Cohesion: 0.15
Nodes (5): fromScore(), self, AttendanceFraudResult, self, AttendanceFraudStatus

### Community 32 - "CertificateController Module"
Cohesion: 0.12
Nodes (3): CertificateController, NotificationController, Illuminate\Support\Facades\Route

### Community 36 - "CheckRole Module"
Cohesion: 0.24
Nodes (6): CheckRole, RedirectIfAuthenticated, SecurityHeaders, UpdateExpiredInternships, Closure, Symfony\Component\HttpFoundation\Response

### Community 37 - "CreateDatabaseBackup Module"
Cohesion: 0.21
Nodes (9): CreateDatabaseBackup, ApplicationStatusNotification, Illuminate\Bus\Queueable, Illuminate\Contracts\Queue\ShouldQueue, Illuminate\Foundation\Bus\Dispatchable, Illuminate\Notifications\Notification, Illuminate\Queue\InteractsWithQueue, Illuminate\Queue\SerializesModels (+1 more)

### Community 38 - "LowonganRequest Module"
Cohesion: 0.18
Nodes (5): LowonganRequest, HtmlSanitizer, DOMDocument, DOMElement, DOMNode

### Community 39 - "CertificateGovernanceController Module"
Cohesion: 0.21
Nodes (3): CertificateGovernanceController, Certificate, Illuminate\Database\Eloquent\Relations\BelongsTo

### Community 40 - "MajorController Module"
Cohesion: 0.23
Nodes (3): MajorController, Major, AuditLogService

### Community 41 - "AttendanceChallengeService Module"
Cohesion: 0.26
Nodes (4): AttendanceChallengeService, AttendanceLockService, Illuminate\Cache\Lock, Illuminate\Contracts\Auth\Authenticatable

### Community 43 - "composer.json Module"
Cohesion: 0.15
Nodes (12): autoload-dev, psr-4, description, extra, laravel, dont-discover, license, minimum-stability (+4 more)

### Community 44 - "LoginRequest Module"
Cohesion: 0.21
Nodes (4): LoginRequest, Illuminate\Auth\Events\Lockout, Illuminate\Cache\RateLimiting\Limit, Illuminate\Support\Facades\RateLimiter

### Community 45 - "TestCase Module"
Cohesion: 0.20
Nodes (3): PHPUnit\Framework\TestCase, GeoDistanceServiceTest, ExampleTest

### Community 47 - "BackfillRolesAndMasterData Module"
Cohesion: 0.27
Nodes (5): BackfillRolesAndMasterData, School, University, Command, UniversityAndSchoolSeeder

### Community 51 - "AuditLogController Module"
Cohesion: 0.22
Nodes (3): AuditLogController, AuditLog, Illuminate\Support\Facades\Request

### Community 52 - "ProfileController Module"
Cohesion: 0.22
Nodes (3): ProfileController, ProfileUpdateRequest, Illuminate\Support\Facades\Redirect

### Community 56 - "require-dev Module"
Cohesion: 0.20
Nodes (10): require-dev, aerni/cloudflared, fakerphp/faker, laravel/breeze, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision (+2 more)

### Community 57 - "scripts Module"
Cohesion: 0.20
Nodes (10): scripts, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump, @php artisan key:generate --ansi, @php artisan package:discover --ansi (+2 more)

### Community 58 - "manifest.json Module"
Cohesion: 0.20
Nodes (9): background_color, description, display, icons, name, orientation, short_name, start_url (+1 more)

### Community 60 - "pestphp/pest-plugin Module"
Cohesion: 0.22
Nodes (9): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, platform, preferred-install, sort-packages (+1 more)

### Community 62 - "EventServiceProvider Module"
Cohesion: 0.25
Nodes (4): EventServiceProvider, Illuminate\Auth\Events\Registered, Illuminate\Auth\Listeners\SendEmailVerificationNotification, Illuminate\Foundation\Support\Providers\EventServiceProvider

### Community 63 - "public.welcome._alur-magang Module"
Cohesion: 0.25
Nodes (7): public.welcome._alur-magang, public.welcome._faq, public.welcome._footer, public.welcome._hero, public.welcome._lowongan-grid, public.welcome._navbar, public.welcome._stats

### Community 69 - "Kernel Module"
Cohesion: 0.40
Nodes (3): Kernel, Illuminate\Console\Scheduling\Schedule, Illuminate\Foundation\Console\Kernel

### Community 72 - "package.json Module"
Cohesion: 0.33
Nodes (5): private, scripts, build, dev, type

### Community 73 - "peserta.dashboard._absensi-card Module"
Cohesion: 0.33
Nodes (5): peserta.dashboard._absensi-card, peserta.dashboard._gps-widget, peserta.dashboard._lamaran-list, peserta.dashboard._logbook-card, peserta.dashboard._stats

### Community 74 - "Handler Module"
Cohesion: 0.40
Nodes (3): Handler, Illuminate\Foundation\Exceptions\Handler, Throwable

### Community 78 - "autoload Module"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 79 - "NullHandler Module"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 80 - "layouts.navigation Module"
Cohesion: 0.40
Nodes (4): layouts.navigation, layouts.partials._mobile-bottom-nav, layouts.partials._mobile-sheet, layouts.partials._notification-bell

### Community 81 - "Graphify Query and Navigation Guidelines Module"
Cohesion: 0.50
Nodes (4): Graphify Query and Navigation Guidelines, Graphify Knowledge Graph Rule, Graphify Knowledge Graph Workflow, Gemini Graphify Instructions

### Community 85 - "Kernel Module"
Cohesion: 0.67
Nodes (3): Illuminate\Contracts\Console\Kernel, Illuminate\Foundation\Application, createApplication()

### Community 86 - "profile.partials.delete-user-form Module"
Cohesion: 0.50
Nodes (3): profile.partials.delete-user-form, profile.partials.update-password-form, profile.partials.update-profile-information-form

### Community 87 - "pdf.partials.footer_page_number Module"
Cohesion: 0.50
Nodes (3): pdf.partials.footer_page_number, pdf.partials.kop_admin_instansi, pdf.partials.ttd_admin_instansi

### Community 88 - "pdf.partials.footer_page_number Module"
Cohesion: 0.50
Nodes (3): pdf.partials.footer_page_number, pdf.partials.kop_admin_instansi, pdf.partials.ttd_admin_instansi

### Community 89 - "pdf.partials.footer_page_number Module"
Cohesion: 0.50
Nodes (3): pdf.partials.footer_page_number, pdf.partials.kop_admin_instansi, pdf.partials.ttd_admin_instansi

### Community 90 - "pdf.partials.footer_page_number Module"
Cohesion: 0.50
Nodes (3): pdf.partials.footer_page_number, pdf.partials.kop_admin_instansi, pdf.partials.ttd_admin_instansi

### Community 91 - "pdf.partials.footer_page_number Module"
Cohesion: 0.50
Nodes (3): pdf.partials.footer_page_number, pdf.partials.kop_admin_instansi, pdf.partials.ttd_admin_instansi

### Community 92 - "pdf.partials.footer_page_number Module"
Cohesion: 0.50
Nodes (3): pdf.partials.footer_page_number, pdf.partials.kop_admin_instansi, pdf.partials.ttd_admin_instansi

### Community 93 - "pdf.partials.footer_page_number Module"
Cohesion: 0.50
Nodes (3): pdf.partials.footer_page_number, pdf.partials.kop_admin_instansi, pdf.partials.ttd_admin_instansi

### Community 94 - "pdf.partials.footer_page_number Module"
Cohesion: 0.50
Nodes (3): pdf.partials.footer_page_number, pdf.partials.kop_admin_kota, pdf.partials.ttd_admin_kota

### Community 95 - "pdf.partials.footer_page_number Module"
Cohesion: 0.50
Nodes (3): pdf.partials.footer_page_number, pdf.partials.kop_admin_kota, pdf.partials.ttd_admin_kota

### Community 96 - "pdf.partials.footer_page_number Module"
Cohesion: 0.50
Nodes (3): pdf.partials.footer_page_number, pdf.partials.kop_admin_kota, pdf.partials.ttd_admin_kota

### Community 97 - "pdf.partials.footer_page_number Module"
Cohesion: 0.50
Nodes (3): pdf.partials.footer_page_number, pdf.partials.kop_admin_kota, pdf.partials.ttd_admin_kota

### Community 98 - "pdf.partials.footer_page_number Module"
Cohesion: 0.50
Nodes (3): pdf.partials.footer_page_number, pdf.partials.kop_admin_kota, pdf.partials.ttd_admin_kota

### Community 99 - "pdf.partials.footer_page_number Module"
Cohesion: 0.50
Nodes (3): pdf.partials.footer_page_number, pdf.partials.kop_admin_kota, pdf.partials.ttd_admin_kota

### Community 100 - "pdf.partials.footer_page_number Module"
Cohesion: 0.50
Nodes (3): pdf.partials.footer_page_number, pdf.partials.kop_admin_kota, pdf.partials.ttd_admin_kota

### Community 101 - "pdf.partials.footer_page_number Module"
Cohesion: 0.50
Nodes (3): pdf.partials.footer_page_number, pdf.partials.kop_admin_kota, pdf.partials.ttd_admin_kota

### Community 102 - "pdf.partials.footer_page_number Module"
Cohesion: 0.50
Nodes (3): pdf.partials.footer_page_number, pdf.partials.kop_admin_kota, pdf.partials.ttd_admin_kota

### Community 103 - "pdf.partials.footer_page_number Module"
Cohesion: 0.50
Nodes (3): pdf.partials.footer_page_number, pdf.partials.kop_admin_kota, pdf.partials.ttd_admin_kota

### Community 114 - "keywords Module"
Cohesion: 0.67
Nodes (3): keywords, framework, laravel

### Community 168 - "Banjar Cultural Elements (Rumah Bubungan Tinggi & Jukung) Module"
Cohesion: 0.67
Nodes (3): Banjar Cultural Elements (Rumah Bubungan Tinggi & Jukung), Motto Kayuh Baimbai, Logo Kota Banjarmasin (Kayuh Baimbai)

## Knowledge Gaps
- **207 isolated node(s):** `name`, `type`, `description`, `laravel`, `framework` (+202 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **65 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User Identity & Profile Management` to `Internship Vacancy & Quota Management`, `Authentication & Testing Infrastructure`, `Supervisor & School Dashboard`, `Participant Application Lifecycle`, `Session & Password Authentication`, `Anti-Fraud & Attendance Rules Engine`, `Attendance Logs & Fraud Detection`, `Fraud Monitoring & Demographics Reporting`, `Certificate Governance & Storage Facades`, `Agency & Category Administration`, `Database Seeders & Permission Config`, `PDF Export & Report Generation`, `ActiveInternController Module`, `AdminSettingController Module`, `LogbookController Module`, `HasFactory Module`, `ReportController Module`, `PesertaViewRegressionTest Module`, `BackfillRolesAndMasterData Module`, `PembimbingLapanganController Module`, `Mail Module`, `AttendanceChallengeTest Module`, `AuditLogController Module`, `ProfileController Module`, `Setting Module`, `LaporanRedesignSmokeTest Module`, `EventServiceProvider Module`, `InstansiController Module`, `CertificateGovernanceTest Module`, `HighPriorityReviewTest Module`, `TrackingController Module`, `AssignMentorRequest Module`?**
  _High betweenness centrality (0.107) - this node is a cross-community bridge._
- **Why does `Application` connect `Participant Application Lifecycle` to `User Identity & Profile Management`, `Internship Vacancy & Quota Management`, `Authentication & Testing Infrastructure`, `Supervisor & School Dashboard`, `Anti-Fraud & Attendance Rules Engine`, `Certificate & LoA Downloads`, `Fraud Monitoring & Demographics Reporting`, `Certificate Governance & Storage Facades`, `Notification & Email Delivery`, `Agency & Category Administration`, `Database Seeders & Permission Config`, `Certificate Generation & Background Tasks`, `Attendance Clock-In & Verification`, `PDF Export & Report Generation`, `ActiveInternController Module`, `LogbookController Module`, `HasFactory Module`, `ReportController Module`, `CertificateController Module`, `CheckRole Module`, `CreateDatabaseBackup Module`, `PembimbingLapanganController Module`, `PesertaViewRegressionTest Module`, `Mail Module`, `StoreDailyLogRequest Module`, `StorageAccessController Module`, `CertificateGovernanceTest Module`, `HighPriorityReviewTest Module`, `TrackingController Module`, `AssignMentorRequest Module`, `RejectApplicationRequest Module`?**
  _High betweenness centrality (0.069) - this node is a cross-community bridge._
- **Why does `Controller` connect `Session & Password Authentication` to `Internship Vacancy & Quota Management`, `Supervisor & School Dashboard`, `Participant Application Lifecycle`, `Certificate & LoA Downloads`, `Fraud Monitoring & Demographics Reporting`, `Certificate Governance & Storage Facades`, `Agency & Category Administration`, `Attendance Clock-In & Verification`, `PDF Export & Report Generation`, `ActiveInternController Module`, `AdminSettingController Module`, `LogbookController Module`, `ReportController Module`, `CertificateController Module`, `LowonganRequest Module`, `CertificateGovernanceController Module`, `MajorController Module`, `PembimbingLapanganController Module`, `PembimbingLapanganController Module`, `AuditLogController Module`, `ProfileController Module`, `EventServiceProvider Module`, `InstansiController Module`, `StorageAccessController Module`, `TrackingController Module`, `DashboardController Module`?**
  _High betweenness centrality (0.026) - this node is a cross-community bridge._
- **What connects `name`, `type`, `description` to the rest of the system?**
  _207 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `User Identity & Profile Management` be split into smaller, more focused modules?**
  _Cohesion score 0.035495403472931564 - nodes in this community are weakly interconnected._
- **Should `Internship Vacancy & Quota Management` be split into smaller, more focused modules?**
  _Cohesion score 0.05331510594668489 - nodes in this community are weakly interconnected._
- **Should `Authentication & Testing Infrastructure` be split into smaller, more focused modules?**
  _Cohesion score 0.04428904428904429 - nodes in this community are weakly interconnected._