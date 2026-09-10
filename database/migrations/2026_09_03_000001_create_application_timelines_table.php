<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('application_timelines')) {
            Schema::create('application_timelines', function (Blueprint $table) {
                $table->id();
                $table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
                $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('event'); // submitted, verified, accepted, rejected, waiting_list, promoted, cancelled, started, completed, expelled, certificate_issued
                $table->string('old_status')->nullable();
                $table->string('new_status')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index(['application_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('application_timelines');
    }
};
