# Design Spec - Seeder Expansion for Scale Testing

This document describes the design for expanding the database seeder to generate hundreds of participant accounts, dozens of field supervisors, dozens of academic supervisors, and dozens of instansi.

## Goals
- Scale up dummy data to match real-world load testing.
- Maintain data integrity, model relationships, and Spatie roles assignment.
- Keep the seeder easy to read, maintain, and execute.

## Design Details
1. **Instansi Expansion**: 
   - Increase the Dinas list in `DatabaseSeeder.php` from 15 to 30 Dinas names (specific to Banjarmasin).
   - This will automatically generate 30 Admin Instansi accounts.
2. **Pembimbing Lapangan**:
   - Keep 2-3 Pembimbing Lapangan per Instansi, yielding ~60-90 total Pembimbing Lapangan.
3. **Pembimbing Akademik/Sekolah**:
   - Increase the count from 10 to 30.
4. **Peserta & Applications**:
   - Increase the count from 60 to 250.
   - For each Peserta, generate 1-2 Applications randomly assigned to the created positions with randomized statuses (`pending`, `diterima`, `ditolak`, `selesai`, `menunggu`).

## Verification
- Run `php artisan db:seed` to verify successful seeding without integrity constraint errors.
