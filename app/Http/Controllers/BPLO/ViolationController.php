<?php

namespace App\Http\Controllers\BPLO;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search');

        /*
        |--------------------------------------------------------------------------
        | Violation Query
        |--------------------------------------------------------------------------
        */

        $query = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'user'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Filter by Status
        |--------------------------------------------------------------------------
        */

        if ($status === 'Pending' || $status === 'Completed') {
            $query->where('status', $status);
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {

                // Search ticket number
                $q->where('ticket_number', 'like', "%{$search}%")

                    // Search driver
                    ->orWhereHas('driver', function ($driver) use ($search) {
                        $driver->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('license_number', 'like', "%{$search}%");
                    })

                    // Search violation type
                    ->orWhereHas('violationType', function ($type) use ($search) {
                        $type->where('name', 'like', "%{$search}%");
                    })

                    // Search officer
                    ->orWhereHas('user', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%");
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Get Violations
        |--------------------------------------------------------------------------
        */

        $violations = $query
            ->orderBy('violation_date', 'desc')
            ->orderBy('violation_time', 'desc')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | All-Time Violation Counter
        |--------------------------------------------------------------------------
        |
        | Counts every violation stored in the database.
        | This does not reset every month.
        |
        */

        $allTimeViolations = Violation::count();

        /*
        |--------------------------------------------------------------------------
        | Notification Data
        |--------------------------------------------------------------------------
        |
        | Used by the BPLO topbar notification bell.
        |
        */

        $pendingViolations = Violation::where('status', 'Pending')
            ->count();

        $recentViolations = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'user'
        ])
            ->where('status', 'Pending')
            ->orderBy('violation_date', 'desc')
            ->orderBy('violation_time', 'desc')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Return Violation Review Page
        |--------------------------------------------------------------------------
        */

        return view('bplo.violations.index', compact(
            'violations',
            'status',
            'search',
            'allTimeViolations',
            'pendingViolations',
            'recentViolations'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Update Violation Status
    |--------------------------------------------------------------------------
    */

    public function updateStatus(Request $request, Violation $violation)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Status
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'status' => ['required', 'in:Pending,Completed'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update Database
        |--------------------------------------------------------------------------
        */

        $violation->status = $validated['status'];
        $violation->save();

        /*
        |--------------------------------------------------------------------------
        | Return to Violation Review
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->back()
            ->with(
                'success',
                'Violation status updated successfully.'
            );
    }
}