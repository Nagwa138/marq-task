<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Public Routes (بدون مصادقة)
|--------------------------------------------------------------------------
*/

// 1. الصفحة الرئيسية (الجديدة)
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (محمية بالمصادقة والمستأجر)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'tenant'])->group(function () {

    // 5. Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/company/switch', [DashboardController::class, 'switchCompany'])->name('company.switch');

    /*
    |--------------------------------------------------------------------------
    | Company Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('companies')->name('companies.')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('index');
        Route::get('/create', [CompanyController::class, 'create'])->name('create');
        Route::post('/', [CompanyController::class, 'store'])->name('store');
        Route::get('/{company}', [CompanyController::class, 'show'])->name('show');
        Route::get('/{company}/edit', [CompanyController::class, 'edit'])->name('edit');
        Route::put('/{company}', [CompanyController::class, 'update'])->name('update');
        Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Customer Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');

        // Routes إضافية للعملاء
        Route::get('/{customer}/invoices', [CustomerController::class, 'invoices'])->name('invoices');
        Route::get('/{customer}/payments', [CustomerController::class, 'payments'])->name('payments');
        Route::post('/{customer}/update-balance', [CustomerController::class, 'updateBalance'])->name('update-balance');
    });

    /*
    |--------------------------------------------------------------------------
    | Invoice Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/create', [InvoiceController::class, 'create'])->name('create');
        Route::post('/', [InvoiceController::class, 'store'])->name('store');
//        Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
//        Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('edit');
//        Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('update');
//        Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');

        // Routes إضافية للفواتير (جديدة)
//        Route::post('/{invoice}/send', [InvoiceController::class, 'send'])->name('send');
//        Route::post('/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('mark-paid');
//        Route::post('/{invoice}/duplicate', [InvoiceController::class, 'duplicate'])->name('duplicate');
//        Route::get('/{invoice}/print', [InvoiceController::class, 'print'])->name('print');
//        Route::get('/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('pdf');
//        Route::post('/{invoice}/add-item', [InvoiceController::class, 'addItem'])->name('add-item');
//        Route::delete('/{invoice}/remove-item/{item}', [InvoiceController::class, 'removeItem'])->name('remove-item');
    });

    /*
    |--------------------------------------------------------------------------
    | Payment Routes (الجديدة بالكامل)
    |--------------------------------------------------------------------------
    */
    Route::prefix('payments')->name('payments.')->group(function () {
        // CRUD الأساسي
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/create', [PaymentController::class, 'create'])->name('create');
        Route::post('/', [PaymentController::class, 'store'])->name('store');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
        Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('edit');
        Route::put('/{payment}', [PaymentController::class, 'update'])->name('update');
        Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');

        // Routes إضافية للمدفوعات (جديدة)
        Route::get('/quick-create', [PaymentController::class, 'createQuickPayment'])->name('quick-create');
        Route::post('/quick-payment', [PaymentController::class, 'storeQuickPayment'])->name('quick-payment');
        Route::get('/export', [PaymentController::class, 'export'])->name('export');

        // API Routes للمدفوعات
        Route::get('/invoice/{invoice}/payments', [PaymentController::class, 'getInvoicePayments'])
            ->name('invoice-payments');
    });
});


require __DIR__.'/auth.php';
