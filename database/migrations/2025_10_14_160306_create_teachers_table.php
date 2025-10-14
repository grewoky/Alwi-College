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
    Schema::create('teachers', function (Blueprint $t) {
        $t->id();
        $t->foreignId('user_id')->constrained()->cascadeOnDelete();
        $t->string('employee_code')->nullable();
        $t->timestamps();

        $t->unique('user_id'); // 1 user = 1 teacher
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
