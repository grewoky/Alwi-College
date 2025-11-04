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
        Schema::create('deleted_lessons_log', function (Blueprint $table) {
            $table->id();
            
            // Data dari lesson yang dihapus
            $table->date('lesson_date');
            $table->unsignedBigInteger('classroom_id');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            
            // Info penghapusan
            $table->timestamp('deleted_at')->useCurrent();
            $table->string('deleted_by')->default('system'); // 'system' atau user_id yang menghapus manual
            $table->text('deletion_reason')->nullable(); // Alasan penghapusan
            
            $table->timestamps();
            
            // Index untuk query cepat
            $table->index('lesson_date');
            $table->index('deleted_at');
            $table->index('classroom_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deleted_lessons_log');
    }
};
