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
        Schema::create('teacher_trips', function (Blueprint $t) {
            $t->id();
            $t->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $t->date('date');
            $t->unsignedTinyInteger('teaching_sessions')->default(0); // +1 per absensi, max 3/hari
            $t->boolean('sunday_bonus')->default(false);              // bonus Minggu
            $t->timestamps();

            $t->unique(['teacher_id','date']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_trips');
    }
};
