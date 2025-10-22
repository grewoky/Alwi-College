<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassRoom;
use App\Models\School;

class ClassRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create SMA school
        $school = School::firstOrCreate(
            ['name' => 'SMA Alwi College']
        );

        // Definisikan ruangan kelas sesuai struktur Anda
        $classRooms = [
            // Grade 10 (Kelas X)
            ['name' => 'Kelas 1B - Ruang Kecil', 'grade' => 10],

            // Grade 11 (Kelas XI)
            ['name' => 'Kelas XI IPA 1 (A21)', 'grade' => 11],
            ['name' => 'Kelas XI IPA 2 (A22)', 'grade' => 11],
            ['name' => 'Kelas XI IPA 3 (A23)', 'grade' => 11],

            ['name' => 'Kelas XI IPS 1 (B21)', 'grade' => 11],
            ['name' => 'Kelas XI IPS 2 (B22)', 'grade' => 11],
            ['name' => 'Kelas XI IPS 3 (B23)', 'grade' => 11],
            ['name' => 'Kelas XI IPS 4 (B24)', 'grade' => 11],

            // Grade 12 (Kelas XII)
            ['name' => 'Kelas XII IPA 1 (A31)', 'grade' => 12],
            ['name' => 'Kelas XII IPA 2 (A32)', 'grade' => 12],

            ['name' => 'Kelas XII IPS 1 (B31)', 'grade' => 12],
            ['name' => 'Kelas XII IPS 2 (B32)', 'grade' => 12],
            ['name' => 'Kelas XII IPS 3 (B33)', 'grade' => 12],
            ['name' => 'Kelas XII IPS 4 (B34)', 'grade' => 12],
        ];

        // Insert ke database
        foreach ($classRooms as $room) {
            ClassRoom::firstOrCreate(
                ['school_id' => $school->id, 'name' => $room['name']],
                [
                    'school_id' => $school->id,
                    'name'      => $room['name'],
                    'grade'     => $room['grade'],
                ]
            );
        }

        $this->command->info('✅ ClassRoom seeding completed! Created ' . count($classRooms) . ' classrooms');
    }
}
