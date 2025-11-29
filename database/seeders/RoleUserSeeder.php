<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['student','teacher','admin'] as $r) {
            Role::firstOrCreate(['name' => $r, 'guard_name' => 'web']);
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@alwi.test'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );
        $teacher = User::updateOrCreate(
            ['email' => 'guru@alwi.test'],
            ['name' => 'Guru', 'password' => Hash::make('password')]
        );
        $student = User::updateOrCreate(
            ['email' => 'siswa@alwi.test'],
            ['name' => 'Siswa', 'password' => Hash::make('password')]
        );

        $admin->assignRole('admin');
        $teacher->assignRole('teacher');
        $student->assignRole('student');
    }
}
