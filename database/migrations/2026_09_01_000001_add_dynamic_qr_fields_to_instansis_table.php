<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('instansis', function (Blueprint $table) {
            $table->boolean('qr_absensi_enabled')->default(false)->after('radius_absen');
            $table->string('kiosk_token', 64)->nullable()->unique()->after('qr_absensi_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instansis', function (Blueprint $table) {
            $table->dropColumn(['qr_absensi_enabled', 'kiosk_token']);
        });
    }
};

