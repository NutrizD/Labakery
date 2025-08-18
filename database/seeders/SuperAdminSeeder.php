<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if super admin already exists
        if (!User::where('role', 'super_admin')->exists()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => Hash::make('superadmin123'),
                'role' => 'super_admin',
            ]);

            $this->command->info('Super Admin user created successfully!');
            $this->command->info('Email: superadmin@gmail.com');
            $this->command->info('Password: superadmin123');
        } else {
            $this->command->info('Super Admin user already exists.');
        }
    }
}
