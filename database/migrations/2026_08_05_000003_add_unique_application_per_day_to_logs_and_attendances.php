<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("DELETE a1 FROM daily_logs a1 INNER JOIN daily_logs a2
            WHERE a1.id > a2.id AND a1.application_id = a2.application_id AND a1.tanggal = a2.tanggal");

        DB::statement("DELETE a1 FROM attendances a1 INNER JOIN attendances a2
            WHERE a1.id > a2.id AND a1.application_id = a2.application_id AND a1.date = a2.date");

        Schema::table('daily_logs', function (Blueprint $table) {
            $table->unique(['application_id', 'tanggal']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->unique(['application_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::table('daily_logs', function (Blueprint $table) {
            $table->dropUnique(['application_id', 'tanggal']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique(['application_id', 'date']);
        });
    }
};