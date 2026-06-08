<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('instansi_id');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['instansi_id']);
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['date']);
        });
    }
};
