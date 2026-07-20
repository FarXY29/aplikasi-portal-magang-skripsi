# Spec: Logbook UI/UX Redesign for Peserta

## Goal
Improve the user interface (UI) and user experience (UX) of the logbook submission and history page for participants (`resources/views/peserta/logbook/index.blade.php`). The goal is to provide a premium, modern dashboard aesthetic with seamless mobile interactions, better coordinate verification feedback, and clear validation status reporting.

## Proposed Changes

### Component 1: Jurnal Submission Form (Left Column)
- **Compact Form Elements**: Optimize spacings to ensure the entire form fits comfortably in standard screen heights.
- **Character Counter**: Add a dynamic character counter below the "Deskripsi Kegiatan" textarea (limit 2000 chars) to prevent silent truncated inputs.
- **Premium Drag-and-Drop Upload Zone**:
  - Implement a clean file input layout using CSS patterns.
  - When a file is chosen, display a clean, full-width thumbnail preview with an overlays delete ("Remove image") button.
- **Modern GPS Verification Status**:
  - Update the coordinates alert box to display a green/teal pulse indicator when locked.
  - Clearly print the name of the Dinas/office the student is assigned to and the max radius allowed.
  - Gracefully handle dev environments (mock coordinates locked on localhost).

### Component 2: Jurnal Timeline/History List (Right Column)
- **Status Filter Pills**: Make the active status pill stand out with a premium drop shadow and distinct background color.
- **Redesigned Logbook Cards**:
  - Color-coded borders matching the status (`pending` -> yellow, `disetujui` -> green, `revisi` -> red).
  - Add icons to card headers for card actions (view, edit, delete).
  - Properly handle validation comments: if the status is `revisi`, show the supervisor's remark in a soft red-bordered comment bubble.
  - Clean date formatting using localized translation (e.g. `d F Y`).
- **Alpine.js Lightbox Gallery**:
  - Support full-screen popup of uploaded proof photographs using a clean modal overlay.

### Component 3: Mobile Responsiveness
- Add media queries to stack the dashboard vertically on screens under `1280px` (`xl`).
- Make touch targets larger and add hover micro-animations (`active:scale-95 transition`).

## Verification Plan
1. **Manual Verification**:
   - Access the logbook page as a `peserta` user on a local server.
   - Verify that the GPS lock automatically succeeds (Mock Local).
   - Test submitting a new entry with/without a photo.
   - Test enlarging the photo via lightbox.
   - Verify error messages display correctly when validation fails.
   - Check mobile layout emulation in dev tools.
