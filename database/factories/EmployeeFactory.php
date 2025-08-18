<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $positions = ['Kasir', 'Admin', 'Manager', 'Staff'];
        $statuses = ['active', 'inactive', 'terminated'];
        
        return [
            'user_id' => User::factory(),
            'employee_id' => 'EMP' . date('Y') . str_pad(fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'position' => fake()->randomElement($positions),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'hire_date' => fake()->dateTimeBetween('-2 years', 'now'),
            'salary' => fake()->numberBetween(2500000, 8000000),
            'status' => fake()->randomElement($statuses),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }

    /**
     * Indicate that the employee is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the employee is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Indicate that the employee is terminated.
     */
    public function terminated(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'terminated',
        ]);
    }
}
