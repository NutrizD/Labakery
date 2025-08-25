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
