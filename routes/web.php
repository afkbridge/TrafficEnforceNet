<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ViolationController;
use App\Http\Controllers\Admin\EnforcerController;
use App\Http\Controllers\Admin\ReportController;

use App\Http\Controllers\Enforcer\DashboardController as EnforcerDashboardController;
use App\Http\Controllers\BPLO\DashboardController as BPLODashboardController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing');
})->name('landing');


/*
|--------------------------------------------------------------------------
| Office Portal
|--------------------------------------------------------------------------
*/

Route::get('/office', function () {
    return view('office.index');
})->name('office.portal');



/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'role:Administrator', 'prevent-back'])
    ->name('admin.dashboard');



/*
|--------------------------------------------------------------------------
| Enforcer Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/enforcer/dashboard', [EnforcerDashboardController::class, 'index'])
    ->middleware(['auth', 'role:POSO Enforcer', 'prevent-back'])
    ->name('enforcer.dashboard');



/*
|--------------------------------------------------------------------------
| BPLO Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/bplo/dashboard', [BPLODashboardController::class, 'index'])
    ->middleware(['auth', 'role:BPLO Personnel', 'prevent-back'])
    ->name('bplo.dashboard');


/*
|--------------------------------------------------------------------------
| Smart Dashboard Redirect
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    $user = auth()->user();

    if ($user->role->name === 'Administrator') {

        return redirect()->route('admin.dashboard');

    }


    if ($user->role->name === 'POSO Enforcer') {

        return redirect()->route('enforcer.dashboard');

    }


    if ($user->role->name === 'BPLO Personnel') {

        return redirect()->route('bplo.dashboard');

    }


    return redirect('/');

})->middleware('auth')->name('dashboard');



/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/reports', [ReportController::class, 'index'])
        ->name('admin.reports.index');



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



    Route::get(
        '/enforcers/{enforcer}/account',
        [EnforcerController::class, 'account']
    )
        ->name('enforcers.account');



    Route::put(
        '/enforcers/{enforcer}/reset-password',
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