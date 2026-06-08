<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel Applications (Data Magang Peserta)
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            
            $table->date('date'); // Tanggal Absen
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa'])->default('hadir');
            
            $table->time('clock_in')->nullable();  // Jam Masuk
            $table->time('clock_out')->nullable(); // Jam Pulang
            
            $table->text('description')->nullable(); // Alasan Izin/Sakit atau Catatan Hadir
            $table->string('proof_file')->nullable(); // File Bukti Surat Dokter/Izin
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};