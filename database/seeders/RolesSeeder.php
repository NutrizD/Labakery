<?php
// database/seeders/RolesSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@kasir.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Buat user Kasir
        User::create([
            'name' => 'Kasir User',
            'email' => 'kasir@kasir.test',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);
    }
}
