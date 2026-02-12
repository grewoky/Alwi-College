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

        // Find old admin user and update, or create new one
        $admin = User::where('email', 'admin@alwi.test')->first();
        if ($admin) {
            $admin->update([
                'email' => 'siswa.alwicollege.x@gmail.com',
                'password' => Hash::make('alwi88888')
            ]);
        } else {
            $admin = User::create([
                'email' => 'siswa.alwicollege.x@gmail.com',
                'name' => 'Admin',
                'password' => Hash::make('alwi88888')
            ]);
        }
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
