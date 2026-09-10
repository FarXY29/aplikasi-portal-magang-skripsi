<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('attendance_disputes')) {
            Schema::create('attendance_disputes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('attendance_id')->constrained('attendances')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->text('reason');
                $table->string('evidence_file')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->enum('proposed_status', ['hadir', 'izin', 'sakit'])->nullable();
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('reviewed_at')->nullable();
                $table->text('reviewer_notes')->nullable();
                $table->timestamps();

                $table->index(['attendance_id', 'status']);
                $table->index(['user_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_disputes');
    }
};
