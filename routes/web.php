<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ComponentCategoryController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\PCController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/list/pcs', [HomeController::class, 'browsePCs'])->name('pcs.browse');
Route::get('/list/pcs/{pc}', [HomeController::class, 'pcDetails'])->name('pc.details');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Users management
    Route::resource('users', UserController::class);

    // Component categories
    Route::resource('component-categories', ComponentCategoryController::class);

    // Components
    Route::resource('components', ComponentController::class);

    // PCs
    Route::resource('pcs', PCController::class);

    // Rentals
    Route::resource('rentals', RentalController::class)->except(['edit', 'update', 'destroy']);
    Route::post('/rentals/{rental}/complete', [RentalController::class, 'complete'])->name('rentals.complete');
    Route::post('/rentals/{rental}/cancel', [RentalController::class, 'cancel'])->name('rentals.cancel');
    
    // Invoices
    Route::get('/rentals/{rental}/invoice/download', [InvoiceController::class, 'downloadInvoice'])->name('rentals.invoice.download');
    Route::get('/rentals/{rental}/invoice/view', [InvoiceController::class, 'viewInvoice'])->name('rentals.invoice.view');

    // Payments
    Route::resource('payments', PaymentController::class)->except(['edit', 'update', 'destroy']);
    Route::post('/payments/{payment}/status', [PaymentController::class, 'updateStatus'])->name('payments.status');

    // Maintenance
    Route::resource('maintenance', MaintenanceController::class);
    Route::post('/maintenance/{maintenance}/complete', [MaintenanceController::class, 'complete'])->name('maintenance.complete');
    Route::post('/maintenance/{maintenance}/cancel', [MaintenanceController::class, 'cancel'])->name('maintenance.cancel');

    // Settings
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/', [SettingController::class, 'update'])->name('update');
        Route::get('/backup-restore', [SettingController::class, 'backupRestore'])->name('backup-restore');
        Route::get('/theme', [SettingController::class, 'theme'])->name('theme');
        Route::post('/theme', [SettingController::class, 'saveTheme'])->name('theme.save');
        Route::get('/notifications', [SettingController::class, 'notifications'])->name('notifications');
        Route::post('/notifications', [SettingController::class, 'saveNotifications'])->name('notifications.save');
        Route::get('/export', [SettingController::class, 'export'])->name('export');
        Route::post('/import', [SettingController::class, 'import'])->name('import');
        Route::post('/reset', [SettingController::class, 'reset'])->name('reset');
    });
});

// Customer Routes (requires authentication)
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/rent/{pc}', [HomeController::class, 'rentPC'])->name('rent');
    Route::post('/rent/{pc}', [HomeController::class, 'processRental'])->name('process-rental');
    Route::get('/rentals', [HomeController::class, 'myRentals'])->name('rentals');
    Route::get('/rentals/{rental}', [HomeController::class, 'rentalDetails'])->name('rental-details');
    
    // Invoice routes for customers
    Route::get('/rentals/{rental}/invoice/download', [InvoiceController::class, 'downloadInvoice'])->name('rentals.invoice.download');
    Route::get('/rentals/{rental}/invoice/view', [InvoiceController::class, 'viewInvoice'])->name('rentals.invoice.view');
});

// Admin Routes (requires admin role)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users management
    Route::resource('users', UserController::class);

    // Component categories - Make sure to use the correct namespace
    Route::resource('component-categories', App\Http\Controllers\Admin\ComponentCategoryController::class);

    // Components
    Route::resource('components', ComponentController::class);

    // PCs
    Route::resource('pcs', PCController::class);

    // Rentals
    Route::resource('rentals', RentalController::class)->except(['edit', 'update', 'destroy']);
    Route::post('/rentals/{rental}/complete', [RentalController::class, 'complete'])->name('rentals.complete');
    Route::post('/rentals/{rental}/cancel', [RentalController::class, 'cancel'])->name('rentals.cancel');
    
    // Invoices for admin
    Route::get('/rentals/{rental}/invoice/download', [InvoiceController::class, 'downloadInvoice'])->name('rentals.invoice.download');
    Route::get('/rentals/{rental}/invoice/view', [InvoiceController::class, 'viewInvoice'])->name('rentals.invoice.view');

    // Payments
    Route::resource('payments', PaymentController::class)->except(['edit', 'update', 'destroy']);
    Route::post('/payments/{payment}/status', [PaymentController::class, 'updateStatus'])->name('payments.status');

    // Maintenance
    Route::resource('maintenance', MaintenanceController::class);
    Route::post('/maintenance/{maintenance}/complete', [MaintenanceController::class, 'complete'])->name('maintenance.complete');
    Route::post('/maintenance/{maintenance}/cancel', [MaintenanceController::class, 'cancel'])->name('maintenance.cancel');

    // Settings
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/', [SettingController::class, 'update'])->name('update');
        Route::get('/backup-restore', [SettingController::class, 'backupRestore'])->name('backup-restore');
        Route::get('/theme', [SettingController::class, 'theme'])->name('theme');
        Route::post('/theme', [SettingController::class, 'saveTheme'])->name('theme.save');
        Route::get('/notifications', [SettingController::class, 'notifications'])->name('notifications');
        Route::post('/notifications', [SettingController::class, 'saveNotifications'])->name('notifications.save');
        Route::get('/export', [SettingController::class, 'export'])->name('export');
        Route::post('/import', [SettingController::class, 'import'])->name('import');
        Route::post('/reset', [SettingController::class, 'reset'])->name('reset');
    });
});

require __DIR__ . '/auth.php';
