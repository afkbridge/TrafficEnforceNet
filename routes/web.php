<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ViolationController;
use App\Http\Controllers\Admin\EnforcerController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('admin.dashboard');

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {

    // Violations
    Route::resource('violations', ViolationController::class);

    // Enforcers
    Route::resource('enforcers', EnforcerController::class);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// Keep these since your Violation module is already using them
Route::get('/violations/{id}/edit', [ViolationController::class, 'edit'])
    ->name('violations.edit');

Route::put('/violations/{id}', [ViolationController::class, 'update'])
    ->name('violations.update');