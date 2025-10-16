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
    Schema::create('lessons', function (Illuminate\Database\Schema\Blueprint $t) {
        $t->id();
        $t->date('date');
        $t->foreignId('class_room_id')->constrained()->cascadeOnDelete();
        $t->foreignId('subject_id')->nullable()->constrained()->nullOnDelete();
        $t->foreignId('teacher_id')->constrained()->cascadeOnDelete();
        $t->time('start_time')->nullable();
        $t->time('end_time')->nullable();
        $t->timestamps();

        $t->unique(['date','class_room_id','teacher_id']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
