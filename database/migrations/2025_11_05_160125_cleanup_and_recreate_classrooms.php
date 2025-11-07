<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Truncate the existing class_rooms table
        Schema::disableForeignKeyConstraints();
        DB::table('class_rooms')->truncate();
        Schema::enableForeignKeyConstraints();

        // Insert simplified class rooms (Kelas 10, 11, 12 only)
        // Get all schools first
        $schools = DB::table('schools')->get();

        foreach ($schools as $school) {
            for ($grade = 10; $grade <= 12; $grade++) {
                DB::table('class_rooms')->insert([
                    'school_id' => $school->id,
                    'name' => "Kelas $grade",
                    'grade' => $grade,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Truncate and recreate old structure if needed
        Schema::disableForeignKeyConstraints();
        DB::table('class_rooms')->truncate();
        Schema::enableForeignKeyConstraints();
    }
};
