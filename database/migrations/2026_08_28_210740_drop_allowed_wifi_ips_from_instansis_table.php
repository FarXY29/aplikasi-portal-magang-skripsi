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
        if (Schema::hasColumn('instansis', 'allowed_wifi_ips')) {
            Schema::table('instansis', function (Blueprint $table) {
                $table->dropColumn('allowed_wifi_ips');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('instansis', 'allowed_wifi_ips')) {
            Schema::table('instansis', function (Blueprint $table) {
                $table->text('allowed_wifi_ips')->nullable();
            });
        }
    }
};
