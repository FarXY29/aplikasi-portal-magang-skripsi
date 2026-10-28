# SIMAGANG — defect fixes + dead-code removal

## Context

The ask: "check if there's something wrong in the app, or if there's unused files." The app's suite is green, but the audit found **five real defects** — silent date corruption on waiting-list promotion, a promotion path that can half-commit, a per-user cache never invalidated, a public endpoint that mints signed attendance QR codes for anyone holding the kiosk URL, and a non-atomic certificate write path whose failure mode surfaces as an unhandled SQL error rather than a validation message — plus **one dead but load-bearing-looking Form Request** and roughly **20 files of dead code**.

Intended end state: those defects are fixed with regression tests, and provably unreferenced files are deleted with zero behaviour change. Everything else the audit surfaced is deliberately left alone (see the scope line at the end).

## Approach

Steps 1–5 and 6–11 are independent of each other except as noted. Steps 1 and 2 both edit `app/Services/ApplicationStateTransitionService.php` in **disjoint** methods — do them in order.

### 1. Make waiting-list promotion transactional and date-consistent

**Defect.** `promoteNextWaitingCandidate()` (`app/Services/ApplicationStateTransitionService.php:266-301`) does `$nextWaiting->update(['tanggal_mulai' => …, 'tanggal_selesai' => …])` **outside** any transaction, then calls `$this->transition($nextWaiting, ApplicationStatus::Diterima, …)`. `handleTransitionToDiterima()` throws when the position is `tutup` (line ~311) or when quota is full (line ~334). When it throws, the savepoint rolls back the status change but the dates were written **before** that savepoint opened, so the candidate keeps rewritten dates with status still `menunggu` — silent corruption, with no `lockForUpdate` on the candidate so two concurrent completions can pick the same person.

**Change** (all in `app/Services/ApplicationStateTransitionService.php`):

1. In `handleTransitionToDiterima()`, replace the two lines that read the range (currently `$start = $app->tanggal_mulai;` / `$end = $app->tanggal_selesai;`, around line ~312) with a context override so the caller can pass the promoted range:

```php
$start = $context['tanggal_mulai'] ?? $app->tanggal_mulai;
$end = $context['tanggal_selesai'] ?? $app->tanggal_selesai;

$dateOverride = array_intersect_key($context, array_flip(['tanggal_mulai', 'tanggal_selesai']));
```

Both capacity queries below already use `$start`/`$end`; they now check the promoted range instead of the stale waiting-list range. Then change the write at line ~345 from

```php
$app->update(['status' => 'diterima', 'verified_by' => $actorId]);
```

to

```php
$app->update(['status' => 'diterima', 'verified_by' => $actorId] + $dateOverride);
```

2. Replace the body of `promoteNextWaitingCandidate()` with:

```php
public function promoteNextWaitingCandidate(Application $completedApp): ?Application
{
    return DB::transaction(function () use ($completedApp) {
        $nextWaiting = Application::where('internship_position_id', $completedApp->internship_position_id)
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'asc')
            ->lockForUpdate()
            ->first();

        if (! $nextWaiting) {
            return null;
        }

        $durationDays = Carbon::parse($nextWaiting->tanggal_mulai)
            ->diffInDays(Carbon::parse($nextWaiting->tanggal_selesai));

        $newStartDate = Carbon::parse($completedApp->tanggal_selesai)->addDay();
        if ($newStartDate->isPast()) {
            $newStartDate = Carbon::tomorrow();
        }

        return $this->transition($nextWaiting, ApplicationStatus::Diterima, [
            'actor_id' => auth()->id(),
            'reason' => 'Promosi langsung dari daftar tunggu setelah peserta sebelumnya lulus.',
            'tanggal_mulai' => $newStartDate->format('Y-m-d'),
            'tanggal_selesai' => $newStartDate->copy()->addDays($durationDays)->format('Y-m-d'),
        ]);
    });
}
```

The date arithmetic is byte-identical to today's. The only behavioural difference: dates are written **by the same transaction that writes the status**, so a throw leaves the candidate completely untouched.

3. Do not catch the exception at the call site (`handleTransitionToSelesai()`, line ~494). Nested `DB::transaction` is a savepoint, so a promotion failure rolls back only the promotion; the completion itself still commits — which is the current intent.

### 2. Lock the waiting list inside `autoReallocateQuota`

**Defect.** `autoReallocateQuota()` (`app/Services/ApplicationStateTransitionService.php:200-257`) selects `$waitingCandidates` **without** `lockForUpdate()` (line ~216), then loops calling `$this->transition()` per candidate. Each call is a nested savepoint inside the caller's transaction. A throw on candidate 3 of 5 therefore rolls back only that one, leaving promotions 1–2 applied while the caller (`handleTransitionToDitolak` :409, `handleTransitionToDibatalkan` :459, `handleTransitionToDikeluarkan` :538) continues and commits.

**Change** (same file):
1. Add `->lockForUpdate()` to the `$waitingCandidates` query at line ~216.
2. Replace the `foreach` body's opening with a state re-check, so a candidate promoted by a concurrent request is skipped instead of throwing:

```php
foreach ($waitingCandidates as $candidate) {
    $candidate = Application::where('id', $candidate->id)->lockForUpdate()->first();

    if (! $candidate || $candidate->status !== 'menunggu') {
        continue;
    }
    // ... existing conflict-count + instansi-quota checks, unchanged ...
}
```

3. Wrap the `foreach` in `DB::transaction(fn () => …)` returning `$promotedCount`, so a mid-loop throw reverts **every** promotion from this call.

Nothing else changes: both conflict checks keep counting `['diterima', 'pending']`, and `max(0, (int) ($position->kuota ?? 1))` is preserved verbatim.

### 3. Invalidate the per-user major-match cache

**Defect.** `app/Http/Controllers/Public/LowonganController.php:38-40` caches per-user matching position IDs for 300 s under `'user_match_pos:'.major_id`. `InternshipPosition::boot()` (`app/Models/InternshipPosition.php:28-36`) forgets `landing_page_stats_v3` and `open_internship_positions_matching_v1` on save/delete — **not** the per-user keys. A peserta keeps seeing a closed or full position as "matching" for up to 5 minutes. `grep -rn "user_match_pos" app` returns exactly these two files.

**Change** — version the key rather than trying to enumerate it:
1. `app/Models/InternshipPosition.php`, inside both the `saved` and `deleted` closures in `boot()`, add after the existing two `Cache::forget` lines:
   `Cache::put('user_match_pos_version', (int) Cache::get('user_match_pos_version', 0) + 1);`
   (Use `put`, not `increment` — `increment` on a missing key behaves differently across stores; this is exact on every driver. Do not set a TTL.)
2. `app/Http/Controllers/Public/LowonganController.php:38`, change the key build to:
   `$majorCacheKey = 'user_match_pos:'.Cache::get('user_match_pos_version', 0).':'.($user->major_id ?? 'txt_'.md5((string) $user->major));`
   The inner `open_internship_positions_matching_v1` lookup and the `300`-second TTL stay unchanged.

### 4. Remove the unauthenticated endpoint that mints signed attendance QR codes

**Defect.** `routes/web.php:45` registers `kiosk.live-qr` with **no** middleware; `KioskPresensiController::fetchLiveQr()` (`app/Http/Controllers/KioskPresensiController.php:33-44`) returns a freshly signed QR token plus SVG for any `kiosk_token`. `qr_absensi_enabled` treats that token as the presence second factor, so anyone who has seen the kiosk URL can poll it and relay valid codes to participants who are not on site. The only consumer is the kiosk page's own poller.

**Change.**
1. `resources/views/kiosk/presensi.blade.php:199-202` — the poller currently branches on whether `KIOSK_TOKEN` exists. Replace the whole `const FETCH_URL = KIOSK_TOKEN ? … : …;` expression with the single authenticated URL:
   ```js
   const FETCH_URL = "{{ route('dinas.kiosk.live_qr.auth', [], false) }}";
   ```
   Leave `const KIOSK_TOKEN = "{{ $instansi->kiosk_token ?? '' }}";` in place if the player uses it elsewhere — grep `KIOSK_TOKEN` in the view and delete the constant only if line 200 is its sole use.
2. `routes/web.php:45` — delete `Route::get('/kiosk/live-qr/{kiosk_token}', …)->name('kiosk.live_qr');`. **Keep** line 44 (`kiosk.presensi.public`) — the physical kiosk screen must remain publicly viewable.
3. `tests/Feature/KioskPresensiTest.php:63-78` — `test_fetch_live_qr_returns_json_payload()` calls `route('kiosk.live_qr', …)` and will now throw `RouteNotFoundException`. Rewrite it to (a) `$this->actingAs($this->adminUser)->getJson(route('dinas.kiosk.live_qr.auth'))` and assert the same JSON shape, and (b) add `$this->getJson(route('kiosk.presensi.public', $this->instansi->kiosk_token))->assertOk()` — the public page stays; only the token-minting endpoint goes.
4. After deleting the route, run `grep -rn "kiosk.live_qr\b" app routes resources tests`. Any remaining hit other than `dinas.kiosk.live_qr.auth` is a missed reference — fix it before moving on.

### 5. Make certificate issuance atomic and enforce number uniqueness

**Defect.** `CertificateController::store()` (`app/Http/Controllers/CertificateController.php:172-247`) validates `nomor_sertifikat` only against `applications` (line ~183), then performs `$app->update(...)` (:198), `Certificate::updateOrCreate(...)` (:207) and `recordTimeline(...)` (:221) with **no transaction**, then renders the PDF (:242). The `certificates.nomor_sertifikat` column **is** already `unique` (`database/migrations/2026_07_18_135256_create_certificates_table.php:15`), so nothing can silently duplicate — instead a collision produces an unhandled `QueryException` **after** `applications.nomor_sertifikat` was already written, and after no `certificates` row exists. Duplicate lookups then hit `Certificate::where('nomor_sertifikat', $keyword)->first()` (`CertificateController::search()`) ambiguously.

**Change** (`app/Http/Controllers/CertificateController.php`):
1. Add `use Illuminate\Validation\Rule;` to the imports.
2. Extend the rules array at line ~183 with a certificate-table check that ignores this application, so re-saving the same certificate is not rejected by its own row:
   ```php
   'nomor_sertifikat' => [
       'required', 'string', 'max:100',
       'unique:applications,nomor_sertifikat,'.$applicationId,
       Rule::unique('certificates', 'nomor_sertifikat')->ignore($applicationId, 'application_id'),
   ],
   ```
   Keep the existing `unique:applications,…` segment exactly as written; the other messages in `$request->validate([...])` stay untouched.
3. Wrap steps 1–2 of the comment block in a transaction. Concretely, from `$tokenVerifikasi = $app->token_verifikasi ?? Str::random(32);` (:196) through the `$app->recordTimeline(...)` call ending at :221, put the statements inside
   ```php
   $result = DB::transaction(function () use ($app, $request, $tokenVerifikasi, $previousStatus) { … return [$app, $tokenVerifikasi, $previousStatus]; });
   ```
   and start the PDF section (`$data = [...]`) **after** it, unchanged in content. `DB` is already imported in this file — verify with `grep -n "^use Illuminate\\\\Support\\\\Facades\\\\DB;" app/Http/Controllers/CertificateController.php` and add it if absent.
4. Do **not** switch this method to `StoreCertificateRequest`. That class's `authorize()` checks the legacy `role === 'admin_instansi'` column and would reject users who hold the role only via Spatie; the method's existing `$this->authorize('manageActiveIntern', $app)` is the correct gate and stays.

### 6. Delete dead PHP classes and files

Each path below was verified to have zero references outside its own definition. Delete with `git rm`; delete nothing not on this list.

- `app/Http/Middleware/UpdateExpiredInternships.php` — never registered in `app/Http/Kernel.php` (absent from `$middleware`, `web`, and `$middlewareAliases`); its job is done by the scheduled `internship:complete-expired` (`app/Console/Commands/CompleteExpiredInternships.php`).
- `app/Http/Middleware/TrustHosts.php` — only occurrence is the commented line at `app/Http/Kernel.php:42`. Delete that comment line too.
- `app/Providers/BroadcastServiceProvider.php` — only occurrence is the commented line at `config/app.php:171`. Delete that comment line too.
- `routes/channels.php` — only loader was `BroadcastServiceProvider::boot()`; nothing else requires it. Delete it in the same commit as the provider.
- `app/Http/Requests/Certificate/StoreCertificateRequest.php` — never injected anywhere; step 5 handles the endpoint. After deleting it, `rmdir app/Http/Requests/Certificate` if the directory is then empty.

**Do NOT delete** (they look unused but are referenced):
- `app/EnvKit/` — registered at `config/app.php:163-164`; the local debug collector.
- `app/Providers/EventServiceProvider.php`, `AuthServiceProvider.php`, `RouteServiceProvider.php` — all registered at `config/app.php:168-172`.
- Any of the six `app/Console/Commands/*` — auto-discovered by `app/Console/Kernel.php:30`; deleting one removes a real artisan command.

### 7. Delete dead Blade components

All nine have zero references in `resources/views`, `app/`, `routes/`, or `tests/`. Live code uses the `<x-ui.*>` equivalents (`layouts/app.blade.php:185`, `layouts/guest.blade.php:82,87`, `layouts/navigation.blade.php:11,119,151`, `pembimbing/absensi.blade.php:19,64`, `pembimbing/logbook.blade.php:19,58`, `pembimbing/partials/_page-header.blade.php:16`).

```
resources/views/components/theme-toggle.blade.php
resources/views/components/text-input.blade.php
resources/views/components/primary-button.blade.php
resources/views/components/application-logo.blade.php
resources/views/components/modal.blade.php
resources/views/components/input-label.blade.php
resources/views/components/pembimbing/filter-bar.blade.php
resources/views/components/pembimbing/pagination.blade.php
resources/views/components/pembimbing/student-switcher.blade.php
```

After the last one, `git rm` leaves an empty `resources/views/components/pembimbing/` directory — it will simply vanish from git; nothing to do.

**Do NOT delete** these three groups:
- `resources/views/components/secondary-button.blade.php` and `resources/views/components/input-error.blade.php` — asserted at `tests/Feature/UiComponentsTest.php:167` and `:171`.
- `resources/views/components/report/*` (8 files) — asserted at `tests/Feature/UiComponentsTest.php:158,163`. They are only alive via tests, but deleting them means also editing that test; not part of this task.
- `resources/views/components/auth/*` — used by the auth screens.

### 8. Delete repo-root leftovers

- `VIEWS_IMPROVEMENT_PLAN.md` — a completed refactor plan; nothing links to it and the views it describes have since been refactored.
- `scripts/` (10 one-off codemods: `add_buttons.php`, `create_pdfs.php`, `generate.php`, `darkmode-bulk.php`, `darkmode-auth.php`, `darkmode-nav.php`, `darkmode-components.php`, `gen_att.php`, `generate-icons.php`, `optimize-pngs.php`) — referenced by no manifest, CI job, or nginx config. Three resolve `__DIR__.'/resources/views/'` to a path that does not exist, and `darkmode-bulk.php` `str_replace`s over **every** view file in place, so keeping them is a live footgun.

Before deleting `scripts/`, run `git grep -n "scripts/" -- ':!scripts'` and confirm it returns nothing (it returns nothing today; the check protects against a file added since this plan was written).

**Do not touch:** `graphify-out/`, `opendesign/`, `docs/`, `.agent*/`, `.asw/`, `.claude/`, `.gemini/`, `.env*` — all git-ignored local state, not repo content. `AGENTS.md`/`CLAUDE.md`/`GEMINI.md` are also git-ignored but remain in per-developer use.

### 9. Fix the env key that silently disables the configured broadcast driver

**Defect.** `.env.example:22` declares `BROADCAST_CONNECTION=log`; `config/broadcasting.php:18` reads `env('BROADCAST_DRIVER', 'null')`. Any deploy that copies `.env.example` silently runs broadcasting on `null`. (Verified as already-correct and left alone: `config/cache.php:18` reads `CACHE_STORE` with a `CACHE_DRIVER` fallback, and `config/session.php:21` reads `SESSION_DRIVER` — both match `.env.example`.)

**Change** (`config/broadcasting.php:18`):
```php
'default' => env('BROADCAST_CONNECTION', env('BROADCAST_DRIVER', 'null')),
```

### 10. Escape the one query-string value that reaches a `<script>` block

**Defect.** `resources/views/peserta/logbook/index.blade.php:246-252` emits `{!! json_encode([... 'filterStatus' => request('status'), 'filterDate' => request('date'), 'filterMonth' => request('month'), …]) !!}` inside `<script type="application/json">`. `json_encode` does not escape `/`, so `?status=</script><img src=x onerror=…>` closes the block; the CSP (`app/Http/Middleware/SecurityHeaders.php:26-31`) allows `'unsafe-inline'` and `'unsafe-eval'`, so injected script runs. The three values only seed Alpine state (`resources/js/peserta/logbook.js:65-67`), never markup.

**Change** — that one call only. Change `{!! json_encode([` at line 247 to
```php
{!! json_encode([
```
…and close it with the flags on the closing call: replace `]) !!}` at line 252 with `], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}`. `JSON.parse()` returns an identical string, so filter behaviour is unchanged; the literal `</script>` becomes `\u003C/script\u003E`.

Read the other three same-shaped blocks before deciding; change only those containing a `request()`/model value:
- `resources/views/peserta/apply.blade.php:100` — values are `route()`, `csrf_token()`, literals. No change.
- `resources/views/peserta/apply_automatic.blade.php:121` — same. No change unless reading it reveals a `request()` value.
- `resources/views/peserta/dashboard.blade.php:102` — same. No change unless reading it reveals a user/model value.

Leave `resources/views/pembimbing_lapangan/logbook.blade.php:374` (`addslashes($fotoTitle)` inside an inline `onclick`) alone — participant-controlled, low impact; a one-line note belongs in the PR description, not in this change.

### 11. Regression tests

New file `tests/Feature/MaintenanceRegressionTest.php` — `declare(strict_types=1)`, `use DatabaseTransactions;`, seed `RoleAndPermissionSeeder` in `setUp()`, and copy the fixture block from `tests/Unit/ApplicationStateTransitionServiceTest.php:36-64` (instansi `max_total_quota=10`, position `kuota`, an `admin_instansi`, a `peserta`). Add `Cache::flush()` in `setUp()`.

| Test method | Fixture → action → assertion |
|---|---|
| `test_waiting_promotion_recalculates_dates` | position `kuota=1`; app A `diterima` with `tanggal_selesai = '2026-11-30'`; app B `menunggu` with dates in October. `$service->finish($appA)`. Assert `$appB->refresh()`: `status === 'diterima'`, `tanggal_mulai === '2026-12-01'`. |
| `test_failed_promotion_leaves_candidate_untouched` | Same, but before `finish($appA)` run `DB::table('internship_positions')->where('id', $pos->id)->update(['status' => 'tutup']);` (bypasses the model, so A stays accepted). `finish($appA)` throws. Assert `$appB->refresh()->status === 'menunggu'` **and** `tanggal_mulai` equals the original October value. This is the test that fails on today's code. |
| `test_closing_position_invalidates_major_match_cache` | `Cache::put('user_match_pos:0:'.$majorId, collect([$pos->id]), 300);` → `$pos->update(['status' => 'tutup']);` → assert `Cache::get('user_match_pos_version') === 1` and the old key still holds its value (proving the version, not the key, moved). |
| `test_live_qr_requires_authentication` | `$this->getJson('/kiosk/live-qr/'.$this->instansi->kiosk_token)->assertNotFound();` — route is gone (`GET` on an undefined URI is 404). |
| `test_kiosk_pages_still_work` | public page: `$this->get(route('kiosk.presensi.public', $token))->assertOk()`; authed JSON: `$this->actingAs($admin)->getJson(route('dinas.kiosk.live_qr.auth'))->assertOk()->assertJsonStructure(['success','svg','remaining_seconds','interval','expires_at','server_time'])`. |
| `test_certificate_store_rejects_number_used_by_another_application` | Two `selesai` applications with `nilai_rata_rata > 0`; give A a `certificates` row with number `MAG-2026-0001`; POST `dinas.sertifikat.store` for B with the same number; assert a session error on `nomor_sertifikat` and that B has no `certificates` row and `nomor_sertifikat` is still null. |
| `test_certificate_store_rolls_back_when_pdf_rendering_fails` | `Pdf::shouldReceive('loadView')->once()->andThrow(new \RuntimeException('boom'));` POST a fresh number for B; assert the response is 500 and `$appB->refresh()->nomor_sertifikat` is still null and `Certificate::where('application_id', $appB->id)->doesntExist()`. |
| `test_logbook_config_escapes_script_breakout` | `$this->actingAs($peserta)->get(route('peserta.logbook.index', ['status' => '</script><img src=x onerror=alert(1)>']))->assertOk();` then assert the response **does not** contain the raw substring `</script><img` and **does** contain `\u003C/script\u003E`. |

Also update, don't duplicate: `tests/Feature/KioskPresensiTest.php:63-78` per step 4.3.

## Critical files & anchors

| File | Anchor | Why |
|---|---|---|
| `app/Services/ApplicationStateTransitionService.php` | `autoReallocateQuota()` :200-257; `promoteNextWaitingCandidate()` :266-301; `handleTransitionToDiterima()` :304-388 | Steps 1+2. 766 lines — read only these three methods, not the file |
| `app/Http/Controllers/CertificateController.php` | `store()` :172-247 | Step 5; rules at :183, writes at :198-221, PDF at :242 |
| `tests/Feature/KioskPresensiTest.php` | `test_fetch_live_qr_returns_json_payload()` :63-78 | Step 4 breaks this test by design; it must be rewritten in the same commit |
| `resources/views/kiosk/presensi.blade.php` | poller `const FETCH_URL` :199-202 | Step 4; the public page must keep refreshing after the route is removed |
| `resources/views/components/` | root files + `pembimbing/` | Step 7; two root forwarders here are **kept** because tests reference them |

## Verification

Prerequisites: `vendor/` and `node_modules/` installed, `.env.testing` present (MySQL DB `aplikasi_magang_skripsi_testing`). Run everything from the repo root.

1. **Before any change**, capture the baseline: `php artisan test --compact` (expect green) — if it is not green, stop and report; the plan assumes a green starting suite.
2. **After steps 1–3:** `php artisan test --filter=ApplicationStateTransitionServiceTest` (17 existing tests must stay green), then `php artisan test --filter=test_failed_promotion_leaves_candidate_untouched` — this test must **fail** if you temporarily revert step 1, which is how you confirm it actually exercises the bug. Re-apply before continuing.
3. **After step 4:** `php artisan route:list --except-vendor | grep kiosk` → exactly `kiosk.presensi.public`, `dinas.kiosk.presensi`, `dinas.kiosk.live_qr.auth`; no bare `kiosk.live_qr`. `php artisan test --filter=KioskPresensiTest` green.
4. **Manual kiosk check (needs a running server + MySQL):** `php artisan serve`, log in as `admin.instansi1@banjarmasin.go.id` / `password`, open `/dinas/presensi-qr-kiosk`; in devtools the poller must hit `/dinas/presensi-qr-live` every ~30 s with 200s and the QR image must change. Then open `/kiosk/presensi/{kiosk_token}` (token from `/dinas/pengaturan`) logged out: page renders 200; the poller may legitimately redirect to login — confirm no JS console error other than the expected 302/401, and that the initially server-rendered QR is still visible.
5. **After step 5:** `php artisan test --filter=test_certificate_store` — both tests green. Then in the browser as admin instansi, issue a certificate normally and confirm the PDF still streams and `certificates` gets a row.
6. **After step 10:** `php artisan test --filter=test_logbook_config_escapes_script_breakout`; then open `/peserta/logbook?status=semua&date=2026-10-01` as `peserta1@gmail.com` and confirm the date filter is pre-applied (proves the JSON still parses).
7. **After step 9:** `php artisan config:show broadcasting` → `default: log` when `.env` was copied from `.env.example`; `php artisan config:clear` afterwards.
8. **After steps 6–8 (deletions):** `git status --short` lists only intended deletions/modifications; `php artisan test --compact` (full suite, ~140 s) green — this is the proof that no deleted class or Blade component was load-bearing. `npm run build` succeeds (catches any `resources/js` import of a deleted view).
9. **Final gate:** `./vendor/bin/pint --test` and `php artisan test` both clean.

## Assumptions & contingencies

- **Kiosk content is not a secret.** Step 4 assumes it is acceptable that the unauthenticated kiosk page can no longer poll for fresh QR codes. If the on-site kiosk screen must refresh without a logged-in browser (e.g. a dumb TV), the fallback — only if step 4.4's grep finds no other consumer — is to keep `kiosk.live-qr` but chain `->middleware(['signed', 'throttle:30,1'])` and have `showKiosk` generate the signed URL via `URL::temporarySignedRoute('kiosk.live_qr', now()->addHours(12), ['kiosk_token' => $instansi->kiosk_token])`. Do this **only** on that evidence; the preferred end state is the deletion.
- **Cache version key (step 3)** assumes `Cache::put`/`Cache::get` on the configured store. If the store is `array` in tests (it is, per `phpunit.xml`), the version key resets per test — hence `Cache::flush()` in `setUp()` and asserting `=== 1`, not `>= 1`.
- **Scope line.** The audit also found unbounded report queries (`ReportService`, `AdminInstansi/ReportController`), `dispatchSync` of the DB dump inside a web request, missing admin lockout guards, an unpaginated `AdminInstansi/LowonganController::index()`, and a `'unsafe-inline'` CSP that makes inline `<script>` risky. Those are design/performance work with several defensible fixes, not single-answer defects, and are **not** in this plan.
