<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('nomor_registrasi', 50)->nullable()->unique()->after('id');
        });

        // Backfill existing applications with unique registration number
        $apps = DB::table('applications')->whereNull('nomor_registrasi')->get();
        foreach ($apps as $app) {
            $date = $app->created_at ? date('Ym', strtotime($app->created_at)) : date('Ym');
            $regNumber = sprintf('REG-%s-%05d', $date, $app->id);
            DB::table('applications')->where('id', $app->id)->update([
                'nomor_registrasi' => $regNumber,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('nomor_registrasi');
        });
    }
};
