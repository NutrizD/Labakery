<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User Admin
        User::firstOrCreate(
            ['email' => 'admin@toko-kue.com'],
            [
                'name' => 'Admin Toko Kue',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // User Kasir 1
        User::firstOrCreate(
            ['email' => 'kasir1@gmail.com'],
            [
                'name' => 'Kasir 1',
                'password' => Hash::make('password123'),
                'role' => 'kasir',
            ]
        );

        // User Kasir 2
        User::firstOrCreate(
            ['email' => 'kasir2@gmail.com'],
            [
                'name' => 'Kasir 2',
                'password' => Hash::make('password123'),
                'role' => 'kasir',
            ]
        );

        // User Manager
        User::firstOrCreate(
            ['email' => 'manager@gmail.com'],
            [
                'name' => 'Manager Toko',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // User dengan email yang ada di .env
        User::firstOrCreate(
            ['email' => 'rifqirahmatullah34@gmail.com'],
            [
                'name' => 'Rifqi Rahmatullah',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        $this->command->info('Users seeded successfully!');
    }
}
