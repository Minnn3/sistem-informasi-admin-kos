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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('rooms', RoomController::class);
    Route::resource('tenants', TenantController::class);
    
    Route::post('/billings/generate', [BillingController::class, 'generate'])->name('billings.generate');
    Route::post('/billings/{billing}/pay', [BillingController::class, 'markAsPaid'])->name('billings.pay');
    Route::get('/billings/{billing}/invoice', [BillingController::class, 'invoice'])->name('billings.invoice');
    Route::resource('billings', BillingController::class);
    
    Route::resource('expenses', ExpenseController::class);
    
    Route::post('/todos/{todo}/toggle', [TodoController::class, 'toggleStatus'])->name('todos.toggle');
    Route::resource('todos', TodoController::class)->except(['create', 'show', 'edit']);
    
    // Rute Laporan
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [\App\Http\Controllers\ReportController::class, 'export'])->name('reports.export');
});

require __DIR__.'/auth.php';
