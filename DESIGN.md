# Design Direction: SIMAGANG

> **Honest Warning:** This direction was authored by the AI agent based on the repository context (SIMAGANG - Kota Banjarmasin). Agent-generated direction risks leaning toward conventional AI defaults and visual monotony. Refine with human brand guidance whenever available.

## 1. Product & Purpose
- **Product:** SIMAGANG (Sistem Informasi Manajemen Magang Pemerintah Kota Banjarmasin)
- **Role:** Official municipal administrative portal managing the entire internship lifecycle (application, institutional verification, dynamic QR attendance, logbook validation, evaluation, and certificate generation).

## 2. Audience & Environment
- **Primary Users:**
  1. *Peserta Magang:* SMK students and university undergraduates operating predominantly on mobile devices (attendance/logbook) and desktops (registration/reports).
  2. *Pembimbing Lapangan & Sekolah:* Mentors reviewing logs and evaluating student competencies.
  3. *Admin Instansi & Admin Kota:* Municipal civil servants managing quotas, disputes, audit trails, and official document issuance.
- **Context:** Formal government digital service requiring high trust, reliable data density, and accessibility compliance.

## 3. Visual Dials
- **Dial:** ENERGY 1 / RHYTHM 2 / MOTION 1
  - **ENERGY 1 (Calm):** Civic authority and clarity over flashiness. No decorative gradients, mesh orbs, or trend-stacking.
  - **RHYTHM 2 (Structured with Functional Breaks):** Predictable administrative layout, with distinct section treatments for data tables, metric summaries, and action panels.
  - **MOTION 1 (Subtle & Functional):** Hover states, discrete modal transitions, and focus rings only. No scroll-reveal animations or bouncing elements.

## 4. Color Palette
- **Core Neutral:** Slate (`slate-900` foreground / `slate-50` background in light mode; `slate-100` foreground / `slate-900` background in dark mode).
- **Brand Primary:** Teal (`teal-700` primary brand action, `teal-800` active, `teal-50` tint). Reflects clean municipal civic tone without generic AI blue-purple gradients.
- **Accent:** Amber (`amber-600`) reserved exclusively for pending statuses, alerts, or dispute warnings.
- **Semantic Feedback:** Emerald for approved/present, Rose for rejected/absent, Sky for general informational badges.
- **Rule:** Maximum 2 core colors + 1 functional accent per page view.

## 5. Typography
- **Typeface:** `Figtree`, sans-serif (configured in Tailwind).
- **Scale:** Standard administrative hierarchy (`text-2xl` page title, `text-lg` section header, `text-sm` body/table data, `text-xs` secondary metadata).
- **Tabular Data:** Tabular figures (`font-mono` / `tabular-nums`) only for numerical metrics, timestamps, and registration codes (`nomor_registrasi`). No decorative uppercase letter-spacing.

## 6. Layout & Components
- **Hierarchy:** One clear focal action or summary card per viewport.
- **Borders & Elevation:** Subtle, crisp borders (`border-slate-200` / `dark:border-slate-800`) over heavy drop shadows. Radius standardized to `rounded-lg` (8px) for cards and inputs, `rounded-md` (6px) for buttons.
- **Forms & Tables:** Dense, scannable tabular views with alternating row legibility and explicit empty/loading/error states.
- **Responsiveness:** Mobile-first attendance verification with minimum 44px tap targets.
