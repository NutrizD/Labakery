<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Menampilkan daftar semua karyawan.
     */
    public function index()
    {
        $employees = Employee::with('user')->latest()->paginate(10);
        return view('employees.index', compact('employees'));
    }

    /**
     * Menampilkan form untuk membuat karyawan baru.
     * (Tidak lagi mewajibkan pilih user; nama lengkap + gender ditambahkan)
     */
    public function create()
    {
        // Opsional (bila suatu saat ingin menautkan ke user tertentu)
        $users = User::whereDoesntHave('employee')->get();

        $positions = ['Kasir', 'Admin', 'Manager', 'Staff'];
        $statuses  = ['active' => 'Aktif', 'inactive' => 'Nonaktif', 'terminated' => 'Diberhentikan'];

        return view('employees.create', compact('users', 'positions', 'statuses'));
    }

    /**
     * Menyimpan karyawan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'gender'    => ['required', 'in:male,female'],
            'user_id'   => ['nullable', 'exists:users,id', 'unique:employees,user_id'],
            'position'  => ['nullable', 'string', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'address'   => ['nullable', 'string', 'max:500'],
            'hire_date' => ['nullable', 'date'],
            'salary'    => ['nullable', 'numeric', 'min:0'],
            'status'    => ['nullable', 'in:active,inactive,terminated'],
            'notes'     => ['nullable', 'string', 'max:1000'],
        ]);

        // Generate employee ID sederhana
        $employeeId = 'EMP' . date('Y') . str_pad(Employee::count() + 1, 4, '0', STR_PAD_LEFT);

        Employee::create([
            'full_name'   => $validated['full_name'],
            'gender'      => $validated['gender'],
            'user_id'     => $validated['user_id'] ?? null,
            'employee_id' => $employeeId,
            'position'    => $validated['position'] ?? null,
            'phone'       => $validated['phone'] ?? null,
            'address'     => $validated['address'] ?? null,
            'hire_date'   => $validated['hire_date'] ?? null,
            'salary'      => $validated['salary'] ?? null,
            'status'      => $validated['status'] ?? 'active',
            'notes'       => $validated['notes'] ?? null,
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
        $statuses  = ['active' => 'Aktif', 'inactive' => 'Nonaktif', 'terminated' => 'Diberhentikan'];

        // Bila tetap ingin opsi ganti tautan ke user (opsional)
        $users = User::whereDoesntHave('employee')
            ->orWhere('id', $employee->user_id)
            ->get();

        return view('employees.edit', compact('employee', 'positions', 'statuses', 'users'));
    }

    /**
     * Memperbarui karyawan.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'gender'    => ['required', 'in:male,female'],
            'user_id'   => ['nullable', 'exists:users,id', 'unique:employees,user_id,' . $employee->id],
            'position'  => ['nullable', 'string', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'address'   => ['nullable', 'string', 'max:500'],
            'hire_date' => ['nullable', 'date'],
            'salary'    => ['nullable', 'numeric', 'min:0'],
            'status'    => ['nullable', 'in:active,inactive,terminated'],
            'notes'     => ['nullable', 'string', 'max:1000'],
        ]);

        $employee->update([
            'full_name' => $validated['full_name'],
            'gender'    => $validated['gender'],
            'user_id'   => $validated['user_id'] ?? null,
            'position'  => $validated['position'] ?? null,
            'phone'     => $validated['phone'] ?? null,
            'address'   => $validated['address'] ?? null,
            'hire_date' => $validated['hire_date'] ?? null,
            'salary'    => $validated['salary'] ?? null,
            'status'    => $validated['status'] ?? $employee->status,
            'notes'     => $validated['notes'] ?? null,
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
     * Laporan ringkas karyawan.
     */
    public function report()
    {
        $totalEmployees      = Employee::count();
        $activeEmployees     = Employee::active()->count();
        $inactiveEmployees   = Employee::where('status', 'inactive')->count();
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
