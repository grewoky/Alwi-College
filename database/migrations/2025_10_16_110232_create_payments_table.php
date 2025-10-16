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
    Schema::create('payments', function (Illuminate\Database\Schema\Blueprint $t) {
        $t->id();
        $t->foreignId('student_id')->constrained()->cascadeOnDelete();
        $t->string('month_period')->nullable(); // opsional: YYYY-MM
        $t->integer('amount')->nullable();      // opsional: nominal
        $t->string('proof_path');               // path file bukti
        $t->enum('status', ['pending','approved','rejected'])->default('pending');
        $t->string('note')->nullable();         // catatan admin
        $t->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
