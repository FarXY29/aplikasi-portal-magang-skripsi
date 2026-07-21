# Design Spec: README.md Update for Portal Magang v2.0+

**Date:** 2026-07-21  
**Topic:** Documentation Update  
**Target File:** [README.md](file:///c:/EnvKit/aplikasi-magang-skripsi%20-%20Copy/README.md)

---

## 1. Goal Description
The current `README.md` is outdated and doesn't reflect the extensive changes introduced in Portal Magang v2.0+. We need to document:
- Private document storage and authorization gates.
- Database backup queue jobs with expiring URL downloads.
- Google Socialite authentication configuration.
- Evaluation gating before certificate downloads.
- New Artisan console commands and scheduled execution patterns.
- Dark mode theme switching support.
- Local environment setups for new `.env` variables.

## 2. Proposed Changes
We will modify [README.md](file:///c:/EnvKit/aplikasi-magang-skripsi%20-%20Copy/README.md) to structure the document into logical sections:
1. **Title & Summary**: Define Portal Magang Kota Banjarmasin and its core purpose.
2. **Key Features (v2.0+)**: Bullet points summarizing Private Storage, Database Backups, Google OAuth, Evaluation gating, radius validation, and Dark Mode.
3. **User Roles**: Outline responsibilities for Admin Kota, Admin Instansi, Pembimbing Lapangan, Pembimbing Akademik, and Peserta.
4. **Technology Stack**: Specify PHP 8.3+, Laravel 13, MySQL/MariaDB, Redis, Tailwind CSS, Alpine.js, Spatie Permission, Socialite, DomPDF, Excel, Simple QR Code, and cloudflared.
5. **Local Setup & Environment variables**: Include detailed instructions on `.env` configuration for Google OAuth, filesystem disk mapping, and queues.
6. **Task Scheduling & Worker Queues**: Document execution commands for worker queue (`queue:work`), scheduler (`schedule:work`), and list schedules:
   - `internship:complete-expired` (daily)
   - `app:send-ending-notifications` (daily @08:00)
   - `backups:prune` (hourly)
7. **Quality Checks & CI/CD**: Document commands used in GitHub Actions (caching, testing, npm compilation).
8. **Security Operations & Migration**: Document the post-deploy security tasks:
   - Role & Master data sync: `php artisan magang:backfill-roles-master`
   - Document migration: `php artisan documents:migrate-private`

## 3. Verification Plan
- Verify that [README.md](file:///c:/EnvKit/aplikasi-magang-skripsi%20-%20Copy/README.md) file builds correctly in Markdown.
- Double check that all command names and environment variable names match the actual implementation in the codebase.
- Run tests (`php artisan test`) to ensure code remains unaffected by documentation updates.
