<?php

namespace App\Http\Controllers\BPLO;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use Illuminate\Http\Request;
use App\Services\AuditLogger;

class ViolationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search');

        // ==========================================================
        // VIOLATION QUERY
        // ==========================================================

        $query = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'violationTypes',
            'user'
        ]);

        // ==========================================================
        // FILTER BY STATUS
        // ==========================================================

        if ($status === 'Pending' || $status === 'Settled') {
            $query->where('status', $status);
        }

        // ==========================================================
        // SEARCH
        // ==========================================================

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

                    // Search legacy violation type
                    ->orWhereHas('violationType', function ($type) use ($search) {
                        $type->where('name', 'like', "%{$search}%");
                    })

                    // Search multiple violation types
                    ->orWhereHas('violationTypes', function ($type) use ($search) {
                        $type->where('name', 'like', "%{$search}%");
                    })

                    // Search officer
                    ->orWhereHas('user', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%");
                    })

                    // Search vehicle plate
                    ->orWhereHas('vehicle', function ($vehicle) use ($search) {
                        $vehicle->where('plate_number', 'like', "%{$search}%");
                    })

                    // Search location
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // ==========================================================
        // GET VIOLATIONS
        // ==========================================================

        $violations = $query
            ->orderBy('violation_date', 'desc')
            ->orderBy('violation_time', 'desc')
            ->get();

        // ==========================================================
        // ALL-TIME VIOLATION COUNTER
        // ==========================================================

        $allTimeViolations = Violation::count();

        // ==========================================================
        // NOTIFICATION DATA
        // ==========================================================

        $pendingViolations = Violation::where('status', 'Pending')
            ->count();

        $recentViolations = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'violationTypes',
            'user'
        ])
            ->where('status', 'Pending')
            ->orderBy('violation_date', 'desc')
            ->orderBy('violation_time', 'desc')
            ->take(5)
            ->get();

        // ==========================================================
        // RETURN VIOLATION REVIEW PAGE
        // ==========================================================

        return view('bplo.violations.index', compact(
            'violations',
            'status',
            'search',
            'allTimeViolations',
            'pendingViolations',
            'recentViolations'
        ));
    }

    // ==========================================================
    // UPDATE VIOLATION STATUS
    // ==========================================================

    public function updateStatus(Request $request, Violation $violation)
    {
        // ==========================================================
        // VALIDATE STATUS
        // ==========================================================

        $validated = $request->validate([
            'status' => ['required', 'in:Pending,Settled'],
        ]);

        // ==========================================================
        // STORE PREVIOUS STATUS
        // ==========================================================

        $oldStatus = $violation->status;
        $newStatus = $validated['status'];

        // ==========================================================
        // UPDATE DATABASE
        // ==========================================================

        $violation->status = $newStatus;
        $violation->save();

        // ==========================================================
        // AUDIT TRAIL
        // ==========================================================

        AuditLogger::log(
            'STATUS_UPDATE',
            'Updated violation ticket ' . $violation->ticket_number .
            ' status from ' . $oldStatus .
            ' to ' . $newStatus . '.',
            $violation
        );

        // ==========================================================
        // RETURN TO VIOLATION REVIEW
        // ==========================================================

        return redirect()
            ->back()
            ->with(
                'success',
                'Violation status updated successfully.'
            );
    }
}