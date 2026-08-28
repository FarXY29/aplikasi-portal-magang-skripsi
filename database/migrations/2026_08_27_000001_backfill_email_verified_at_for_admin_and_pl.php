<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            // Update berdasarkan kolom role legacy
            DB::table('users')
                ->whereIn('role', ['admin_kota', 'admin_instansi', 'pembimbing_lapangan'])
                ->whereNull('email_verified_at')
                ->update(['email_verified_at' => now()]);

            // Update berdasarkan relasi Spatie RBAC jika tabel model_has_roles & roles tersedia
            if (Schema::hasTable('roles') && Schema::hasTable('model_has_roles')) {
                $exemptRoleIds = DB::table('roles')
                    ->whereIn('name', ['admin_kota', 'admin_instansi', 'pembimbing_lapangan'])
                    ->pluck('id');

                if ($exemptRoleIds->isNotEmpty()) {
                    $userIdsWithSpatieRole = DB::table('model_has_roles')
                        ->where('model_type', \App\Models\User::class)
                        ->whereIn('role_id', $exemptRoleIds)
                        ->pluck('model_id');

                    if ($userIdsWithSpatieRole->isNotEmpty()) {
                        DB::table('users')
                            ->whereIn('id', $userIdsWithSpatieRole)
                            ->whereNull('email_verified_at')
                            ->update(['email_verified_at' => now()]);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Data update backfill tidak perlu dibalikkan secara destruktif
    }
};

