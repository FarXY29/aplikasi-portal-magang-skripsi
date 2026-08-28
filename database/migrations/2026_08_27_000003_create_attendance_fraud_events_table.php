<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Event fraud individual per attempt — admin dapat mengetahui MENGAPA
     * sebuah absensi dianggap suspicious (contoh: IMPOSSIBLE_TRAVEL beserta
     * distance/elapsed/required_speed).
     */
    public function up(): void
    {
        Schema::create('attendance_fraud_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_attempt_id')->constrained('attendance_attempts')->cascadeOnDelete();

            $table->string('code', 50); // contoh: IMPOSSIBLE_TRAVEL
            $table->string('severity', 10); // low | medium | high | critical
            $table->unsignedTinyInteger('score_delta');
            $table->json('metadata')->nullable();

            $table->timestamp('created_at')->nullable();

            $table->index(['attendance_attempt_id', 'code']);
            $table->index(['code', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_fraud_events');
    }
};
