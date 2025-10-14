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
    Schema::create('info_files', function (Illuminate\Database\Schema\Blueprint $t) {
        $t->id();
        $t->foreignId('student_id')->constrained()->cascadeOnDelete();
        $t->string('title');
        $t->string('file_path');
        $t->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_files');
    }
};
