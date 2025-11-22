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
        // Daftar sekolah yang digunakan di aplikasi beserta kelas grade-levelnya
        $schoolsWithGrades = [
            'Negeri'           => [10, 11, 12],
            'IGS'              => [10, 11, 12],
            'Xaverius 3'       => [10, 11, 12],
            'Bangau'           => [10, 11, 12],
            'Kumbang'          => [10, 11, 12]
        ];

        $createdCount = 0;
        $deletedCount = 0;

        foreach ($schoolsWithGrades as $schoolName => $grades) {
            $school = School::firstOrCreate(['name' => $schoolName]);

            $allowedNames = collect($grades)->map(fn($grade) => 'Kelas ' . $grade);

            // Bersihkan varian lama (IPA/IPS dsb) agar hanya tersisa per-grade
            $deletedCount += ClassRoom::where('school_id', $school->id)
                ->whereNotIn('name', $allowedNames)
                ->delete();

            foreach ($grades as $grade) {
                $roomName = 'Kelas ' . $grade;
                $classRoom = ClassRoom::firstOrCreate(
                    ['school_id' => $school->id, 'name' => $roomName],
                    ['grade' => $grade]
                );

                if ($classRoom->wasRecentlyCreated) {
                    $createdCount++;
                } else {
                    // Pastikan grade tetap sinkron jika record sudah ada
                    if ($classRoom->grade !== $grade) {
                        $classRoom->grade = $grade;
                        $classRoom->save();
                    }
                }
            }
        }

        $this->command->info("✅ ClassRoom seeding completed! {$createdCount} records ensured, {$deletedCount} old variants removed.");
    }
}
