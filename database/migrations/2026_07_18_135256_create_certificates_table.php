<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            
            $table->string('nomor_sertifikat')->unique();
            $table->string('token_verifikasi')->unique(); // Untuk scan QR
            
            $table->string('qr_code_path')->nullable(); // Lokasi penyimpanan gambar QR
            $table->string('signer_name')->nullable(); // Nama Pejabat Penandatangan
            $table->text('signature_mock')->nullable(); // Dummy TTE / Mock Signature Hash
            
            $table->timestamp('published_at')->nullable(); // Tanggal Diterbitkan
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
