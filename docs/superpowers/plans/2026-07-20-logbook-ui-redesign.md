# Logbook UI/UX Redesign Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Complete revamp of the logbook submission and history index page (`resources/views/peserta/logbook/index.blade.php`) to deliver a modern, premium, and highly responsive user experience.

**Architecture:** A unified responsive grid dashboard. The left column features a compact sticky form with visual GPS indicators, file drag-and-drop previews, and validation alerts. The right column displays an interactive timeline of status-bordered logbook cards with a full-screen image lightbox gallery.

**Tech Stack:** HTML5, CSS (Vanilla), Tailwind CSS, Alpine.js, Blade template.

## Global Constraints
- Do not introduce external styling libraries; use the existing `resources/css/peserta.css` and Tailwind utilities.
- Maintain fallback checks for local testing (localhost mock GPS location).
- Ensure all tests in `RolePesertaTest` and `LogbookStatusTest` pass cleanly after changes.

---

### Task 1: Form UI Revamp (Left Column)

**Files:**
- Modify: `resources/views/peserta/logbook/index.blade.php`

**Interfaces:**
- Consumes: Blade variables `$activeApp` and `$logs`

- [ ] **Step 1: Implement form layout updates**
  Update the left column form structure to make it compact and visually aligned. Wrap elements in high-contrast card blocks.
- [ ] **Step 2: Add textarea character counter**
  Add a real-time character counter below the "Deskripsi Kegiatan" textarea with a limit of 2000 characters. Show count in green, transitioning to red when approaching the limit.
- [ ] **Step 3: Revamp drag-and-drop file upload zone**
  Improve the file upload container to display a large styled dashed border, upload icon, and clear upload guide text. When a file is chosen, display a premium preview overlay with a "Remove" button.
- [ ] **Step 4: Design premium GPS verification status display**
  Render a detailed card showing the name of their Dinas (`$activeApp->position->instansi->nama_dinas`) and the max radius (`$activeApp->position->instansi->radius_absen`). Use a pulsing green dot for local/verified status and a yellow pulse while locking.
- [ ] **Step 5: Verify form UI changes**
  Open the logbook page in a browser, confirm that the textarea character count works, that mock GPS status displays "Lokasi Terkunci (Mock Local)", and that selecting an image displays a thumbnail preview.

---

### Task 2: Logbook Timeline & Lightbox Revamp (Right Column)

**Files:**
- Modify: `resources/views/peserta/logbook/index.blade.php`

**Interfaces:**
- Consumes: Blade variable `$logs`

- [ ] **Step 1: Redesign logbook cards**
  Style cards with thick color-coded left borders based on status:
  - `pending` -> yellow border, light yellow gradient background.
  - `disetujui` -> green border, light green gradient background.
  - `revisi` -> red border, light red gradient background.
- [ ] **Step 2: Format metadata and timestamps**
  Update cards to display standard metadata icons (calendar, map marker, clock) and formatted localized timestamps (e.g., `d M Y`).
- [ ] **Step 3: Enhance supervisor comments section**
  Format supervisor revisions (`komentar_pembimbing_lapangan`) in a distinct, styled alert bubble with a quote icon, only visible if the card status is `revisi`.
- [ ] **Step 4: Add Alpine.js Image Lightbox**
  Create an Alpine.js modal block at the bottom of the page that listens to the `galleryOpen` state and renders the clicked documentation image full-screen with slide-in animation.
- [ ] **Step 5: Verify history timeline and lightbox**
  Check that cards render beautifully with color-coded status badges. Click on a proof image thumbnail and verify that the lightbox displays full-screen and can be closed.

---

### Task 3: Mobile Layout and Transitions

**Files:**
- Modify: `resources/views/peserta/logbook/index.blade.php`

**Interfaces:**
- Consumes: Tailwind viewport breakpoints

- [ ] **Step 1: Make layouts responsive**
  Ensure the grid configuration switches cleanly to `grid-cols-1` on screens under `xl` and uses appropriate sticky side padding.
- [ ] **Step 2: Add hover and micro-animations**
  Apply Tailwind interactive transitions (`transition-all duration-200 active:scale-95`) to all clickable filters and buttons.
- [ ] **Step 3: Run regression tests**
  Execute the test suite to ensure that form inputs and validation flow remains fully functional.
  Run: `php artisan test`
  Expected: All 56 feature tests pass successfully.
