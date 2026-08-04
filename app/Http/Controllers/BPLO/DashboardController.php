<?php

namespace App\Http\Controllers\BPLO;

use App\Http\Controllers\Controller;
use App\Models\Violation;

class DashboardController extends Controller
{
    public function index()
    {


        /*
        |--------------------------------------------------------------------------
        | SUMMARY COUNTS
        |--------------------------------------------------------------------------
        */


        // Total violation records
        $totalViolations = Violation::count();




        // Violations waiting for BPLO review
        $pendingViolations = Violation::where(
            'status',
            'Pending'
        )->count();





        // Completed / processed violations
        $reviewedViolations = Violation::where(
            'status',
            'Completed'
        )->count();






        /*
        |--------------------------------------------------------------------------
        | RECENT VIOLATION PREVIEW
        |--------------------------------------------------------------------------
        |
        | Load related data:
        | - Driver information
        | - Vehicle information
        | - Violation type
        | - Officer/User who created the violation
        |
        */


        $recentViolations = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'user'
        ])

        // Sort by actual violation occurrence
        ->orderBy(
            'violation_date',
            'desc'
        )

        ->orderBy(
            'violation_time',
            'desc'
        )

        ->take(10)

        ->get();






        return view(
            'bplo.dashboard',
            compact(
                'totalViolations',
                'pendingViolations',
                'reviewedViolations',
                'recentViolations'
            )
        );

    }
}