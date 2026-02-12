<?php

require 'vendor/autoload.php';

use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Update admin user
$admin = User::where('email', 'admin@alwi.test')->first();

if ($admin) {
    $admin->update([
        'email' => 'siswa.alwicollege.x@gmail.com',
        'password' => Hash::make('alwi88888')
    ]);
    echo "✓ Admin user berhasil diperbarui!\n";
    echo "Email: siswa.alwicollege.x@gmail.com\n";
    echo "Password: alwi88888 (hashed securely)\n";
} else {
    // Jika user admin belum ada, buat baru
    $admin = User::create([
        'email' => 'siswa.alwicollege.x@gmail.com',
        'name' => 'Admin',
        'password' => Hash::make('alwi88888')
    ]);
    $admin->assignRole('admin');
    echo "✓ Admin user baru berhasil dibuat!\n";
    echo "Email: siswa.alwicollege.x@gmail.com\n";
}
