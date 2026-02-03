<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Routes العامة (بدون tenant)
Route::middleware('guest')->group(function () {
    Route::get('/register/company', [CompanyController::class, 'create'])->name('register.company');
    Route::post('/register/company', [CompanyController::class, 'store']);
});

// Routes محمية بالمستأجر
Route::middleware(['auth', 'tenant'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // العملاء
    Route::resource('customers', CustomerController::class);

    // الفواتير
    Route::resource('invoices', InvoiceController::class);
    Route::post('/invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
    Route::post('/invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');

    // المدفوعات
    Route::resource('payments', PaymentController::class);
});

require __DIR__.'/auth.php';
