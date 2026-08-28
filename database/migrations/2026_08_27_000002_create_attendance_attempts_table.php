<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Audit SETIAP attempt absensi (clock-in/clock-out), termasuk yang ditolak.
     * Sumber bukti investigasi fraud — tidak menggantikan tabel attendances.
     */
    public function up(): void
    {
        Schema::create('attendance_attempts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('application_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('instance_id')->nullable()->constrained('instansis')->nullOnDelete();
            // Attendance yang dihasilkan attempt sukses (null bila ditolak)
            $table->foreignId('attendance_id')->nullable()->constrained('attendances')->nullOnDelete();

            $table->string('attendance_type', 10); // clock_in | clock_out
            $table->uuid('attempt_uuid')->unique();
            $table->string('idempotency_key', 64)->nullable()->unique();

            // Bukti lokasi (semua berasal dari client — UNTRUSTED)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('accuracy', 10, 2)->nullable();
            $table->decimal('altitude', 10, 2)->nullable();
            $table->decimal('speed', 10, 2)->nullable();
            $table->decimal('heading', 6, 2)->nullable();

            // Waktu: client hanya signal, server yang authoritative
            $table->unsignedBigInteger('client_timestamp')->nullable(); // epoch ms
            $table->timestamp('server_received_at');

            // Analisis geofence
            $table->decimal('distance_to_instance', 10, 2)->nullable();
            $table->decimal('location_margin', 10, 2)->nullable();

            // Bukti request/network
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('session_hash', 64)->nullable();

            // Hasil fraud engine
            $table->unsignedTinyInteger('risk_score')->nullable();
            $table->string('fraud_status', 20)->nullable();
            $table->json('risk_indicators')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['application_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_attempts');
    }
};
