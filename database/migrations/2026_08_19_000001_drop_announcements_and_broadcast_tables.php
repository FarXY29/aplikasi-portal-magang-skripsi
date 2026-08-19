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
        Schema::dropIfExists('broadcast_logs');
        Schema::dropIfExists('announcements');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('announcements')) {
            Schema::create('announcements', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->longText('content');
                $table->enum('type', ['info', 'warning', 'urgent', 'event'])->default('info');
                $table->enum('target_audience', ['all', 'peserta', 'admin_instansi', 'pembimbing'])->default('all');
                $table->string('banner_image')->nullable();
                $table->boolean('is_published')->default(true);
                $table->timestamp('published_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->boolean('send_email_broadcast')->default(false);
                $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
                $table->timestamps();

                $table->index(['target_audience', 'is_published', 'published_at', 'expires_at'], 'idx_announcements_audience_pub');
            });
        }

        if (! Schema::hasTable('broadcast_logs')) {
            Schema::create('broadcast_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('announcement_id')->constrained('announcements')->cascadeOnDelete();
                $table->string('recipient_role', 50)->default('all');
                $table->unsignedInteger('total_recipients')->default(0);
                $table->enum('status', ['queued', 'processing', 'completed', 'failed'])->default('queued');
                $table->timestamp('sent_at')->nullable();
                $table->timestamps();
            });
        }
    }
};
