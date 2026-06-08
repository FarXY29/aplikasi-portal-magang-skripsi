<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('applications', function (Blueprint $table) {
            // Menambahkan 10 Kriteria Penilaian
            $table->integer('nilai_sikap')->nullable();          // 1. Sikap/Sopan santun
            // $table->integer('nilai_disiplin')->nullable();       // 2. Kedisiplinan
            $table->integer('nilai_kesungguhan')->nullable();    // 3. Kesungguhan
            $table->integer('nilai_mandiri')->nullable();        // 4. Kemampuan Bekerja Mandiri
            $table->integer('nilai_kerjasama')->nullable();      // 5. Kemampuan Bekerja Sama
            $table->integer('nilai_ketelitian')->nullable();     // 6. Ketelitian
            $table->integer('nilai_pendapat')->nullable();       // 7. Kemampuan Mengemukakan pendapat
            $table->integer('nilai_serap_hal_baru')->nullable(); // 8. Kemampuan Menyerap Hal Baru
            $table->integer('nilai_inisiatif')->nullable();      // 9. Inisiatif dan Kreatifitas
            $table->integer('nilai_kepuasan')->nullable();       // 10. Kepuasan Pemberi Kerja Praktek
            
            $table->decimal('nilai_rata_rata', 5, 2)->nullable(); // Untuk menyimpan hasil akhir
        });
    }

    public function down() {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'nilai_sikap', 'nilai_disiplin', 'nilai_kesungguhan', 'nilai_mandiri', 
                'nilai_kerjasama', 'nilai_ketelitian', 'nilai_pendapat', 
                'nilai_serap_hal_baru', 'nilai_inisiatif', 'nilai_kepuasan', 'nilai_rata_rata'
            ]);
        });
    }
};