<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    /**
     * Menampilkan daftar semua karyawan.
     */
    public function index()
    {
        $employees = Employee::with('user')
            ->latest()
            ->paginate(10);
        
        return view('employees.index', compact('employees'));
    }

    /**
     * Menampilkan form untuk membuat karyawan baru.
     */
    public function create()
    {
        $users = User::whereDoesntHave('employee')->get();
        $positions = ['Kasir', 'Admin', 'Manager', 'Staff'];
        
        return view('employees.create', compact('users', 'positions'));
    }

    /**
     * Menyimpan karyawan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id', 'unique:employees,user_id'],
            'position' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'hire_date' => ['required', 'date'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive,terminated'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // Generate employee ID
        $employeeId = 'EMP' . date('Y') . str_pad(Employee::count() + 1, 4, '0', STR_PAD_LEFT);

        Employee::create([
            'user_id' => $request->user_id,
            'employee_id' => $employeeId,
            'position' => $request->position,
            'phone' => $request->phone,
            'address' => $request->address,
            'hire_date' => $request->hire_date,
            'salary' => $request->salary,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail karyawan.
     */
    public function show(Employee $employee)
    {
        $employee->load('user', 'transactions');
        return view('employees.show', compact('employee'));
    }

    /**
     * Menampilkan form untuk mengedit karyawan.
     */
    public function edit(Employee $employee)
    {
        $positions = ['Kasir', 'Admin', 'Manager', 'Staff'];
        return view('employees.edit', compact('employee', 'positions'));
    }

    /**
     * Memperbarui karyawan.
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'position' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'hire_date' => ['required', 'date'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive,terminated'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $employee->update([
            'position' => $request->position,
            'phone' => $request->phone,
            'address' => $request->address,
            'hire_date' => $request->hire_date,
            'salary' => $request->salary,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Menghapus karyawan.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil dihapus.');
    }

    /**
     * Menampilkan laporan karyawan.
     */
    public function report()
    {
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::active()->count();
        $inactiveEmployees = Employee::where('status', 'inactive')->count();
        $terminatedEmployees = Employee::where('status', 'terminated')->count();

        $employeesByPosition = Employee::select('position', DB::raw('count(*) as total'))
            ->groupBy('position')
            ->get();

        $recentHires = Employee::with('user')
            ->where('hire_date', '>=', now()->subMonths(6))
            ->orderBy('hire_date', 'desc')
            ->get();

        return view('employees.report', compact(
            'totalEmployees',
            'activeEmployees',
            'inactiveEmployees',
            'terminatedEmployees',
            'employeesByPosition',
            'recentHires'
        ));
    }
}
