<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('application_timelines') && !Schema::hasColumn('application_timelines', 'updated_at')) {
            Schema::table('application_timelines', function (Blueprint $table) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('application_timelines') && Schema::hasColumn('application_timelines', 'updated_at')) {
            Schema::table('application_timelines', function (Blueprint $table) {
                $table->dropColumn('updated_at');
            });
        }
    }
};
