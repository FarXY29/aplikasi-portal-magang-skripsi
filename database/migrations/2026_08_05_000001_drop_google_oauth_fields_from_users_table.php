<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('users', 'google_id')) {
                $columns[] = 'google_id';
            }
            if (Schema::hasColumn('users', 'sso_id')) {
                $columns[] = 'sso_id';
            }
            if (Schema::hasColumn('users', 'sso_provider')) {
                $columns[] = 'sso_provider';
            }
            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->after('email');
            }
            if (! Schema::hasColumn('users', 'sso_provider')) {
                $table->string('sso_provider', 50)->nullable()->after('google_id');
            }
            if (! Schema::hasColumn('users', 'sso_id')) {
                $table->string('sso_id', 100)->nullable()->index()->after('sso_provider');
            }
        });
    }
};
