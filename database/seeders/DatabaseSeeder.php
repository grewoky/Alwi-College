<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
{
    $this->call(RoleUserSeeder::class);
    $this->call(MasterDataSeeder::class);

    \App\Models\User::firstOrCreate(
        ['email' => 'test@example.com'],
        ['name' => 'Test User', 'password' => \Illuminate\Support\Facades\Hash::make('password'), 'email_verified_at'=>now()]
    );
}

}
