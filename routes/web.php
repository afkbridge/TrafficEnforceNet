<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ViolationController;
use App\Http\Controllers\Admin\EnforcerController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ViolationTypeController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AuditTrailController;

use App\Http\Controllers\Enforcer\DashboardController as EnforcerDashboardController;
use App\Http\Controllers\Enforcer\ViolationController as EnforcerViolationController;
use App\Http\Controllers\Enforcer\ProfileController as EnforcerProfileController;

use App\Http\Controllers\BPLO\DashboardController as BPLODashboardController;
use App\Http\Controllers\BPLO\ViolationController as BPLOViolationController;
use App\Http\Controllers\BPLO\SettingsController as BPLOSettingsController;

use App\Http\Controllers\SuperAdmin\UserManagementController;
use App\Http\Controllers\SuperAdmin\SAProfileController;

use App\Models\Violation;

use App\Http\Controllers\PublicPortal\SearchController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function (Request $request) {

    $violation = null;

    if ($request->filled('ticket_number')) {

        $violation = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'user'
        ])
            ->where('ticket_number', $request->ticket_number)
            ->first();
    }

    return view('landing', compact('violation'));

})->name('landing');


Route::get('/check-ticket', [SearchController::class, 'check'])
    ->name('public.ticket.check');


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
| Super Admin - User Management & My Account
|--------------------------------------------------------------------------
|
| Super Administrator only.
|
*/

Route::middleware(['auth', 'role:Super Administrator', 'prevent-back'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */

    Route::get('/super-admin/users', [UserManagementController::class, 'index'])
        ->name('super-admin.users');

    Route::post('/super-admin/users', [UserManagementController::class, 'store'])
        ->name('super-admin.users.store');

    Route::put('/super-admin/users/{user}', [UserManagementController::class, 'update'])
        ->name('super-admin.users.update');

    Route::patch('/super-admin/users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])
        ->name('super-admin.users.reset-password');

    Route::patch('/super-admin/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])
        ->name('super-admin.users.toggle-status');

    Route::delete('/super-admin/users/{user}', [UserManagementController::class, 'destroy'])
        ->name('super-admin.users.destroy');


    /*
    |--------------------------------------------------------------------------
    | Super Administrator - My Account
    |--------------------------------------------------------------------------
    */

    Route::get('/super-admin/profile', [SAProfileController::class, 'edit'])
        ->name('super-admin.profile');

    Route::put('/super-admin/profile', [SAProfileController::class, 'updateProfile'])
        ->name('super-admin.profile.update');

    Route::put('/super-admin/profile/password', [SAProfileController::class, 'updatePassword'])
        ->name('super-admin.profile.password');
});


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
| BPLO Routes
|--------------------------------------------------------------------------
*/

Route::get('/bplo/dashboard', [BPLODashboardController::class, 'index'])
    ->middleware(['auth', 'role:BPLO Personnel', 'prevent-back'])
    ->name('bplo.dashboard');


/*
|--------------------------------------------------------------------------
| BPLO Setting Review
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:BPLO Personnel', 'prevent-back'])->group(function () {

    Route::get('/bplo/settings', [BPLOSettingsController::class, 'index'])
        ->name('bplo.settings');

    Route::put('/bplo/settings/account', [BPLOSettingsController::class, 'updateAccount'])
        ->name('bplo.settings.account');

    Route::put('/bplo/settings/password', [BPLOSettingsController::class, 'updatePassword'])
        ->name('bplo.settings.password');
});


/*
|--------------------------------------------------------------------------
| BPLO Violation Review
|--------------------------------------------------------------------------
*/

Route::get('/bplo/violations', [BPLOViolationController::class, 'index'])
    ->middleware(['auth', 'role:BPLO Personnel', 'prevent-back'])
    ->name('bplo.violations.index');


Route::get('/bplo/violations/export', [BPLOViolationController::class, 'export'])
    ->middleware(['auth', 'role:BPLO Personnel', 'prevent-back'])
    ->name('bplo.violations.export');


/*
|--------------------------------------------------------------------------
| BPLO Update Violation Status
|--------------------------------------------------------------------------
*/

Route::patch(
    '/bplo/violations/{violation}/status',
    [BPLOViolationController::class, 'updateStatus']
)
    ->middleware(['auth', 'role:BPLO Personnel', 'prevent-back'])
    ->name('bplo.violations.status');


/*
|--------------------------------------------------------------------------
| BPLO Driver History
|--------------------------------------------------------------------------
*/

Route::get(
    '/bplo/violations/driver-history/{driver}',
    [BPLOViolationController::class, 'driverHistory']
)
    ->middleware(['auth', 'role:BPLO Personnel', 'prevent-back'])
    ->name('bplo.violations.driver-history');


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

    if ($user->role->name === 'Super Administrator') {
        return redirect()->route('super-admin.users');
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

    Route::get('/admin/reports/export/excel', [ReportController::class, 'exportExcel'])
        ->name('admin.reports.export.excel');


    /*
    |--------------------------------------------------------------------------
    | Audit Monitoring
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/audit-monitoring',
        [AuditTrailController::class, 'index']
    )
        ->middleware(['role:Administrator', 'prevent-back'])
        ->name('admin.audit.index');


    /*
    |--------------------------------------------------------------------------
    | Admin Violation Management
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/violations/export',
        [ViolationController::class, 'export']
    )->name('admin.violations.export');

    Route::resource('violations', ViolationController::class);


    /*
    |--------------------------------------------------------------------------
    | Admin Driver History
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/violations/driver/{driver}',
        [ViolationController::class, 'driverHistory']
    )
        ->middleware(['role:Administrator', 'prevent-back'])
        ->name('violations.driver-history');


    Route::get('/violations/create', [ViolationController::class, 'create'])
        ->name('admin.violations.create');

    Route::post('/violations', [ViolationController::class, 'store'])
        ->name('admin.violations.store');


    /*
    |--------------------------------------------------------------------------
    | Admin Settings
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/settings', function () {
        return view('admin.settings.index');
    })->name('admin.settings');


    Route::put(
        '/admin/settings/account',
        [SettingsController::class, 'updateAccount']
    )->name('admin.settings.account');


    Route::put(
        '/admin/settings/password',
        [SettingsController::class, 'updatePassword']
    )->name('admin.settings.password');


    /*
    |--------------------------------------------------------------------------
    | Violation Types
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/settings/violation-types',
        [ViolationTypeController::class, 'index']
    )->name('admin.violation-types.index');


    Route::post(
        '/admin/settings/violation-types',
        [ViolationTypeController::class, 'store']
    )->name('admin.violation-types.store');


    Route::put(
        '/admin/settings/violation-types/{violationType}',
        [ViolationTypeController::class, 'update']
    )->name('admin.violation-types.update');


    Route::delete(
        '/admin/settings/violation-types/{violationType}',
        [ViolationTypeController::class, 'destroy']
    )->name('admin.violation-types.destroy');


    /*
    |--------------------------------------------------------------------------
    | Enforcer Management
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Create Enforcer Profile for Existing User
    |--------------------------------------------------------------------------
    |
    | These routes do NOT create a new user account.
    | They create an Enforcer profile linked to an existing
    | POSO Enforcer user account.
    |
    */

    Route::get(
        '/enforcers/{user}/profile/create',
        [EnforcerController::class, 'createProfile']
    )->name('enforcers.profile.create');


    Route::post(
        '/enforcers/{user}/profile',
        [EnforcerController::class, 'storeProfile']
    )->name('enforcers.profile.store');


    /*
    |--------------------------------------------------------------------------
    | Standard Enforcer Resource Routes
    |--------------------------------------------------------------------------
    */

    Route::resource('enforcers', EnforcerController::class);


    /*
    |--------------------------------------------------------------------------
    | Enforcer Account
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/enforcers/{enforcer}/account',
        [EnforcerController::class, 'account']
    )->name('enforcers.account');


    /*
    |--------------------------------------------------------------------------
    | Enforcer Password Reset
    |--------------------------------------------------------------------------
    */

    Route::put(
        '/enforcers/{enforcer}/reset-password',
        [EnforcerController::class, 'resetPassword']
    )->name('enforcers.resetPassword');


    /*
    |--------------------------------------------------------------------------
    | Administrator & BPLO User Management
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | Create Administrator / BPLO Account
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/users/staff',
        [EnforcerController::class, 'storeStaff']
    )->name('users.staff.store');


    /*
    |--------------------------------------------------------------------------
    | Reset Administrator / BPLO Password
    |--------------------------------------------------------------------------
    */

    Route::put(
        '/users/{user}/reset-password',
        [EnforcerController::class, 'resetStaffPassword']
    )->name('users.staff.resetPassword');


    /*
    |--------------------------------------------------------------------------
    | Enable / Disable Administrator / BPLO Account
    |--------------------------------------------------------------------------
    */

    Route::patch(
        '/users/{user}/toggle-status',
        [EnforcerController::class, 'toggleStaffStatus']
    )->name('users.staff.toggleStatus');


    /*
    |--------------------------------------------------------------------------
    | Delete Administrator / BPLO Account
    |--------------------------------------------------------------------------
    */

    Route::delete(
        '/users/{user}',
        [EnforcerController::class, 'destroyStaff']
    )->name('users.staff.destroy');


    /*
    |--------------------------------------------------------------------------
    | Enforcer Violation Module
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:POSO Enforcer')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Issue Traffic Ticket
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/enforcer/issue-ticket',
            [EnforcerViolationController::class, 'create']
        )->name('enforcer.violations.create');


        Route::post(
            '/enforcer/issue-ticket',
            [EnforcerViolationController::class, 'store']
        )->name('enforcer.violations.store');


        /*
        |--------------------------------------------------------------------------
        | Driver's License OCR
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/enforcer/ocr/driver-license',
            [EnforcerViolationController::class, 'ocrDriverLicense']
        )->name('enforcer.ocr.driver-license');


        /*
        |--------------------------------------------------------------------------
        | Traffic Citation Ticket OCR
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/enforcer/ocr/citation-ticket',
            [EnforcerViolationController::class, 'ocrCitationTicket']
        )->name('enforcer.ocr.citation-ticket');


        /*
        |--------------------------------------------------------------------------
        | Enforcer Violations
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/enforcer/violations',
            [EnforcerViolationController::class, 'index']
        )->name('enforcer.violations.index');


        Route::get(
            '/enforcer/violations/{id}',
            [EnforcerViolationController::class, 'show']
        )->name('enforcer.violations.show');


        /*
        |--------------------------------------------------------------------------
        | Success Page
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/enforcer/success',
            [EnforcerViolationController::class, 'success']
        )->name('enforcer.success');


        /*
        |--------------------------------------------------------------------------
        | Enforcer Heartbeat
        |--------------------------------------------------------------------------
        */

        Route::post('/enforcer/heartbeat', function () {

            auth()->user()->update([
                'last_seen_at' => now(),
            ]);

            return response()->json([
                'success' => true,
            ]);

        })->name('enforcer.heartbeat');


        /*
        |--------------------------------------------------------------------------
        | Enforcer Profile
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/enforcer/profile',
            [EnforcerProfileController::class, 'show']
        )->name('enforcer.profile');


        /*
        |--------------------------------------------------------------------------
        | Enforcer Change Password
        |--------------------------------------------------------------------------
        */

        Route::put(
            '/enforcer/profile/password',
            [EnforcerProfileController::class, 'updatePassword']
        )->name('enforcer.password.update');
    });


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