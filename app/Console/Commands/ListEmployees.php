<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;

class ListEmployees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employees:list {--status= : Filter by status (active, inactive, terminated)} {--position= : Filter by position}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all employees with optional filtering';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = Employee::with('user');

        // Filter by status if provided
        if ($status = $this->option('status')) {
            $query->where('status', $status);
            $this->info("Filtering by status: {$status}");
        }

        // Filter by position if provided
        if ($position = $this->option('position')) {
            $query->where('position', $position);
            $this->info("Filtering by position: {$position}");
        }

        $employees = $query->get();

        if ($employees->isEmpty()) {
            $this->warn('No employees found.');
            return;
        }

        $this->info("Found {$employees->count()} employee(s):\n");

        $headers = ['ID', 'Name', 'Email', 'Position', 'Status', 'Hire Date', 'Salary'];
        $rows = [];

        foreach ($employees as $employee) {
            $rows[] = [
                $employee->employee_id,
                $employee->user->name,
                $employee->user->email,
                $employee->position,
                $employee->status,
                $employee->formatted_hire_date,
                $employee->formatted_salary,
            ];
        }

        $this->table($headers, $rows);

        // Summary statistics
        $this->newLine();
        $this->info('Summary:');
        $this->line("Total: {$employees->count()}");
        $this->line("Active: " . $employees->where('status', 'active')->count());
        $this->line("Inactive: " . $employees->where('status', 'inactive')->count());
        $this->line("Terminated: " . $employees->where('status', 'terminated')->count());
    }
}
