# Views Improvement Plan

Refactor of `resources/views/` (207 blade files, ~2.3 MB). Goals: dedup + split monoliths, inline JS/CSS to Vite bundles, UI consistency, dead code removal, page weight. Priority: per role folder.

## Ground rules

- **Zero visual change.** All extraction moves markup verbatim. Golden snapshot diff must be empty after every phase.
- **Exception:** `pdf/` and `emails/` keep inline styles — dompdf and email clients require them. Only dedup partials there, never externalize CSS.
- Gate after every phase: `php artisan test` + `npm run build` + golden diff empty.
- One folder (or one concern) per commit, so regressions bisect clean.

## Findings (baseline, measured)

| Fact | Detail |
|---|---|
| Existing test suite | 60+ feature tests; `FullSystemRoleAndPageVerificationTest` hits 170 routes across all roles — but asserts 200 status only, not content |
| Worst duplication | `admin_kota/laporan/pendaftaran.blade.php` (800 lines) vs `admin_instansi/laporan/pendaftaran.blade.php` (771 lines): **92% identical** |
| Settings pages | `admin_kota/settings/index` vs `admin_instansi/settings`: only 17% identical — split, do NOT merge |
| Monoliths | `public/welcome/_lowongan-grid` 96 KB; `admin_kota/laporan/pendaftaran` 62 KB; `admin_instansi/laporan/pendaftaran` 61 KB; `admin_kota/laporan/peserta_global` 54 KB; `pembimbing_lapangan/attendance` 50 KB; `admin_kota/laporan/grading` 50 KB; `admin_instansi/dashboard` 49 KB; `admin_kota/laporan/instansi_disiplin` 48 KB; `admin_kota/settings/index` 44 KB; `admin_instansi/settings` 43 KB; `pembimbing_lapangan/logbook` 39 KB; `admin_kota/dashboard` 37 KB; `layouts/navigation` 36 KB; `public/verifikasi/scanner` 36 KB |
| Inline assets | 36 views with inline `<script>`, 40 with inline `<style>` |
| CDN loads | 7 views: leaflet 1.9.4 (cdnjs), fontawesome |
| Dead npm deps | react, react-dom, @types/react, @types/react-dom, @vitejs/plugin-react, @radix-ui/react-icons, @radix-ui/react-select, @radix-ui/react-slot, lucide-react, class-variance-authority, clsx, tailwind-merge, react-day-picker — zero `.jsx`/`.tsx` files, zero view references |
| Dead components | `components/auth-session-status`, `components/danger-button`, `components/dropdown-link`, `components/nav-link`, `components/responsive-nav-link`, `components/ui/skeleton`, `components/ui/stat-card` — zero references |
| Component library | `x-ui.*` exists but lightly used: alert 25, page-header 23, badge 6, filter-bar 5, rich-text-editor 4, empty-state 4, confirm-dialog 1 |
| Vite entries | `app.css`, `app.js`, `peserta/apply-form.js`, `peserta/dashboard.js`, `peserta/logbook.js`; vendor chunk: alpinejs, alpine plugins, turbo |
| Layout styles | 65 views on `x-app-layout` components; 6 on `@extends` (all emails — correct for emails) |

## Phase 0 — Golden snapshot tests (safety net)

New `tests/Feature/ViewGoldenTest.php`:

- Render every role page using existing factories/seed patterns (reuse setup from `FullSystemRoleAndPageVerificationTest`).
- Save rendered HTML to `tests/snapshots/<name>.html` on first run; on later runs diff against snapshot and fail on mismatch.
- Hand-rolled assert helper — no new composer dependency.
- Normalize only non-visual noise: CSRF tokens, asset hashes (`?v=`/manifest URLs), timestamps if flaky.
- Capture baseline **before** any refactor lands.

Done when: baseline snapshots committed, suite green.

## Phase 1 — Dead code + deps (quick win)

1. `package.json`: remove react stack, radix ×3, lucide-react, cva, clsx, tailwind-merge, react-day-picker, @types/*. Keep `leaflet` (npm) — switch the 7 CDN views to bundled leaflet 1.9.4 + fontawesome via Vite. Same versions → identical render.
2. Delete the 7 dead components.
3. `npm install` refresh lockfile; `npm run build`; full test + golden diff.

## Phase 2 — Inline JS/CSS → Vite (skip pdf/emails)

1. Inline `<style>` blocks → verbatim into `resources/css/pages/<page>.css`, imported once from `app.css`. Repeated per-page bytes become one cached bundle = page-weight win.
2. Inline `<script>` → `resources/js/<role>/<page>.js`. Blade-injected values move to `data-*` attributes + `@json`. Tiny Alpine snippets fold into existing `app.js`.
3. New Vite entries only for big page scripts — avoid config bloat.
4. Turbo is active: extracted scripts must bind `turbo:load` (verify per page).

Order: one role folder at a time, gate after each.

## Phase 3 — admin_kota + admin_instansi (biggest win)

1. Merge 92%-dup `laporan/pendaftaran` into one shared view + scope param (`kota` vs `instansi`), reusing `components/report/*` (toolbar, header, pill already exist).
2. Split remaining laporan monoliths (peserta_global, grading, instansi_disiplin, penyerapan_kuota, rekap) into partials; share any identical blocks across the two folders.
3. Split dashboards (49 KB / 37 KB) into partials following the `peserta/dashboard/_*` pattern.
4. Split settings pages (17% dup — split only, no merge).
5. Extract inline scripts/styles per Phase 2 rules.

## Phase 4 — public

1. Split `_lowongan-grid.blade.php` (96 KB) into card partial + grid shell.
2. `verifikasi/scanner` (36 KB): split, extract QR script to Vite.
3. welcome/, tracking, lowongan: inline asset extraction, dead-file sweep.

## Phase 5 — peserta

1. Inline scripts → existing 3 vite entries (`apply-form.js`, `dashboard.js`, `logbook.js`).
2. Dashboard partial pattern already good — extend to `logbook/`, `absensi/`, `apply`.

## Phase 6 — pembimbing + pembimbing_lapangan

1. Split `pembimbing_lapangan/attendance` (50 KB) and `logbook` (39 KB) into partials.
2. Diff `pembimbing/partials/_absensi-table`, `_student-cards`, `_student-table` against pembimbing_lapangan equivalents; share where identical.

## Phase 7 — layouts + components

1. Split `layouts/navigation.blade.php` (36 KB) using existing `layouts/partials/nav/`.
2. Audit `components/`; adopt `x-ui.*` only where markup repeats ≥3× verbatim (identical output).
3. Remove any newly-dead components.

## Phase 8 — auth, profile, kiosk, pdf, emails (light pass)

1. auth/profile: Breeze leftovers — unify under existing components only if rendered HTML is identical.
2. pdf/: partial dedup within `pdf/partials/` only; styles stay inline (dompdf).
3. emails/: keep `@extends` layout; dedup shared blocks only.
4. kiosk: single file — extract inline script, split if large.

## Verification gate (every phase)

1. `php artisan test` — full suite green.
2. `npm run build` — clean build.
3. `ViewGoldenTest` diff empty (snapshots recaptured only by explicit decision, recorded in commit message).

## Decisions taken (veto if wrong)

1. Golden-snapshot tests hand-rolled — no spatie snapshot dependency.
2. leaflet/fontawesome bundled via Vite instead of CDN (offline-safe, same versions).
3. pdf/ + emails/ inline CSS untouched — platform constraint, not debt.
