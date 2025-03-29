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

    // Payments
    Route::resource('payments', PaymentController::class)->except(['edit', 'update', 'destroy']);
    Route::post('/payments/{payment}/status', [PaymentController::class, 'updateStatus'])->name('payments.status');

    // Maintenance
    Route::resource('maintenance', MaintenanceController::class);
    Route::post('/maintenance/{maintenance}/complete', [MaintenanceController::class, 'complete'])->name('maintenance.complete');
    Route::post('/maintenance/{maintenance}/cancel', [MaintenanceController::class, 'cancel'])->name('maintenance.cancel');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});

// Customer Routes (requires authentication)
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/rent/{pc}', [HomeController::class, 'rentPC'])->name('rent');
    Route::post('/rent/{pc}', [HomeController::class, 'processRental'])->name('process-rental');
    Route::get('/rentals', [HomeController::class, 'myRentals'])->name('rentals');
    Route::get('/rentals/{rental}', [HomeController::class, 'rentalDetails'])->name('rental-details');
});
// Admin Routes (requires admin or operator role)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

    // Payments
    Route::resource('payments', PaymentController::class)->except(['edit', 'update', 'destroy']);
    Route::post('/payments/{payment}/status', [PaymentController::class, 'updateStatus'])->name('payments.status');

    // Maintenance
    Route::resource('maintenance', MaintenanceController::class);
    Route::post('/maintenance/{maintenance}/complete', [MaintenanceController::class, 'complete'])->name('maintenance.complete');
    Route::post('/maintenance/{maintenance}/cancel', [MaintenanceController::class, 'cancel'])->name('maintenance.cancel');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});
require __DIR__ . '/auth.php';
