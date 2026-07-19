<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Buat Daftar Permissions
        $permissions = [
            // Master Data & RBAC (Admin Kota)
            'manage-universities',
            'manage-schools',
            'manage-instansi',
            'manage-users',
            'manage-rbac',
            'manage-settings',
            'view-audit-log',

            // Lowongan & Kuota
            'create-lowongan',
            'edit-lowongan',
            'delete-lowongan',
            'view-lowongan',

            // Lamaran & Seleksi
            'apply-magang',
            'cancel-lamaran',
            'verify-lamaran',
            'shortlist-lamaran',
            'approve-lamaran',
            'reject-lamaran',

            // Presensi & Logbook
            'checkin-attendance',
            'checkout-attendance',
            'verify-attendance',
            'create-logbook',
            'batch-approve-logbook',

            // Penilaian & Sertifikat
            'input-grading',
            'view-grading',
            'generate-certificate',
            'verify-certificate',

            // Laporan & Analitik
            'view-executive-report',
            'export-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 2. Buat Role dan Assign Permissions

        // Role: admin_kota (Super Admin)
        $roleAdminKota = Role::updateOrCreate(['name' => 'admin_kota', 'guard_name' => 'web']);
        $roleAdminKota->syncPermissions(Permission::all());

        // Role: admin_instansi (Admin Dinas/Badan/Bagian)
        $roleAdminInstansi = Role::updateOrCreate(['name' => 'admin_instansi', 'guard_name' => 'web']);
        $roleAdminInstansi->syncPermissions([
            'create-lowongan', 'edit-lowongan', 'delete-lowongan', 'view-lowongan',
            'verify-lamaran', 'shortlist-lamaran', 'approve-lamaran', 'reject-lamaran',
            'verify-attendance', 'batch-approve-logbook', 'view-grading', 'generate-certificate', 'verify-certificate',
            'view-executive-report', 'export-reports',
        ]);

        // Role: pembimbing_lapangan (Mentor di Kantor Instansi)
        $rolePembimbingLapangan = Role::updateOrCreate(['name' => 'pembimbing_lapangan', 'guard_name' => 'web']);
        $rolePembimbingLapangan->syncPermissions([
            'view-lowongan',
            'verify-attendance', 'batch-approve-logbook',
            'input-grading', 'view-grading', 'verify-certificate',
        ]);

        // Role: pembimbing (Pembimbing Akademik/Dosen/Guru Sekolah)
        $rolePembimbingAkademik = Role::updateOrCreate(['name' => 'pembimbing', 'guard_name' => 'web']);
        $rolePembimbingAkademik->syncPermissions([
            'view-lowongan',
            'view-grading', 'verify-certificate',
        ]);

        // Role: peserta (Mahasiswa / Siswa Magang)
        $rolePeserta = Role::updateOrCreate(['name' => 'peserta', 'guard_name' => 'web']);
        $rolePeserta->syncPermissions([
            'view-lowongan', 'apply-magang', 'cancel-lamaran',
            'checkin-attendance', 'checkout-attendance',
            'create-logbook', 'view-grading', 'verify-certificate',
        ]);
    }
}
