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
        Schema::table('applications', function (Blueprint $table) {
            if (Schema::hasColumn('applications', 'nama_pembimbing_sekolah')) {
                $table->dropColumn('nama_pembimbing_sekolah');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('nama_pembimbing_sekolah')->nullable()->after('asal_instansi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'nama_pembimbing_sekolah')) {
                $table->dropColumn('nama_pembimbing_sekolah');
            }
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->string('nama_pembimbing_sekolah')->nullable();
        });
    }
};
