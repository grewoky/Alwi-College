<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Sekolah
        $schoolNames = ['Bangau','Xaverius 3','Negeri','IGS'];
        $schools = [];
        foreach ($schoolNames as $nm) {
            $schools[$nm] = \App\Models\School::firstOrCreate(['name'=>$nm]);
        }

        // 2) Kelas contoh per sekolah (grade 10-12)
        // Kamu bisa modifikasi sesuai kebutuhan
        foreach ($schools as $name => $school) {
            foreach ([10,11,12] as $grade) {
                foreach (['IPA','IPS'] as $suffix) {
                    \App\Models\ClassRoom::firstOrCreate([
                        'school_id' => $school->id,
                        'name'      => "{$grade} {$suffix}",
                        'grade'     => $grade,
                    ]);
                }
            }
        }

        // 3) Mata pelajaran
        foreach (['Matematika','Fisika','Kimia','Biologi','Ips','SNBT','TKA'] as $sub) {
            \App\Models\Subject::firstOrCreate(['name'=>$sub]);
        }

        // 4) Kaitkan user guru/siswa yang sudah di-seed sebelumnya
        //    - Buat Teacher untuk user guru
        //    - Buat Student untuk user siswa (taruh di salah satu kelas default)

        $teacherUser = \App\Models\User::where('email','guru@alwi.test')->first();
        if ($teacherUser) {
            \App\Models\Teacher::firstOrCreate(['user_id'=>$teacherUser->id], ['employee_code'=>'T-001']);
        }

        $studentUser = \App\Models\User::where('email','siswa@alwi.test')->first();
        if ($studentUser) {
            $someClass = \App\Models\ClassRoom::first(); // ambil kelas pertama sebagai default
            \App\Models\Student::firstOrCreate(
                ['user_id'=>$studentUser->id],
                ['class_room_id'=>$someClass?->id, 'nis'=>'S-001']
            );
        }
    }
}