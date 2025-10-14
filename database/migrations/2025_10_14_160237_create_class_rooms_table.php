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
    Schema::create('class_rooms', function (Blueprint $t) {
        $t->id();
        $t->foreignId('school_id')->constrained()->cascadeOnDelete();
        $t->string('name');                 // contoh: "10 IPA 1"
        $t->unsignedTinyInteger('grade');   // 10/11/12
        $t->timestamps();

        $t->unique(['school_id','name']);   // cegah duplikat nama kelas dalam 1 sekolah
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_rooms');
    }
};
