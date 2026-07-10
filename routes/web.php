<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ViolationController;
use App\Http\Controllers\Admin\EnforcerController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('admin.dashboard');


Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})
->middleware('auth')
->name('dashboard');



/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Violation Management
    |--------------------------------------------------------------------------
    */

    Route::resource('violations', ViolationController::class);



    /*
    |--------------------------------------------------------------------------
    | Enforcer Management
    |--------------------------------------------------------------------------
    */

    Route::resource('enforcers', EnforcerController::class);


    // Enforcer Account Information
    Route::get('/enforcers/{enforcer}/account',
        [EnforcerController::class, 'account']
    )
    ->name('enforcers.account');


    // Reset Enforcer Password
    Route::put('/enforcers/{enforcer}/reset-password',
        [EnforcerController::class, 'resetPassword']
    )
    ->name('enforcers.resetPassword');



    /*
    |--------------------------------------------------------------------------
    | User Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');


    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');


    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';