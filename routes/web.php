<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

// Routes yang bisa diakses oleh semua user yang sudah login (admin & karyawan)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // =============================================
    // ROOMS - Karyawan hanya bisa lihat (index)
    // =============================================
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');

    // =============================================
    // TENANTS - Karyawan hanya bisa lihat (index)
    // =============================================
    Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index');
    Route::get('/tenants/{tenant}', [TenantController::class, 'show'])->name('tenants.show');

    // =============================================
    // BILLINGS - Karyawan hanya bisa lihat (index, invoice)
    // =============================================
    Route::get('/billings', [BillingController::class, 'index'])->name('billings.index');
    Route::get('/billings/{billing}', [BillingController::class, 'show'])->name('billings.show');
    Route::get('/billings/{billing}/invoice', [BillingController::class, 'invoice'])->name('billings.invoice');

    // =============================================
    // EXPENSES - Karyawan hanya bisa lihat (index)
    // =============================================
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');

    // Rute Laporan - semua bisa lihat
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [\App\Http\Controllers\ReportController::class, 'export'])->name('reports.export');
});

// =============================================
// Routes KHUSUS ADMIN - create, edit, delete
// =============================================
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    // Rooms - admin full CRUD
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
    Route::patch('/rooms/{room}', [RoomController::class, 'update']);
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');

    // Tenants - admin full CRUD
    Route::get('/tenants/create', [TenantController::class, 'create'])->name('tenants.create');
    Route::post('/tenants', [TenantController::class, 'store'])->name('tenants.store');
    Route::get('/tenants/{tenant}/edit', [TenantController::class, 'edit'])->name('tenants.edit');
    Route::put('/tenants/{tenant}', [TenantController::class, 'update'])->name('tenants.update');
    Route::patch('/tenants/{tenant}', [TenantController::class, 'update']);
    Route::delete('/tenants/{tenant}', [TenantController::class, 'destroy'])->name('tenants.destroy');

    // Billings - admin full CRUD
    Route::post('/billings/generate', [BillingController::class, 'generate'])->name('billings.generate');
    Route::post('/billings/{billing}/pay', [BillingController::class, 'markAsPaid'])->name('billings.pay');
    Route::get('/billings/create', [BillingController::class, 'create'])->name('billings.create');
    Route::post('/billings', [BillingController::class, 'store'])->name('billings.store');
    Route::get('/billings/{billing}/edit', [BillingController::class, 'edit'])->name('billings.edit');
    Route::put('/billings/{billing}', [BillingController::class, 'update'])->name('billings.update');
    Route::patch('/billings/{billing}', [BillingController::class, 'update']);
    Route::delete('/billings/{billing}', [BillingController::class, 'destroy'])->name('billings.destroy');

    // Expenses - admin full CRUD
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::patch('/expenses/{expense}', [ExpenseController::class, 'update']);
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Todos - admin full
    Route::post('/todos/{todo}/toggle', [TodoController::class, 'toggleStatus'])->name('todos.toggle');
    Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
    Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');
    Route::put('/todos/{todo}', [TodoController::class, 'update'])->name('todos.update');
    Route::patch('/todos/{todo}', [TodoController::class, 'update']);
    Route::delete('/todos/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy');
});

require __DIR__.'/auth.php';
