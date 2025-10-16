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
    Schema::create('attendances', function (Illuminate\Database\Schema\Blueprint $t) {
        $t->id();
        $t->foreignId('lesson_id')->constrained()->cascadeOnDelete();
        $t->foreignId('student_id')->constrained()->cascadeOnDelete();
        $t->enum('status', ['present','alpha']);
        $t->foreignId('marked_by')->constrained('users')->cascadeOnDelete();
        $t->timestamp('marked_at')->nullable();
        $t->timestamps();

        $t->unique(['lesson_id','student_id']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
