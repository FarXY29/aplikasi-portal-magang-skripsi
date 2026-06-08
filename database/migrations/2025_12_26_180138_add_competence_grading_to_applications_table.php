<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('applications', function (Blueprint $table) {
            // Menambah kategori nilai sesuai kebutuhan report
            $table->integer('nilai_teknis')->nullable()->after('pembimbing_lapangan_id');
            $table->integer('nilai_disiplin')->nullable()->after('nilai_teknis');
            $table->integer('nilai_perilaku')->nullable()->after('nilai_disiplin');
        });
    }

    public function down() {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['nilai_teknis', 'nilai_disiplin', 'nilai_perilaku']);
        });
    }
};