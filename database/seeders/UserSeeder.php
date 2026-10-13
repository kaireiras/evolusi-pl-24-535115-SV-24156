<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@suratsurga.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // 2. Akun User Biasa
        User::updateOrCreate(
            ['email' => 'user@suratsurga.com'],
            [
                'name' => 'user',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]
        );
    }
}