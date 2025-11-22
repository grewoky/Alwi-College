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
        Schema::table('teacher_trips', function (Blueprint $table) {
            if (! Schema::hasColumn('teacher_trips', 'bonus')) {
                $table->unsignedInteger('bonus')->default(0)->after('teaching_sessions');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_trips', function (Blueprint $table) {
            if (Schema::hasColumn('teacher_trips', 'bonus')) {
                $table->dropColumn('bonus');
            }
        });
    }
};
