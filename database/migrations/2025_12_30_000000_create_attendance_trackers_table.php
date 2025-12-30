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
        Schema::create('attendance_trackers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->integer('attendance_count')->default(0); // Counter 0-30
            $table->timestamp('period_start_date')->nullable(); // Tanggal mulai rolling 30 hari
            $table->timestamp('last_attendance_date')->nullable(); // Tanggal absensi terakhir
            $table->json('monthly_records')->nullable(); // Simpan rekap bulanan sebelum reset {month: count, ...}
            $table->timestamps();

            $table->unique('student_id'); // 1 tracker per siswa
            $table->index('attendance_count');
            $table->index('period_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_trackers');
    }
};
