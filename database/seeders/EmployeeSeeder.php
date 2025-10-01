<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user yang sudah ada
        $users = User::all();

        if ($users->count() > 0) {
            foreach ($users as $index => $user) {
                // Skip jika user sudah memiliki employee record
                if ($user->employee) {
                    continue;
                }

                $positions = ['Kasir', 'Admin', 'Manager', 'Staff'];
                $statuses = ['active', 'active', 'active', 'inactive']; // Kebanyakan aktif

                Employee::create([
                    'user_id' => $user->id,
                    'employee_id' => 'EMP' . date('Y') . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                    'position' => $positions[array_rand($positions)],
                    'phone' => '08' . rand(100000000, 999999999),
                    'address' => 'Jl. Contoh No. ' . rand(1, 100) . ', Kota Contoh',
                    'hire_date' => Carbon::now()->subMonths(rand(1, 24)),
                    'salary' => rand(2500000, 8000000),
                    'status' => $statuses[array_rand($statuses)],
                    'notes' => rand(0, 1) ? 'Karyawan teladan' : null,
                ]);
            }
        }
    }
}
