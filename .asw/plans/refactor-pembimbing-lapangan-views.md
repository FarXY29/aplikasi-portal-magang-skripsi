# refactor-pembimbing-lapangan-views

## TL;DR
Refactor all 4 Blade view templates in `resources/views/pembimbing_lapangan/` (`dashboard.blade.php`, `attendance.blade.php`, `logbook.blade.php`, and `penilaian.blade.php`) to eliminate DOM bloat (e.g. modals trapped inside `<tbody>`), add mobile card responsive views for attendance monitoring, extract reusable partials into `resources/views/pembimbing_lapangan/partials/`, standardize dark mode tokens and accessibility (`aria-label`, semantic tags), while strictly preserving 100% of route bindings, form contracts, batch actions, and client-side score calculations.

## Objective
Modernize and streamline `resources/views/pembimbing_lapangan/` views for better maintainability, performance, accessibility, and responsive user experience without altering any existing server-side routes, controllers, validation rules, or database models.

## Non-goals
- No modifications to backend controller business logic in `PembimbingLapanganController.php` or `PembimbingLogbookService.php`.
- No route changes or URL changes in `routes/pembimbing.php`.
- No changes to database schemas, migrations, seeders, or policies.
- No alteration of form payload keys (`status`, `status_validasi`, `komentar`, `log_ids`, `nilai_*`, `catatan_pembimbing_lapangan`, `pembimbing_lapangan_note`).

## Discovery
- `resources/views/pembimbing_lapangan/dashboard.blade.php`: Contains stats grid (Total Bimbingan, Sedang Magang, Selesai), desktop table and mobile card view. Shows pending counts and active intern list with action buttons for Logbook, Absensi, and Penilaian.
- `resources/views/pembimbing_lapangan/attendance.blade.php`: 500-line monolithic file. Contains filter bar with segmented controls, quick date pills, desktop table, and inline `<x-modal>` dialogs inside `<tbody>` inside `@forelse` loop (creating invalid HTML and DOM bloat). Lacks dedicated mobile card view.
- `resources/views/pembimbing_lapangan/logbook.blade.php`: 454-line file with student switcher, filter bar, 2-column master-detail layout (sidebar list with batch validation + detail pane with documentation image, task description, prior feedback, and single validation form).
- `resources/views/pembimbing_lapangan/penilaian.blade.php`: 170-line grading view with intern profile card, dynamic average score calculation (`initGradeCalculator`), 5 criteria inputs (Kerajinan, Disiplin, Adaptasi, Kreatifitas, Skill & Pengetahuan), and supervisor feedback textarea.
- Existing tests in `tests/Feature/RolePembimbingLapanganTest.php` and `tests/Feature/FullSystemRoleAndPageVerificationTest.php` pass cleanly.

## Decisions
- **Characterization First**: Create dedicated feature test suite `tests/Feature/PembimbingLapanganViewsTest.php` validating rendering, table/card views, filtering params, modal attributes, batch validation payload contracts, and grading calculation form inputs before any view modifications.
- **Extract Modals from Table Body**: Move `<x-modal>` templates in `attendance.blade.php` outside the `<tbody>` into a clean partial `resources/views/pembimbing_lapangan/partials/_attendance-modals.blade.php` to prevent invalid HTML inside table rows and reduce DOM rendering weight.
- **Add Dedicated Mobile Cards to Attendance**: Add responsive mobile card view (`md:hidden`) for attendance monitoring matching the design language of `dashboard.blade.php` and `resources/views/pembimbing/dashboard.blade.php`.
- **Extract Reusable Partials**:
  - `resources/views/pembimbing_lapangan/partials/_stats-grid.blade.php` for dashboard metric cards.
  - `resources/views/pembimbing_lapangan/partials/_attendance-modals.blade.php` for proof preview and rejection modals.
- **Alpine & JS Hygiene**: Keep `initGradeCalculator` compatible with both Turbo (`turbo:load`) and standard page loads (`DOMContentLoaded`), ensuring real-time calculation and predicate updates.
- **Accessibility & Contrast**: Add proper `aria-label`, `scope="col"`, semantic `<section>` / `<main>` wrappers, and ensure text contrast passes WCAG AA in both light and dark modes.

## TODOs

- [ ] Characterization Tests: Write and run `tests/Feature/PembimbingLapanganViewsTest.php`
  - Files: `tests/Feature/PembimbingLapanganViewsTest.php`
  - RED: Ensure test suite runs and covers all 4 views with specific assertions (status codes, view data, filter queries, action buttons, form action targets, CSRF, modal IDs, input names).
  - GREEN: Verify all characterization tests pass against current views before making structural changes.
  - Real-surface QA: Run `php artisan test --filter=PembimbingLapanganViewsTest`.
  - Evidence: Test run exit code 0 with all assertions passing.
  - Cleanup: None.
  - Commit: NO (draft message provided upon completion).

- [ ] Refactor `dashboard.blade.php` & Extract `_stats-grid.blade.php`
  - Files: `resources/views/pembimbing_lapangan/dashboard.blade.php`, `resources/views/pembimbing_lapangan/partials/_stats-grid.blade.php`
  - RED: N/A (baseline test established).
  - GREEN: `dashboard.blade.php` uses extracted clean partials, standardized semantic HTML, accessible table headers, unified badge helpers, and preserves all links (`pembimbing_lapangan.logbook`, `pembimbing_lapangan.attendance.index`, `pembimbing_lapangan.penilaian`).
  - Real-surface QA: Execute `php artisan test --filter=PembimbingLapanganViewsTest`.
  - Evidence: All dashboard assertions pass; clean diff.
  - Cleanup: Remove any unused legacy classes or duplicated markup.
  - Commit: NO.

- [ ] Refactor `attendance.blade.php` & Extract `_attendance-modals.blade.php`
  - Files: `resources/views/pembimbing_lapangan/attendance.blade.php`, `resources/views/pembimbing_lapangan/partials/_attendance-modals.blade.php`
  - RED: N/A.
  - GREEN: Relocate modals outside `<tbody>` to clean partial `_attendance-modals.blade.php`, add responsive mobile card view (`md:hidden`), streamline filter form and quick date buttons, preserve validation action forms (`pembimbing_lapangan.attendance.validate`), approve/reject buttons, and proof iframe/image previews.
  - Real-surface QA: Execute `php artisan test --filter=PembimbingLapanganViewsTest` and `php artisan test --filter=RolePembimbingLapanganTest`.
  - Evidence: Attendance filtering, modal triggers, and form submissions verify cleanly.
  - Cleanup: Remove inline modal definitions from inside table rows.
  - Commit: NO.

- [ ] Refactor `logbook.blade.php`
  - Files: `resources/views/pembimbing_lapangan/logbook.blade.php`
  - RED: N/A.
  - GREEN: Streamline master-detail layout, standardize filter bar, maintain Alpine `activeTab` reactive switcher, preserve batch validation form (`pembimbing_lapangan.logbook.batch_validasi`) with JavaScript requirement for revision comment, preserve single log validation form (`pembimbing_lapangan.logbook.validasi`), and maintain image zoom lightbox modal trigger (`openImageModal`).
  - Real-surface QA: Execute `php artisan test --filter=PembimbingLapanganViewsTest`.
  - Evidence: Single and batch validation view tests pass.
  - Cleanup: Clean up inline style hacks.
  - Commit: NO.

- [ ] Refactor `penilaian.blade.php`
  - Files: `resources/views/pembimbing_lapangan/penilaian.blade.php`
  - RED: N/A.
  - GREEN: Standardize grading layout with sticky participant overview sidebar, accessible 5-criteria inputs (`nilai_kerajinan`, `nilai_disiplin`, `nilai_adaptasi`, `nilai_kreatifitas`, `nilai_skill_pengetahuan`), supervisor comments textarea, and Turbo-compatible `initGradeCalculator` script.
  - Real-surface QA: Execute `php artisan test --filter=PembimbingLapanganViewsTest`.
  - Evidence: Form fields, calculation logic, and validation submission pass.
  - Cleanup: Remove any duplicate inline scripts.
  - Commit: NO.

## Parallel Execution Waves

```text
Wave 1 (Baseline & Characterization):
- Task 1: Write and verify `tests/Feature/PembimbingLapanganViewsTest.php`

Wave 2 (View Refactoring - Serialized per view to ensure isolated verification):
- Task 2: Refactor `dashboard.blade.php` & partials
- Task 3: Refactor `attendance.blade.php` & modal partials
- Task 4: Refactor `logbook.blade.php`
- Task 5: Refactor `penilaian.blade.php`

Wave 3 (Final Verification):
- Task 6: Full test suite execution & graphify update
```

## Dependency Matrix

| Task | Depends on | Blocks | Can parallelize with |
|---|---|---|---|
| 1 (Characterization Test) | none | 2, 3, 4, 5 | none |
| 2 (Dashboard Refactor) | 1 | 6 | 3, 4, 5 |
| 3 (Attendance Refactor) | 1 | 6 | 2, 4, 5 |
| 4 (Logbook Refactor) | 1 | 6 | 2, 3, 5 |
| 5 (Penilaian Refactor) | 1 | 6 | 2, 3, 4 |
| 6 (Final Verification) | 2, 3, 4, 5 | none | none |

## Final Verification Wave
- [ ] Run full automated test suite: `php artisan test`
- [ ] Run view-specific regression suite: `php artisan test --filter=Pembimbing`
- [ ] Run role end-to-end suite: `php artisan test --filter=FullSystemRoleAndPageVerificationTest`
- [ ] Run knowledge graph update: `graphify update .`
- [ ] Review git diff to ensure no regressions or unintended changes to public contracts or controllers.

Next: `start-work refactor-pembimbing-lapangan-views`
