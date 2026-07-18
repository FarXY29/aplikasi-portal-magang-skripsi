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
        Schema::table('attendances', function (Blueprint $table) {
            // Kolom Geotagging & Network
            $table->decimal('latitude_in', 10, 8)->nullable()->after('clock_in');
            $table->decimal('longitude_in', 11, 8)->nullable()->after('latitude_in');
            
            $table->decimal('latitude_out', 10, 8)->nullable()->after('clock_out');
            $table->decimal('longitude_out', 11, 8)->nullable()->after('latitude_out');

            $table->string('ip_address')->nullable()->after('longitude_out');
            $table->string('device_info')->nullable()->after('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'latitude_in',
                'longitude_in',
                'latitude_out',
                'longitude_out',
                'ip_address',
                'device_info'
            ]);
        });
    }
};
