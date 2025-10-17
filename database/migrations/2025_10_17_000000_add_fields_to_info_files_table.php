<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add school, class, subject, and material fields to info_files table
     */
    public function up(): void
    {
        Schema::table('info_files', function (Blueprint $table) {
            // Tambah column jika belum ada
            if (!Schema::hasColumn('info_files', 'school')) {
                $table->string('school')->nullable()->after('student_id');
            }
            if (!Schema::hasColumn('info_files', 'class_name')) {
                $table->string('class_name')->nullable()->after('school');
            }
            if (!Schema::hasColumn('info_files', 'subject')) {
                $table->string('subject')->nullable()->after('class_name');
            }
            if (!Schema::hasColumn('info_files', 'material')) {
                $table->string('material')->nullable()->after('subject');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('info_files', function (Blueprint $table) {
            $table->dropColumn(['school', 'class_name', 'subject', 'material']);
        });
    }
};
