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
        if (! Schema::hasTable('major_categories')) {
            Schema::create('major_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('code', 50)->unique();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('majors')) {
            Schema::create('majors', function (Blueprint $table) {
                $table->id();
                $table->foreignId('major_category_id')->constrained('major_categories')->cascadeOnDelete();
                $table->string('name');
                $table->enum('degree_level', ['SMK', 'D3', 'D4', 'S1', 'S2'])->default('S1');
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->unique(['major_category_id', 'name', 'degree_level'], 'uq_majors_cat_name_degree');
            });
        }

        if (! Schema::hasColumn('users', 'major_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('major_id')->nullable()->after('major')->constrained('majors')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('internship_positions', 'required_major_category_id')) {
            Schema::table('internship_positions', function (Blueprint $table) {
                $table->foreignId('required_major_category_id')->nullable()->after('deskripsi')->constrained('major_categories')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('internship_positions', 'required_major_category_id')) {
            Schema::table('internship_positions', function (Blueprint $table) {
                $table->dropForeign(['required_major_category_id']);
                $table->dropColumn('required_major_category_id');
            });
        }

        if (Schema::hasColumn('users', 'major_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['major_id']);
                $table->dropColumn('major_id');
            });
        }

        Schema::dropIfExists('majors');
        Schema::dropIfExists('major_categories');
    }
};
