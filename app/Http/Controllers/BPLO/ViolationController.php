<?php

namespace App\Http\Controllers\BPLO;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Services\AuditLogger;
use App\Exports\BploViolationsExport;
use Maatwebsite\Excel\Facades\Excel;
class ViolationController extends Controller
{
    // ==========================================================
    // VIOLATION REVIEW
    // ==========================================================

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
                        $driver->where(function ($nameQuery) use ($search) {
                            $nameQuery
                                ->whereRaw(
                                    "CONCAT_WS(' ', first_name, middle_name, last_name) LIKE ?",
                                    ["%{$search}%"]
                                )
                                ->orWhereRaw(
                                    "CONCAT(first_name, ' ', last_name) LIKE ?",
                                    ["%{$search}%"]
                                )
                                ->orWhere('first_name', 'like', "%{$search}%")
                                ->orWhere('middle_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        })
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


                    // Search vehicle plate
                    ->orWhereHas('vehicle', function ($vehicle) use ($search) {
                        $vehicle->where('plate_number', 'like', "%{$search}%");
                    });
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
    // DRIVER HISTORY
    // ==========================================================

    public function driverHistory(Driver $driver)
    {
        // ==========================================================
        // LOAD DRIVER RELATIONSHIPS
        // ==========================================================

        $driver->load([
            'violations.vehicle',
            'violations.violationType',
            'violations.violationTypes',
            'violations.user',
            'vehicles',
        ]);

        // ==========================================================
        // SORT VIOLATIONS
        // ==========================================================

        $violations = $driver->violations
            ->sortByDesc(function ($violation) {
                return ($violation->violation_date ?? '') . ' ' .
                    ($violation->violation_time ?? '');
            })
            ->values();

        // ==========================================================
        // VIOLATION SUMMARY
        // ==========================================================

        $totalViolations = $violations->count();

        $pendingViolations = $violations
            ->filter(function ($violation) {
                return strtolower($violation->status ?? '') === 'pending';
            })
            ->count();

        $settledViolations = $violations
            ->filter(function ($violation) {
                return strtolower($violation->status ?? '') === 'settled';
            })
            ->count();

        // ==========================================================
        // RETURN DRIVER HISTORY DATA
        // ==========================================================

        return response()->json([
            'driver' => [
                'id' => $driver->id,
                'name' => $driver->full_name,
                'license_number' => $driver->license_number,
                'address' => $driver->address,
                'contact_number' => $driver->contact_number,
                'license_type' => $driver->license_type,
                'license_expiration' => $driver->license_expiration,
            ],

            'summary' => [
                'total' => $totalViolations,
                'pending' => $pendingViolations,
                'settled' => $settledViolations,
            ],

            'vehicles' => $driver->vehicles
                ->map(function ($vehicle) {
                    return [
                        'id' => $vehicle->id,
                        'plate_number' => $vehicle->plate_number,
                        'vehicle_type' => $vehicle->vehicle_type,
                    ];
                })
                ->values(),

            'violations' => $violations
                ->map(function ($violation) {

                    // ==================================================
                    // GET VIOLATION NAMES
                    // ==================================================

                    $violationNames = $violation->violationTypes
                        ->pluck('name')
                        ->filter()
                        ->values();

                    // Fallback to legacy single violation type
                    if (
                        $violationNames->isEmpty() &&
                        $violation->violationType
                    ) {
                        $violationNames->push(
                            $violation->violationType->name
                        );
                    }

                    // ==================================================
                    // VEHICLE INFORMATION
                    // ==================================================

                    $vehicleInfo = 'N/A';

                    if ($violation->vehicle) {
                        $plate = $violation->vehicle->plate_number ?? '';
                        $type = $violation->vehicle->vehicle_type ?? '';

                        if ($plate && $type) {
                            $vehicleInfo = $plate . ' (' . $type . ')';
                        } elseif ($plate) {
                            $vehicleInfo = $plate;
                        } elseif ($type) {
                            $vehicleInfo = $type;
                        }
                    }

                    // ==================================================
                    // RETURN VIOLATION DATA
                    // ==================================================

                    return [
                        'id' => $violation->id,
                        'ticket_number' => $violation->ticket_number,
                        'date' => $violation->violation_date,
                        'time' => $violation->violation_time,
                        'violation' => $violationNames->implode(', '),
                        'vehicle' => $vehicleInfo,
                        'location' => $violation->location ?? 'N/A',
                        'status' => $violation->status,
                    ];
                })
                ->values(),
        ]);
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
        // ==========================================================
    // EXPORT BPLO VIOLATION RECORDS TO EXCEL
    // ==========================================================

    public function export()
    {
        $fileName = 'BPLO_Violation_Report_' .
            now()->format('Y-m-d_H-i-s') .
            '.xlsx';

        return Excel::download(
            new BploViolationsExport(),
            $fileName
        );
    }
}
