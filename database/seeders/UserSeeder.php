<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator',
                'no_hp' => '081234567890',
                'email' => 'admin@tkjstore.test',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Developer TKJ',
                'no_hp' => '081234567891',
                'email' => 'developer@tkjstore.test',
                'username' => 'developer',
                'password' => Hash::make('password'),
                'role' => 'developer',
            ],
            [
                'name' => 'User Toko',
                'no_hp' => '081234567892',
                'email' => 'user@tkjstore.test',
                'username' => 'user',
                'password' => Hash::make('password'),
                'role' => 'user',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
