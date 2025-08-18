<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;

// Rute utama akan dialihkan ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute untuk autentikasi (login, register, dan logout)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Grup rute yang hanya bisa diakses setelah pengguna login
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/kasir', [TransactionController::class, 'kasir'])->name('kasir');
    Route::post('/kasir/transaksi', [TransactionController::class, 'store'])->name('kasir.store');

    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transaksi/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/struk/{transaction}', [TransactionController::class, 'receipt'])->name('transactions.receipt');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Grup rute untuk admin (admin dan super admin)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/laporan/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
        Route::get('/laporan/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
        Route::get('/laporan/transaction/{transaction}', [ReportController::class, 'transactionDetails'])->name('reports.transaction.details');

        // Routes untuk manajemen karyawan
        Route::resource('employees', EmployeeController::class);
        Route::get('/employees/report', [EmployeeController::class, 'report'])->name('employees.report');
    });

    // Grup rute khusus untuk super admin (hanya super admin)
    Route::middleware(['super.admin'])->group(function () {
        Route::resource('users', UserController::class)->except(['create', 'store']);
        Route::get('/users/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
        // Registration routes (hanya untuk Super Admin)
        Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register']);
    });
});
